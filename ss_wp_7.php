<?php
    /*
        Plugin Name: SoftwareSeni WP Training 7
        Description: Objectives - creating user role
        Version: 1.0
        Author: Bismoko Widyatno
    */

    /**
     * --------------------------------------------------------------------------
     * Main class for this plugin. This class will handle most of the 
     * plugin logic
     * --------------------------------------------------------------------------
     **/
    class SS_WP_7_Main {
        function __construct() {
            
        }

        //-- function to create shortcode for displaying all staff and manager
        function ssWp7CreateShortcode() {
            ob_start();

            return ob_get_clean();
        }

        //-- function for executing some task when plugins loaded
        function ssWp7PluginsLoadedHandlers() {
            //-- register wp 7 shortcode
            add_shortcode( 'wp7_users', array( $this, 'ssWp7CreateShortcode' ) );
        }
    }

    //-- run the main class
    $ss_wp_7_main_class = new SS_WP_7_Main();
?>