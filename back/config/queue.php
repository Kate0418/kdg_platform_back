<?php

return [
    "default" => env("QUEUE_CONNECTION", "sqs"),

    "connections" => [
        "sqs" => [
            "driver" => "sqs",
            "key" => env("AWS_ACCESS_KEY_ID"),
            "secret" => env("AWS_SECRET_ACCESS_KEY"),
            "prefix" => env(
                "SQS_PREFIX",
                "https://sqs.ap-northeast-1.amazonaws.com/your-account-id"
            ),
            "queue" => env("SQS_QUEUE", "your-queue-name"),
            "region" => env("AWS_DEFAULT_REGION", "ap-northeast-1"),
            "suffix" => env("SQS_SUFFIX"),
            "after_commit" => false,
        ],
    ],
];
