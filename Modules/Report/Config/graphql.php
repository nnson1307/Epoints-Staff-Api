<?php

return [
    'schemas' => [
        'default' => [
            'query' => [
                'check_version' => \Modules\User\GraphQL\Queries\CheckVersionQuery::class,
            ]
        ],
        'user' => [
            'query' => [
                // 'example_query' => ExampleQuery::class,
                'me' => \Modules\User\GraphQL\Queries\MeQuery::class,
            ],
            'mutation' => [
                // 'example_mutation'  => ExampleMutation::class,
            ],
            'middleware' => ['auth'],
            'method' => ['get', 'post'],
        ],
    ],
    'types' => [
        // 'example'           => ExampleType::class,
        // 'relation_example'  => ExampleRelationType::class,
        // \Rebing\GraphQL\Support\UploadType::class,
        'user::check_version' => \Modules\User\GraphQL\Types\CheckVersionType::class,
        'user::user' => \Modules\User\GraphQL\Types\UserType::class,
    ],
];