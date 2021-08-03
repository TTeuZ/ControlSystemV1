<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Paulo Mateus',
            'email' => 'pauloalves@plk.com.br',
            'password' => bcrypt('pregox99'),
        ]);
        User::create([
            'name' => 'João Moreira',
            'email' => 'joao@plk.com.br',
            'password' => bcrypt('joaoadmin'),
        ]);
        User::create([
            'name' => 'Jefferson',
            'email' => 'jefferson@plk.com.br',
            'password' => bcrypt('jeffersonadmin'),
        ]);
        User::create([
            'name' => 'Suelen',
            'email' => 'suelen@plk.com.br',
            'password' => bcrypt('suelenadmin'),
        ]);
        User::create([
            'name' => 'Jorge Peres',
            'email' => 'jorge@plk.com.br',
            'password' => bcrypt('jorgeadmin'),
        ]);
        User::create([
            'name' => 'Rafael',
            'email' => 'rafael@plk.com.br',
            'password' => bcrypt('rafaelplk'),
        ]);
    }
}
