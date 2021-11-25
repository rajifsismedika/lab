<?php

namespace Modules\HealthcareBridge\Helper;

use Illuminate\Support\Facades\Config;

class Helper
{
    public static function statusName($status_id) {
        $status_mapping = Config::get('healthcarebridge.status');
        
        return $status_mapping[$status_id] ?? $status_id;
    }

    public static function statusId($status_name) {
        $status_mapping = Config::get('healthcarebridge.status');

        return array_search($status_name, $status_mapping);
    }
}