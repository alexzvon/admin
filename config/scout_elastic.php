<?php

return [
    'client' => [
        'hosts' => [
            env('SCOUT_ELASTIC_HOST', '78.155.206.6:9235')
        ]
    ],
    'update_mapping' => env('SCOUT_ELASTIC_UPDATE_MAPPING', true),
    'indexer' => env('SCOUT_ELASTIC_INDEXER', 'single'),
    'document_refresh' => env('SCOUT_ELASTIC_DOCUMENT_REFRESH')
];