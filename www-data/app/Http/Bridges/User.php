<?php

/*
 * Преобразует реквест в записи БД
 */

namespace App\Http\Bridges;

use App\Http\Requests\UserRequest;
use App\Models\User as UserModel;
use Exception;

class User
{
    private $request;
    private $model;

    public function __construct(UserRequest $request)
    {
        $this->request = $request;

        if ($id = $request->input('id')) {
            $this->model = UserModel::whereId($id)->firstOrFail();
        } else {
            $this->model = new UserModel;
        }
    }

    /**
     * @throws Exception
     */
    public function save()
    {
        $model = $this->model;
        $request = $this->request;
        $data = $request->all();

        if (isset($data['password']) && $data['password']) {
            //$data['password'] = bcrypt($data['password']);
            //die($data['password']);
        } else {
            unset($data['password']);
        }

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
