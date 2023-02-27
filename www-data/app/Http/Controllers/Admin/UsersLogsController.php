<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Time;
use App\Http\Controllers\Controller;
use App\Models\UserLog;
use App\Services\PermissionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;
use Yajra\DataTables\DataTables;

class UsersLogsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function list()
    {
        return view('admin.users-logs.list', $this->getViewAdditional());
    }

    /**
     * @return array
     */
    public function getViewAdditional()
    {
        return [
            'route' => 'users-logs',
            'model' => 'client',
            'template' => 'users-logs'
        ];
    }

    // TODO нет валидации входных параметров, могут быть исключения при неправильных данных

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_USER_LOG, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $query = $this->getObjectClass()->query()->latest();

        if (request()->has('user_id_val')) {
            $query = $query->where('user_id', (int)request()->input('user_id_val'));
        }
        if (request()->has('date_range_val')) {
            $dates = explode(' - ', request()->input('date_range_val'));
            $query = $query->where('created_at', '>=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[0])))
                ->where('created_at', '<=', Time::applySavingTimezone(Carbon::createFromFormat('m-d-Y H:i:s', $dates[1])));
        }

        return Datatables::of($query->with(['model', 'user']))
            ->addColumn(
                'logLine', function (UserLog $log) {
                    if (!$log->model) {
                        return '<span class="text-danger">model deleted in DB: ' . $log->model_type . ' [' . $log->model_key . ']</span>';
                    }

                    return $log->model->getLogsLine();
                }
            )
            ->editColumn(
                'created_at', function (UserLog $log) {
                    return Time::applyTimezone($log->created_at);
                }
            )
            ->rawColumns(['logLine'])
            ->removeColumn('model_type')
            ->removeColumn('model')
            ->make();
    }

    /**
     * @return UserLog|Application|mixed
     */
    public function getObjectClass()
    {
        return app(UserLog::class);
    }

    /**
     * @param  Request $request
     * @return Application|Factory|View
     */
    public function view(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_USER_LOG, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $userLog = UserLog::whereId($request->get('id'))->first();
        return view(
            'admin.users-logs.store', [
                'object' => $userLog,
            ] + $this->getViewAdditional()
        );
    }

    /**
     * @param  Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_USER_LOG, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            UserLog::whereId($request->get('id'))->delete();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }
        return redirect()->back()->with('message', 'Successfully deleted!');
    }

    /**
     * @return Application|Factory|View
     */
    public function add()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_USER_LOG, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return view('admin.users-logs.store', $this->getViewAdditional());
    }

    /**
     * @param  Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        if (!$request->has('id')) {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_USER_LOG, PermissionService::A_CREATE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
        } else {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_USER_LOG, PermissionService::A_CREATE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
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
            ->route('admin.users-logs.view', $object)
            ->with('message', 'Successfully saved!');
    }
}
