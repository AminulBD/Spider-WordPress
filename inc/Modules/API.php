<?php

namespace AminulBD\Spider\WordPress\Modules;

use AminulBD\Spider\WordPress\Contracts\Module;

class API extends Module {
    public static string $name = 'API';
    public static string $version = '1.0.0';
    public static string $type = 'any';

    public function __construct() {
        $this->add_action('rest_api_init', [$this, 'routes']);

        // TODO: check if the user is logged in
    }

    public function routes() {
        register_rest_route('spider/v1', '/engines', [
            'methods' => 'GET',
            'callback' => [$this, 'get_engines'],
        ]);

        register_rest_route('spider/v1', '/engines', [
            'methods' => 'POST',
            'callback' => [$this, 'create_engine'],
        ]);

        register_rest_route('spider/v1', '/engines/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_engine'],
        ]);

        register_rest_route('spider/v1', '/engines/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'update_engine'],
        ]);

        register_rest_route('spider/v1', '/engines/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_engine'],
        ]);
    }

    public function get_engines() {
        return [
            'message' => 'Engines fetched',
        ];
    }

    public function create_engine() {
        return [
            'message' => 'Engine created',
        ];
    }

    public function get_engine() {
        return [
            'message' => 'Engine fetched',
        ];
    }

    public function update_engine() {
        return [
            'message' => 'Engine updated',
        ];
    }

    public function delete_engine() {
        return [
            'message' => 'Engine deleted',
        ];
    }
}
