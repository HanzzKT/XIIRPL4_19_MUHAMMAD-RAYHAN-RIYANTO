<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\ComplaintCategory;
use App\Models\Complaint;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      
       
        // ========================================
        // 1. CREATE USERS (Admin, Manager, CS Staff, Customers)
        // ========================================
        
        $admin = User::updateOrCreate(
            ['email' => 'admin@karunialaris.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@karunialaris.com'],
            [
                'name' => 'Manager CS',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'division' => 'Customer Service',
                'is_active' => true,
            ]
        );

        $csStaff = User::updateOrCreate(
            ['email' => 'cs@karunialaris.com'],
            [
                'name' => 'Staff CS 1',
                'password' => Hash::make('password'),
                'role' => 'cs',
                'is_active' => true,
            ]
        );

        $csStaff2 = User::updateOrCreate(
            ['email' => 'cs2@karunialaris.com'],
            [
                'name' => 'Staff CS 2',
                'password' => Hash::make('password'),
                'role' => 'cs',
                'is_active' => true,
            ]
        );

        $csStaff3 = User::updateOrCreate(
            ['email' => 'cs3@karunialaris.com'],
            [
                'name' => 'Staff CS 3',
                'password' => Hash::make('password'),
                'role' => 'cs',
                'is_active' => true,
            ]
        );

        $csStaff4 = User::updateOrCreate(
            ['email' => 'cs4@karunialaris.com'],
            [
                'name' => 'Staff CS 4',
                'password' => Hash::make('password'),
                'role' => 'cs',
                'is_active' => true,
            ]
        );

        // 5 Customers — masing-masing punya 3 complaint (1 baru/proses + 2 selesai)
        $customer1 = User::updateOrCreate(
            ['email' => 'budi@email.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'sari@email.com'],
            [
                'name' => 'Sari Dewi',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $customer3 = User::updateOrCreate(
            ['email' => 'andi@email.com'],
            [
                'name' => 'Andi Wijaya',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $customer4 = User::updateOrCreate(
            ['email' => 'dewi@email.com'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $customer5 = User::updateOrCreate(
            ['email' => 'eka@email.com'],
            ['name' => 'Eka Saputra', 'password' => Hash::make('password'), 'role' => 'customer', 'is_active' => true]
        );

        // End user creations

        // ========================================
        // 2. CREATE COMPLAINT CATEGORIES
        // ========================================
        
        // Kategori 1: Tabung Bocor        → 57608102.webp
        // Kategori 2: Galon Kotor          → images (1).jpg, maxresdefault.jpg, istockphoto-902252336-170667a.jpg
        // Kategori 3: Keterlambatan        → Gemini_Generated_Image_y9s6tjy9s6tjy9s6.png
        // Kategori 4: Tabung Rusak         → images.jpg
        // Kategori 5: Layanan Buruk        → Gemini_Generated_Image_vm7w3nvm7w3nvm7w.png
        // Kategori 6: Staff Tidak Ramah    → Gemini_Generated_Image_6p19c16p19c16p19.png
        // Kategori 7: Lainnya

        $categories = [
            ['name' => 'Tabung Bocor', 'description' => 'Komplain terkait tabung gas yang bocor'],
            ['name' => 'Galon Kotor', 'description' => 'Komplain terkait galon air yang kotor'],
            ['name' => 'Keterlambatan Pengiriman', 'description' => 'Komplain terkait keterlambatan pengiriman'],
            ['name' => 'Tabung Rusak', 'description' => 'Komplain terkait tabung yang rusak'],
            ['name' => 'Layanan Buruk', 'description' => 'Komplain terkait pelayanan yang buruk'],
            ['name' => 'Staff Tidak Ramah', 'description' => 'Komplain terkait sikap staff yang tidak ramah'],
            ['name' => 'Lainnya', 'description' => 'Komplain lainnya yang tidak termasuk dalam kategori di atas'],
        ];

        foreach ($categories as $category) {
            ComplaintCategory::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }

        // ========================================
        // 3. CREATE CUSTOMER PROFILES
        // ========================================
        
        $customerProfile1 = Customer::updateOrCreate(
            ['user_id' => $customer1->id],
            [
                'phone' => '081388556335',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
            ]
        );

        $customerProfile2 = Customer::updateOrCreate(
            ['user_id' => $customer2->id],
            [
                'phone' => '081234567891',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
            ]
        );

        $customerProfile3 = Customer::updateOrCreate(
            ['user_id' => $customer3->id],
            [
                'phone' => '081234567892',
                'address' => 'Jl. Thamrin No. 789, Jakarta',
            ]
        );

        $customerProfile4 = Customer::updateOrCreate(
            ['user_id' => $customer4->id],
            [
                'phone' => '081234567893',
                'address' => 'Jl. Gatot Subroto No. 321, Jakarta',
            ]
        );

        $customerProfile5 = Customer::updateOrCreate(
            ['user_id' => $customer5->id],
            ['phone' => '081234567894', 'address' => 'Jl. Ahmad Yani No. 555, Jakarta']
        );

        // End profile creations

        // ========================================
        // 4. CREATE COMPLAINTS
        // ========================================
        // Tiap customer: 1 complaint aktif (baru/proses) + 2 complaint selesai
        // Kategori berbeda-beda per orang

        // ============================================================
        // CUSTOMER 1 — Budi Santoso
        // Aktif: BARU (Tabung Bocor) | Selesai: Galon Kotor, Keterlambatan
        // ============================================================

        // Budi — BARU — Tabung Bocor
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 1, // Tabung Bocor
            'description' => 'Tabung gas 12kg yang baru diantar ternyata bocor di bagian regulator. Sudah coba dipasang tapi gas terus keluar.',
            'customer_phone' => '081388556335',
            'status' => 'baru',
            'image_path' => 'complaints/images/57608102.webp',
            'location' => $customerProfile1->address,
        ]);

        // Budi — SELESAI 1 — Galon Kotor
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 2, // Galon Kotor
            'description' => 'Galon air yang diantar keruh dan ada endapan putih di dalamnya. Tidak layak minum.',
            'customer_phone' => '081388556335',
            'status' => 'selesai',
            'handled_by' => $csStaff->id,
            'resolved_by' => $csStaff->id,
            'resolved_at' => now()->subDays(5),
            'cs_response' => 'Galon sudah kami ganti dengan yang baru dan bersih. Terima kasih atas laporannya.',
            'cs_response_updated_at' => now()->subDays(5),
            'image_path' => 'complaints/images/images (1).jpg',
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(5),
            'location' => $customerProfile1->address,
        ]);

        // Budi — SELESAI 2 — Keterlambatan Pengiriman
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 3, // Keterlambatan Pengiriman
            'description' => 'Pesanan gas sudah 3 hari belum sampai, padahal dijanjikan 1 hari.',
            'customer_phone' => '081388556335',
            'status' => 'selesai',
            'handled_by' => $csStaff2->id,
            'resolved_by' => $csStaff2->id,
            'resolved_at' => now()->subDays(10),
            'cs_response' => 'Mohon maaf atas keterlambatan pengiriman. Pesanan sudah berhasil diantar.',
            'cs_response_updated_at' => now()->subDays(10),
            'image_path' => 'complaints/images/Gemini_Generated_Image_y9s6tjy9s6tjy9s6.png',
            'created_at' => now()->subDays(14),
            'updated_at' => now()->subDays(10),
            'location' => $customerProfile1->address,
        ]);

        // ============================================================
        // CUSTOMER 2 — Sari Dewi
        // Aktif: DIPROSES (Staff Tidak Ramah) | Selesai: Tabung Rusak, Layanan Buruk
        // ============================================================

        // Sari — DIPROSES — Staff Tidak Ramah
        Complaint::create([
            'customer_id' => $customerProfile2->id,
            'complaint_category_id' => 6, // Staff Tidak Ramah
            'description' => 'Staff pengiriman tidak ramah saat mengantar galon, berbicara kasar dan melempar galon di depan rumah.',
            'customer_phone' => '081234567891',
            'status' => 'diproses',
            'handled_by' => $csStaff->id,
            'cs_response' => 'Kami sedang menindaklanjuti laporan ini dengan pihak terkait. Mohon kesabarannya.',
            'cs_response_updated_at' => now()->subHours(2),
            'image_path' => 'complaints/images/Gemini_Generated_Image_6p19c16p19c16p19.png',
            'location' => $customerProfile2->address,
        ]);

        // Sari — SELESAI 1 — Tabung Rusak
        Complaint::create([
            'customer_id' => $customerProfile2->id,
            'complaint_category_id' => 4, // Tabung Rusak
            'description' => 'Tabung gas yang diantar sudah karatan dan berkarat parah. Sangat berbahaya untuk digunakan.',
            'customer_phone' => '081234567891',
            'status' => 'selesai',
            'handled_by' => $csStaff3->id,
            'resolved_by' => $csStaff3->id,
            'resolved_at' => now()->subDays(4),
            'cs_response' => 'Tabung karatan sudah kami tarik dan diganti dengan tabung baru. Terima kasih laporannya.',
            'cs_response_updated_at' => now()->subDays(4),
            'image_path' => 'complaints/images/images.jpg',
            'created_at' => now()->subDays(6),
            'updated_at' => now()->subDays(4),
            'location' => $customerProfile2->address,
        ]);

        // Sari — SELESAI 2 — Layanan Buruk
        Complaint::create([
            'customer_id' => $customerProfile2->id,
            'complaint_category_id' => 5, // Layanan Buruk
            'description' => 'Sudah komplain berkali-kali soal pengiriman terlambat tetapi tidak ada perbaikan sama sekali.',
            'customer_phone' => '081234567891',
            'status' => 'selesai',
            'handled_by' => $csStaff4->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subDays(9),
            'escalation_reason' => 'Customer sudah komplain berulang kali, perlu penanganan langsung dari manager',
            'escalated_by' => $csStaff4->id,
            'manager_claimed_by' => $manager->id,
            'manager_claimed_at' => now()->subDays(9)->addHours(1),
            'resolved_by' => $manager->id,
            'resolved_at' => now()->subDays(8),
            'action_notes' => 'Sudah koordinasi dengan tim pengiriman untuk perbaikan layanan.',
            'cs_response' => 'Mohon maaf atas ketidaknyamanannya. Kami sudah memperbaiki sistem pengiriman.',
            'cs_response_updated_at' => now()->subDays(8),
            'image_path' => 'complaints/images/Gemini_Generated_Image_vm7w3nvm7w3nvm7w.png',
            'created_at' => now()->subDays(12),
            'updated_at' => now()->subDays(8),
            'location' => $customerProfile2->address,
        ]);

        // ============================================================
        // CUSTOMER 3 — Andi Wijaya
        // Aktif: BARU (Galon Kotor) | Selesai: Tabung Bocor, Staff Tidak Ramah
        // ============================================================

        // Andi — BARU — Galon Kotor
        Complaint::create([
            'customer_id' => $customerProfile3->id,
            'complaint_category_id' => 2, // Galon Kotor
            'description' => 'Galon yang baru diantar tutupnya sudah rusak dan pecah. Air mengalir keluar saat dipasang.',
            'customer_phone' => '081234567892',
            'status' => 'baru',
            'image_path' => 'complaints/images/images (1).jpg',
            'location' => $customerProfile3->address,
        ]);

        // Andi — SELESAI 1 — Tabung Bocor
        Complaint::create([
            'customer_id' => $customerProfile3->id,
            'complaint_category_id' => 1, // Tabung Bocor
            'description' => 'Tabung gas 3kg bocor dari bagian katup atas. Tercium bau gas yang menyengat.',
            'customer_phone' => '081234567892',
            'status' => 'selesai',
            'handled_by' => $csStaff2->id,
            'resolved_by' => $csStaff2->id,
            'resolved_at' => now()->subDays(6),
            'cs_response' => 'Tabung bocor sudah kami ganti dengan tabung baru yang sudah dicek keamanannya.',
            'cs_response_updated_at' => now()->subDays(6),
            'image_path' => 'complaints/images/57608102.webp',
            'created_at' => now()->subDays(8),
            'updated_at' => now()->subDays(6),
            'location' => $customerProfile3->address,
        ]);

        // Andi — SELESAI 2 — Staff Tidak Ramah
        Complaint::create([
            'customer_id' => $customerProfile3->id,
            'complaint_category_id' => 6, // Staff Tidak Ramah
            'description' => 'Driver pengiriman membentak saya saat diminta hati-hati meletakkan galon.',
            'customer_phone' => '081234567892',
            'status' => 'selesai',
            'handled_by' => $csStaff->id,
            'resolved_by' => $csStaff->id,
            'resolved_at' => now()->subDays(12),
            'cs_response' => 'Driver sudah kami tegur dan diberikan pelatihan ulang. Mohon maaf atas kejadian ini.',
            'cs_response_updated_at' => now()->subDays(12),
            'image_path' => 'complaints/images/Gemini_Generated_Image_6p19c16p19c16p19.png',
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(12),
            'location' => $customerProfile3->address,
        ]);

        // ============================================================
        // CUSTOMER 4 — Dewi Lestari
        // Aktif: DIPROSES (Tabung Rusak) | Selesai: Keterlambatan, Galon Kotor
        // ============================================================

        // Dewi — DIPROSES — Tabung Rusak (di-eskalasi ke manager)
        Complaint::create([
            'customer_id' => $customerProfile4->id,
            'complaint_category_id' => 4, // Tabung Rusak
            'description' => 'Tabung gas 12kg yang dikirim penyok dan catnya mengelupas. Ragu untuk digunakan karena takut berbahaya.',
            'customer_phone' => '081234567893',
            'status' => 'diproses',
            'handled_by' => $csStaff3->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subHours(3),
            'escalation_reason' => 'Masalah keamanan tabung yang penyok, perlu penanganan khusus dari manager',
            'escalated_by' => $csStaff3->id,
            'manager_claimed_by' => $manager->id,
            'manager_claimed_at' => now()->subHours(1),
            'image_path' => 'complaints/images/images.jpg',
            'location' => $customerProfile4->address,
        ]);

        // Dewi — SELESAI 1 — Keterlambatan Pengiriman
        Complaint::create([
            'customer_id' => $customerProfile4->id,
            'complaint_category_id' => 3, // Keterlambatan Pengiriman
            'description' => 'Pengiriman galon air dijanjikan pagi tapi baru datang malam hari. Sangat mengganggu.',
            'customer_phone' => '081234567893',
            'status' => 'selesai',
            'handled_by' => $csStaff4->id,
            'resolved_by' => $csStaff4->id,
            'resolved_at' => now()->subDays(3),
            'cs_response' => 'Mohon maaf atas keterlambatan. Jadwal pengiriman sudah kami perbaiki.',
            'cs_response_updated_at' => now()->subDays(3),
            'image_path' => 'complaints/images/Gemini_Generated_Image_y9s6tjy9s6tjy9s6.png',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(3),
            'location' => $customerProfile4->address,
        ]);

        // Dewi — SELESAI 2 — Galon Kotor
        Complaint::create([
            'customer_id' => $customerProfile4->id,
            'complaint_category_id' => 2, // Galon Kotor
            'description' => 'Galon air yang diterima ada lumut hijau di dalam dan tercium bau tidak sedap.',
            'customer_phone' => '081234567893',
            'status' => 'selesai',
            'handled_by' => $csStaff->id,
            'resolved_by' => $csStaff->id,
            'resolved_at' => now()->subDays(9),
            'cs_response' => 'Galon sudah diganti dengan yang baru. Kami akan meningkatkan kontrol kualitas.',
            'cs_response_updated_at' => now()->subDays(9),
            'image_path' => 'complaints/images/images (1).jpg',
            'created_at' => now()->subDays(11),
            'updated_at' => now()->subDays(9),
            'location' => $customerProfile4->address,
        ]);

        // ============================================================
        // CUSTOMER 5 — Eka Saputra
        // Aktif: BARU (Layanan Buruk) | Selesai: Tabung Bocor, Tabung Rusak
        // ============================================================

        // Eka — BARU — Layanan Buruk
        Complaint::create([
            'customer_id' => $customerProfile5->id,
            'complaint_category_id' => 5, // Layanan Buruk
            'description' => 'Sudah telepon CS 5 kali tapi tidak pernah diangkat. Saat datang ke toko juga tidak dilayani dengan baik.',
            'customer_phone' => '081234567894',
            'status' => 'baru',
            'image_path' => 'complaints/images/Gemini_Generated_Image_vm7w3nvm7w3nvm7w.png',
            'location' => $customerProfile5->address,
        ]);

        // Eka — SELESAI 1 — Tabung Bocor
        Complaint::create([
            'customer_id' => $customerProfile5->id,
            'complaint_category_id' => 1, // Tabung Bocor
            'description' => 'Tabung gas yang baru dibeli bocor dari bagian bawah saat digunakan untuk memasak.',
            'customer_phone' => '081234567894',
            'status' => 'selesai',
            'handled_by' => $csStaff2->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subDays(7),
            'escalation_reason' => 'Tabung bocor mengancam keselamatan, perlu penanganan cepat',
            'escalated_by' => $csStaff2->id,
            'manager_claimed_by' => $manager->id,
            'manager_claimed_at' => now()->subDays(7)->addHours(1),
            'resolved_by' => $manager->id,
            'resolved_at' => now()->subDays(6),
            'action_notes' => 'Tabung sudah ditarik, diganti baru, dan supplier diperingatkan.',
            'cs_response' => 'Tabung bocor sudah kami ganti dan pastikan aman. Mohon maaf atas ketidaknyamanannya.',
            'cs_response_updated_at' => now()->subDays(6),
            'image_path' => 'complaints/images/57608102.webp',
            'created_at' => now()->subDays(9),
            'updated_at' => now()->subDays(6),
            'location' => $customerProfile5->address,
        ]);

        // Eka — SELESAI 2 — Tabung Rusak
        Complaint::create([
            'customer_id' => $customerProfile5->id,
            'complaint_category_id' => 4, // Tabung Rusak
            'description' => 'Cat tabung gas terkelupas parah dan ada bekas penyok di bagian samping.',
            'customer_phone' => '081234567894',
            'status' => 'selesai',
            'handled_by' => $csStaff4->id,
            'resolved_by' => $csStaff4->id,
            'resolved_at' => now()->subDays(13),
            'cs_response' => 'Tabung rusak sudah ditarik dan diganti. Terima kasih atas perhatiannya terhadap keamanan.',
            'cs_response_updated_at' => now()->subDays(13),
            'image_path' => 'complaints/images/images.jpg',
            'created_at' => now()->subDays(16),
            'updated_at' => now()->subDays(13),
            'location' => $customerProfile5->address,
        ]);

       
    }
}
