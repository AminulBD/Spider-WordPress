<?php
namespace AminulBD\Spider\WordPress;

class WordPress
{
	private static self|null $instance = null;

	public static function init($args)
	{
		if (self::$instance === null) {
	      self::$instance = new self($args);
	    }

	    return self::$instance;
	}
}
