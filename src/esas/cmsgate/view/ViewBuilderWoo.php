<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 03.03.2020
 * Time: 16:07
 */

namespace esas\cmsgate\view;


use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

class ViewBuilderWoo extends ViewBuilder
{
    public static function elementAdminMessages()
    {
        return
            parent::elementMessages(
                "notice updated",
                "notice error",
                "notice error"
            );
    }

    public static function elementClientMessages()
    {
        return
            parent::elementMessages(
                "woocommerce-info",
                "woocommerce-error",
                "woocommerce-error"
            );
    }

    public static function elementMessage($class, $text)
    {
        return
            element::div(
                attribute::clazz($class),
                element::p(
                    element::content($text)
                )
            );
    }
}