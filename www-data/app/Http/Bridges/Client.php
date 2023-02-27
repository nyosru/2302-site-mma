<?php

/*
 * Преобразует реквест в записи БД
 */

namespace App\Http\Bridges;

use App\Http\Requests\ClientRequest;
use App\Models\Client as ClientModel;
use Exception;

class Client
{
    private $request;
    private $model;

    public function __construct(ClientRequest $request)
    {
        $this->request = $request;

        if ($id = $request->input('id')) {
            $this->model = ClientModel::whereId($id)->firstOrFail();
        } else {
            $this->model = new ClientModel;
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
