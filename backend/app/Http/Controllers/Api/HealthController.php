<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $database = [
            'connection' => config('database.default'),
            'ok' => false,
        ];

        try {
            DB::connection()->getPdo();
            $database['ok'] = true;
        } catch (Throwable $exception) {
            $database['error'] = $exception->getMessage();
        }

        $healthy = $database['ok'];

        return $this->success([
            'status' => $healthy ? 'ok' : 'degraded',
            'app' => config('app.name'),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
            'database' => $database,
        ], status: $healthy ? 200 : 503);
    }
}
