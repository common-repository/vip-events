<?php
if (!class_exists('Alweventsadmin')) {
    class Alweventsadmin {
        public static $instance;
        public function __construct() {
            add_action('init', array($this, 'init_hooks'));
            add_action( 'admin_init', array( $this, 'admin_init_hooks' ) );
        }
        function init_hooks() {
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        }
        function admin_init() {
            
        }
        public static function create_instance() {
            if (is_null(self::$instance))
                self::$instance = new Alweventsadmin();
            return self::$instance;
        }
        function admin_enqueue_scripts($hook)
        {
            wp_enqueue_style( 'jquery-ui-style-events_css', ALW_EVENTS_PLUGIN_URL .'css/jquery-ui.css' );
            wp_enqueue_style( 'style_custom_events_admin', ALW_EVENTS_PLUGIN_URL .'css/event-custom-admin.css' );
           wp_enqueue_script( 'jquery-ui-datepicker' );            
            wp_enqueue_script('alw-events-custom-script-js', ALW_EVENTS_PLUGIN_URL . 'js/event-custom-admin.js', array('jquery'));
        }

    }

}
Alweventsadmin::create_instance();
