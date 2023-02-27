<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRolePermission;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Lauthz\Facades\Enforcer;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yajra\DataTables\Facades\DataTables;
use function redirect;

class UserRolePermissionAdminController extends Controller
{

    public function listView()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }

        return view('admin.role-permissions.list');
    }

    public function addView()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }

        return view('admin.role-permissions.store');
    }

    public function delete($id)
    {
        if ($id) {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_PERMISSION, PermissionService::A_DELETE
            )
            ) {
                throw new AccessDeniedHttpException();
            }

            $p = UserRolePermission::find((int)$id);
            auth()->user()->createLog($p, 'Delete');
            $p->delete();

            return redirect()->back()->with(
                [
                    'message' => 'Deleted!'
                ]
            );
        } else {
            return abort(404);
        }
    }

    public function store($key, Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_CHANGE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $all = $request->input('Permission');

        return $this->response(
            function () use ($all, $key) {
                foreach ($all as $subject => $actions) {
                    foreach ($actions as $action => $enabled) {
                        if (false === $enabled) {
                            Enforcer::removePolicy($key, $subject, $action); //(string $sec, string $ptype, array $oldRule, array $newRule): bool
                        } else {
                            Enforcer::addPolicy($key, $subject, $action);
                        }
                    }
                }

                $p = new UserRolePermission();
                $p->fill($all);

                auth()->user()->createLog($p, 'Edit');
                return 'Changed successful';
            }
        );
    }

    public function editView($key)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_CHANGE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        View::share('pageTitle', $key);
        $policies = Enforcer::getPolicy($key);
        if (!$policies) {
            throw new NotFoundHttpException();
        }
        $arr = [];
        foreach ($policies as $v) {
            if (!isset($arr[$v[0]])) {
                $arr[$v[0]] = [];
            }
            $arr[$v[0]][$v[1]][] = $v[2];
        }
        $p = PermissionService::getPolicies($key);

        $res = [];
        foreach ($p as $subject => $actions) {
            foreach ($actions as $action) {
                $res[$subject][$action] = in_array($action, $arr[$key][$subject] ?? []);
            }
        }

        View::share('roles', [$res]);
        View::share('key', $key);
        View::share('translateKey', 'permissions');
        View::share('hasRole', Enforcer::hasRoleForUser((string)$this->getVisitor()->id, $key));
        return view('admin.role-permissions.store');
        //return view('admin.permissions.view');
        //    }
        //        if ($id) {
        //            if (!PermissionService::allowed(
        //                $this->getVisitor()->id,
        //                PermissionService::O_PERMISSION, PermissionService::A_CHANGE
        //            )
        //            ) {
        //                throw new AccessDeniedHttpException();
        //            }
        //
        //            $p = UserRolePermission::find($id);
        //
        //            return view('admin.role-permissions.store', compact(['p']));
        //        } else {
        //            return abort(404);
        //        }
    }

    public function viewData()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }

        $model = UserRolePermission::query();

        return DataTables::eloquent($model)
            ->addColumn(
                'action', function ($p) {
                    return view('admin.role-permissions.list_action', compact(['p']));
                }
            )
            ->make();

    }

}
