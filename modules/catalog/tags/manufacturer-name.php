<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\CoreXDynamicTagsXTag as Tag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXCatalogXTagsXManufacturerName extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'manufacturer-name';
    }

    public function getTitle()
    {
        return __('Brand Name');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function render()
    {
        $vars = &\Context::getContext()->smarty->tpl_vars;

        echo isset($vars['product_manufacturer']) ? $vars['product_manufacturer']->value->name : (
            isset($vars['manufacturer']) ? $vars['manufacturer']->value['name'] : ''
        );
    }

    protected function renderSmarty()
    {
        echo '{if $product.id_manufacturer}' .
                '{$ce_brand = ce_new(Manufacturer, $product.id_manufacturer, $language.id)}' .
                '{$ce_brand->name}' .
            '{/if}';
    }
}
