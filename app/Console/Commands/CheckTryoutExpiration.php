<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\TryoutExpirationService;

class CheckTryoutExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-tryout-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check tryout expiration and send daily notifications to all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('role', 'user')->get();
        
        $this->info("Checking tryout expiration for " . $users->count() . " users...");
        
        foreach ($users as $user) {
            TryoutExpirationService::checkExpirationForUser($user);
        }
        
        $this->info("Tryout expiration check completed successfully!");
    }
}
