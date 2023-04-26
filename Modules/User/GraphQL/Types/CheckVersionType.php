<?php
namespace Modules\User\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

/**
 * Class CheckVersionType
 * @package Modules\User\GraphQL\Types
 * @author DaiDP
 * @since Dec, 2019
 */
class CheckVersionType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'CheckVersion',
        'description'   => 'Check version data'
    ];

    public function fields(): array
    {
        return [
            'is_update' => [
                'type' => Type::int(),
                'description' => 'Is need update. 1: There has a new version. 0: Current Version is lastest'
            ],
            'version' => [
                'type' => Type::string(),
                'description' => 'Lastest version'
            ],
            'release_date' => [
                'type' => Type::string(),
                'description' => 'Release date of Lastest version'
            ],
            'link' => [
                'type' => Type::string(),
                'description' => 'Link to download update file'
            ]
        ];
    }
}