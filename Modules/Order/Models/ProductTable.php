<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductTable extends Model
{
    use ListTableTrait;
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const IS_REPRESENTATIVE = 1;

    public function getItem($id)
    {
        $oSelect = $this->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->leftJoin('product_model', 'product_model.product_model_id', '=', 'products.product_model_id')
            ->leftJoin('units', 'units.unit_id', '=', 'products.unit_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'products.supplier_id')
            ->select(
                'products.product_id as productId',
                'products.product_name as productName',
                'products.cost as cost',
                'products.price_standard as price',
                'products.is_promo as isPromo',
                'products.is_inventory_warning as isInventoryWarning',
                'products.inventory_warning as inventoryWarning',
                'products.is_actived as isActived',
                'product_categories.category_name as categoryName',
                'product_categories.product_category_id as productCategoryId',
                'product_model.product_model_name as productModelName',
                'product_model.product_model_id as productModelId',
                'suppliers.supplier_name as supplierName',
                'suppliers.supplier_id as supplierId',
                'units.name as unitName',
                'units.unit_id as unitId',
                'products.is_all_branch as isAllBranchPrice',
                'products.avatar as avatar',
                'products.description as description',
                'products.is_sales as isSale',
                'products.type_refer_commission',
                'products.refer_commission_value',
                'products.type_staff_commission',
                'products.staff_commission_value',
                'products.type_deal_commission',
                'products.deal_commission_value',
                'products.description_detail',
                'products.type_app',
                'products.percent_sale',
                'products.product_code',
                'products.inventory_management',
                'inventory_management'
            )
            ->where('products.is_deleted', 0)->where('products.product_id', $id)->first();
        return $oSelect;
    }
}