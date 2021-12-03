<?php

return [
    'name' => 'HealthcareBridge',
    'request_status' => [
        0 => 'draft',
        1 => 'kirim',
        2 => 'diterima',
        '-1' => 'ditolak'
    ],
    'mutasi_status' => [
        0 => 'draft',
        1 => 'terkirim',
        '-1' => 'ditolak'
    ],
    'receive_status' => [
        0 => 'draft',
        1 => 'diterima',
        '-1' => 'ditolak'
    ],
];
