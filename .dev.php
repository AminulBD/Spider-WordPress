<?php

add_filter('script_loader_tag', function ($tag, $handle, $src) {
	if ('spider-admin' !== $handle) {
		return $tag;
	}

	return '<script type="module" src="' . esc_url('http://localhost:5080/ui/admin.js') . '"></script>';
}, 10, 3);

add_filter('style_loader_tag', function ($tag, $handle, $src) {
	if ('spider-admin' === $handle) {
		return '';
	}

	return $tag;
}, 10, 3);
