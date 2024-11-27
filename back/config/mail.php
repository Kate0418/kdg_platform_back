<?php

return [
    "default" => env("MAIL_MAILER", "ses"),

    "mailers" => [
        "ses" => [
            "transport" => "ses",
        ],
    ],

    "from" => [
        "address" => env("MAIL_FROM_ADDRESS", "your@email.com"),
        "name" => env("MAIL_FROM_NAME", "Your Name"),
    ],

    "ses" => [
        "key" => env("AWS_ACCESS_KEY_ID"),
        "secret" => env("AWS_SECRET_ACCESS_KEY"),
        "region" => env("AWS_DEFAULT_REGION", "us-east-1"),
    ],
];
