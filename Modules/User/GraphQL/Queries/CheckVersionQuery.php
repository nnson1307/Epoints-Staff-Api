<?php
namespace Modules\User\GraphQL\Queries;

use Closure;
use Modules\User\Repositories\Device\DeviceRepoInterface;
use MyCore\GraphQL\Support\BaseAuthQuery;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

/**
 * Class CheckVersionQuery
 * @package Modules\User\GraphQL\Queries
 * @author DaiDP
 * @since Dec, 2019
 */
class CheckVersionQuery extends BaseAuthQuery
{
    protected $attributes = [
        'name' => 'Check version query'
    ];

    public function type(): Type
    {
        return GraphQL::type('user::check_version');
    }

    public function args(): array
    {
        return [
            'platform' => ['name' => 'platform', 'type' => Type::nonNull(Type::string())],
            'version' => ['name' => 'version', 'type' => Type::nonNull(Type::string())],
            'release_date' => ['name' => 'release_date', 'type' => Type::nonNull(Type::string())]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $rDevice = app()->get(DeviceRepoInterface::class);

        return $rDevice->checkVersion($args);
    }
}