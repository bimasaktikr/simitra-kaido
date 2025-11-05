<?php

return [
    'base_url' => env('ML_API_BASE_URL', 'http://localhost:8001'),
    
    // ML API Configuration
    'ml_api_url' => env('ML_API_URL', 'http://localhost:8001'),
    'airflow_url' => env('AIRFLOW_URL', 'http://localhost:8080/api/v1'),
    
    'navigation' => [
        'token' => [
            'cluster' => null,
            'group' => 'User',
            'sort' => -1,
            'icon' => 'heroicon-o-key',
        ],
    ],
    'models' => [
        'token' => [
            'enable_policy' => true,
        ],
    ],
    'route' => [
        'panel_prefix' => false,
        'use_resource_middlewares' => false,
    ],
    'tenancy' => [
        'enabled' => false,
        'awareness' => false,
    ],
];
