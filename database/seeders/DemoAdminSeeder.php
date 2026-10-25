<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'demo@example.com']);
        $admin->name = 'Demo Admin';
        $admin->password = Hash::make('Demo@12345');
        $admin->is_admin = true;
        $admin->save();
    }
}