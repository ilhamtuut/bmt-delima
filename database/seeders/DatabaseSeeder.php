<?php

namespace Database\Seeders;

use DB;
use App\Models\Bank;
use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Permission;
use App\Models\BankDeposit;
use App\Models\DepositoType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>'super_admin', 'display_name' => 'Super Admin', 'description' => 'Super Admin', 'created_at' => now(), 'updated_at'=>now()],
            ['name'=>'admin', 'display_name' => 'Admin', 'description' => 'Admin', 'created_at' => now(), 'updated_at'=>now()],
            ['name'=>'member', 'display_name' => 'Member', 'description' => 'Member', 'created_at' => now(), 'updated_at'=>now()],
        ];
        Role::insert($data);

        $data = [
            ['name'=>'administrator', 'display_name' => 'Administrator', 'description' => 'Administrator', 'created_at' => now(), 'updated_at'=>now()],
        ];
        Permission::insert($data);

        $depositoType = [
            ['name' => 'Deposito 12 Bulan 10%', 'contract' => 12, 'percent' => 0.10, 'minimal' => 1000000, 'created_at' => now(), 'updated_at'=>now()],
            ['name' => 'Deposito 24 Bulan 24%', 'contract' => 24, 'percent' => 0.24, 'minimal' => 1000000, 'created_at' => now(), 'updated_at'=>now()],
            ['name' => 'Deposito 24 Bulan 30%', 'contract' => 24, 'percent' => 0.30, 'minimal' => 110000000, 'created_at' => now(), 'updated_at'=>now()],
        ];
        DepositoType::insert($depositoType);

        $settings = [
            ['name' => 'Minimal Withdrawal', 'value' => 100000, 'type' => 'IDR', 'status' => 1, 'created_at' => now(), 'updated_at'=>now()],
            ['name' => 'Maximum Withdrawal', 'value' => 100000000, 'type' => 'IDR', 'status' => 1, 'created_at' => now(), 'updated_at'=>now()],
        ];
        Setting::insert($settings);

        $bank = [
            ['code' => 'BCA', 'name' => 'Bank Central Asia', 'created_at' => now(), 'updated_at'=>now()],
            ['code' => 'BRI', 'name' => 'Bank Rakyat Indonesia', 'created_at' => now(), 'updated_at'=>now()],
            ['code' => 'BNI', 'name' => 'Bank Negara Indonesia', 'created_at' => now(), 'updated_at'=>now()],
            ['code' => 'Mandiri', 'name' => 'Bank Mandiri', 'created_at' => now(), 'updated_at'=>now()],
        ];
        Bank::insert($bank);

        $superadmin = User::create([
            'account_number' => '690-1000000',
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'email_verified_at' => now(),
            'status' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => now(), 
            'updated_at'=>now()
        ]);
        $superadmin->attachRole('super_admin');
        $superadmin->attachPermission('administrator');

        $admin = User::create([
            'account_number' => '690-1000001',
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'status' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => now(), 
            'updated_at'=>now()
        ]);
        $admin->attachRole('admin');
        $admin->attachPermission('administrator');

        $member = User::create([
            'account_number' => '690-1000002',
            'name' => 'Aditya',
            'username' => 'aditya',
            'email' => 'aditya@gmail.com',
            'email_verified_at' => now(),
            'status' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => now(), 
            'updated_at'=>now()
        ]);
        $member->attachRole('member');

        DB::table('permission_role')->insert([
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 1, 'role_id' => 2]
        ]);
    }
}
