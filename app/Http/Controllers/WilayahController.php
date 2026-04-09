<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WilayahController extends Controller
{
    private const BASE_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api';
    private const CACHE_TTL = 86400; // 24 jam

    public function provinces(): JsonResponse
    {
        $data = Cache::remember('wilayah.provinces', self::CACHE_TTL, function () {
            $response = Http::timeout(10)->get(self::BASE_URL . '/provinces.json');
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data);
    }

    public function regencies(string $provinceId): JsonResponse
    {
        $data = Cache::remember("wilayah.regencies.{$provinceId}", self::CACHE_TTL, function () use ($provinceId) {
            $response = Http::timeout(10)->get(self::BASE_URL . "/regencies/{$provinceId}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data);
    }
}
