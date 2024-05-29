<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function dashboard()
    {
        return $this->sendResponse([], 'Dashboard overview successfully.');
    }

    public function user()
    {
        $users = User::all();

        return $this->sendResponse($users, 'Users retrieved successfully.');
    }

    public function userById($id)
    {
        $user = User::find($id);

        if(is_null($user))
        {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($user, 'User details successfully.');
    }
}
