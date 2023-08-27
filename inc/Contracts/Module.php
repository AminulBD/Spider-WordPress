<?php

namespace AminulBD\Spider\WordPress\Contracts;

abstract class Module {
    public static string $name;
    public static string $version;
    public static string $type; // 'any' or 'backend', 'frontend'

    private array $actions = [];
    private bool $actions_loaded = false;
    private array $filters = [];
    private bool $filters_loaded = false;

    public function boot() {
        $this->load_actions();
        $this->load_filters();
    }

    private function load_actions() {
        if($this->actions_loaded) {
            return;
        }

        $this->actions_loaded = true;
        foreach($this->actions as $action) {
            add_action(
                $action['hook_name'],
                $action['callback'],
                $action['priority'],
                $action['accepted_args']
            );
        }
    }

    private function load_filters() {
        if($this->filters_loaded) {
            return;
        }

        $this->filters_loaded = true;
        foreach($this->filters as $filter) {
            add_filter(
                $filter['hook_name'],
                $filter['callback'],
                $filter['priority'],
                $filter['accepted_args']
            );
        }
    }

    public function add_action( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 ) {
        $this->actions[] = [
            'hook_name' => $hook_name,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
    }

    public function add_filter( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 ) {
        $this->filters[] = [
            'hook_name' => $hook_name,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
    }
}
