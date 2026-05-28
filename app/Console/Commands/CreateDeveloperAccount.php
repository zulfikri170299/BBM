<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateDeveloperAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:developer {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a user as a developer to hide their activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $user = \App\Models\User::where('username', $username)->first();

        if (!$user) {
            $this->error("User with username '$username' not found.");
            return 1;
        }

        $user->update(['is_developer' => true]);

        $this->info("User '$username' has been set as a developer. Their activity will no longer be logged.");
        return 0;
    }
}
