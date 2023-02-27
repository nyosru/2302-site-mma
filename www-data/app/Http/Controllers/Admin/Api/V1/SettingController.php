<?php

namespace App\Http\Controllers\Admin\Api\V1;

use App\Http\Bridges\Setting as SettingBridge;
use App\Http\Requests\SettingRequest;
use App\Http\Resources\Setting as SettingResource;
use App\Http\Resources\SettingCollection;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SettingController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return SettingCollection
     */
    public function index(Request $request)
    {
        try {
            $Settings = $this->applyFilters((new Setting), $request)->paginate(20);
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to apply filters'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new SettingCollection($Settings);
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
     * @return SettingResource
     */
    public function create(SettingRequest $request)
    {
        return $this->save($request);
    }

    protected function save($request)
    {
        try {
            $item = new SettingBridge($request);
            $item->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to save'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new SettingResource($item->getModel());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return SettingResource
     */
    public function read($id)
    {
        try {
            $item = Setting::whereId($id)->firstOrFail();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to read'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new SettingResource($item, true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return SettingResource
     */
    public function update(SettingRequest $request)
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
            $item = Setting::whereId($id)->firstOrFail();
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
        $toObj = function ($k, $v) {
            return [
                'value' => $k,
                'label' => $v,
            ];
        };

        function array_map_assoc(callable $f, array $a)
        {
            return array_map($f, array_keys($a), $a);
        }

        $data = [

        ];

        return ['data' => $data];
    }
}
