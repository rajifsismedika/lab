<?php

namespace Modules\HealthcareBridge\Helper;

use Illuminate\Support\Facades\Config;

class Helper
{
    public static function statusRequestName($status_id) {
        $status_mapping = Config::get('healthcarebridge.request_status');
        
        return $status_mapping[$status_id] ?? $status_id;
    }

    public static function statusRequestId($status_name) {
        $status_mapping = Config::get('healthcarebridge.request_status');

        return array_search($status_name, $status_mapping);
    }
    
    public static function statusMutasiName($status_id) {
        $status_mapping = Config::get('healthcarebridge.mutasi_status');
        
        return $status_mapping[$status_id] ?? $status_id;
    }

    public static function statusMutasiId($status_name) {
        $status_mapping = Config::get('healthcarebridge.mutasi_status');

        return array_search($status_name, $status_mapping);
    }
    
    public static function statusReceiveName($status_id) {
        $status_mapping = Config::get('healthcarebridge.receive_status');
        
        return $status_mapping[$status_id] ?? $status_id;
    }

    public static function statusReceiveId($status_name) {
        $status_mapping = Config::get('healthcarebridge.receive_status');

        return array_search($status_name, $status_mapping);
    }
}