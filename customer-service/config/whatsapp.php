<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi WhatsApp API
    | Sesuaikan dengan provider yang digunakan (Twilio, Vonage, dll)
    |
    */

    'api_url' => env('WHATSAPP_API_URL', 'https://api.whatsapp.com/v1'),
    'api_token' => env('WHATSAPP_API_TOKEN', ''),
    'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'your_verify_token'),
    
    // Auto-reply templates
    'auto_reply_enabled' => env('WHATSAPP_AUTO_REPLY_ENABLED', true),
    
    'templates' => [
        'complaint_received' => [
            'message' => "Halo! Terima kasih telah menghubungi PT Karunia Laris Abadi.\n\nKomplain Anda telah kami terima dengan nomor: #{complaint_id}\nKategori: {category}\n\nTim Customer Service kami akan segera menindaklanjuti komplain Anda. Mohon tunggu konfirmasi lebih lanjut.\n\nTerima kasih atas kepercayaan Anda."
        ],
        
        'complaint_processed' => [
            'message' => "Update komplain #{complaint_id}:\n\nStatus: {status}\nCatatan: {notes}\n\nTerima kasih atas kesabaran Anda."
        ],
        
        'complaint_resolved' => [
            'message' => "Komplain #{complaint_id} telah diselesaikan.\n\nSolusi: {solution}\n\nApakah Anda puas dengan penyelesaian ini? Silakan balas dengan 'YA' atau 'TIDAK'.\n\nTerima kasih."
        ]
    ],
    
    // Keyword detection untuk auto-categorization
    'keywords' => [
        'bocor' => ['bocor', 'leak', 'gas keluar', 'berbau gas', 'bocor gas'],
        'kotor' => ['kotor', 'bau', 'keruh', 'tidak bersih', 'berbau air', 'air kotor'],
        'terlambat' => ['terlambat', 'belum datang', 'lama', 'tunggu', 'telat'],
        'rusak' => ['rusak', 'pecah', 'retak', 'tidak bisa', 'broken'],
        'layanan' => ['pelayanan', 'service', 'kurang baik', 'tidak ramah', 'buruk']
    ],
    
    // Business hours untuk auto-reply
    'business_hours' => [
        'enabled' => true,
        'start' => '08:00',
        'end' => '17:00',
        'timezone' => 'Asia/Jakarta',
        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']
    ],
    
    'outside_hours_message' => "Terima kasih telah menghubungi PT Karunia Laris Abadi. Saat ini di luar jam operasional kami (08:00-17:00). Pesan Anda akan kami proses pada jam kerja berikutnya."
];
