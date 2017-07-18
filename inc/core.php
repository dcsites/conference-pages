<?php

namespace ConferencePages;

class Core {

	protected $options_slug;

	public static function get_instance() {

        static $instance = null;

        if ( null === $instance ) {
			$instance = new static();
		}

        return $instance;
    }

	private function __clone(){}

    private function __wakeup(){}

	protected function __construct() {
		$this->options_slug = SLUG . '-options';

		add_action( 'init', [ $this, 'plugin_update' ], 5 );
		add_action( 'init', [ $this, 'register_objects' ], 9 );

		// add_action( 'init', [ $this, 'debug' ], 9999 );
	}

	public function debug() {
		$roles = wp_roles();
		echo "<h1>RSH DUMP</h1><pre>";var_dump( $roles->roles['administrator'] );echo"</pre>";
		wp_die( "<pre>".print_r( $GLOBALS['wp_post_types']['conference'] ,1)."</pre>","Debug",["response"=>404]);
	}

	public function plugin_update() {
		// If we're already at the current version, abort.
		if ( VERSION === get_option( SLUG . '-version' ) ) {
			return;
		}

		update_option( SLUG . '-version', VERSION );

		$this->register_conference_editor();

		flush_rewrite_rules();
	}

	public function register_objects() {
		// define( 'EP_TAXYEAR', 262144 );

		$this->register_conference();
		$this->register_year();
	}

	protected function register_conference() {

		$args = array(
			'capability_type' => [ 'conference', 'conferences' ],
			'hierarchical'    => true,
			'supports'        => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ],
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-nametag',
			'rewrite'         => array(
				'with_front'  => false,
				// 'ep_mask'     => EP_PERMALINK | EP_TAXYEAR,
				),
			);

		$this->register_post_type( 'Conference', $args );
	}

	protected function register_year() {
		$args = array(
			'rewrite'      => array(
				'slug'     => 'conference',
				'ep_mask'  => EP_PERMALINK,
				),
			'hierarchical' => true,
			'show_in_rest' => true,
			);

		$this->register_taxonomy( 'Year', 'conference', $args );
	}

	protected function register_conference_editor() {
		$base_args = array(
			'read'                         => true,
			'upload_files'                 => true,
			'edit_posts'                   => true,
			'edit_published_posts'         => true,
			'delete_posts'                 => true,
			'delete_published_posts'       => true,
			'publish_posts'                => true,
			);

		$args = array(
			'edit_conferences'             => true,
			'edit_published_conferences'   => true,
			'delete_conferences'           => true,
			'delete_published_conferences' => true,
			'publish_conferences'          => true,
			);

		remove_role( 'conference-editor' );

		add_role( 'conference-editor', __( 'Conference Editor', SLUG ), array_merge( $args, $base_args ) );

		// Add capabilities to exisitng roles
		$role_names = [ 'editor', 'administrator' ];

		foreach( $role_names as $role_name ) {
			$role = get_role( $role_name );
			call_user_func_array( [ $role, 'add_cap' ], array_keys( $args ) );
		}
	}

	/**
	 * Helper function to register post types with some default args
	 *
	 * @param string $single The single name of the post type
	 * @param array  $args Any overrides to the default arguments
	 * @param string $plural The plural name of the post type. Defaults to single name + "s"
	 * @param string $slug The slug of the post type. Defaults to single name, lowercase, dashes instead of spaces
	 */
	protected function register_post_type( $single, $args = array(), $plural = null, $slug = null ) {

		$plural = $plural ?: $single . 's';

		$slug   = $slug ?: str_replace( ' ', '-', strtolower( $single ) );

		$labels = array(
				   'name'               => $plural,
				   'singular_name'      => $single,
				   'add_new_item'       => 'Add New ' . $single,
				   'edit_item'          => 'Edit ' . $single,
				   'new_item'           => 'New ' . $single,
				   'search_items'       => 'Search '. $plural,
				   'parent_item_colon'  => '',
				  );

		$default_args = array(
				   'labels'             => $labels,
				   'public'             => true,
				   'has_archive'		=> true,
				   'rewrite'            => array( 'with_front' => false ),
				   'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
				  );

		$args = wp_parse_args( $args, $default_args );

		register_post_type( $slug, $args );
	}

	/**
	 * Helper function to register taxonomies with some default args
	 *
	 * @param string $single The single name of the taxonomy
	 * @param array  $objects The post types to attach the taxonomy
	 * @param array  $args Any overrides to the default arguments
	 * @param string $plural The plural name of the taxonomy
	 * @param string $slug The slug of the taxonomy
	 */
	protected function register_taxonomy( $single, $objects = array(), $args = array(), $plural = null, $slug = null ) {

		$plural = ! $plural ? $single . 's' : $plural;

		$slug   = ! $slug ? str_replace( ' ', '-', strtolower( $single ) ) : $slug;

		$labels = array(
				   'name'               => $plural,
				   'singular_name'      => $single,
				   'add_new_item'       => 'Add New ' . $single,
				   'edit_item'          => 'Edit ' . $single,
				   'new_item'           => 'New ' . $single,
				   'search_items'       => 'Search '. $plural,
				  );

		$default_args = array(
				   'labels'             => $labels,
				   'rewrite'            => array( 'with_front' => false ),
				  );

		$args = wp_parse_args( $args, $default_args );

		register_taxonomy( $slug, $objects, $args );
	}
}

Core::get_instance();
