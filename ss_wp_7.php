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
        var $ss_max_user_per_page = 5;

        function __construct() {
            /**
            * execute this when plugin activated and have been loaded
            * 1. register shortcode
            **/
            add_action( 'plugins_loaded', array( $this, 'ssWp7PluginsLoadedHandlers' ), 2 );

            //-- add new user roles
            register_activation_hook( __FILE__, array( $this, 'ssWp7CreateUserRole' ) );

            //-- remove user role on deactivation
            register_deactivation_hook( __FILE__, array( $this, 'ssWp7RemoveUserRole' ) );
        }

        //-- function to create shortcode for displaying all staff and manager
        function ssWp7CreateShortcode( $ss_shortcode_atts = array() ) {
            ob_start();

            //-- add shortcode attribute
            $ss_shortcode_atts = array_change_key_case( (array)$ss_shortcode_atts, CASE_LOWER );

            //-- override default shortcode parameters
            $ss_wp_7_roles_atts = shortcode_atts([
                                        'roles_to_show' => [ "ss_staff", "ss_manager" ],
                                    ], $ss_shortcode_atts );
            
            //-- changes role to show into array variable
            $ss_wp_7_roles_atts[ 'roles_to_show' ] = explode( ',', $ss_wp_7_roles_atts[ 'roles_to_show' ] );

            //-- display list of staff and managers
            $this->ssDisplayStaffManager( $ss_wp_7_roles_atts[ 'roles_to_show' ] );

            return ob_get_clean();
        }

        //-- function to dislay all staff and managers
        function ssDisplayStaffManager( $roles ) {
            $ss_current_page    = get_query_var('paged') ? (int) get_query_var('paged') : 1;

            //-- all get staff and manager
            $ss_user_args   = array(
                'role__in'  => $roles,
                'number'    => $this->ss_max_user_per_page,
                'paged'     => $ss_current_page,
                'orderby'   => 'display_name',
                'order'     => 'ASC'
            );

            $ss_user_results    = new WP_User_Query( $ss_user_args );
            $ss_user_count      = $ss_user_results->get_total();
            $ss_max_page        = ceil( $ss_user_count/$this->ss_max_user_per_page );

            // User Loop
            if ( !empty( $ss_user_results->get_results() ) ) {
        ?>

                <!-- users container -->
                <div class="users-container ui list">
        <?php
                foreach ( $ss_user_results->get_results() as $ss_user_result ) {
                    echo '<div class="item">' . $ss_user_result->display_name . '</div>';
                }
        ?> 
                </div>
                <!-- end users container -->
        <?php
            } else {
                echo 'No users found.';
            }

            //-- pagination
            echo paginate_links( array(
                'base' => str_replace( $ss_max_page, '%#%', esc_url( get_pagenum_link( $ss_max_page ) ) ),
                'format' => '?paged=%#%',
                'prev_text' => __('&laquo; Previous'),
                'next_text' => __('Next &raquo;'), 
                'total' => $ss_max_page, 
                'current' => max( 1, get_query_var('paged') )
            ));
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

        //-- function to remove role on deactivation
        function ssWp7RemoveUserRole() {
            //-- remove staff
            if( get_role( 'ss_staff' ) ){
                remove_role( 'ss_staff' );
            }

            //-- remove manager
            if( get_role( 'ss_manager' ) ){
                remove_role( 'ss_manager' );
            }
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