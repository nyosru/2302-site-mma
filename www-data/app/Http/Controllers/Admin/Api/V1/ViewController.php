<?php

namespace App\Http\Controllers\Admin\Api\V1;

use App\Http\Bridges\View as ViewBridge;
use App\Http\Requests\ViewRequest;
use App\Http\Resources\View as ViewResource;
use App\Http\Resources\ViewCollection;
use App\Models\App;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ViewController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return ViewCollection
     */
    public function index(Request $request)
    {
        try {
            $views = $this->applyFilters((new View), $request)->paginate(20);
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to apply filters'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }
        return new ViewCollection($views);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return ViewResource
     */
    public function create(ViewRequest $request)
    {
        return $this->save($request);
    }

    protected function save($request)
    {
        try {
            $item = new ViewBridge($request);
            $item->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to save'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }
        return new ViewResource($item->getModel());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return ViewResource
     */
    public function read($id)
    {
        try {
            $item = View::whereId((int)$id)->firstOrFail();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to read'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }

        return new ViewResource($item, true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return ViewResource
     */
    public function update(ViewRequest $request)
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
            $item = View::whereId((int)$id)->firstOrFail();
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
            'apps' => App::get()->map(
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
