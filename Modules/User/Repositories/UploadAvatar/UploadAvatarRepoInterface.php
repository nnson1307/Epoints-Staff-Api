<?php

namespace Modules\User\Repositories\UploadAvatar;

/**
 * Interface AuthenRepoInterface
 * @package Modules\User\Repositories\UploadAvatar
 * @author todh
 * @since April, 2023
 */
interface UploadAvatarRepoInterface
{
    /**
     * upload avatar by links
     * @param array $all
     * @return mixed
     */
    public function uploadAvatarByAppLinks(array $all);
}
