<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Time;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Yajra\DataTables\DataTables;

class AdminNotificstionsController extends Controller
{
    public $template = 'notifications';
    public $route = 'notifications';
    public $model = 'adminNotification';

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
            PermissionService::O_NOTIFICATION, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $query = $this->getObjectClass()->query()->latest();

        if (request()->has('app_id_val')) {
            $query = $query->where('app_id', (int)request()->input('app_id_val'));
        }
        if (request()->has('date_range_val')) {
            $dates = explode(' - ', request()->input('date_range_val'));
            $query = $query->where('created_at', '>=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[0])))
                ->where('created_at', '<=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[1])));
        }

        return Datatables::of($query)
            ->editColumn(
                'created_at', function (AdminNotification $log) {
                    return Time::applyTimezone($log->created_at);
                }
            )
            ->make();
    }

    public function getObjectClass()
    {
        return app(AdminNotification::class);
    }

    public function view(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_VIEW
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
            PermissionService::O_NOTIFICATION, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $object = $this->getObjectClass()->find((int)$request->route($this->model));
        $object->delete();
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
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_NOTIFICATION, PermissionService::A_CHANGE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
            $object = $this->getObjectClass()->find((int)request()->input('id'));

        } else {
            $object = $this->getObjectClass();
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_NOTIFICATION, PermissionService::A_CREATE
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
