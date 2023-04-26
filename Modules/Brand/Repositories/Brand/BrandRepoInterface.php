<?php

namespace Modules\Brand\Repositories\Brand;

interface BrandRepoInterface
{

    /**
     * Đăng kí cộng tác viên
     * @param array $input
     * @return mixed
     */
    public function registerBrand($input);

    public function scanBrand($input);
}



