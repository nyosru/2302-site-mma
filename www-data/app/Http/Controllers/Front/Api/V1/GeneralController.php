<?php

namespace App\Http\Controllers\Front\Api\V1;

use App\Http\Libs\Dispatcher;
use App\Models\AdminNotification;
use App\Models\App;
use App\Models\Client;
use App\Models\Clients_event_params;
use App\Models\View;
use DateTime;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class GeneralController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function download($id)
    {
        $app = App::where('app_id', (int)$id)->firstOrFail();

        header("Location: " . $app->makeDownloadUrl());
        die;
    }

    public function event($id, $eventName, Request $request)
    {
        $data = $request->json()->all();

        $app = App::where('app_id', (int)$id)->firstOrFail();

        $bundle_id = $id;
        $uid = $request->input('uid', false);
        $bid = $request->input('bid', false);

        if ($uid) {
            // От трекера передан bid, остальные данные забираем из бд
            $client_params = Clients_event_params::where('client', $uid)->firstOrFail();
            $client_params = json_decode($client_params, true);
            $client_params = json_decode($client_params['params'], true);


            $tid = !empty($client_params['tid']) ? $client_params['tid'] : '';
            $appsflyer_id = !empty($client_params['appsflyer_id']) ? $client_params['appsflyer_id'] : '';
            $aid = !empty($client_params['aid']) ? $client_params['aid'] : '';

        } else {
            $tid = $request->get('tid');
            $appsflyer_id = $request->get('appsflyer_id');
            $aid = $request->get('aid');
        }

        if (isset($_GET['payout'])) {
            if ($_GET['payout'] <= 0) { //  - regist

                return;
                $answer = [
                    'result' => 'payout=0'
                ];

                $newClick = new Client();
                $newClick->click_type = Client::TYLE_EVENT;
                $newClick->ip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();
                $newClick->tid = htmlspecialchars($tid);
                $_GET['SYSTEM_ANSWER'] = $answer;
                $newClick->request = json_encode($_GET);
                $newClick->app_id = $app->id;
                $newClick->result = 0;
                $newClick->save();

                try {
                    $test = json_decode($newClick->request);
                } catch (Exception $exception) {
                    AdminNotification::create(
                        [
                            'app_id' => $newClick->app_id,
                            'text' => "Receive invalid GET params from client: " . $newClick->id,
                            'readed' => false,
                        ]
                    );
                }

                $af_result = $this->sendToAppsflyer(
                    $app->app_id, $app->af_dev_key, [
                        'appsflyer_id' => $appsflyer_id,
                        'eventName' => 'af_reg',
                        'eventValue' => 0,
                        'af_currency' => 'USD',
                        'af_events_api' => 'true'
                    ]
                );

                // TODO $ab_result не массив тут

                return response()->json(
                    is_array($af_result) ? array_merge($af_result, $answer) : $answer
                );
            }
        }

        if ($app->af_dev_key) {
            $fb_result = '';
            if ($aid && !empty($app->fb_access_token)) {


                $post_fb_data = array(
                    'advertiser_id' => $aid,
                    'advertiser_tracking_enabled' => 1,
                    'application_tracking_enabled' => 1,
                    'custom_events' => array(
                        "_eventName" => "CUSTOM_APP_EVENTS",
                        "fb_content_type" => "product",
                        "_valueToSum" => $_GET['payout'],
                        "fb_currency" => "USD",
                    )

                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/621736335186541/activities?event=CUSTOM_APP_EVENTS");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $post_fb_data = http_build_query($post_fb_data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fb_data);

                //$headers = [];
                //$headers[] = 'Content-Type: application/json';
                //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $fb_result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
            }


            $af_result = $this->sendToAppsflyer(
                $app->app_id, $app->af_dev_key, [
                    'appsflyer_id' => $appsflyer_id,
                    'eventName' => $eventName,
                    'eventValue' => $request->get('eventValue'),
                    'af_currency' => 'USD',
                    'af_events_api' => 'true'
                ]
            );

            if ($app->unity_campaign_id && $app->game_id && $request->get('aid') && $request->get('bundle_id')) {
                $url = 'https://ads-secondary-conversion.unityads.unity3d.com/v1/events?aid=' . $aid . '&tracking_enabled=1&campaign_id=' . $app->unity_campaign_id . '&game_id=' . $app->game_id . '&platform=android&event=purchase&value=100&currency=USD&was_conversion_attributed=1&bundle_id=' . $bundle_id;

                try {
                    $attempt = file_get_contents($url);

                    if ($attempt !== false) {
                        $unityResult = true;
                    }
                } catch (Exception $e) {
                    $unityResult = false;
                }

            } else {
                $unityResult = false;
            }

            $answer[] = [
                'af_result' => $af_result,
                'unity_result' => $unityResult,
                'fb_result' => $fb_result,
                'result' => 1
            ];

            $newClick = new Client();
            $newClick->click_type = Client::TYLE_EVENT;
            $newClick->ip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();
            $newClick->tid = htmlspecialchars($request->get('tid'));
            $_GET['SYSTEM_ANSWER'] = $answer;
            //$_GET['SYSTEM_JSON_DATA'] = $data;
            //$_GET['SYSTEM_POST_DATA'] = $_POST;
            $newClick->request = json_encode($_GET);
            $newClick->app_id = $app->id;
            $newClick->result = ($af_result && $unityResult) ? 1 : 0;
            $newClick->save();

            try {
                $test = json_decode($newClick->request);
            } catch (Exception $exception) {
                AdminNotification::create(
                    [
                        'app_id' => $newClick->app_id,
                        'text' => "Receive invalid GET params from client: " . $newClick->id,
                        'readed' => false,
                    ]
                );
            }

            return response()->json($answer);
        } else {
            return response()->json(
                [
                    'result' => 'no_dev_keys'
                ]
            );
        }
    }

    public function sendToAppsflyer($app_id, $af_dev_key, $data): bool|string
    {
        try {
            // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api2.appsflyer.com/inappevent/' . $app_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt(
                $ch, CURLOPT_POSTFIELDS, json_encode(
                    $data
                )
            );

            $headers = [];
            $headers[] = 'authentication: ' . $af_dev_key;
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return false;
        }
        return $result;
    }

    public function usrtr(Request $request)
    {
        // dd($request->all());
        //https://monte-media.com/api/v1/usrtr/?e=123&p=12312321

        $phone = $request->input('p', false);
        $email = $request->input('e', false);

        if ($phone) {
            $exph = DB::table('usrtr_phones')->select('phone')->where('phone', '=', $phone)->first();
            if (empty($exph)) {
                DB::table('usrtr_phones')->insert(
                    [
                        'phone' => $phone,
                        'created_at' => date("Y-m-d H:i:s"),

                    ]
                );
            }
        }

        if ($email) {
            $exph = DB::table('usrtr_emails')->select('email')->where('email', '=', $email)->first();
            if (empty($exph)) {
                DB::table('usrtr_emails')->insert(
                    [
                        'email' => $email,
                        'created_at' => date("Y-m-d H:i:s"),

                    ]
                );
            }
        }

        return response()->json(
            [
                'result' => 'ok'
            ]
        );

    }

    public function app_errors($id, Request $request)
    {
        $app = App::where('app_id', (int)$id)->firstOrFail();

        $rawPostData = file_get_contents("php://input");


        //dd($request->all());
        $stop_words = array(
            '(info)',
            '(trace)',
            '(debug)',
            '(warn)',
            'ErrorUtils'
        );


        $type_error = $request->input('type_error', false);
        $url = $request->input('url', false);
        $message_error = $request->input('message_error', false);
        if ($message_error) {

            foreach ($stop_words as $stop_word) {
                if (strpos($message_error, $stop_word) !== false) {
                    return false;
                }
            }


            DB::table('app_errors')
                ->updateOrInsert(
                    [

                        'message_error' => $message_error,
                        'app_id' => $id,
                    ],
                    [
                        'type_error' => $type_error ? $type_error : '',
                        'url' => $url ? $url : '',
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                        'count_error' => DB::raw('count_error + 1'),
                    ]
                );


            //            \DB::table('app_errors')->insert(
            //                [
            //                    'type_error' => $type_error ? $type_error : '',
            //                    'message_error' => $message_error,
            //                    'app_id' => $id,
            //                    'url' => $url ? $url : '',
            //                    'created_at' =>date("Y-m-d H:i:s"),
            //                    'updated_at' => date("Y-m-d H:i:s"),
            //                ]
            //            );

            return response()->json(
                [
                    'result' => 'ok'
                ]
            );

        }

        return response()->json(
            [
                'result' => 'false',
                'allreq' => json_encode($request->all()),
                'rawPostData' => $rawPostData
            ]
        );

        //$errors = \DB::table('app_errors')->select('message', 'created_at')->where('app_id', '=', $id)->get();

    }

    public function twa($id, Request $request)
    {
        $app = App::where('app_id', $id)->firstOrFail();

        $result = Dispatcher::isClientTrusted($request, $app, 'twa');

        $event_client_id = md5($request->input('bid') . '_' . $id);
        $Clients_event = Clients_event_params::firstOrNew(array('client' => $event_client_id));
        $Clients_event->params = json_encode($request->all());
        $Clients_event->save();


        //Костыль для TWA приложений, bannerUrl по сути урл заглушки для таких приложений
        if ($result['productionUrl']) {
            header("Location: {$result['productionUrl']}");
            die;
        } else {
            header("Location: {$result['bannerUrl']}");
            die;
        }
    }

    public function launch($bundleId, Request $request)
    {
        try {
            $app = App::whereAppId( (string)$bundleId)->first();
            if (null === $app) {
                throw new \Exception('App not found');
            }
            $result = Dispatcher::isClientTrusted($request, $app);

            $event_client_id = md5($request->input('bid') . '_' . $bundleId);
            $Clients_event = Clients_event_params::firstOrNew(array('client' => $event_client_id));
            $Clients_event->params = json_encode($request->all());
            $Clients_event->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to launch: ' . $e->getMessage()
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            [
                'banner_url' => (!empty($result['productionUrl'])) ? $result['productionUrl'] . '&uid=' . $event_client_id : $result['productionUrl'],
                'banner' => $result['bannerUrl'],
            ]
        );
    }

    public function view($id)
    {
        try {
            $app = App::where('app_id', (int)$id)->firstOrFail();

            $newView = new View();
            $newView->request = json_encode($_GET);
            $newView->app_id = $app->id;

            $columns = Schema::getColumnListing($newView->getTable());

            foreach ($_GET as $field => $value) {
                if (in_array($field, $columns)) {
                    $newView->$field = $value;
                }
            }

            $newView->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to view'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            [
                'result' => 'ok',
            ]
        );
    }

    private function isBetween($from, $till, $input)
    {
        $f = DateTime::createFromFormat('!H:i', $from);
        $t = DateTime::createFromFormat('!H:i', $till);
        $i = DateTime::createFromFormat('!H:i', $input);
        if ($f > $t) {
            $t->modify('+1 day');
        }
        return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
    }
}
