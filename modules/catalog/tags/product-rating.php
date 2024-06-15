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

class ModulesXCatalogXTagsXProductRating extends Tag
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-rating';
    }

    public function getTitle()
    {
        return __('Product Rating');
    }

    public function getGroup()
    {
        return Module::CATALOG_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'type';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'type',
            [
                'label' => __('Field'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'average_grade' => __('Average Grade'),
                    'nb_comments' => __('Comments Number'),
                ],
                'default' => 'average_grade',
            ]
        );
    }

    public function render()
    {
        $vars = &\Context::getContext()->smarty->tpl_vars;
        $product = &$vars['product']->value;

        switch ($this->getSettings('type')) {
            case 'average_grade':
                if (isset($product['productComments'])) {
                    echo $product['productComments']['averageRating'];
                } elseif (isset($vars['ratings'])) {
                    echo $vars['ratings']->value['avg'];
                }
                break;
            case 'nb_comments':
                if (isset($product['productComments'])) {
                    echo $product['productComments']['nbComments'];
                } elseif (isset($vars['nbComments'])) {
                    echo $vars['nbComments']->value;
                }
                break;
        }
    }

    protected function renderSmarty()
    {
        if ($this->getSettings('type') === 'average_grade') {
            echo '{if isset($product.productComments)}{$product.productComments.averageRating}{elseif isset($ratings.avg)}{$ratings.avg}{/if}';
        } else {
            echo '{if isset($product.productComments)}{$product.productComments.nbComments}{elseif isset($nbComments)}{$nbComments}{/if}';
        }
    }
}
