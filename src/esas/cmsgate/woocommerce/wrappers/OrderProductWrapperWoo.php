<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 14:01
 */

namespace esas\cmsgate\woocommerce\wrappers;

use esas\cmsgate\wrappers\OrderProductSafeWrapper;
use Throwable;
use WC_Order_Item;

class OrderProductWrapperWoo extends OrderProductSafeWrapper
{
    /**
     * @var WC_Order_Item
     */
    private $orderProduct;

    /**
     * OrderProductWrapperWoo constructor.
     * @param $orderProduct
     */
    public function __construct($orderProduct)
    {
        parent::__construct();
        $this->orderProduct = $orderProduct;
    }


    /**
     * Артикул товара
     * @throws Throwable
     * @return string
     */
    public function getInvIdUnsafe()
    {
        return $this->orderProduct->get_product_id();
    }

    /**
     * Название или краткое описание товара
     * @throws Throwable
     * @return string
     */
    public function getNameUnsafe()
    {
        return $this->orderProduct->get_name();
    }

    /**
     * Количество товароа в корзине
     * @throws Throwable
     * @return mixed
     */
    public function getCountUnsafe()
    {
        return $this->orderProduct->get_quantity();
    }

    /**
     * Цена за единицу товара
     * @throws Throwable
     * @return mixed
     */
    public function getUnitPriceUnsafe()
    {
//        return $this->orderProduct->get_subtotal();
        return $this->orderProduct->get_order()->get_item_total($this->orderProduct); //стоимость товара можно получить только так
    }
}