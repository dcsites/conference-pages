<?php

namespace MyPlugin\Lib;

class Core {

	public static function get_instance() {

        static $instance = null;

        if ( null === $instance )
			$instance = new static();

        return $instance;
    }

	private function __clone(){}

    private function __wakeup(){}

	protected function __construct() {
	}
}

Core::get_instance();
