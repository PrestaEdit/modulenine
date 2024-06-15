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

class ModulesXPremiumXWidgetsXTemplate extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'template';
    }

    public function getTitle()
    {
        return __('Template');
    }

    public function getIcon()
    {
        return 'eicon-document-file';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['template', 'library', 'block', 'page', 'content', 'anywhere'];
    }

    protected function getTemplateOptions()
    {
        $options = [];
        $ps = _DB_PREFIX_;

        if ($rows = \Db::getInstance()->executeS("
            SELECT `id_ce_template` AS id, `title`, `type` FROM `{$ps}ce_template`
            WHERE `active` = 1 AND `type` IN ('block', 'page')
        ")) {
            $types = [
                'block' => __('Block'),
                'page' => __('Page'),
            ];
            foreach ($rows as &$row) {
                $options[$row['id']] = "#{$row['id']} {$row['title']} ({$types[$row['type']]})";
            }
        }

        return $options;
    }

    protected function getContentOptions()
    {
        $options = [];
        $ps = _DB_PREFIX_;
        $id_lang = (int) $this->context->language->id;
        $id_shop = (int) $this->context->shop->id;

        if ($rows = \Db::getInstance()->executeS("
            SELECT c.`id_ce_content` AS id, cl.`title`, c.`hook` FROM `{$ps}ce_content` c
            INNER JOIN `{$ps}ce_content_lang` cl ON cl.`id_ce_content` = c.`id_ce_content` AND cl.`id_lang` = $id_lang AND cl.`id_shop` = $id_shop
            WHERE c.`active` = 1 AND c.`id_product` = 0
        ")) {
            foreach ($rows as &$row) {
                $options[$row['id']] = "#{$row['id']} {$row['title']} ({$row['hook']})";
            }
        }

        return $options;
    }

    protected function _registerControls()
    {
        $is_admin = $this->context->controller instanceof \AdminCEEditorController;

        $this->startControlsSection(
            'section_template',
            [
                'label' => __('Template'),
            ]
        );

        $this->addControl(
            'type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'content' => __('Content Anywhere'),
                    'template' => __('Saved Templates'),
                ],
                'default' => 'template',
            ]
        );

        $this->addControl(
            'template_id',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'options' => $is_admin ? $this->getTemplateOptions() : [],
                'condition' => [
                    'type' => 'template',
                ],
            ]
        );

        $this->addControl(
            'content_id',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'placeholder' => __('Select...'),
                ],
                'options' => $is_admin ? $this->getContentOptions() : [],
                'condition' => [
                    'type' => 'content',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if ('template' === $settings['type']) {
            $uid = new UId($settings['template_id'], UId::TEMPLATE);
        } elseif ('content' === $settings['type']) {
            $uid = new UId($settings['content_id'], UId::CONTENT, $this->context->language->id, $this->context->shop->id);
        }
        $uid->id && print Plugin::$instance->frontend->getBuilderContentForDisplay($uid);
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
