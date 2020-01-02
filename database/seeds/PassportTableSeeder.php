<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PassportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * TODO: Remover essa Seeed (ta aqui só pra facilitar a vida nos testes =D )
         */
        $passport = [
            'id' => 1,
            'name' => 'VUTTR API Password Grant Client',
            'secret' => 'KLU0d23CvY6LrdbjnxgDS4BMupOHolSUWr6mDJwF', 
            'redirect' => 'http://localhost', 
            'personal_access_client' => 0, 
            'password_client' => 1, 
            'revoked' => 0, 
            'created_at' => Carbon::now(), 
            'updated_at' => Carbon::now(), 
        ];

        DB::table('oauth_clients')->insert($passport);
    }
}
