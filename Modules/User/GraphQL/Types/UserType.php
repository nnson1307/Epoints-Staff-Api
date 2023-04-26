<?php
namespace Modules\User\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

/**
 * Class UserType
 * @package Modules\User\GraphQL\Types
 * @author DaiDP
 * @since Dec, 2019
 */
class UserType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'UserType',
        'description'   => 'User data'
    ];

    public function fields(): array
    {
        return [
            'staff_id' => [
                'type' => Type::int(),
                'description' => 'Staff ID'
            ],
            'department_id' => [
                'type' => Type::int(),
                'description' => 'Staff department ID'
            ],
            'branch_id' => [
                'type' => Type::int(),
                'description' => 'Brand ID'
            ],
            'staff_title_id' => [
                'type' => Type::int(),
                'description' => 'Staff Title ID'
            ],
            'user_name' => [
                'type' => Type::string(),
                'description' => 'Username use for login'
            ],
            'full_name' => [
                'type' => Type::string(),
                'description' => 'Full name'
            ],
            'birthday' => [
                'type' => Type::string(),
                'description' => 'Day of birth'
            ],
            'gender' => [
                'type' => Type::string(),
                'description' => 'Gender'
            ],
            'phone1' => [
                'type' => Type::string(),
                'description' => 'Main mobile phone'
            ],
            'phone2' => [
                'type' => Type::string(),
                'description' => 'Backup mobile phone'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'Email'
            ],
            'facebook' => [
                'type' => Type::string(),
                'description' => 'Facebook url'
            ],
            'date_last_login' => [
                'type' => Type::string(),
                'description' => 'Last login'
            ],
            'is_admin' => [
                'type' => Type::string(),
                'description' => 'Is supper admin'
            ],
            'staff_avatar' => [
                'type' => Type::string(),
                'description' => 'Avatar of staff'
            ],
            'address' => [
                'type' => Type::string(),
                'description' => 'Staff home address'
            ]
        ];
    }
}