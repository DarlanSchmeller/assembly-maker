<?php

use PhpMqtt\Client\Repositories\MemoryRepository;

return [

    'default_connection' => 'default',

    'connections' => [

        'default' => [
            'host' => env('MQTT_HOST', 'broker.hivemq.com'),
            'port' => env('MQTT_PORT', 1883),
            'client_id' => env('MQTT_CLIENT_ID', 'laravel_mqtt_client_001'),
            'use_tls' => env('MQTT_USE_TLS', false),
            'tls_profile' => null,
            'username' => env('MQTT_AUTH_USERNAME'),
            'password' => env('MQTT_AUTH_PASSWORD'),
            'clean_session' => env('MQTT_CLEAN_SESSION', true),
            'repository' => MemoryRepository::class,
            'logging' => [
                'enable' => env('MQTT_LOG_ENABLE', false),
                'channel' => env('MQTT_LOG_CHANNEL', null),
            ],
            'connection_settings' => [
                'keep_alive_interval' => 60,
                'reconnect_retries' => 10,
                'reconnect_delay' => 3,
                'resend_timeout' => 10,
            ],
        ],

    ],

];
