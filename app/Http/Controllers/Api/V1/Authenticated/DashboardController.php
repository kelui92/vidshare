<?php

namespace App\Http\Controllers\Api\V1\Authenticated;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class DashboardController extends AbstractAuthenticatedController
{
    public function index() {
        return new UserResource($this->user);
    }
}
