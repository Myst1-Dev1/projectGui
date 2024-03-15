<?php

function theme_support_tag() {
    // Adiciona suporte dinâmico para a tag de título
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('category-thumbnails');
}

add_action('after_setup_theme', 'theme_support_tag');

function my_custom_logo_link() {
    $logo_id = get_theme_mod('custom_logo');
    $logo_url = wp_get_attachment_image_url($logo_id, 'full');
    ?>
    <link rel="shortcut icon" href="<?php echo $logo_url; ?>" />
    <?php
  }
  add_action('wp_head', 'my_custom_logo_link');

function getStyles() {

    $version = wp_get_theme()->get('Version');
    wp_enqueue_style('get-styles', get_template_directory_uri() . "../css/style.css", array('get-bootstrap'), $version, 'all');
    wp_enqueue_style('custom-style', get_template_directory_uri(), "/style.css", array('get-bootstrap'), $version, 'all');
    wp_enqueue_style('get-bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css", array(), '5.3.2', 'all');
    wp_enqueue_style('get-fontawesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css", array(), '6.5.1', 'all');
}

add_action('wp_enqueue_scripts', 'getStyles');

function getScripts() {
    wp_enqueue_script('get-jquery', 'https://code.jquery.com/jquery-3.4.1.slim.min.js', array(), '3.4.1', true);
    wp_enqueue_script('get-popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array(), '1.16.0', true);
    wp_enqueue_script('get-bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array(), '4.4.1', true);
    wp_enqueue_script('get-bootstrap', get_template_directory_uri() . "../js/main.js", array(), '1.0', true);
}

add_action('wp_enqueue_scripts', 'getScripts');

function getMenus() {
    $locations = array(
        'primary' => "Príncipal",
    );

    register_nav_menus($locations);
}

add_action('init', 'getMenus');

?>