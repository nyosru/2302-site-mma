<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use App\Models\App;
use App\Models\Client;
use Illuminate\Console\Command;

class FetchAppsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:apps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $apps = App::where('status', 1)->get();

        foreach ($apps as $app) {
            $ex = Client::where('app_id', $app->id)
                ->where('created_at', '>=', now()->subHours(2))
                ->where('created_at', '<=', now())
                ->exists();
            if (!$ex) {
                AdminNotification::create(
                    [
                        'app_id' => $app->id,
                        'text' => "No receive any clients from app in last 2 hours",
                        'readed' => false,
                    ]
                );
            } else {
                $ex2 = Client::where('app_id', $app->id)
                    ->where('created_at', '>=', now()->subHours(2))
                    ->where('created_at', '<=', now())
                    ->where('result', 1)
                    ->exists();
                if (!$ex2) {
                    AdminNotification::create(
                        [
                            'app_id' => $app->id,
                            'text' => "No receive accepted clients from app in last 2 hours",
                            'readed' => false,
                        ]
                    );
                }
            }

        }

        return 0;
    }
}
