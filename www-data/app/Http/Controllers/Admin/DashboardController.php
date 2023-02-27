<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Time;
use App\Http\Controllers\Front\Controller;
use App\Models\App;
use App\Models\Client;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function view()
    {
        $date_start = now()->startOfHour()->subHour();
        $date_end = now()->startOfHour();

        //        dd(request()->input('date_range'));

        if (request()->has('date_range')) {
            $dates = explode(' - ', request()->input('date_range'));
            $date_start = Time::applySavingTimezone(Carbon::createFromFormat('d-m-Y H:i:s', $dates[0]));
            $date_end = Time::applySavingTimezone(Carbon::createFromFormat('d-m-Y H:i:s', $dates[1]));
        }


        $result = Cache::remember(
            'dashboard_cache_' . $date_start . '_' . $date_end . '_' . request()->input('app_id', 'all'), now()->addMinutes(10), function () use ($date_start, $date_end) {
                $res = collect();
                $app = request()->input('app_id', 'all');
                if ($app !== 'all') {

                    $clients = Client::where('app_id', $app)
                        ->where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)
                        ->get(
                            [
                            'click_type',
                            'result',
                            'created_at'
                            ]
                        );

                    $diff = $date_end->diffInDays($date_start);

                    $analytics = $clients->where('click_type', Client::TYLE_LAUNCH)->groupBy(
                        function ($date) use ($diff) {

                            if ($diff > 7) {
                                return Time::applyTimezone(Carbon::parse($date->created_at))->format('d.m.Y');

                            }

                            return Time::applyTimezone(Carbon::parse($date->created_at))->format('d.m.Y H');
                        }
                    );

                    $analytics_rejects = [];
                    $analytics_accepts = [];

                    foreach ($analytics as $key => &$analytic) {
                        $analytics_rejects[$key] = $analytic->where('result', 0)->count();
                        $analytics_accepts[$key] = $analytic->where('result', 1)->count();
                    }
                    return [
                    'app' => App::find($app),
                    'clients' => $clients,
                    'downloads_count' => $clients->where('click_type', Client::TYLE_DOWNLOAD)->count(),
                    'launch_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->count(),
                    'launch_success_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->where('result', 1)->count(),
                    'analytics_rejects' => $analytics_rejects,
                    'analytics_accepts' => $analytics_accepts,
                    ];
                } else {

                    $apps_ids = Client::where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)
                        ->groupBy('app_id')->pluck('app_id');

                    foreach (App::where('status', 1)->whereIn('id', $apps_ids)->get() as $item) {
                        $clients = Client::where('app_id', $item->id)
                        ->where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)
                        ->get(
                            [
                                'click_type',
                                'result'
                            ]
                        );

                        $res->push(
                            [
                            'app' => $item,
                            'clients' => $clients,
                            'downloads_count' => $clients->where('click_type', Client::TYLE_DOWNLOAD)->count(),
                            'launch_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->count(),
                            'launch_success_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->where('result', 1)->count(),
                            ]
                        );
                    }

                    return $res->sortBy(
                        [
                        ['launch_count', 'desc'],
                        ['launch_success_count', 'desc'],
                        ]
                    );
                }


            }
        );


        return view('dashboard', compact(['result', 'date_start', 'date_end']));
    }

    public function analytics()
    {
        $date_start = now()->startOfHour()->subHour();
        $date_end = now()->startOfHour();

        //        dd(request()->input('date_range'));

        if (request()->has('date_range')) {
            $dates = explode(' - ', request()->input('date_range'));
            $date_start = Time::applySavingTimezone(Carbon::createFromFormat('d-m-Y H:i:s', $dates[0]));
            $date_end = Time::applySavingTimezone(Carbon::createFromFormat('d-m-Y H:i:s', $dates[1]));
        }

        //        $result = [];
        $result = Cache::remember(
            'dashboard_analytics_cache_' . $date_start . '_' . $date_end . '_' . request()->input('country', 'all'), now()->addMinutes(10), function () use ($date_start, $date_end) {
                $res = collect();


                $apps_ids = Client::where('created_at', '>=', $date_start)
                    ->where('created_at', '<=', $date_end)
                    ->groupBy('app_id')->pluck('app_id');

                foreach (App::where('status', 1)->whereIn('id', $apps_ids)->get() as $item) {
                    $clients = Client::where('app_id', $item->id)
                    ->where('created_at', '>=', $date_start)
                    ->where('created_at', '<=', $date_end);


                    if (request()->input('country', 'all') !== 'all') {
                        $clients = $clients->where('country', request()->input('country', 'all'));
                    }

                    $clients = $clients->get(
                        [
                        'click_type',
                        'result',
                        'country',
                        'request'
                        ]
                    );


                    $res->push(
                        [
                        'app' => $item,
                        'clients' => $clients,
                        'unique_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->map(
                            function (Client $client) {
                                try {
                                    $data = json_decode($client->request);
                                    return $data->bid;
                                } catch (Exception $exception) {
                                    return null;
                                }
                            }
                        )->unique()->filter(fn($item) => $item !== null)->count(),
                        'unique_success_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->where('result', 1)->map(
                            function (Client $client) {
                                try {
                                    $data = json_decode($client->request);
                                    return $data->bid;
                                } catch (Exception $exception) {
                                    return null;
                                }
                            }
                        )->unique()->filter(fn($item) => $item !== null)->count(),
                        'launch_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->count(),
                        'launch_success_count' => $clients->where('click_type', Client::TYLE_LAUNCH)->where('result', 1)->count(),
                        ]
                    );
                }

                return $res->sortBy(
                    [
                    ['launch_count', 'desc'],
                    ['launch_success_count', 'desc'],
                    ]
                );


            }
        );


        return view('analytics', compact(['result', 'date_start', 'date_end']));
    }
}
