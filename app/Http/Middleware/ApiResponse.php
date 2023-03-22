<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use \App\Http\Controllers\DataHandler;

class ApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $result = $next($request);

        if ($result instanceof JsonResponse) {
            if ($withData = $request->input('withData')) {
                $data = DataHandler::get($withData);
                $data['key'] = DataHandler::getRelevantKey();

                $result->setData(array_merge($result->getData(true), [
                    'withData' => $data
                ]));
            }
        }

        return $result;
    }
}


