<?php

namespace App\Http\Controllers\Api\V1\Authenticated;

use App\Http\Controllers\Api\V1\AbstractAccessController;
use Illuminate\Http\Request;

/**
 * Class AbstractAuthenticatedController
 * @package App\Http\Controllers\Api\V1\Authenticated
 *
 * Description: Abstract class for grabbing the user from the request access token.
 * This class should be extended by any subclass in Authenticated that require ANY user data.
 */
abstract class AbstractAuthenticatedController extends AbstractAccessController
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $this->user = $request->user();

            return $next($request);
        });
    }
}
