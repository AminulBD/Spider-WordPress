<?php
namespace AminulBD\Spider\WordPress\Api\V1;

class Engines_Controller extends \WP_REST_Posts_Controller {
	protected $post_type;
	protected $meta;
	protected $password_check_passed = array();
	protected $allow_batch = array( 'v1' => true );

	public function __construct() {
		parent::__construct('spider');
		$obj             = get_post_type_object( $this->post_type );
		$this->namespace = ! empty( $obj->rest_namespace ) ? $obj->rest_namespace : 'spider/v1';

		$this->meta = new \WP_REST_Post_Meta_Fields( $this->post_type );
	}
}
