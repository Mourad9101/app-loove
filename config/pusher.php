<?php

return [
    'app_id' => $_ENV['PUSHER_APP_ID'] ?? '',
    'key' => $_ENV['PUSHER_KEY'] ?? '',
    'secret' => $_ENV['PUSHER_SECRET'] ?? '',
    'cluster' => $_ENV['PUSHER_CLUSTER'] ?? 'eu',
    'useTLS' => $_ENV['PUSHER_USE_TLS'] ?? true
];