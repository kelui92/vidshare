<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

/**
 * Class AbstractAccessController
 * @package App\Http\Controllers\Api\V1
 *
 * Contains generic json response calls.
 */
abstract class AbstractAccessController extends Controller
{
    use ValidatesRequests;

    protected function sendSuccessJson($message, $status = 200)
    {
        return response()->json(["data" => ["message" => $message]], $status);
    }

    protected function sendErrorJson($message, $status = 422)
    {
        return response()->json(["error" => ["message" => $message]], $status);
    }

    protected function sendMail(String $to, String $class, array $params)
    {
        Mail::to($to)->send(new $class(...$params));
    }
}
