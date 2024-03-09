<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DepositoType;

class CustomDepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $depositoType = [
            ['name' => 'Custom Deposito', 'contract' => 0, 'percent' => 0, 'minimal' => 0, 'status' => 0, 'created_at' => now(), 'updated_at'=>now()],
        ];
        DepositoType::insert($depositoType);
    }
}
