<?php

namespace AminulBD\Spider\WordPress;

class Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue() {
        $manifest = json_decode( file_get_contents( SPIDER_PATH . '/public/manifest.json'), true);

        $js = $manifest['ui/admin.js'] ?? null;
        if($js) {
            wp_enqueue_script('spider-admin-script', SPIDER_URL . 'public/' . $js['file'], [], SPIDER_VERSION, true);
        }

        $css = $manifest['ui/admin.css'] ?? null;
        if($css) {
            wp_enqueue_style('spider-admin-style', SPIDER_URL . 'public/' . $css['file'], [], SPIDER_VERSION);
        }
    }

    public function add_menu() {
        add_menu_page(
            'Spider',
            'Spider',
            'manage_options',
            'spider',
            [$this, 'render'],
            'dashicons-admin-site-alt3',
            20
        );
    }

    public function render() {
        $config = [
            'api' => '/wp-json/spider/v1',
            'version' => '1.0.0',
            'assets_uri' => SPIDER_URL . 'public/',
        ];

        echo '<script>window.spiderConfig = ' . json_encode( $config ) . ';</script>';
        ?>
        <div class="wrap">
            <div id="spider-admin-app"></div>
        </div>
        <?php
    }
}
