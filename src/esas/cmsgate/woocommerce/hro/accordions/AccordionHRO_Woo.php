<?php


namespace esas\cmsgate\woocommerce\hro\accordions;


use esas\cmsgate\hro\accordions\AccordionHRO_v2;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\CssPreset;

class AccordionHRO_Woo extends AccordionHRO_v2
{
    public static function builder() {
        return new AccordionHRO_Woo();
    }

    public function build() {
        return element::div(
            attribute::id("payment"),
            element::ul(
                attribute::clazz("wc_payment_methods payment_methods methods"),
                $this->tabs
            ),
            CssPreset::elementAccordionV1(),
            element::styleFile(dirname(__FILE__) . "/liCorrection.css")
        );
    }
}