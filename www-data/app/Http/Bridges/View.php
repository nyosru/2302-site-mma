<?php

/*
 * Преобразует реквест в записи БД
 */

namespace App\Http\Bridges;

use App\Http\Requests\ViewRequest;
use App\Models\View as ViewModel;
use Exception;

class View
{
    private $request;
    private $model;

    public function __construct(ViewRequest $request)
    {
        $this->request = $request;

        if ($id = $request->input('id')) {
            $this->model = ViewModel::whereId($id)->firstOrFail();
        } else {
            $this->model = new ViewModel;
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
