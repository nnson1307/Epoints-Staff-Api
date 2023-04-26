<?php

return [
    'schemas' => [
        'delivery' => [
            'query' => [
                // 'example_query' => ExampleQuery::class,
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
    ],
];