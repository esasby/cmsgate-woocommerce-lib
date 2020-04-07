<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 07.04.2020
 * Time: 11:37
 */

namespace esas\cmsgate\wrappers;


class OrderWrapperFactoryWoo extends OrderWrapperFactory
{

    public function getOrderWrapperByOrderId($orderId)
    {
        return new OrderWrapperWoo($orderId);
    }

    public function getOrderWrapperByOrderForCurrentUser()
    {
        $currentUser = get_current_user_id();
        $lastOrder = wc_get_customer_last_order($currentUser);
        $orderId = $lastOrder->get_id();
        return $this->getOrderWrapperByOrderId($orderId);
    }

    public function getOrderWrapperByExtId($extId)
    {
        /** @var WP_Post[] $posts */
        $posts = get_posts( array(
            'meta_key'    => OrderWrapperWoo::EXTID_METADATA_KEY,
            'meta_value'  => $extId,
            'post_type'   => 'shop_order',
            'post_status' => 'any'
        ));
        $post = $posts[0];
        return $this->getOrderWrapperByOrderId($post->ID);
    }
}