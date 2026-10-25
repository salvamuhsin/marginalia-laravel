<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command {
    protected $signature = 'shop:admin {email} {--name=Admin} {--password=}';
    protected $description = 'Create an admin user, or promote an existing user to admin';

    public function handle(): int {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        if (! $user) {
            $pw = $this->option('password') ?: $this->secret('Password (min 8 characters)');
            if (strlen((string) $pw) < 8) { $this->error('Password must be at least 8 characters.'); return 1; }
            $user = User::create(['name' => $this->option('name'), 'email' => $email, 'password' => Hash::make($pw)]);
            $this->info("Created user {$email}.");
        }
        $user->is_admin = true;
        $user->save();
        $this->info("{$email} is now an admin. Log in, then open /admin.");
        return 0;
    }
}
