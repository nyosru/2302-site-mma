<?php

namespace App\Http\Controllers\Admin\Api\V1;

use App\Http\Bridges\User as UserBridge;
use App\Http\Requests\UserRequest;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index(Request $request)
    {
        try {
            $Users = $this->applyFilters((new User), $request)->paginate(20);
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to apply filters'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new UserCollection($Users);
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
                    if (trim($value) != '') {
                        $query->where($field, 'like', '%' . mysql_escape($value) . '%');
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
     * @return UserResource
     */
    public function create(UserRequest $request)
    {
        return $this->save($request);
    }

    protected function save(UserRequest $request)
    {
        try {
            $item = new UserBridge($request);
            $item->save();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to save'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new UserResource($item->getModel());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return UserResource
     */
    public function read($id)
    {
        try {
            $item = User::whereId($id)->firstOrFail();
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            \Illuminate\Support\Facades\Response::json(
                array(
                    'message' => 'Failed to read'
                ), Response::HTTP_BAD_REQUEST
            );
        }
        return new UserResource($item, true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return UserResource
     */
    public function update(UserRequest $request)
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
            $item = User::whereId($id)->firstOrFail();
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

        $data['languages'][] = ['value' => null, 'label' => 'All'];

        return ['data' => $data];
    }
}
