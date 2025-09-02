<?php
// تسجيل ملفات CSS و JS
function mytheme_enqueue_scripts() {
    wp_enqueue_style( 'mytheme-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_scripts' );



function mytheme_update_checker( $transient ) {

    if ( empty($transient->checked) ) {
        return $transient;
    }

    // رابط API الخاص بك + المفتاح
    $api_url = "https://infinity-player.online/api/theme-update.php?api_key=123456";

    $request = wp_remote_get($api_url);

    if ( is_wp_error($request) ) {
        return $transient;
    }

    $body = json_decode(wp_remote_retrieve_body($request));

    if ($body && isset($body->version)) {
        $theme = wp_get_theme('mytheme'); // اسم مجلد الثيم
        $current_version = $theme->get('Version');

        if ( version_compare($current_version, $body->version, '<') ) {
            $transient->response['mytheme'] = (object) [
                'theme'       => 'mytheme',
                'new_version' => $body->version,
                'url'         => 'https://infinity-player.online',
                'package'     => $body->download_url, // رابط مباشر للـ ZIP
            ];
        }
    }

    return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'mytheme_update_checker');
