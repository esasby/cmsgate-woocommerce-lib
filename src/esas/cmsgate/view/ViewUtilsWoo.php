<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 02.03.2020
 * Time: 11:27
 */

namespace esas\cmsgate\view;


class ViewUtilsWoo extends ViewUtils
{
    public static function logAndGetMsg($loggerName, $ex)
    {
        $message = parent::logAndGetMsg($loggerName, $ex);
        wc_add_notice($message, 'error');
    }

}