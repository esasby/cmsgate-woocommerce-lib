<?php

use esas\cmsgate\Registry;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Add settings link
//add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cmsgate_settings_link');
function cmsgate_settings_link($links)
{
    $plugin_links = array(
        element::a(
            attribute::href(admin_url('admin.php?page=wc-settings&tab=checkout&section=' . Registry::getRegistry()->getPaySystemName())),
            element::content(\esas\cmsgate\lang\Translator::fromRegistry()->translate(AdminViewFields::SETTINGS))
        )->__toString()
    );
    // Merge our new link with the default ones
    return array_merge($plugin_links, $links);
}
