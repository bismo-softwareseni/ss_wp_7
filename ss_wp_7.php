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
            //-- add new user roles
            register_activation_hook( __FILE__, array( $his, 'ssWp7CreateUserRole' ) );

            /**
             * execute this when plugin activated and have been loaded
             * 1. register shortcode
             **/
            add_action( 'plugins_loaded', array( $this, 'ssWp7PluginsLoadedHandlers' ) );
        }

        //-- function to create shortcode for displaying all staff and manager
        function ssWp7CreateShortcode() {
            ob_start();

            return ob_get_clean();
        }

        //-- function to create user role
        function ssWp7CreateUserRole() {
            //-- create role staff ( can view profile and posts )
            add_role(
                'ss_staff',
                __( 'Staff' ),
                array(
                    'read'          => true,
                    'publish_posts' => true,
                    'edit_posts'    => true,
                    'delete_posts'  => true 
                )
            );

            //-- create role manager ( can view profile, posts, users )
            add_role(
                'ss_manager',
                __( 'Manager' ),
                array(
                    'read'          => true,
                    'publish_posts' => true,
                    'edit_posts'    => true,
                    'delete_posts'  => true,
                    'list_users'    => true,
                    'remove_users'  => true,
                    'promote_users' => true

                )
            );
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