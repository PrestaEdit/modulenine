<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_10_1($module)
{
    Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

    // Register hooks
    $module->registerHook('displayOverrideTemplate');
    $module->registerHook('filterProductSearch', null, 1);

    // Clear caches
    CE\Plugin::instance()->files_manager->clearCache();
    Media::clearCache();

    // Alter DB tables character set
    $ps = _DB_PREFIX_;
    $db = Db::getInstance();
    $res = $db->execute("ALTER TABLE `{$ps}ce_meta` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_revision` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_template` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_content` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_content_shop` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_content_lang` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_theme` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_theme_shop` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_theme_lang` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_icon_set` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
        && $db->execute("ALTER TABLE `{$ps}ce_font` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Remove useless files
    foreach ([
        'classes/assets/CEAssetManager-1.6.php',
        'core/base/header-footer-base.php',
        'core/base/theme-document.php',
        'core/base/theme-page-document.php',
        'core/document-types/content.php',
        'core/document-types/footer.php',
        'core/document-types/header.php',
        'includes/editor.php',
        'includes/base/widget-category-base.php',
        'includes/base/widget-product-base.php',
        'includes/managers/schemes.php',
        'includes/widgets/ajax-search.php',
        'includes/widgets/animated-headline.php',
        'includes/widgets/breadcrumb.php',
        'includes/widgets/call-to-action.php',
        'includes/widgets/category-tree.php',
        'includes/widgets/contact-form.php',
        'includes/widgets/countdown.php',
        'includes/widgets/currency-selector.php',
        'includes/widgets/email-subscription.php',
        'includes/widgets/facebook-button.php',
        'includes/widgets/facebook-page.php',
        'includes/widgets/flip-box.php',
        'includes/widgets/image-hotspot.php',
        'includes/widgets/image-slider.php',
        'includes/widgets/language-selector.php',
        'includes/widgets/layer-slider.php',
        'includes/widgets/module.php',
        'includes/widgets/nav-menu.php',
        'includes/widgets/shopping-cart.php',
        'includes/widgets/sign-in.php',
        'includes/widgets/site-logo.php',
        'includes/widgets/site-title.php',
        'includes/widgets/testimonial-carousel.php',
        'includes/widgets/trustedshops-reviews.php',
        'modules/catalog/widgets/manufacturer-image.php',
        'modules/dynamic-tags/tags/cart-rule-date-time.php',
        'modules/dynamic-tags/tags/specific-price-rule-date-time.php',
        'views/css/admin.css',
        'views/css/media.css',
        'views/js/admin.js',
        'views/templates/front/preview-1.6.tpl',
        'views/templates/front/theme/layouts/layout-product-block.tpl',
    ] as $file) {
        file_exists(_CE_PATH_ . $file) && unlink(_CE_PATH_ . $file);
    }
    foreach ([
        'includes/schemes',
        'includes/traits',
        'modules/creative',
        'modules/sticky',
        'views/lib/font-awesome/fonts',
        'views/lib/iris',
        'views/lib/jquery-easing',
        'views/lib/tinymce/plugins',
        'views/lib/tinymce/skins',
        'views/lib/wp-color-picker',
        'views/templates/front/theme-1.6',
    ] as $dir) {
        Tools::deleteDirectory(_CE_PATH_ . $dir);
    }
    array_map('unlink', glob(_CE_PATH_ . 'core/document-types/page-*.php', GLOB_NOSORT));
    array_map('unlink', glob(_CE_PATH_ . 'core/document-types/prod*.php', GLOB_NOSORT));
    array_map('unlink', glob(_CE_PATH_ . 'includes/widgets/product-*.php', GLOB_NOSORT));
    array_map('unlink', glob(_CE_PATH_ . 'modules/catalog/widgets/product-*.php', GLOB_NOSORT));

    return $res;
}
