<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-admin {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the email argument or ask for it
        $email = $this->argument('email') ?? $this->ask('Enter the admin email');

        // Get the password argument or ask for it
        $password = $this->argument('password') ?? $this->secret('Enter the admin password');

        // Prompt for additional user details
        $firstName = $this->ask('Enter the first name');
        $lastName = $this->ask('Enter the last name');
        $phone = $this->ask('Enter the phone number');

        // Validate the email
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $this->error('An account with this email already exists.');
            return 1;
        }

        // Create the admin user
        try {
            User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $phone,
                'role' => 'admin', // Default role if required
            ]);

            $this->info('Admin user created successfully');
            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}
