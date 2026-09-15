<?php
namespace App\Modules\Pimpinan\Sync\Controllers;

use App\Modules\Pimpinan\AdminBaseController;
use App\Modules\Api\Sync\Models\PajakSync;

class Sync extends AdminBaseController
{
    public function index()
    {
        $sync = new PajakSync();
        $data = [
            'data_user'  => $this->data_user,
            'role_login' => $this->data_user->role,
            'menu'       => 'Monitoring Perpajakan',
            'fiture'     => 'Sinkron API Pajak',
            'hp'         => $this->hp,
            'status'     => $sync->status(),
            'tickUrl'    => site_url('pimpinan/sync/tick'),
        ];
        return view('App\Modules\Pimpinan\Sync\Views\index', $data);
    }

    public function tick()
    {
        set_time_limit(180);
        return $this->response->setJSON((new PajakSync())->tick());
    }

    public function full()
    {
        $tahun = (int) ($this->request->getGet('tahun') ?? 2026);
        return $this->response->setJSON((new PajakSync())->enqueueFull($tahun));
    }
}
