<?php

namespace App\Console\Commands;

use App\Services\PermissionService;
use Illuminate\Console\Command;

class InstallPolicyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'desc';

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
        PermissionService::install();

        return 0;
    }
}
