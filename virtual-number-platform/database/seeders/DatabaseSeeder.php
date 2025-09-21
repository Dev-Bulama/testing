<?php

namespace Database\Seeders;

use App\Models\PhoneNumber;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
            'wallet_balance' => 0,
            'password' => Hash::make('password'),
        ]);

        $customer = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);

        $provider = Provider::create([
            'name' => 'Twilio',
            'status' => true,
            'configuration' => ['country' => 'US', 'default_cost' => 5],
        ]);

        PhoneNumber::create([
            'provider_id' => $provider->id,
            'number' => '+15551234567',
            'country' => 'US',
            'status' => PhoneNumber::STATUS_AVAILABLE,
            'capabilities' => ['sms'],
            'cost' => 5,
        ]);
    }
}
