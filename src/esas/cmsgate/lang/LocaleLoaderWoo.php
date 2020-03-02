<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:09
 */

namespace esas\cmsgate\lang;

class LocaleLoaderWoo extends LocaleLoaderCms
{
    public function getLocale()
    {
        return get_locale();
    }


}