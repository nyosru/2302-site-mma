<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRoles;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use function redirect;

class UserRoleAdminController extends Controller
{

    public function listView()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_VIEW
        )
        ) {
            return abort(403);
        }
        View::share('map', PermissionService::getRoles());

        return view('admin.role.list2');
    }

    public function addView()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_CREATE
        )
        ) {
            return abort(403);
        }

        return view('admin.role.store');
    }

    public function delete($id, Request $request)
    {
        if ($id) {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_PERMISSION, PermissionService::A_CHANGE
            )
            ) {
                return abort(403);
            }

            $p = UserRoles::find((int)$id);
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

    public function store(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            $p = null;
            $created = false;

            if ($request->has('id')) {
                if (!PermissionService::allowed(
                    $this->getVisitor()->id,
                    PermissionService::O_PERMISSION, PermissionService::A_CREATE
                )
                ) {
                    return abort(403);
                }


                $p = UserRoles::find($request->input('id'));
                if (!$p) {
                    return abort(404);
                }

            } else {
                if (!PermissionService::allowed(
                    $this->getVisitor()->id,
                    PermissionService::O_PERMISSION, PermissionService::A_CHANGE
                )
                ) {
                    return abort(403);
                }


                $p = new UserRoles();
                $created = true;

                //$post->creator_id = auth()->user()->id;

            }


            $p->fill(
                $request->except(
                    [
                        //            'key'
                    ]
                )
            );
            $p->fill(
                $request->except(
                    [
                        //            'key'
                    ]
                )
            );

            $p->save();

            if ($created) {
                auth()->user()->createLog($p, 'Create');
            } else {
                auth()->user()->createLog($p, 'Edit');
            }
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
        }

        return redirect()->route('admin.view_role', ['id' => $p->key]);


        //return view('admin.blog.store');
    }

    public function editView($id)
    {
        if ($id) {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_PERMISSION, PermissionService::A_CHANGE
            )
            ) {
                return abort(403);
            }

            $p = UserRoles::find((int)$id);

            return view('admin.role.store', compact(['p']));
        } else {
            return abort(404);
        }
    }

    public function viewData()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_PERMISSION, PermissionService::A_VIEW
        )
        ) {
            return abort(403);
        }


        $model = UserRoles::query();


        return DataTables::eloquent($model)
            ->addColumn(
                'action', function ($p) {
                    return view('admin.role.list_action', compact(['p']));
                }
            )
            ->make();

    }

}
