<?php

return [
    'list' => [
        [
            'name' => 'Home',
            'route' => 'admin.home',
            'active' => 'admin.home*',
            'icon' => 'block'
        ],
        [
            'name' => 'Program',
        ],
        [
            'name' => 'Pengguna',
            'route' => 'admin.user.index',
            'active' => 'admin.user*',
            'icon' => 'block',
            'permission' => 'Pengguna: Lihat'
        ],
        [
            'name' => 'Persyaratan Pendaftaran PPDS',
            'route' => 'admin.requirement.index',
            'active' => 'admin.requirement.*',
            'icon' => 'block',
            'permission' => 'Persyaratan: Lihat, Tambah, Ubah, Hapus'
        ],
        [
            'name' => 'Pendaftaran PPDS',
            'route' => 'admin.registrant.index',
            'active' => 'admin.registrant.*',
            'icon' => 'block',
            'permission' => 'Pendaftar: Lihat'
        ],
        [
            'name' => 'Validasi Ujian Pendaftaran PPDS',
            'route' => 'admin.registrant-validation.index',
            'active' => 'admin.registrant-validation*',
            'icon' => 'block',
            'permission' => 'Pendaftar Validation: Lihat, Tambah, Ubah, Hapus'
        ],
        [
            'name' => 'Persyaratan Ujian Nasional',
            'route' => 'admin.requirement-graduation.index',
            'active' => 'admin.requirement-graduation.*',
            'icon' => 'block',
            'permission' => 'Persyaratan Ujian: Lihat, Tambah, Ubah, Hapus'
        ],
        [
            'name' => 'Pendaftaran Ujian Nasional',
            'route' => 'admin.registrant-graduation.index',
            'active' => 'admin.registrant-graduation.*',
            'icon' => 'block',
            'permission' => 'Pendaftar Ujian: Lihat'
        ],
                [
            'name' => 'Jadwal Ujian Nasional',
            'route' => 'admin.exam.index',
            'active' => 'admin.exam*',
            'icon' => 'block',
            'permission' => 'Jadwal Ujian: Lihat'
        ],
        [
            'name' => 'Persyaratan Sertifikat Terbit',
            'route' => 'admin.requirement-certificate.index',
            'active' => 'admin.requirement-certificate.*',
            'icon' => 'block',
            'permission' => 'Persyaratan Sertifikat: Lihat, Tambah, Ubah, Hapus'
        ],
        [
            'name' => 'Pendaftar Sertifikat Terbit',
            'route' => 'admin.registrant-certificate.index',
            'active' => 'admin.registrant-certificate.*',
            'icon' => 'block',
            'permission' => 'Pendaftar Sertifikat: Lihat'
        ],
        [
            'name' => 'Sertifikat Terbit',
            'route' => 'admin.sertifikat.index',
            'active' => 'admin.Sertifikat.*',
            'icon' => 'block',
            'permission' => 'Sertifikat: Lihat, Download'
        ],
//        [
//            'name' => 'Kursus',
//            'route' => 'admin.course.index',
//            'active' => 'admin.course*',
//            'icon' => 'block',
//            'permission' => 'Kursus: Lihat, Tambah, Ubah, Hapus'
//        ],
        [
            'name' => 'Pengaturan',
        ],
        [
            'name' => 'Periode',
            'route' => 'admin.period.index',
            'active' => 'admin.period.*',
            'icon' => 'block',
            'permission' => 'Periode: Lihat'
        ],
        [
            'name' => 'Universitas',
            'route' => 'admin.setting.university.index',
            'active' => 'admin.setting.university*',
            'icon' => 'block',
            'permission' => 'Universitas: Lihat'
        ],
        [
            'name' => 'Pengelolaan Admin',
            'route' => 'admin.setting.admin.index',
            'active' => 'admin.setting.admin*',
            'icon' => 'block',
            'permission' => 'Pengelolaan Admin: Lihat, Tambah, Ubah, Hapus'
        ],
        [
            'name' => 'Hak Akses',
            'route' => 'admin.setting.role.index',
            'active' => 'admin.setting.role*',
            'icon' => 'block',
            'permission' => 'Hak Akses: Lihat, Tambah, Ubah, Hapus'
        ],
    ],
    'icon' => [
        'block' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"/>
                            <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"/>
                        </g>
                    </svg>'
    ]
];
