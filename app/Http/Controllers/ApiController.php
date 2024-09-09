<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    protected $logging = 4;

    /***
     * these methods are from
     * https://www.itsolutionstuff.com/post/build-restful-api-in-laravel-58-exampleexample.html
     * and may be used (but have not so far) to standardise the API
     */

    /**
     * success response method.
     *
     * @return Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
