<?php

namespace ConferencePages;

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

delete_option( SLUG . '-version' );
delete_option( SLUG . '-options' );
