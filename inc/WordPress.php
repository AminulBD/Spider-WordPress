<?php
namespace AminulBD\Spider\WordPress;

use AminulBD\Spider\WordPress\Modules\{
    Admin_UI, API, Post_Type
};

class WordPress
{
    public string $version = '1.0.0';
	private static ?self $instance = null;

	public function __construct(array $args = []) {
	    $this->version = $args['version'] ?? $this->version;

		$this->load_modules();
    }

	public static function init($args)
	{
		if (self::$instance === null) {
	      self::$instance = new self($args);
	    }

	    return self::$instance;
	}

	public function load_modules() {
        $core = [
            Admin_UI::class,
            API::class,
            Post_Type::class,
        ];

        $custom = apply_filters('spider_modules', []);
        $modules = array_merge($core, $custom);
        $args = [
            'version' => $this->version,
        ];

        foreach($modules as $module) {
            if($module::$type === 'backend' && is_admin()) {
                $load = new $module($args);
                $load->boot();
            } else if($module::$type === 'frontend' && !is_admin()) {
                $load = new $module($args);
                $load->boot();
            } else {
                $load = new $module($args);
                $load->boot();
            }
            new $module();
        }
    }
}
