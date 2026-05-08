<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateParams
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //request uri parameterek validalasa
        //hiba ha nem ok 
        $number = $request->route()->parameters['number'];
        $string = $request->route()->parameters['string'];
        
        $errors = [];
        if(!filter_var($number, FILTER_VALIDATE_INT)){
            $errors[] = 'number is not int';
        }
        if(!is_string($string)){
            $errors[] = 'string is not string';
        }
        if(!empty($errors)){
            return response()->json(['errors' => $errors], 418);
        }

        //kulonben:
        return $next($request);
    }
}
