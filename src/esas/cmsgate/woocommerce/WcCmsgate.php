<?php

namespace esas\cmsgate\woocommerce;

use esas\cmsgate\Registry;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\view\admin\ConfigForm;
use esas\cmsgate\messenger\Messages;
use esas\cmsgate\view\ViewUtils;
use Exception;
use Throwable;
use WC_Payment_Gateway;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WcCmsgate extends WC_Payment_Gateway
{

    /**
     * @var ConfigForm
     */
    protected $configForm;

    protected static $plugin_options = null;

    protected static $_instance = null;

    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Setup our Gateway's id, description and other values
    function __construct()
    {
        // The global ID for this Payment method
        $this->id = Registry::getRegistry()->getPaySystemName();
        // This basically defines your settings which are then loaded with init_settings()
        $this->init_form_fields();
        // After init_settings() is called, you can get the settings and load them into variables, e.g:
        // $this->title = $this->get_option( 'title' );
        $this->init_settings();
        // The Title shown on the top of the Payment Gateways Page next to all the other Payment Gateways
        $this->method_title = Registry::getRegistry()->getTranslator()->translate(Messages::ADMIN_PAYMENT_METHOD_NAME);
        // The description for this Payment Gateway, shown on the actual Payment options page on the backend
        $this->method_description = Registry::getRegistry()->getTranslator()->translate(Messages::ADMIN_PAYMENT_METHOD_DESCRIPTION);
        // The title to be used for the vertical tabs that can be ordered top to bottom
        $this->title = Registry::getRegistry()->getConfigWrapper()->getPaymentMethodName();
        // If you want to show an image next to the gateway's name on the frontend, enter a URL to an image.
        $this->icon = null;
        // Bool. Can be set to true if you want payment fields to show on the checkout
        // if doing a direct integration, which we are doing in this case
        $this->has_fields = true;
        // Supports the default description
        $this->supports = array('');
        $this->description = wpautop(Registry::getRegistry()->getConfigWrapper()->getPaymentMethodDetails());
        // Save settings
        if (is_admin()) {
            // Versions over 2.0
            // Save our administration options. Since we are not going to be doing anything special
            // we have not defined 'process_admin_options' in this class so the method in the parent
            // class will be used instead
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }
        // добавляем хук для отображение ошибок, почему-то в wooсommerce ошибки валидации настроек не отображются по умолчанию
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'display_settings_errors'));
    }

    /**
     * Переопределяем метод для подключения собственных валидаторов
     * @param string $key
     * @param array $field
     * @param array $post_data
     * @return string|void
     * @throws Exception
     */
    public function get_field_value($key, $field, $post_data = array())
    {
        $value = parent::get_field_value($key, $field, $post_data);
        $validationResult = $this->configForm->getManagedFields()->validate($key, $value);
        if (!$validationResult->isValid())
            throw new Exception($validationResult->getErrorTextFull()); //TODO
        return $value;
    }

    // Build the administration fields for this specific Gateway
    public function init_form_fields()
    {
        $this->configForm = Registry::getRegistry()->getConfigForm();
        $this->form_fields = $this->configForm->generate();
    }

    public function display_settings_errors()
    {
        $this->display_errors();
    }

    public function savesettings($configForm = null)
    {
        try {
            if ($configForm == null)
                $configForm = Registry::getRegistry()->getConfigForm();
            if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST)) && is_array($_POST) && count($_POST) > 0) {
                $configForm->validate();
                $configForm->save();
            }
        } catch (Throwable $e) {
            Logger::getLogger("SaveSettings")->error("Exception", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("SaveSettings")->error("Exception", $e);
        }
    }
}