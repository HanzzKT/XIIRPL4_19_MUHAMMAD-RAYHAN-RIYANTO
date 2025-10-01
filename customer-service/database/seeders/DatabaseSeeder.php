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
        // Create Admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@karunialaris.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Manager
        $manager = User::create([
            'name' => 'Manager CS',
            'email' => 'manager@karunialaris.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        // Create CS staff
        $csStaff = User::create([
            'name' => 'Staff CS',
            'email' => 'cs@karunialaris.com',
            'password' => Hash::make('password'),
            'role' => 'cs',
            'is_active' => true,
        ]);

        // Create sample customers
        $customer1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $customer2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'sari@email.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $customer3 = User::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi@email.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Create complaint categories
        $categories = [
            // Original categories
            ['name' => 'Tabung Bocor', 'description' => 'Komplain terkait tabung gas yang bocor'],
            ['name' => 'Galon Kotor', 'description' => 'Komplain terkait galon air yang kotor'],
            ['name' => 'Keterlambatan Pengiriman', 'description' => 'Komplain terkait keterlambatan pengiriman'],
            ['name' => 'Tabung Rusak', 'description' => 'Komplain terkait tabung yang rusak'],
            ['name' => 'Layanan Buruk', 'description' => 'Komplain terkait pelayanan yang buruk'],
            ['name' => 'Staff Tidak Ramah', 'description' => 'Komplain terkait sikap staff yang tidak ramah'],
            ['name' => 'Lainnya', 'description' => 'Komplain lainnya yang tidak termasuk dalam kategori di atas'],
        ];

        foreach ($categories as $category) {
            ComplaintCategory::create($category);
        }

        // Create customer profiles
        $customerProfile1 = Customer::create([
            'user_id' => $customer1->id,
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123, Jakarta',
        ]);

        $customerProfile2 = Customer::create([
            'user_id' => $customer2->id,
            'phone' => '081234567891',
            'address' => 'Jl. Sudirman No. 456, Jakarta',
        ]);

        $customerProfile3 = Customer::create([
            'user_id' => $customer3->id,
            'phone' => '081234567892',
            'address' => 'Jl. Thamrin No. 789, Jakarta',
        ]);

        // Create sample complaints
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 1,
            'description' => 'Tabung gas 12kg yang baru diantar ternyata bocor di bagian regulator. Sudah coba dipasang tapi gas terus keluar.',
            'customer_phone' => '081234567890',
            'status' => 'diproses',
        ]);

        Complaint::create([
            'customer_id' => $customerProfile2->id,
            'complaint_category_id' => 2,
            'description' => 'Galon air yang diantar dalam kondisi kotor dan berbau tidak sedap. Ada kerak putih di bagian dalam galon.',
            'customer_phone' => '081234567891',
            'status' => 'selesai',
        ]);

        // Create escalated complaint with manager action
        Complaint::create([
            'customer_id' => $customerProfile3->id,
            'complaint_category_id' => 3,
            'description' => 'Pesanan gas yang dijanjikan hari Senin baru datang hari Rabu. Sudah menunggu tapi tidak ada kabar dari driver.',
            'customer_phone' => '081234567892',
            'status' => 'selesai',
            'handled_by' => $csStaff->id,
            'resolved_by' => $csStaff->id,
            'resolved_at' => now(),
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subHours(2),
            'escalation_reason' => 'tidak ada kabar driver',
            'escalated_by' => $csStaff->id,
            'action_notes' => 'Manager Action: resolved - Notes: driver sebentar lagi akan tiba karena kendala distribusi',
            'cs_response' => 'saya sudah menghubungi driver sebentar lagi akan tiba',
            'cs_response_updated_at' => now()->subMinutes(30),
        ]);

        // Create escalated complaint waiting for manager action
        Complaint::create([
            'customer_id' => $customerProfile1->id,
            'complaint_category_id' => 1,
            'description' => 'Tabung gas bocor parah, sudah ganti regulator tetap bocor. Kemungkinan ada masalah di tabung.',
            'customer_phone' => '081234567890',
            'status' => 'diproses',
            'handled_by' => $csStaff->id,
            'escalation_to' => $manager->id,
            'escalated_at' => now()->subHour(),
            'escalation_reason' => 'masalah teknis tabung yang kompleks, perlu penanganan khusus',
            'escalated_by' => $csStaff->id,
        ]);
    }
}
