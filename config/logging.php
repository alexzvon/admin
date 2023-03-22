<?php

return [

  // Default Log Channel
  'default' => 'stack',

    /*
    | Log Channels
    | Available Drivers: "single", "daily", "slack", "syslog", "errorlog", "custom", "stack"
    */
  'channels' => [


    'stack' => [
      'driver' => 'stack',
      'channels' => [
        'single',
        'sentry',
          //'slack',
      ],
    ],

    'sentry' => [
      'driver' => 'sentry',
    ],

    'single' => [
      'driver' => 'single',
      'path' => storage_path('logs/laravel.log'),
      'level' => 'debug',
    ],

    'daily' => [
      'driver' => 'daily',
      'path' => storage_path('logs/laravel.log'),
      'level' => 'debug',
      'days' => 7,
    ],

    'slack' => [
      'driver' => 'slack',
      'url' => 'https://hooks.slack.com/services/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',
      'username' => 'errors-candellabra-admin',
      'emoji' => ':boom:',
      'level' => 'critical',
    ],

    'syslog' => [
      'driver' => 'syslog',
      'level' => 'debug',
    ],

    'errorlog' => [
      'driver' => 'errorlog',
      'level' => 'debug',
    ],
  ],

];
