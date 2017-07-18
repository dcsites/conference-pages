<?php

namespace ConferencePages;

class Admin extends Core {

    protected $options_page = '';

    protected $title = '';

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

		parent::__construct();

        $this->title = __( 'Conference Options', SLUG );

        // Create an options page for the plugin
        add_action( 'admin_init', [ $this, 'init' ] );
        add_action( 'admin_menu', [ $this, 'add_options_page' ] );
        add_action( 'cmb2_admin_init',  [ $this, 'add_options_metaboxes' ] );
	}

    /**
     * Register our setting to WP
     * @since  0.1.0
     */
    public function init() {
        register_setting( $this->options_slug, $this->options_slug );
    }

    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page() {
        add_options_page( $this->title, $this->title, 'manage_options', $this->options_slug, [ $this, 'admin_page_display' ] );
    }

    /**
     * Admin page markup. Mostly handled by CMB2
     * @since  0.1.0
     */
    public function admin_page_display() {
        ?>
        <div class="wrap cmb2-options-page <?php echo $this->options_slug; ?>">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <?php
			if ( function_exists( '\cmb2_metabox_form' ) ) {
				\cmb2_metabox_form( $this->options_slug, $this->options_slug, [ 'cmb_styles' => true ] );
			}
			?>
        </div>
        <?php
    }

    /**
     * Create an options page
     *
     * Create an options page for the plugin
     */
    public function add_options_metaboxes() {

        $cmb_options = new_cmb2_box( array(
            'id'      => $this->options_slug,
            'title'   => __( 'Conference Options', SLUG ),
            'hookup'  => false,
            'show_on' => array(
                'key'   => 'options-page',
                'value' => [ $this->options_slug ]
            	),
        	) );

		$cmb_options->add_field( array(
			'id'   => 'title',
			'name' => 'Title',
			'desc' => 'What is the title?',
			'type' => 'text',
			) );
    }
}

Admin::get_instance();
