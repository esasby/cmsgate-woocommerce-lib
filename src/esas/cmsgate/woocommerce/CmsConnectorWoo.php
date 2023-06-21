<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate\woocommerce;

use esas\cmsgate\CmsConnector;
use esas\cmsgate\descriptors\CmsConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\hro\HROManager;
use esas\cmsgate\hro\panels\MessagesPanelHRO;
use esas\cmsgate\Registry;
use esas\cmsgate\woocommerce\hro\panels\MessagesPanelHRO_Woo;
use esas\cmsgate\woocommerce\lang\LocaleLoaderWoo;
use esas\cmsgate\woocommerce\wrappers\OrderWrapperWoo;
use WP_Post;

class CmsConnectorWoo extends CmsConnector
{
    /**
     * Для удобства работы в IDE и подсветки синтаксиса.
     * @return $this
     */
    public static function fromRegistry()
    {
        return Registry::getRegistry()->getCmsConnector();
    }

    public function init() {
        parent::init();

        HROManager::fromRegistry()->addImplementation(MessagesPanelHRO::class, MessagesPanelHRO_Woo::class);
    }


    public function createCommonConfigForm($managedFields)
    {
        // not implemented
    }

    public function createOrderWrapperByOrderId($orderId)
    {
        return new OrderWrapperWoo($orderId);
    }

    public function createOrderWrapperByOrderNumber($orderNumber)
    {
        /** @var WP_Post[] $posts */
        $posts = get_posts(array(
            'meta_key' => OrderWrapperWoo::EXTID_ORDER_NUMBER_KEY,
            'meta_value' => $orderNumber,
            'post_type' => 'shop_order',
            'post_status' => 'any'
        ));
        $post = $posts[0];
        return $this->createOrderWrapperByOrderId($post->ID);
    }

    public function createOrderWrapperForCurrentUser()
    {
        $currentUser = get_current_user_id();
        $lastOrder = wc_get_customer_last_order($currentUser);
        $orderId = $lastOrder->get_id();
        return $this->createOrderWrapperByOrderId($orderId);
    }

    public function createOrderWrapperByExtId($extId)
    {
        /** @var WP_Post[] $posts */
        $posts = get_posts(array(
            'meta_key' => OrderWrapperWoo::EXTID_METADATA_KEY,
            'meta_value' => $extId,
            'post_type' => 'shop_order',
            'post_status' => 'any'
        ));
        $post = $posts[0];
        return $this->createOrderWrapperByOrderId($post->ID);
    }

    public function createConfigStorage()
    {
        return new ConfigStorageWoo();
    }

    public function createLocaleLoader()
    {
        return new LocaleLoaderWoo();
    }

    public function createCmsConnectorDescriptor()
    {
        return new CmsConnectorDescriptor(
            "cmsgate-woocommerce-lib",
            new VersionDescriptor(
                "v2.0.1",
                "2023-06-21"
            ),
            "Cmsgate Woocommerce connector",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-woocommerce-lib/browse",
            VendorDescriptor::esas(),
            "woocommerce"
        );
    }


}