<?php


namespace esas\cmsgate\woocommerce\hro\panels;


use esas\cmsgate\hro\panels\MessagesPanelHRO_v1;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

class MessagesPanelHRO_Woo extends MessagesPanelHRO_v1
{
    public static function builder() {
        return new MessagesPanelHRO_Woo();
    }

    protected function elementAlertError($message) {
        wc_add_notice($message, 'error');
        return $this->elementWoocommerceMessage($message, "woocommerce-error");
    }

    protected function elementAlertInfo($message) {
        wc_add_notice($message, 'notice');
        return $this->elementWoocommerceMessage($message, "woocommerce-info");
    }

    protected function elementAlertWarn($message) {
        wc_add_notice($message, 'error');
        return $this->elementWoocommerceMessage($message, "woocommerce-error");
    }

    protected function elementAlertSuccess($message) {
        wc_add_notice($message, 'success');
        return $this->elementWoocommerceMessage($message, "woocommerce-message");
    }


    protected function elementWoocommerceMessage($message, $class) {
        return element::div(
            attribute::clazz($class),
            element::content($message)
        );
    }
}