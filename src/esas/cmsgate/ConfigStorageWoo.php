<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 13:14
 */

namespace esas\cmsgate;

use Exception;

class ConfigStorageWoo extends ConfigStorageCms
{
    private $settings;

    public function __construct()
    {
        parent::__construct();
        $this->settings = get_option("woocommerce_" . Registry::getRegistry()->getPaySystemName() . "_settings", null);
    }


    /**
     * @param $key
     * @return string
     * @throws Exception
     */
    public function getConfig($key)
    {
        if (array_key_exists($key, $this->settings))
            return $this->settings[$key];
        else
            return "";
    }

    /**
     * @param $cmsConfigValue
     * @return bool
     * @throws Exception
     */
    public function convertToBoolean($cmsConfigValue)
    {
        return strtolower($cmsConfigValue) == 'yes';
    }

    /**
     * Сохранение значения свойства в харнилища настроек конкретной CMS.
     *
     * @param string $key
     * @throws Exception
     */
    public function saveConfig($key, $value)
    {
//        update_option( $option_name, $newvalue, $autoload ) //скорее всего тут все обновляется группой
        $this->settings[$key] = $value;
        update_option("woocommerce_" . Registry::getRegistry()->getPaySystemName() . "_settings", $this->settings);
        //todo D:/work/esas/sources/php/CMSSources/wordpress/wp-content/plugins/woocommerce/includes/abstracts/abstract-wc-settings-api.php:219
    }

    public function getConstantConfigValue($key)
    {
        switch ($key) {
            case ConfigFields::useOrderNumber(): // в woo orderNumber управляется внешними плагинами, поэтому перекладываем на них ответсвенность
                return true;
            default:
                return parent::getConstantConfigValue($key);
        }
    }
}