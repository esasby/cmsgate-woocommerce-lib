<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate;

use esas\cmsgate\lang\LocaleLoaderWoo;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\wrappers\OrderWrapperWoo;
use esas\cmsgate\wrappers\SystemSettingsWrapperWoo;

class CmsConnectorWoo extends CmsConnector
{
        /**
     * Для удобства работы в IDE и подсветки синтаксиса.
     * @return $this
     */
    public static function getInstance() {
        return Registry::getRegistry()->getSystemSettingsWrapper();
    }

    public function createCommonConfigForm($managedFields)
    {
        // not implemented
    }

    public function createSystemSettingsWrapper()
    {
        return new SystemSettingsWrapperWoo();
    }

    public function createOrderWrapperByOrderId($orderId)
    {
        return new OrderWrapperWoo($orderId);
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
        $posts = get_posts( array(
            'meta_key'    => OrderWrapperWoo::EXTID_METADATA_KEY,
            'meta_value'  => $extId,
            'post_type'   => 'shop_order',
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
}