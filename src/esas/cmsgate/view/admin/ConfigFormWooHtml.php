<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 02.03.2020
 * Time: 16:05
 */

namespace esas\cmsgate\view\admin;


use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\view\admin\fields\ConfigField;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldFile;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldPassword;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;

class ConfigFormWooHtml extends ConfigFormHtml
{
    private $orderStatuses;

    public function __construct($managedFields, $headingTitle, $submitUrl, $submitButtons)
    {
        parent::__construct($managedFields, $headingTitle, $submitUrl, $submitButtons);
        $orderStatuses = $array = wc_get_order_statuses();
        foreach ($orderStatuses as $statusKey => $statusName) {
            $this->orderStatuses[$statusKey] = new ListOption($statusKey, $statusName);
        }
    }


    public function generate()
    {
        return
            element::form(
                attribute::action($this->getSubmitUrl()),
                attribute::method("post"),
                attribute::enctype("multipart/form-data"),
                attribute::id("config-form"),
                element::div(
                    attribute::clazz("wrap"),
                    element::h2($this->getHeadingTitle()),
                    element::table(
                        attribute::clazz("form-table"),
                        parent::generate() // добавляем поля
                    )
                ),
                element::p(
                    attribute::clazz("submit"),
                    $this->elementSubmitButtons()
                )
            );
    }

    private function elementSubmitButtons()
    {
        $ret = "";
        if (isset($this->submitButtons)) {
            foreach ($this->submitButtons as $buttonName => $buttonValue) {
                $ret .= self::elementInputSubmit($buttonName, $buttonValue) . "&nbsp;";
            }
//        } else if (isset($this->submitUrl))
        } else
            $ret = self::elementInputSubmit("submit_button", Registry::getRegistry()->getTranslator()->translate(AdminViewFields::CONFIG_FORM_BUTTON_SAVE));
        return $ret;
    }

    private static function elementInputSubmit($name, $value)
    {
        return
            element::input(
                attribute::clazz("button-primary woocommerce-save-button"),
                attribute::type("submit"),
                attribute::name($name),
                attribute::value($value)
            );
    }

    /**
     * Формирование html разметки для текстового поля. Он используется по умолчанию и для всех остальных типов полей
     * (если не переопределены соответствующие методы)
     * @param ConfigField $configField
     * @return mixed
     */
    function generateTextField(ConfigField $configField)
    {
        return
            self::elementTr(
                $configField,
                self::elementInput($configField, "text")
            );
    }

    public function generatePasswordField(ConfigFieldPassword $configField)
    {
        return
            self::elementTr(
                $configField,
                self::elementInput($configField, "password")
            );
    }

    public function generateTextAreaField(ConfigFieldTextarea $configField)
    {
        return
            self::elementTr(
                $configField,
                element::textarea(
                    attribute::rows("3"),
                    attribute::cols("20"),
                    attribute::clazz("input-text wide-input "),
                    attribute::type("textarea"),
                    attribute::name($configField->getKey()),
                    attribute::id($configField->getKey()),
                    attribute::style("max-width:80%;"),
                    element::content($configField->getValue())
                )
            );
    }


    public function generateFileField(ConfigFieldFile $configField)
    {
        return
            element::tr(
                attribute::valign("top"),
                self::elementTh($configField),
                element::td(
                    element::input(
                        attribute::type("file"),
                        attribute::name($configField->getKey())
                    ),
                    self::elementValidationError($configField),
                    element::p(
                        element::font(
                            attribute::color("green"),
                            element::content($configField->getValue())
                        )
                    )
                )
            );
    }

    public function generateCheckboxField(ConfigFieldCheckbox $configField)
    {
        return
            self::elementTr(
                $configField,
                element::input(
                    attribute::type("checkbox"),
                    attribute::name($configField->getKey()),
                    attribute::value("yes"),
                    attribute::checked($configField->isChecked())
                )
            );
    }

    public function generateListField(ConfigFieldList $configField)
    {
        return
            self::elementTr(
                $configField,
                element::select(
                    attribute::clazz("select"),
                    attribute::name($configField->getKey()),
                    attribute::id($configField->getKey()),
                    parent::elementOptions($configField)
                )
            );
    }


    public static function elementLabel(ConfigField $configField)
    {
        return
            element::label(
                attribute::forr($configField->getKey()),
                element::content($configField->getName()),
                element::span(
                    attribute::data_toggle("tooltip"),
                    attribute::title($configField->getDescription())

                )
            );
    }

    private static function elementInput(ConfigField $configField, $type)
    {
        return
            element::input(
                attribute::clazz("input-text regular-input"),
                attribute::name($configField->getKey()),
                attribute::type($type),
                attribute::id($configField->getKey()),
                attribute::placeholder($configField->getName()),
                attribute::value($configField->getValue())
            );
    }

    private static function elementTr(ConfigField $configField, $thContent)
    {
        return
            element::tr(
                attribute::valign("top"),
                self::elementTh($configField),
                element::td(
                    attribute::clazz("forminp"),
                    $thContent,
                    self::elementValidationError($configField)
                )
            );
    }

    private static function elementTh(ConfigField $configField)
    {
        return
            element::th(
                attribute::scope("row"),
                attribute::clazz("titledesc"),
//                element::content($configField->getName())
                self::elementLabel($configField)
            );
    }

    public static function elementValidationError(ConfigField $configField)
    {
        $validationResult = $configField->getValidationResult();
        if ($validationResult != null && !$validationResult->isValid())
            return
                element::p(
                    element::font(
                        attribute::color("red"),
                        element::content($validationResult->getErrorTextSimple())
                    )
                );
        else
            return "";
    }


    /**
     * @return ListOption[]
     */
    public function createStatusListOptions()
    {
        return $this->orderStatuses;
    }
}