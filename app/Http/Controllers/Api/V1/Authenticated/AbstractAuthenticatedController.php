<?php

namespace App\Http\Controllers\Api\V1\Authenticated;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbstractAuthenticatedController extends Controller
{
    protected $user;

    public function __construct() {
        $this->middleware(function (Request $request, $next) {
            $this->user = $request->user();

            return $next($request);
        });
    }
}
