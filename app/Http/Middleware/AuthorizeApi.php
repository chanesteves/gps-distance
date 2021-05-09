<?php

namespace App\Http\Middleware;

use Closure;

use App\Api;

use Illuminate\Support\Str;


class AuthorizeApi
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
        if(!isset($request->key)) {
            return response()->json([
                                        "data"      => [], 
                                        "message"   => "Don't have an API key for this request",
                                        "errors"    => []
                                    ], 403, [], JSON_PRETTY_PRINT);
        }

        $api_keys = Api::pluck('key')->toArray();

        if (count($api_keys) == 0) {
            $key = Str::random(60);

            // create permanent API token
            Api::create(["key" => Str::random(60)]);

            $api_keys[] = $key;
        }

        if (!in_array($request->key, $api_keys)) {
            return response()->json([
                "data"      => [], 
                "message"   => "Invalid API key.",
                "errors"    => []
            ], 403, [], JSON_PRETTY_PRINT);
        }

        return $next($request);
    }
}
