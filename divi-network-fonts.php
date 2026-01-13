<?php

/**
 * Plugin Name: Divi Network Fonts
 * Description: Network-wide shared font library for Divi using a single registry file.
 */

if (! defined('ABSPATH')) exit;

define('DIVI_NETWORK_FONTS_PATH', WP_CONTENT_DIR . '/uploads/fonts/');
define('DIVI_NETWORK_FONTS_URL',  network_site_url('/wp-content/uploads/fonts/'));

/**
 * Font registry version (changes when fonts.json changes)
 * This is used ONLY for cache-busting.
 */
function divi_network_fonts_version()
{
    $file = DIVI_NETWORK_FONTS_PATH . 'fonts.json';
    return file_exists($file) ? filemtime($file) : null;
}

/**
 * Load and cache font registry
 */
function divi_network_fonts_registry()
{
    static $registry = null;
    if ($registry !== null) {
        return $registry;
    }

    $file = DIVI_NETWORK_FONTS_PATH . 'fonts.json';

    if (! file_exists($file)) {
        return $registry = [];
    }

    $data = json_decode(file_get_contents($file), true);
    if (! is_array($data)) {
        return $registry = [];
    }

    return $registry = $data;
}

/**
 * 1) Register fonts in Divi dropdown
 */
add_filter('et_websafe_fonts', function ($fonts) {

    foreach (divi_network_fonts_registry() as $font) {
        if (empty($font['label'])) continue;

        $fonts[$font['label']] = [
            'styles'        => ! empty($font['variable'])
                ? '100,200,300,400,500,600,700,800,900'
                : '400',
            'character_set' => $font['character_set'] ?? 'latin',
            'type'          => $font['type'] ?? 'sans-serif',
            'standard'      => 1,
        ];
    }

    return $fonts;
});

/**
 * 2) Autoload fonts (frontend + builder)
 */
function divi_network_fonts_enqueue()
{
    $css = '';

    foreach (divi_network_fonts_registry() as $font) {
        if (empty($font['file']) || empty($font['family'])) {
            continue;
        }

        $url = esc_url(DIVI_NETWORK_FONTS_URL . $font['file']);

        if (! empty($font['variable'])) {
            $css .= "
@font-face {
  font-family: '{$font['family']}';
  src: url('{$url}');
  font-weight: 100 900;
  font-style: normal;
  font-display: swap;
}
";
        } else {
            $css .= "
@font-face {
  font-family: '{$font['family']}';
  src: url('{$url}');
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}
";
        }
    }

    if (! $css) return;

    wp_register_style(
        'divi-network-fonts',
        false,
        [],
        divi_network_fonts_version()
    );

    wp_enqueue_style('divi-network-fonts');
    wp_add_inline_style('divi-network-fonts', $css);
}

add_action('wp_enqueue_scripts', 'divi_network_fonts_enqueue', 15);
add_action('admin_enqueue_scripts', 'divi_network_fonts_enqueue', 15);
