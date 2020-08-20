<?php

return [
    'validation_status' => [
        'badge' => [
            'Baru' => 'warning',
            'Diterima' => 'success',
            'Ditolak' => 'danger'
        ]
    ],

    'registrant_status' => [
        'Request' => 'Pengajuan Baru',
        'Approve' => 'Lolos Persyaratan',
        'Reject' => 'Tidak Lolos Persyaratan',
        'badge' => [
            'Request' => 'warning',
            'Approve' => 'success',
            'Reject' => 'danger'
        ],
        'graduation' => [
            'Request' => 'Penilaian Persyaratan',
            'Approve' => 'Lolos Persyaratan',
            'Reject' => 'Tidak Lolos Persyaratan',
        ]
    ],

    'exam_status' => [
        'badge' => [
            'Lulus' => 'success',
            'Tidak Lulus' => 'danger'
        ],
    ],

    'tahap_kompetensi' => [
        1 => 'Tahap 1 Awal / Dasar – MDU – MDK',
        2 => 'Tahap 2 Magang / OTL 1',
        3 => 'Tahap 3 Mandiri / OTL 2'
    ]
];
