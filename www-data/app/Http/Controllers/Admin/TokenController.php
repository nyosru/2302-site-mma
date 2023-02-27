<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenRequest;
use App\Models\Token;
use App\Services\PermissionService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Exceptions\Exception;

class TokenController extends Controller
{
    const TOKEN_LENGTH = 40;
    const VISIBLE_TOKEN_LENGTH = 10;

    public string $template = 'tokens';
    public string $route = 'tokens';
    public string $model = 'token';

    /**
     * @return array
     */
    public function getViewAdditional(): array
    {
        return [
            'route' => $this->route,
            'model' => $this->model,
            'template' => $this->template,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_TOKEN, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return view('admin.' . $this->route . '.list', $this->getViewAdditional());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_TOKEN, PermissionService::A_CREATE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        return view('admin.' . $this->route . '.store', $this->getViewAdditional());
    }

    /**
     * Store the resource in storage.
     *
     * @param StoreTokenRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTokenRequest $request): RedirectResponse
    {
        // Check permissions
        if (!$request->has('id')) {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_TOKEN, PermissionService::A_CREATE
            )) {
                throw new AccessDeniedHttpException();
            }
        } else {
            if (!PermissionService::allowed(
                $this->getVisitor()->id,
                PermissionService::O_TOKEN, PermissionService::A_CHANGE
            )) {
                throw new AccessDeniedHttpException();
            }
        }

        // Store the resource
        try {
            if ($request->has('id')) {
                $object = $this->getObjectClass()->query()->where('id', (int)(request()->input('id')))->first();
                $object->fill($request->except(['id', 'token']));
            } else {
                $object = $this->getObjectClass();
                $object->fill($request->except(['id']));
            }
            $object->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }

        return redirect()
            ->route('admin.' . $this->route . '.list')
            ->with('message', 'Successfully saved!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Token $token
     * @return Application|Factory|View
     */
    public function edit(Token $token): View|Factory|Application
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_TOKEN, PermissionService::A_CHANGE
        )) {
            throw new AccessDeniedHttpException();
        }
        return view(
            'admin.' . $this->route . '.store', [
                'object' => $token
            ] + $this->getViewAdditional()
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Token $token
     * @return RedirectResponse
     */
    public function destroy(Token $token): RedirectResponse
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_TOKEN, PermissionService::A_DELETE
        )) {
            throw new AccessDeniedHttpException();
        }
        try {
            $token->delete();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred');
        }
        return redirect()->to(route('admin.' . $this->route . '.list'))->with('message', 'Successfully deleted!');
    }

    /**
     * @return Token|Application|mixed
     */
    public function getObjectClass(): mixed
    {
        return app(Token::class);
    }

    /**
     * @return JsonResponse|DataTableAbstract
     * @throws Exception
     */
    public function data(): JsonResponse|DataTableAbstract
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_TOKEN, PermissionService::A_VIEW
        )) {
            throw new AccessDeniedHttpException();
        }
        $tokensDataTable = datatables($this->getObjectClass()->query())
            ->addColumn('actions', function ($object) {
                return view('admin.' . $this->route . '.list-actions', ['object' => $object] + $this->getViewAdditional());
            })->make();
        $response = $tokensDataTable->getData(true);
        foreach ($response['data'] as &$responseDataItem) {
            $responseDataItem['token'] = Str::limit($responseDataItem['token'], self::VISIBLE_TOKEN_LENGTH,
                str_repeat('*', self::TOKEN_LENGTH - self::VISIBLE_TOKEN_LENGTH));
        }
        $tokensDataTable->setData($response);

        return $tokensDataTable;
    }
}
