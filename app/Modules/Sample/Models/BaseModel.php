<?php

namespace Modules\KalenderKegiatan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    public function getTable()
    {
        // Use the singular form of the class name instead of plural
        return $this->table ?? Str::snake(class_basename($this));
    }
}
