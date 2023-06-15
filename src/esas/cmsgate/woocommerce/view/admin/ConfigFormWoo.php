<?php

/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 30.09.2018
 * Time: 15:19
 */

namespace esas\cmsgate\woocommerce\view\admin;

use esas\cmsgate\lang\Translator;
use esas\cmsgate\view\admin\ConfigFormArray;
use esas\cmsgate\view\admin\fields\ConfigField;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldPassword;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;

class ConfigFormWoo extends ConfigFormArray
{
    private $orderStatuses;

    /**
     * ConfigFieldsRenderWoo constructor.
     */
    public function __construct($formKey, $managedFields)
    {
        parent::__construct($formKey, $managedFields);
        $orderStatuses = $array = wc_get_order_statuses();
        foreach ($orderStatuses as $statusKey => $statusName) {
            $this->orderStatuses[$statusKey] = new ListOption($statusKey, $statusName);
        }
    }

    public function generateTextField(ConfigField $configField)
    {
        $ret = array(
            'title' => $configField->getName(),
            'type' => 'text',
            'desc_tip' => $configField->getDescription()
        );
        if ($configField->hasDefault()) {
            $ret['default'] = $configField->getDefault();
        }
        return $ret;
    }

    public function generateTextAreaField(ConfigFieldTextarea $configField)
    {
        $ret = array(
            'title' => $configField->getName(),
            'type' => 'textarea',
            'desc_tip' => $configField->getDescription(),
            'css' => 'max-width:80%;'
        );
        if ($configField->hasDefault()) {
            $ret['default'] = $configField->getDefault();
        }
        return $ret;
    }

    public function generatePasswordField(ConfigFieldPassword $configField)
    {
        return array(
            'title' => $configField->getName(),
            'type' => 'password',
            'desc_tip' => $configField->getDescription()
        );
    }


    public function generateCheckboxField(ConfigFieldCheckbox $configField)
    {
        $ret = array(
            'title' => $configField->getName(),
            'type' => 'checkbox',
            'desc_tip' => $configField->getDescription(),
        );
        if ($configField->hasDefault()) {
            $ret['default'] = $configField->getDefault() ? "yes" : "no";
        }
        return $ret;
    }

    public function generateListField(ConfigFieldList $configField)
    {
        $optionsArray = array();
        foreach ($configField->getOptions() as $option) {
            $optionsArray[$option->getValue()] = $option->getName();
        }
        $ret = array(
            'title' => $configField->getName(),
            'type' => 'select',
            'desc_tip' => $configField->getDescription(),
            'options' => $optionsArray
        );
//        if ($configField->hasDefault()) {
//            $ret['default'] = $configField->getDefault();
//        }
        return $ret;
    }

    /**
     * @return ListOption[]
     */
    public function createStatusListOptions()
    {
        return $this->orderStatuses;
    }

    /**
     * Надо вызывать отдельно от конструктора, т.к. если для модуля будет несколько групп настроек в разных ConfigForm
     * возникает задвоение
     * @return $this
     */
    public function addCmsManagedFields()
    {
        $this->managedFields->addField(new ConfigFieldCheckbox(
            AdminViewFieldsWoo::ENABLE_MODULE,
            Translator::fromRegistry()->translate(AdminViewFieldsWoo::ENABLE_MODULE),
            ''
        ));
        return $this;
    }
}