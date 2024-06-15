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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\CoreXDynamicTagsXTag as Tag;
use CE\CoreXFilesXCSSXBase as Base;
use CE\CoreXFilesXCSSXPost as Post;

class ModulesXCatalogXFilesXCSSXProductMiniature extends Post
{
    private $forceInline;

    public function __construct($post_id, $forceInline = false)
    {
        $this->forceInline = $forceInline;

        parent::__construct($post_id);
    }

    public function enqueue()
    {
        // CSS related dynamic tags are not supported
        remove_action('elementor/css-file/post/enqueue', [Plugin::$instance->dynamic_tags, 'afterEnqueuePostCss']);

        Base::enqueue();

        if ($this->forceInline) {
            $this->printCss();
        }
    }

    public function getMeta($property = null)
    {
        if (!$this->forceInline) {
            return parent::getMeta($property);
        }

        // Parse CSS first, to get the fonts list.
        $css = $this->getContent();

        $meta = [
            'status' => self::CSS_STATUS_INLINE,
            'fonts' => $this->getFonts(),
            'css' => $css,
        ];

        if ($property) {
            return isset($meta[$property]) ? $meta[$property] : null;
        }

        return $meta;
    }

    protected function parseContent()
    {
        Tag::setRenderMethod('renderSmarty');
        DataTag::setGetterMethod('getSmartyValue');

        $content = parent::parseContent();

        Tag::setRenderMethod('render');
        DataTag::setGetterMethod('getValue');

        return $content;
    }
}
