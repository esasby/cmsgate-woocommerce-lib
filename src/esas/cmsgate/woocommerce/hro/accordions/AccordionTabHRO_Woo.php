<?php


namespace esas\cmsgate\woocommerce\hro\accordions;


use esas\cmsgate\hro\accordions\AccordionTabHRO_v2;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

/**
 * Version based on CSS accordion
 * @package esas\cmsgate\hro\accordions
 */
class AccordionTabHRO_Woo extends AccordionTabHRO_v2
{

    public static function builder() {
        return new AccordionTabHRO_Woo();
    }

    public function build() {
        return
            element::li(
                attribute::id("tab-" . $this->key),
                attribute::clazz("tab wc_payment_method"),
                element::input(
                    attribute::id("input-" . $this->key),
                    attribute::type("radio"),
                    attribute::name("tabs2"),
                    attribute::clazz("input-radio"),
                    ($this->onlyOneTabEnabled ? attribute::style("display: none") : ""),
                    attribute::checked($this->checked)),
                element::label(
                    attribute::forr("input-" . $this->key),
                    attribute::clazz($this->getCssClass4TabHeaderLabel()),
                    element::content($this->header)
                ),
                $this->elementTabBody($this->key, $this->body)
            );
    }

    public function getCssClass4TabBodyContent() {
        return "payment_box";
    }

    /**
     * @return string
     */
    public function getCssClass4TabHeaderLabel() {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4TabBody() {
        return "";
    }

}