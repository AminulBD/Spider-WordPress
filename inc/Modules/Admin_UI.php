<?php

namespace AminulBD\Spider\WordPress\Modules;

use AminulBD\Spider\WordPress\Contracts\Module;

class Admin_UI extends Module {
    public static string $name = 'Admin_UI';
    public static string $version = '1.0.0';
    public static string $type = 'backend';
    private array $args = [];

    public function __construct(array $args = []) {
        $this->args = $args;

        $this->add_action('admin_menu', [$this, 'menu']);
        $this->add_action('admin_enqueue_scripts', [$this, 'assets']);
    }

    public function assets() {
        $manifest = json_decode( file_get_contents( SPIDER_PATH . '/public/manifest.json'), true);

        $js = $manifest['ui/admin.js'] ?? null;
        if($js) {
            wp_enqueue_script('spider-admin-script', SPIDER_URL . 'public/' . $js['file'], [], $this->args['version'] ?? self::$version, true);
        }

        $css = $manifest['ui/admin.css'] ?? null;
        if($css) {
            wp_enqueue_style('spider-admin-style', SPIDER_URL . 'public/' . $css['file'], [], $this->args['version'] ?? self::$version);
        }
    }

    public function menu() {
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
        $data = [
            '_nonce' => wp_create_nonce( 'wp_rest' ),
            'config' => [
                'api_endpoint' => 'spider/v1',
                'version' => self::$version,
                'assets_uri' => SPIDER_URL . 'public/',
            ],
        ];

        echo '<script>window.spider = ' . json_encode( $data ) . ';</script>';
        ?>
        <div class="wrap">
            <div id="spider-admin-app"></div>
        </div>
        <?php
    }
}
