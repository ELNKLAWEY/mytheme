<?php
// تسجيل ملفات CSS و JS
function mytheme_enqueue_scripts() {
    wp_enqueue_style( 'mytheme-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_scripts' );
