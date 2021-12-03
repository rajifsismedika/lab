<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HisRs extends Model
{
    use HasFactory;

    protected $connection = 'appt_ab';

    protected $table = 'rs';

    protected $primaryKey = 'RSID';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\HealthcareBridge\Database\factories\HisRsFactory::new();
    }
}
