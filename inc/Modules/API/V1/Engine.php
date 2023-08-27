<?php

namespace AminulBD\Spider\WordPress\Modules\API\V1;

use AminulBD\Spider\WordPress\Contracts\Module;

class Engine extends Module {
    public static string $name = 'APIV1_Engine';
    public static string $version = '1.0.0';
    public static string $type = 'any';

    public function __construct() {
        $this->add_action('rest_api_init', [$this, 'routes']);

        // TODO: Authorizations and Schema
    }

    public function routes() {
        register_rest_route('spider/v1', '/engines', [
            'methods' => 'GET',
            'callback' => [$this, 'index'],
            'permission_callback' => [$this, 'has_access'],
        ]);

        register_rest_route('spider/v1', '/engines', [
            'methods' => 'POST',
            'callback' => [$this, 'create'],
            'permission_callback' => [$this, 'has_access'],
        ]);

        register_rest_route('spider/v1', '/engines/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get'],
            'permission_callback' => [$this, 'has_access'],
        ]);

        register_rest_route('spider/v1', '/engines/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'update'],
            'permission_callback' => [$this, 'has_access'],
        ]);

        register_rest_route('spider/v1', '/engines/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_engine'],
            'permission_callback' => [$this, 'has_access'],
        ]);
    }

    private function transform( \WP_Post $engine ) {
        return [
            'id' => $engine->ID,
            'name' => $engine->post_title,
            'identifier' => $engine->post_name,
            'config' => json_decode($engine->post_content, true),
            'status' => $engine->post_status,
        ];
    }

    public function has_access() {
        return current_user_can('manage_options');
    }

    private function clean_fields( array $data ) {
        $defaults = [
            'name' => null,
            'identifier' => null,
            'status' => 'inactive',
            'config' => [],
        ];
        $filtered = array_intersect_key( $data, $defaults );
        return array_merge($defaults, $filtered);
    }

    public function index() {
        $engines = get_posts( [
            'post_type' => 'spider_engine',
            'post_status' => [ 'active', 'inactive' ],
        ] );

        return [
            'message' => 'All available engines are fetched.',
            'data' => array_map([$this, 'transform'], $engines),
        ];
    }

    public function create( \WP_REST_Request $request ) {
        $data = $this->clean_fields( $request->get_json_params() );
        $id = wp_insert_post( [
            'post_type' => 'spider_engine',
            'post_title' => $data['name'],
            'post_name' => $data['identifier'],
            'post_content' => json_encode($data['config'], JSON_PRETTY_PRINT),
            'post_status' => $data['status'],
        ] );

        return [
            'message' => 'Engine has been created.',
            'data' => array_merge($data, [ 'id' => $id ]),
        ];
    }

    public function get( \WP_REST_Request $request ) {
        $id = $request->get_param( 'id' );
        $engine = get_post( $id );

        if( !$engine || $engine->post_type !== 'spider_engine' ) {
            return [
                'message' => 'The requested engine does not exist.',
            ];
        }

        return [
            'message' => 'The requested engine is fetched.',
            'data' => $this->transform( $engine ),
        ];
    }

    public function update( \WP_REST_Request $request ) {
        $id = $request->get_param( 'id' );
        $engine = get_post( $id );

        if( !$engine || $engine->post_type !== 'spider_engine' ) {
            return [
                'message' => 'The requested engine does not exist.',
            ];
        }

        $data = $this->clean_fields( $request->get_json_params() );

        wp_update_post( [
            'ID' => $id,
            'post_title' => $data['name'],
            'post_name' => $data['identifier'],
            'post_content' => json_encode($data['config'], JSON_PRETTY_PRINT),
            'post_status' => $data['status'],
        ] );

        return [
            'message' => 'Engine has been updated.',
            'data' => array_merge($data, [ 'id' => $id ]),
        ];
    }

    public function delete_engine( \WP_REST_Request $request ) {
        $id = $request->get_param( 'id' );
        $engine = get_post( $id );

        if( !$engine || $engine->post_type !== 'spider_engine' ) {
            return [
                'message' => 'The requested engine does not exist.',
            ];
        }

        wp_delete_post( $id, true );

        return [
            'message' => 'The engine has been deleted.',
        ];
    }
}
