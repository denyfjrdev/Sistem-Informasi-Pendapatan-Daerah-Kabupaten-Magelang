<?php
namespace App\Modules\Api\Sync\Controllers;

use App\Modules\Api\ApiBaseController;
use App\Modules\Api\Sync\Models\PajakSync;

class Sync extends ApiBaseController
{
    public function index()
    {
        return $this->response->setJSON((new PajakSync())->status());
    }

    public function full()
    {
        $tahun = (int) ($this->request->getGet('tahun') ?? 2026);
        return $this->response->setJSON((new PajakSync())->enqueueFull($tahun));
    }

    public function tick()
    {
        set_time_limit(180);
        return $this->response->setJSON((new PajakSync())->tick());
    }
}
