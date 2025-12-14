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
                'is_active' => true,
            ]
        );

        $csStaff = User::updateOrCreate(
            ['email' => 'cs@karunialaris.com'],
            [
                'name' => 'Staff CS',
                'password' => Hash::make('password'),
                'role' => 'cs',
                'is_active' => true,
            ]
        );

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

        // ========================================
        // 2. CREATE COMPLAINT CATEGORIES
        // ========================================
        
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
                'phone' => '081234567890',
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

        // ========================================
        // 4. CREATE COMPLAINTS WITH WORKFLOW SCENARIOS
        // ========================================

        // SCENARIO 1: Complaint Baru (belum ditangani)
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 1,
            'description' => 'Tabung gas 12kg yang baru diantar ternyata bocor di bagian regulator. Sudah coba dipasang tapi gas terus keluar.',
            'customer_phone' => '081234567890',
            'status' => 'baru',
            'location' => $customerProfile1->address,
        ]);

        // SCENARIO 2: Complaint Baru (menunggu CS claim)
        Complaint::create([
            'customer_id' => $customerProfile2->id,
            'complaint_category_id' => 6,
            'description' => 'Staff pengiriman tidak ramah saat mengantar galon, berbicara kasar dan melempar galon.',
            'customer_phone' => '081234567891',
            'status' => 'baru',
            'location' => $customerProfile2->address,
        ]);

        // SCENARIO 3: Complaint Diproses oleh CS (belum ada response)
        Complaint::create([
            'customer_id' => $customerProfile3->id,
            'complaint_category_id' => 2,
            'description' => 'Galon air yang diantar dalam kondisi kotor dan berbau tidak sedap. Ada kerak putih di bagian dalam galon.',
            'customer_phone' => '081234567892',
            'status' => 'diproses',
            'handled_by' => $csStaff->id,
            'location' => $customerProfile3->address,
        ]);

        // SCENARIO 4: Complaint Diproses dengan CS Response
        Complaint::create([
            'customer_id' => $customerProfile4->id,
            'complaint_category_id' => 3,
            'description' => 'Pesanan gas yang dijanjikan hari Senin baru datang hari Kamis. Sudah menunggu 3 hari tidak ada kabar.',
            'customer_phone' => '081234567893',
            'status' => 'diproses',
            'handled_by' => $csStaff->id,
            'cs_response' => 'Mohon maaf atas keterlambatan. Kami sedang menghubungi driver untuk segera mengirimkan pesanan Anda.',
            'cs_response_updated_at' => now()->subHours(1),
            'location' => $customerProfile4->address,
        ]);

        // SCENARIO 5: Complaint Diescalate ke Manager (menunggu manager claim)
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 4,
            'description' => 'Tabung gas bocor parah, sudah ganti regulator tetap bocor. Kemungkinan ada masalah di tabung.',
            'customer_phone' => '081234567890',
            'status' => 'diproses',
            'handled_by' => $csStaff->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subHours(2),
            'escalation_reason' => 'Masalah teknis tabung yang kompleks, perlu penanganan khusus dari manager',
            'escalated_by' => $csStaff->id,
            'location' => $customerProfile1->address,
        ]);

        // SCENARIO 6: Complaint Diescalate dan sudah di-claim Manager (menunggu action)
        Complaint::create([
            'customer_id' => $customerProfile2->id,
            'complaint_category_id' => 5,
            'description' => 'Pelayanan sangat buruk, sudah komplain berkali-kali tapi tidak ada tindak lanjut.',
            'customer_phone' => '081234567891',
            'status' => 'diproses',
            'handled_by' => $csStaff->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subHours(3),
            'escalation_reason' => 'Customer sudah komplain berulang kali, perlu penanganan langsung dari manager',
            'escalated_by' => $csStaff->id,
            'manager_claimed_by' => $manager->id,
            'manager_claimed_at' => now()->subHour(),
            'location' => $customerProfile2->address,
        ]);

        // SCENARIO 7: Complaint Selesai (resolved by CS tanpa escalation)
        Complaint::create([
            'customer_id' => $customerProfile3->id,
            'complaint_category_id' => 2,
            'description' => 'Galon yang diterima ada retakan kecil di bagian bawah.',
            'customer_phone' => '081234567892',
            'status' => 'selesai',
            'handled_by' => $csStaff->id,
            'resolved_by' => $csStaff->id,
            'resolved_at' => now()->subDays(1),
            'cs_response' => 'Kami sudah mengirimkan galon pengganti yang baru. Terima kasih atas laporannya.',
            'cs_response_updated_at' => now()->subDays(1)->subHours(2),
            'created_at' => now()->subDays(1)->subHours(3),
            'updated_at' => now()->subDays(1),
            'location' => $customerProfile3->address,
        ]);

        // SCENARIO 8: Complaint Selesai (resolved by Manager setelah escalation)
        Complaint::create([
            'customer_id' => $customerProfile4->id,
            'complaint_category_id' => 3,
            'description' => 'Pesanan gas yang dijanjikan hari Senin baru datang hari Rabu. Sudah menunggu tapi tidak ada kabar dari driver.',
            'customer_phone' => '081234567893',
            'status' => 'selesai',
            'handled_by' => $csStaff->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subDays(2),
            'escalation_reason' => 'Keterlambatan pengiriman yang signifikan, customer sangat kecewa',
            'escalated_by' => $csStaff->id,
            'manager_claimed_by' => $manager->id,
            'manager_claimed_at' => now()->subDays(2)->addHours(1),
            'resolved_by' => $manager->id,
            'resolved_at' => now()->subDays(1),
            'action_notes' => 'Sudah menghubungi driver dan memastikan pengiriman segera dilakukan. Memberikan kompensasi berupa diskon untuk pembelian berikutnya.',
            'cs_response' => 'Pesanan Anda sudah dalam perjalanan dan akan tiba dalam 1 jam. Mohon maaf atas ketidaknyamanannya.',
            'cs_response_updated_at' => now()->subDays(1)->subHours(2),
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(1),
            'location' => $customerProfile4->address,
        ]);

        // SCENARIO 9: Complaint Selesai (return to CS dari Manager, kemudian resolved)
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 7,
            'description' => 'Ada kesalahan harga yang ditagihkan, lebih mahal dari harga normal.',
            'customer_phone' => '081234567890',
            'status' => 'selesai',
            'handled_by' => $csStaff->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subDays(3),
            'escalation_reason' => 'Masalah terkait harga, perlu konfirmasi dari manager',
            'escalated_by' => $csStaff->id,
            'manager_claimed_by' => $manager->id,
            'manager_claimed_at' => now()->subDays(3)->addHours(2),
            'resolved_by' => $csStaff->id,
            'resolved_at' => now()->subDays(2),
            'action_notes' => 'Harga sudah sesuai dengan kebijakan terbaru. CS dapat menjelaskan ke customer tentang perubahan harga.',
            'cs_response' => 'Mohon maaf atas kebingungannya. Harga tersebut sudah sesuai dengan kebijakan terbaru perusahaan yang berlaku sejak bulan ini. Kami sudah mengirimkan email penjelasan detail.',
            'cs_response_updated_at' => now()->subDays(2)->subHours(1),
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(2),
            'location' => $customerProfile1->address,
        ]);

       
    }
}
