<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class DailyBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notification to user for remind daily backup';

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
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        $message = 'لطفا از اسناد خود نسخه پشتیبان تهیه کنید.';
        $url = route('file-manager.index');

        Notification::send($users, new SendMessage($message, $url));
        $this->info('Done!');
        return 1;
    }
}
