<?php

namespace AminulBD\Spider\WordPress\Api\V1;

class Admin_Controller extends \WP_REST_Controller {
    public function __construct() {
        $this->namespace = 'spider/v1';
        $this->rest_base = 'config';
    }
}
