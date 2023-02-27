<?php

/*
 * Преобразует реквест в записи БД
 */

namespace App\Http\Bridges;

use App\Http\Requests\AppRequest;
use App\Models\App as AppModel;
use Exception;

class App
{
    private $request;
    private $model;

    public function __construct(AppRequest $request)
    {
        $this->request = $request;

        if ($id = $request->input('id')) {
            $this->model = AppModel::whereId($id)->firstOrFail();
        } else {
            $this->model = new AppModel;
        }
    }

    public function save()
    {
        $model = $this->model;
        $request = $this->request;
        $data = $request->all();

        if (!isset($data['banner_url'])) {
            $data['banner_url'] = '';
        }


        $model->fill($data);

        //dd($model);

        if (!$model->save()) {
            throw new Exception('Saving error');
        }

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
