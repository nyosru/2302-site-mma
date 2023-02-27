<?php

namespace App\Http\Controllers\Admin\Api\V1;

use App\Http\Bridges\Client as ClientBridge;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\Client as ClientResource;
use App\Http\Resources\ClientCollection;
use App\Models\App;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ClientController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return ClientCollection
     */
    public function index(Request $request)
    {
        try {
            $clients = $this->applyFilters((new Client), $request)->paginate(20);
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to apply filters'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new ClientCollection($clients);
    }

    protected function applyFilters($model, Request $request)
    {
        $query = $model::query();
        $filter = $request->get('filter');
        $sortField = $request->input('sort_field');
        $sortDirection = $request->input('sort_direction');

        if ($categoryId = $request->get('categoryId')) {
            $query->where('category_id', $categoryId);
        }

        if ($filter) {
            $columns = Schema::getColumnListing($model->getTable());

            if (!is_array($filter)) {
                $filter = json_decode($filter);
            }

            if ($filter) {
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

            $filter = (array)$filter;

            if ($filter['date_from']) {
                $dateFrom = date('Y-m-d H:i:s', strtotime($filter['date_from']));
                $query->where('created_at', '>=', $dateFrom);
            }

            if ($filter['date_to']) {
                $dateTo = date('Y-m-d H:i:s', strtotime($filter['date_to']));
                $query->where('created_at', '<=', $dateTo);
            }
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'DESC');
        }

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return ClientResource
     */
    public function create(ClientRequest $request)
    {
        return $this->save($request);
    }

    protected function save($request)
    {
        try {
            $item = new ClientBridge($request);
            $item->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to save'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new ClientResource($item->getModel());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return ClientResource
     */
    public function read($id)
    {
        try {
            $item = Client::whereId((int)$id)->firstOrFail();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to read'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new ClientResource($item, true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return ClientResource
     */
    public function update(ClientRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return string[]
     */
    public function delete($id)
    {
        try {
            $item = Client::whereId($id)->firstOrFail();
            $item->delete();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to delete'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return ['result' => 'success'];
    }

    public function meta()
    {
        $data = [
            'apps' => App::where('status', App::STATUS_ACTIVE)->get()->map(
                function ($app) {
                    return [
                        'value' => $app['id'],
                        'label' => $app['name'],
                    ];
                }
            ),

        ];

        return ['data' => $data];
    }
}
