<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Time;
use App\Http\Controllers\Controller;
use App\Models\BannedId;
use App\Models\Client;
use App\Models\ClientLog;
use App\Services\PermissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Yajra\DataTables\DataTables;

class ClientsController extends Controller
{
    public $template = 'clients';
    public $route = 'clients';
    public $model = 'client';

    public function logs($bid)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_CLIENT, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $clientLogs = ClientLog::whereBid( $bid)->with(
            [
                'app'
            ]
        )->orderBy('created_at','desc')->get();

        $apps = $clientLogs->pluck('app_id')->unique()->toArray();
        $logs = collect()->merge($clientLogs)->merge(
            BannedId::where('client_bid', $bid)
                ->whereIn('app_id', $apps)->get()
        );
        return view(
            'admin.' . $this->template . '.logs', array_merge(
                $this->getViewAdditional(),
                [
                    'bid' => $bid,
                    'logs' => $logs,
                ]
            )
        );
    }

    public function getViewAdditional()
    {
        return [
            'route' => $this->route,
            'model' => $this->model,
            'template' => $this->template,
        ];
    }

    public function deleteFromBid($bid)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_CLIENT, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            $object = BannedId::where('client_bid', $bid)->first();
            auth()->user()->createLog($object, 'Delete');

            $object->delete();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }
        return redirect()->back()->with('message', 'Successfully deleted!');
    }

    public function delete(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_CLIENT, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $object = $this->getObjectClass()->find($request->route($this->model));
        $object->delete();
        return redirect()->back()->with('message', 'Successfully deleted!');
    }

    public function getObjectClass()
    {
        return app(Client::class);
    }

    public function list()
    {
        return view('admin.' . $this->template . '.list', $this->getViewAdditional());
    }

    public function listData()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_CLIENT, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $query = $this->getObjectClass()->query()->latest();

        try {
            if (request()->has('app_id_val')) {
                $query = $query->where('app_id', request()->input('app_id_val'));
            }
            if (request()->has('date_range_val')) {
                $dates = explode(' - ', request()->input('date_range_val'));
                $query = $query->where('created_at', '>=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[0])))
                    ->where('created_at', '<=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[1])));
            }
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
        }
        return Datatables::of($query)
            //            ->addColumn('actions', function($object){
            //                return view('admin.' . $this->template . '.list-actions', [
            //                        'object'    =>  $object,
            //                    ] + $this->getViewAdditional());
            //            })
            ->make();
    }

    public function view(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_CLIENT, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $object = $this->getObjectClass()->find((int)$request->route($this->model));
        if (!$object) {
            throw new NotFoundHttpException();
        }
        return view(
            'admin.' . $this->template . '.store', [
                'object' => $object,
            ] + $this->getViewAdditional()
        );
    }

    public function add(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_CLIENT, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return view('admin.' . $this->template . '.store', $this->getViewAdditional());
    }

    public function store(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_CLIENT, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            $object = null;
            if ($request->has('id')) {
                $object = $this->getObjectClass()->find((int)request()->input('id'));
            } else {
                $object = $this->getObjectClass();
            }

            $object->fill($request->except(['id']));

            $object->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }

        return redirect()
            ->route('admin.' . $this->route . '.view', $object)
            ->with('message', 'Successfully saved!');
    }
}
