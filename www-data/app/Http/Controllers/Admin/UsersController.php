<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class UsersController extends Controller
{
    public $template = 'users';
    public $route = 'users';
    public $model = 'user';

    public function __construct(
        private UserService $userService
    ) {
    }

    public function list()
    {
        return view('admin.users.list', $this->getViewAdditional());
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
            PermissionService::O_USER, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return datatables($this->getObjectClass()->query())
            ->addColumn(
                'actions', function ($object) {
                    return view(
                        'admin.users.list-actions', [
                        'object' => $object,
                        ] + $this->getViewAdditional()
                    );
                }
            )
            ->make();
    }

    public function getObjectClass()
    {
        return app(User::class);
    }

    public function view(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_USER, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $object = $this->getObjectClass()->find($request->route($this->model));
        return view(
            'admin.users.store', [
                'object' => $object,
            ] + $this->getViewAdditional()
        );
    }

    public function delete($id, Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_USER, PermissionService::A_DELETE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            $user = User::whereId($id);

            auth()->user()->createLog($user->first(), 'Delete');
            $user->delete();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }
        return redirect()->back()->with('message', 'Successfully deleted!');
    }

    public function add(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_USER, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return view('admin.users.store', $this->getViewAdditional());
    }

    // TODO нет валидации входных параметров, могут быть исключения при неправильных данных

    /**
     * @throws \Exception
     */
    public function store(UserRequest  $request)
    {
        if (!$request->has('id')) {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_USER, PermissionService::A_CREATE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
        } else {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_USER, PermissionService::A_CHANGE
            )
            ) {
                throw new AccessDeniedHttpException();
            }
        }
        $user = $this->userService->save(
            userId: (int)$request->get('id'),
            email: $request->get('email'),
            name: $request->get('name'),
            password: $request->get('password'),
            role: $request->get('role_key')
        );

        $this->userService->changeRole($this->getVisitor()->id,
            (int)$request->get('id') ?: $user->id,
            $request->get('role_key'));

        if (!$request->has('id')) {
            auth()->user()->createLog($user, 'Create');
        } else {
            auth()->user()->createLog($user, 'Edit');
        }

        return redirect()
            ->route('admin.' . $this->route . '.view', $user)
            ->with('message', 'Successfully saved!');
    }
}
