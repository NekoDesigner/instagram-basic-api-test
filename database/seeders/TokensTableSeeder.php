<?php

namespace Database\Seeders;

use App\Models\Token;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a token for the Instagram API
        Token::create([
            'app' => 'INSTAGRAM',
            'token' => config('services.instagram_basic.access_token'),
            'expires_in' => config('services.instagram_basic.expires_in')
        ]);

    }
}
