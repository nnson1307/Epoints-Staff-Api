<?php
namespace Modules\User\GraphQL\Queries;

use Closure;
use Modules\User\Repositories\User\UserRepoInterface;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

/**
 * Class MeQuery
 * @package Modules\User\GraphQL\Queries
 * @author DaiDP
 * @since Dec, 2019
 */
class MeQuery extends Query
{
    protected $attributes = [
        'name' => 'Get info of current user'
    ];

    public function type(): Type
    {
        return GraphQL::type('user::user');
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $rUser = app()->get(UserRepoInterface::class);

        return $rUser->getUserInfo();
    }
}