<?php

namespace Modules\ChatHub\Repositories\Product;

interface ProductRepoInterface
{
    
       /**
     * Lấy danh sách sản phẩm theo type
     *
     * @param $input
     * @return mixed
     */
    public function getProducts($input);

}



