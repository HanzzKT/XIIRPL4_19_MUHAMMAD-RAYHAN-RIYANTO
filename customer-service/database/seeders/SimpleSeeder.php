<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Illuminate\Support\Facades\Hash;

class SimpleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@karunialaris.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $cs = User::create([
            'name' => 'Staff CS',
            'email' => 'cs@karunialaris.com',
            'password' => Hash::make('password'),
            'role' => 'cs',
            'is_active' => true,
        ]);

        $customer1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Create customer profile
        $customerProfile = Customer::create([
            'user_id' => $customer1->id,
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123, Jakarta',
        ]);

        // Create categories
        ComplaintCategory::create(['name' => 'Tabung Bocor', 'description' => 'Komplain tabung bocor']);
        ComplaintCategory::create(['name' => 'Galon Kotor', 'description' => 'Komplain galon kotor']);
        ComplaintCategory::create(['name' => 'Layanan Buruk', 'description' => 'Komplain layanan buruk']);

        // Create simple complaint
        Complaint::create([
            'customer_id' => $customerProfile->id,
            'complaint_category_id' => 1,
            'title' => 'Staff tidak ramah',
            'description' => 'Staff pengiriman tidak ramah saat mengantar galon',
            'customer_phone' => '081234567890',
            'status' => 'baru',
            'source' => 'phone',
        ]);
    }
}
