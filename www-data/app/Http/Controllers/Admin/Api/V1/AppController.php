<?php

namespace App\Http\Controllers\Admin\Api\V1;

use App\Http\Bridges\App as AppBridge;
use App\Http\Requests\AppRequest;
use App\Http\Resources\App as AppResource;
use App\Http\Resources\AppCollection;
use App\Models\App;
use App\Services\AppService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use SoftInvest\Http\Controllers\HttpResponseController;
use Throwable;

class AppController extends HttpResponseController
{
    public function __construct(
        private AppService $appService
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return AppCollection
     */
    // TODO возможно не полный функционал тк часть закомментирована
    public function index(Request $request)
    {
        try {
            $apps = $this->applyFilters((new App), $request)->paginate(100);
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to get filters'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }

        //$apps = App::where('status', '!=', App::STATUS_DELETED)->get();
        // \DB::enableQueryLog();

        // $apps = \DB::table('apps')->paginate(20);
        //  dd(\DB::getQueryLog());

        // return $apps;
        return new AppCollection($apps);
    }

    protected function applyFilters($model, Request $request)
    {
        $query = $model::query()->where('status', '!=', $model::STATUS_DELETED);
        $filter = $request->get('filter');
        $sortField = $request->input('sort_field');
        $sortDirection = $request->input('sort_direction');

        if ($categoryId = $request->get('categoryId')) {
            $query->where('category_id', (int)$categoryId);
        }

        if ($filter) {
            $columns = Schema::getColumnListing($model->getTable());

            if (!is_array($filter)) {
                $filter = json_decode($filter, true);
            }

            foreach ($filter as $field => $value) {
                if (in_array($field, $columns)) {
                    if (!is_numeric($value)) {
                        $query->where($field, 'like', '%' . mysql_escape($value) . '%');
                    } else {
                        if ($value) {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }


        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'DESC');
        }

        return $query;
    }

    public function setstate()
    {
        $request = \Illuminate\Support\Facades\Request::capture();
        return $this->response(
            function () use ($request) {
                $app = App::whereAppId($request->get('bundle_id'))->first();
                if (null === $app) {
                    throw new Exception('Invalid bundle_id');
                }
                $states = [AppService::NONE, AppService::LIVE, AppService::BAN];
                $statesId = [AppService::NONE => 0, AppService::LIVE => 1, AppService::BAN => 2];

                if (!in_array(
                    $request->get('state'), $states
                )
                ) {
                    throw new Exception('Invalid state');
                }

                if ($request->get('state') != $states[$app->app_state_id]) {
                    if (!$this->appService->canChangeState($states[$app->app_state_id] ?? null, $request->get('state'))) {
                        throw new Exception('Invalid state change');
                    }
                }
                $app->app_state_id = $statesId[$request->get('state')];
                $app->save();
                return true;
            }
        );
    }

    protected function save($request)
    {
        try {
            $item = new AppBridge($request);
            $item->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to save'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }
        return new AppResource($item->getModel());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return AppResource
     */
    public function create(AppRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return AppResource
     */
    public function read($id): AppResource
    {
        try {
            $item = App::whereId((int)$id)->firstOrFail();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to read'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }
        return new AppResource($item, true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AppRequest $request
     * @return AppResource
     */
    public function update(AppRequest $request): AppResource
    {
        return $this->save($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return string[]
     */
    public function delete($id)
    {
        try {
            $item = App::whereId((int)$id)->firstOrFail();
            $item->delete();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to delete'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }
        return ['result' => 'success'];
    }

    public function meta()
    {
        $data = [

        ];

        return ['data' => $data];
    }
}
