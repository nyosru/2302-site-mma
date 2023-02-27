<?php

namespace App\Http\Controllers\Front;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FrontController extends \App\Http\Controllers\Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {


        return abort(404);
    }

    public function show_errors(Request $request)
    {
        $hide_error_id = $request->input('hide_error_id', false);
        $show_hide = $request->input('show_hide', false);

        if ($hide_error_id) {
            $hide_error_id = (int)$hide_error_id;
            DB::table('app_errors')
                ->where('id', $hide_error_id)
                ->update(['hide_error' => 1]);
        }

        if ($show_hide) {
            $errors = DB::table('app_errors')->get();
        } else {
            $errors = DB::table('app_errors')->where('hide_error', '=', '0')->get();
        }


        return view(
            'front.errors', [
                'errors' => $errors
            ]
        );

    }

    public function show_usrtr()
    {
        $phones = DB::table('usrtr_phones')->get();
        $emails = DB::table('usrtr_emails')->get();


        return view(
            'front.usrtr', [
                'phones' => $phones,
                'emails' => $emails
            ]
        );

    }

}
