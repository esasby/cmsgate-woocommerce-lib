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


    /**
     * LocaleLoaderWoo constructor.
     */
    public function __construct()
    {
        $this->addExtraVocabularyDir(dirname(__FILE__));
    }

    public function getLocale()
    {
        return get_locale();
    }

}