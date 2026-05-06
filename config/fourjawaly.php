<?php

return [
    'api_key' => env('FOURJAWALY_API_KEY'),
    'api_secret' => env('FOURJAWALY_API_SECRET'),
    'sender_name' => env('FOURJAWALY_SENDER_NAME'),
    'four_jawaly_base_url' => env('FOURJAWALY_BASE_URL', 'https://api-sms.4jawaly.com/api/v1'),
];