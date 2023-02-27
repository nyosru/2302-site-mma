<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequest;
use App\Models\App;
use App\Services\AppService;
use App\Services\PermissionService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Exceptions\Exception;

class AppsController extends Controller
{
    public $template = 'apps';
    public $route = 'apps';
    public $model = 'app';

    public function __construct(
        private AppService $appService
    )
    {
    }

    /**
     * @return Application|Factory|View
     */
    public function list()
    {
        return view('admin.apps.list', $this->getViewAdditional());
    }

    /**
     * @return array
     */
    public function getViewAdditional()
    {
        return [
            'route' => $this->route,
            'model' => $this->model,
            'template' => $this->template,
        ];
    }

    /**
     * @return JsonResponse|DataTableAbstract
     * @throws Exception
     */
    public function data()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return datatables($this->getObjectClass()->query()->select(['*', DB::raw("(case when app_state_id=1 then 'live' else case when app_state_id=2 then 'ban' else null end end) as app_state_id")]))
            ->addColumn(
                'actions', function ($object) {
                return view(
                    'admin.apps.list-actions', [
                        'object' => $object,
                    ] + $this->getViewAdditional()
                );
            }
            )
            ->editColumn(
                'app_state_id', function ($object) {
                return match ($object->app_state_id) {
                    'ban' => '<span class="badge badge-danger">Banned</span>',
                    'live' => '<span class="badge badge-success">Live</span>',
                    default => '<span class="badge badge-light">None</span>'
                };
            }
            )
            ->rawColumns(['app_state_id'])
            ->make();
    }

    /**
     * @return App|Application|mixed
     */
    public function getObjectClass()
    {
        return app(App::class);
    }

    /**
     * @return Application|Factory|View
     */
    public function listTrashed()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return view('admin.apps.list-trashed', $this->getViewAdditional());
    }

    /**
     * @return JsonResponse|DataTableAbstract
     * @throws Exception
     */
    public function listTrashedData()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return datatables($this->getObjectClass()->query()->onlyTrashed())
            ->addColumn(
                'actions', function ($object) {
                return view(
                    'admin.apps.list-actions-trashed', [
                        'object' => $object,
                    ] + $this->getViewAdditional()
                );
            }
            )
            ->make();
    }

    // TODO нет валидации входных параметров, могут быть исключения при неправильных данных

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function view(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $object = $this->getObjectClass()->query()->withTrashed()->where('id', $request->route($this->model))->first();
        \Illuminate\Support\Facades\View::share('stores', $this->appService->getStores());
        if ($request->has('error')) {
            \Illuminate\Support\Facades\View::share('error', 1);
        }
        return view(
            'admin.apps.store', [
                'object' => $object,
            ] + $this->getViewAdditional()
        );
    }

    // TODO нет валидации входных параметров, могут быть исключения при неправильных данных

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            // $app = App::whereId($request->get('id'))->first();

            $app = App::withTrashed()->whereId($id)->first();
            if ($app) {
                if ($app->trashed()) {
                    auth()->user()->createLog($app, 'Restore from trash');
                    $app->forceDelete();
                } else {

                    auth()->user()->createLog($app, 'Delete');

                    $app->delete();
                }
            }
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }
        return redirect()->to(route('admin.apps.list'))->with('message', 'Successfully deleted!');
    }

    // TODO нет валидации входных параметров, могут быть исключения при неправильных данных

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }

        try {
            $object = $this->getObjectClass()->query()->withTrashed()
                ->where('id', (int)$request->route($this->model))->first();

            if ($object->trashed()) {
                $object->restore();
            }

            auth()->user()->createLog($object, 'Restore');
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }
        return redirect()->back()->with('message', 'Successfully restored!');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function add(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_APPLICATION, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        if ($request->has('error')) {
            \Illuminate\Support\Facades\View::share('error', 1);
        }
        \Illuminate\Support\Facades\View::share('stores', $this->appService->getStores());
        return view('admin.apps.store', $this->getViewAdditional());
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(AppRequest $request)
    {
        if (!$request->has('id')) {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_APPLICATION, PermissionService::A_CREATE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
        } else {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_APPLICATION, PermissionService::A_CHANGE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
        }

        try {
            $object = null;
            $create = false;
            if ($request->has('id')) {
                $object = $this->getObjectClass()->query()->withTrashed()->where('id', (int)(request()->input('id')))->first();
                if (null === $object) {
                    $create = true;
                }
            } else {
                $object = $this->getObjectClass();
                $create = true;
            }
            if (!$create) {
                if ($object->app_state_id != $request->get('app_state_id')) {
                    $states = [
                        'none','live','ban'
                    ];
                    if (!$this->appService->canChangeState($states[$object->app_state_id], $states[(int)$request->get('app_state_id')])) {
                        return redirect()->to(route('admin.' . $this->route . '.view', $object).'?error=1');
                    }
                }
            }

            $object->fill($request->except(['id']));

            $object->app_state_id = (int)$request->input('app_state_id');

            $object->banner_url = str_replace(' ','',$request->input('banner_url'));
            $object->download_url = str_replace(' ','',$request->input('download_url'));
            $object->url = str_replace(' ','',$request->input('url'));
            $object->url = str_replace(',','',$object->url);
            $object->ban_by_tid = $request->input('ban_by_tid') === 'on';
            $object->store_id = (int)$request->input('store_id');
            $object->app_id = str_replace(' ','',$request->input('app_id'));
            $object->ban_if_countries_not_matched = $request->input('ban_if_countries_not_matched') === 'on';
            $object->ban_if_no_country = $request->input('ban_if_no_country') === 'on';
            $object->allowed_countries_filter = $request->input('allowed_countries_filter') === 'on';
            $object->banned_devices_filter = $request->input('banned_devices_filter') === 'on';
            $object->banned_partners_filter = $request->input('banned_partners_filter') === 'on';
            $object->banned_time_filter = $request->input('banned_time_filter') === 'on';


            $object->save();

            if ($create) {
                auth()->user()->createLog($object, 'Create');
            } else {
                auth()->user()->createLog($object, 'Edit');
            }
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }

        return redirect()
            ->route('admin.' . $this->route . '.view', $object)
            ->with('message', 'Successfully saved!');
    }
}
