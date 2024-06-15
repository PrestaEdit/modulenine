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

class CEFont extends ObjectModel
{
    public $family;
    public $files;

    public static $definition = [
        'table' => 'ce_font',
        'primary' => 'id_ce_font',
        'fields' => [
            'family' => ['type' => self::TYPE_STRING, 'validate' => 'isImageTypeName', 'required' => true, 'size' => 128],
            'files' => ['type' => self::TYPE_STRING, 'validate' => 'isJson'],
        ],
    ];

    protected static $format = [
        'woff' => 'woff',
        'woff2' => 'woff2',
        'ttf' => 'truetype',
        'otf' => 'opentype',
    ];

    public static function getAllowedExt()
    {
        return array_keys(self::$format);
    }

    public static function familyExists($family, $exclude_id = 0)
    {
        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'ce_font';
        $id_ce_font = (int) $exclude_id;
        $family = $db->escape($family);

        return (bool) $db->getValue(
            "SELECT `id_ce_font` FROM `$table` WHERE `family` = '$family' AND `id_ce_font` != $id_ce_font"
        );
    }

    public static function generateFontsList()
    {
        $fonts = [];
        $font_types = [];
        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'ce_font';
        $rows = $db->executeS("SELECT `family`, `files` FROM `$table` ORDER BY `family`");

        if ($rows) {
            foreach ($rows as &$row) {
                if ($font_face = self::getFontFaceFromData($row['family'], $row['files'])) {
                    $fonts[$row['family']] = ['font_face' => $font_face];
                }
                $font_types[$row['family']] = 'custom';
            }
        }
        Configuration::updateGlobalValue('elementor_fonts_manager_fonts', json_encode($fonts));
        Configuration::updateGlobalValue('elementor_fonts_manager_font_types', json_encode($font_types));
    }

    public static function getFontFaceFromData($family, $data)
    {
        is_array($data) || $data = json_decode($data, true);

        if (!$data) {
            return '';
        }
        ob_start();

        foreach ($data as &$font) {
            $src = [];

            foreach (self::$format as $ext => $format) {
                if (!empty($font[$ext]['url'])) {
                    if (strpos($font[$ext]['url'], 'modules/') === 0 || strpos($font[$ext]['url'], 'themes/') === 0) {
                        $url = '{{BASE}}' . trim($font[$ext]['url']);
                    } else {
                        $url = trim($font[$ext]['url']);
                    }
                    $src[] = "url('$url') format('$format')";
                }
            }
            $src = implode(",\n\t\t", $src);

            echo "@font-face {\n";
            echo "\tfont-family: '$family';\n";
            echo "\tfont-weight: {$font['font_weight']};\n";
            echo "\tfont-style: {$font['font_style']};\n";
            echo "\tfont-display: swap;\n";
            echo "\tsrc: $src;\n";
            echo "}\n";
        }

        return ob_get_clean();
    }

    public function add($auto_date = true, $null_values = false)
    {
        if ($result = parent::add($auto_date, $null_values)) {
            self::generateFontsList();
        }

        return $result;
    }

    public function update($null_values = false)
    {
        if ($result = parent::update($null_values)) {
            self::generateFontsList();
        }

        return $result;
    }

    public function delete()
    {
        if ($result = parent::delete()) {
            self::generateFontsList();
        }

        return $result;
    }

    public function __toString()
    {
        return self::getFontFaceFromData($this->family, $this->files);
    }
}
