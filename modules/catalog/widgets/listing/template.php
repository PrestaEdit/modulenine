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

class ModulesXCatalogXWidgetsXListingXTemplate extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'listing-template';
    }

    public function getTitle()
    {
        return __('Listing Template');
    }

    public function getIcon()
    {
        return 'eicon-document-file';
    }

    public function getCategories()
    {
        return ['listing-elements'];
    }

    public function getKeywords()
    {
        return ['listing', 'template', 'theme'];
    }

    protected function getListingOptions()
    {
        $options = [];
        $ps = _DB_PREFIX_;
        $id_lang = (int) $this->context->language->id;
        $id_shop = (int) $this->context->shop->id;

        if ($rows = \Db::getInstance()->executeS("
            SELECT t.`id_ce_theme` AS id, tl.`title` FROM `{$ps}ce_theme` t
            INNER JOIN `{$ps}ce_theme_lang` tl ON tl.`id_ce_theme` = t.`id_ce_theme` AND tl.`id_lang` = $id_lang AND tl.`id_shop` = $id_shop
            WHERE t.`active` = 1 AND t.`type` = 'listing-page'
        ")) {
            $type = __('Listing Page');

            foreach ($rows as &$row) {
                $options[$row['id']] = "#{$row['id']} {$row['title']} ($type)";
            }
        }

        return $options;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_template',
            [
                'label' => __('Listing Template'),
            ]
        );

        $this->addControl(
            'theme_id',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'options' => $this->context->controller instanceof \AdminCEEditorController ? $this->getListingOptions() : [],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        if ($id = $this->getSettings('theme_id')) {
            $uid = new UId($id, UId::THEME, $this->context->language->id, $this->context->shop->id);

            echo Plugin::$instance->frontend->getBuilderContentForDisplay($uid);
        }
    }

    public function renderPlainContent()
    {
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }
}
