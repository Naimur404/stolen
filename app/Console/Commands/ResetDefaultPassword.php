<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetDefaultPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:resetPassword';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Default password';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user){
            $user->password = bcrypt('123456789');
            $user->save();
        }
        $this->info('Password set to 123456789');
        return Command::SUCCESS;
    }
}
