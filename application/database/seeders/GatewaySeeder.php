<?php

namespace Database\Seeders;

use App\Models\Gateway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gateways')->insert([
            'name' => 'GatewayOne',
            'is_active' => true,
            'priority' => 1,
            'url' => 'http://gateways:3001',
            'data' => json_encode([
                'email' => 'dev@betalent.tech',
                'token' => 'FEC9BB078BF338F464F96B48089EB498',
            ]),
        ]);

        DB::table('gateways')->insert([
            'name' => 'GatewayTwo',
            'is_active' => true,
            'priority' => 2,
            'url' => 'http://gateways:3002',
            'data' => json_encode([
                'token' => 'tk_f2198cc671b5289fa856',
                'secret' => '3d15e8ed6131446ea7e3456728b1211f',
            ]),
        ]);
    }
}
