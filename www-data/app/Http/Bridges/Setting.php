<?php

/*
 * Преобразует реквест в записи БД
 */

namespace App\Http\Bridges;

use App\Http\Requests\SettingRequest;
use App\Models\Setting as SettingModel;
use Exception;

class Setting
{
    private $request;
    private $model;

    public function __construct(SettingRequest $request)
    {
        $this->request = $request;

        if ($id = $request->input('id')) {
            $this->model = SettingModel::whereId($id)->firstOrFail();
        } else {
            $this->model = new SettingModel;
        }
    }

    public function save()
    {
        $model = $this->model;
        $request = $this->request;
        $data = $request->all();

        $model->fill($data);

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
