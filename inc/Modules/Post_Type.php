<?php

namespace AminulBD\Spider\WordPress\Modules;

class Post_Type {
    public function __construct() {

    }

    private function types() {
        function wporg_custom_post_type() {
    		array(
    			'labels'      => array(
    				'name'          => __('Products', 'textdomain'),
    				'singular_name' => __('Product', 'textdomain'),
    			),
    				'public'      => true,
    				'has_archive' => true,
    		)
    	);
    }
}
