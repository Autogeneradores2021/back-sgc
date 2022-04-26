<?php

namespace App\Console\Commands;

use App\Models\SecurityUser;
use Illuminate\Console\Command;

class SecurityQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get users of security module of EH';

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
        SecurityUser::getUsers();
    }
}
