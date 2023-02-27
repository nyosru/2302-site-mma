<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Time;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\PermissionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;
use Yajra\DataTables\DataTables;

class LogsController extends Controller
{
    public $template = 'logs';
    public $route = 'logs';
    public $model = 'client';

    public function list()
    {
        return view('admin.' . $this->template . '.list', $this->getViewAdditional());
    }

    public function getViewAdditional()
    {
        return [
            'route' => $this->route,
            'model' => $this->model,
            'template' => $this->template,
        ];
    }

    public function listData()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_LOG, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $query = $this->getObjectClass()->query()->latest();
        try {
            if (request()->has('app_id_val')) {
                $query = $query->where('app_id', (int)request()->input('app_id_val'));
            }
            if (request()->has('date_range_val')) {
                $dates = explode(' - ', request()->input('date_range_val'));
                $query = $query->where('created_at', '>=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[0])))
                    ->where('created_at', '<=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[1])));
            }
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
        }

        return Datatables::of($query->with(['clientLog']))
            ->addColumn(
                'log', function (Client $object) {

                    try {
                        $itemLog = $object->clientLog;

                        if (!$itemLog) {
                            return 'no logs';
                        }

                        return view(
                            'admin.clients.log-part', [
                            'logs' => $itemLog->log,
                            ]
                        );
                    } catch (Exception $exception) {
                        return '-';
                    }

                }
            )
            ->make();
    }

    public function getObjectClass()
    {
        return app(Client::class);
    }

    public function view(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_LOG, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $object = $this->getObjectClass()->find((int)$request->route($this->model));
        return view(
            'admin.' . $this->template . '.store', [
                'object' => $object,
            ] + $this->getViewAdditional()
        );
    }

    public function delete(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_LOG, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            $object = $this->getObjectClass()->find((int)$request->route($this->model));
            $object->delete();
        } catch (Throwable) {
            return redirect()->back()->with('message', 'Error occurred');
        }
        return redirect()->back()->with('message', 'Successfully deleted!');
    }

    public function add(Request $request)
    {
        return view('admin.' . $this->template . '.store', $this->getViewAdditional());
    }

    public function store(Request $request)
    {
        $object = null;
        if ($request->has('id')) {
            $object = $this->getObjectClass()->find((int)request()->input('id'));
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_LOG, PermissionService::A_CHANGE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
        } else {
            $object = $this->getObjectClass();
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_LOG, PermissionService::A_CREATE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
        }

        $object->fill($request->except(['id']));

        $object->save();

        return redirect()
            ->route('admin.' . $this->route . '.view', $object)
            ->with('message', 'Successfully saved!');
    }
}
