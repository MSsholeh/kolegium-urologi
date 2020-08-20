<?php

namespace App\Console\Commands\Daster;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daster:create-admin {name} {email} {password} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $role = $this->argument('role');

        if (Admin::whereEmail($email)->first()) {

            $this->error('Email sudah ada.');

            return false;
        }

        Role::findByName($role, 'admin');

        $admin = Admin::create([
           'name' => $name,
           'email' => $email,
           'password' => Hash::make($password)
        ]);

        $admin->assignRole($role);

        $this->info('Admin Created');
    }
}
