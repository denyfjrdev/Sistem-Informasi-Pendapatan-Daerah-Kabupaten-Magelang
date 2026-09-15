<?php
namespace App\Modules\Api\Sync\Models;

class PajakSync
{
    private $stateFile;
    private $lockFile;
    private $kecamatanPbb = [
        '010','020','030','040','050','060','070','080','090','100',
        '110','120','130','140','150','160','170','180','190','200','210',
    ];

    public function __construct()
    {
        $dir = WRITEPATH . 'tmp';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->stateFile = $dir . DIRECTORY_SEPARATOR . 'pajak_sync_state.json';
        $this->lockFile  = $dir . DIRECTORY_SEPARATOR . 'pajak_sync.lock';
    }

    public function enqueueFull(int $tahun = 2026): array
    {
        $state = [
            'mode'      => 'full',
            'tahun'     => $tahun,
            'queue'     => $this->jobsFull($tahun),
            'done'      => 0,
            'failed'    => [],
            'last'      => null,
            'updated'   => date('Y-m-d H:i:s'),
        ];
        $this->writeState($state);
        return [
            'status' => true,
            'mode'   => 'full',
            'queued' => count($state['queue']),
            'tahun'  => $tahun,
        ];
    }

    public function status(): array
    {
        $state = $this->readState();
        return [
            'status'    => true,
            'mode'      => $state['mode'] ?? 'idle',
            'remaining' => count($state['queue'] ?? []),
            'done'      => (int) ($state['done'] ?? 0),
            'last'      => $state['last'] ?? null,
            'failed'    => $state['failed'] ?? [],
            'updated'   => $state['updated'] ?? null,
            'busy'      => $this->isLocked(),
        ];
    }

    public function tick(): array
    {
        $fp = fopen($this->lockFile, 'c+');
        if ($fp === false) {
            return ['status' => false, 'message' => 'gagal buka lock'];
        }
        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            fclose($fp);
            return ['status' => true, 'busy' => true, 'message' => 'masih proses'];
        }

        try {
            $state = $this->readState();
            if (empty($state['queue'])) {
                $tahun = (int) ($state['tahun'] ?? date('Y'));
                $bulan = (int) date('n');
                if ((int) date('Y') !== $tahun) {
                    $bulan = 12;
                }
                $state['mode']  = 'live';
                $state['tahun'] = $tahun;
                $state['queue'] = $this->jobsLive($tahun, $bulan);
            }

            $job = array_shift($state['queue']);
            $hasil = $this->jalankan($job);

            $state['done']    = (int) ($state['done'] ?? 0) + 1;
            $state['last']    = ['job' => $job, 'hasil' => $hasil, 'waktu' => date('Y-m-d H:i:s')];
            $state['updated'] = date('Y-m-d H:i:s');
            if (empty($hasil['ok'])) {
                $state['failed'][] = $state['last'];
                $state['failed'] = array_slice($state['failed'], -20);
            }
            $this->writeState($state);

            return [
                'status'    => true,
                'busy'      => false,
                'mode'      => $state['mode'],
                'remaining' => count($state['queue']),
                'done'      => $state['done'],
                'job'       => $job,
                'hasil'     => $hasil,
            ];
        } finally {
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    private function jobsFull(int $tahun): array
    {
        $jobs = [['type' => 'lra', 'tahun' => $tahun]];
        for ($b = 1; $b <= 12; $b++) {
            $jobs[] = ['type' => 'esptpd', 'tahun' => $tahun, 'bulan' => $b];
            $jobs[] = ['type' => 'opsen', 'tahun' => $tahun, 'bulan' => $b];
            foreach ($this->kecamatanPbb as $kd) {
                $jobs[] = [
                    'type'          => 'bphtb',
                    'tahun'         => $tahun,
                    'bulan'         => $b,
                    'kd_kecamatan'  => $kd,
                ];
            }
        }
        return $jobs;
    }

    private function jobsLive(int $tahun, int $bulan): array
    {
        $jobs = [
            ['type' => 'lra', 'tahun' => $tahun],
            ['type' => 'esptpd', 'tahun' => $tahun, 'bulan' => $bulan],
            ['type' => 'opsen', 'tahun' => $tahun, 'bulan' => $bulan],
        ];
        foreach ($this->kecamatanPbb as $kd) {
            $jobs[] = [
                'type'          => 'bphtb',
                'tahun'         => $tahun,
                'bulan'         => $bulan,
                'kd_kecamatan'  => $kd,
            ];
        }
        return $jobs;
    }

    private function jalankan(array $job): array
    {
        $type  = $job['type'] ?? '';
        $tahun = (int) ($job['tahun'] ?? 2026);
        $bulan = (int) ($job['bulan'] ?? 0);

        if ($type === 'lra') {
            return $this->http('GET', 'api/esptpd/realisasi/lra_bulanan_pajak_jenis?tahun=' . $tahun);
        }
        if ($type === 'esptpd') {
            return $this->http('GET', 'api/esptpd/realisasi/realisasi_perkelurahan_bulanan?tahun=' . $tahun . '&bulan=' . $bulan);
        }
        if ($type === 'opsen') {
            return $this->http('GET', 'api/opsen/load?tahun=' . $tahun . '&bulan=' . $bulan);
        }
        if ($type === 'bphtb') {
            $kd = $job['kd_kecamatan'] ?? '010';
            return $this->http('GET', 'api/bphtb/load?tahun=' . $tahun . '&bulan=' . $bulan . '&kd_kecamatan=' . $kd, 100);
        }

        return ['ok' => false, 'message' => 'jenis job tidak dikenal'];
    }

    private function http(string $method, string $path, int $timeout = 120): array
    {
        $base  = rtrim((string) (env('app.baseURL') ?: 'http://localhost/'), '/');
        $token = (string) env('KEY_AUTH');
        $url   = $base . '/' . ltrim($path, '/');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_HTTPHEADER     => [
                'Token: ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        $body = curl_exec($ch);
        $err  = curl_error($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            return ['ok' => false, 'message' => $err, 'http' => $code];
        }

        $json = json_decode((string) $body, true);
        $ok   = $code >= 200 && $code < 300;
        if (is_array($json) && array_key_exists('status', $json)) {
            $ok = $ok && (bool) $json['status'];
        }

        $ringkas = is_array($json)
            ? [
                'status'      => $json['status'] ?? null,
                'message'     => $json['message'] ?? null,
                'jml_terisi'  => $json['jml_terisi'] ?? null,
                'jml_pajak'   => $json['jml_pajak'] ?? null,
                'kd_kecamatan'=> $json['kd_kecamatan'] ?? null,
                'bulan'       => $json['bulan'] ?? null,
            ]
            : ['body' => substr((string) $body, 0, 120)];

        return ['ok' => $ok, 'http' => $code, 'data' => $ringkas];
    }

    private function readState(): array
    {
        if (!is_file($this->stateFile)) {
            return ['mode' => 'idle', 'queue' => [], 'done' => 0, 'failed' => []];
        }
        $raw = json_decode((string) file_get_contents($this->stateFile), true);
        return is_array($raw) ? $raw : ['mode' => 'idle', 'queue' => [], 'done' => 0, 'failed' => []];
    }

    private function writeState(array $state): void
    {
        file_put_contents($this->stateFile, json_encode($state, JSON_UNESCAPED_UNICODE));
    }

    private function isLocked(): bool
    {
        $fp = @fopen($this->lockFile, 'c+');
        if ($fp === false) {
            return false;
        }
        $ok = flock($fp, LOCK_EX | LOCK_NB);
        if ($ok) {
            flock($fp, LOCK_UN);
        }
        fclose($fp);
        return !$ok;
    }
}
