<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use SoftInvest\Http\Controllers\HttpResponseController;

class Controller extends HttpResponseController
{
    /**
     * @return User
     */
    public function getVisitor(): User
    {
        /**
         * @var ?User $user
         */
        $user = Auth::user();
        if (null === $user) {
            $user = new User();
        }

        return $user;
    }
}
