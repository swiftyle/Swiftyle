<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class HandleApiExceptions
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
                'error' => class_basename($e),
            ], $e->getStatusCode());
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'status_code' => 404,
                'error' => class_basename($e),
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'status_code' => 422,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'status_code' => 500,
                'error' => class_basename($e),
                'trace' => $e->getTrace(),
            ], 500);
        }
    }
}
