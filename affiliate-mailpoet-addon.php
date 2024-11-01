<?php

/**
 * Plugin Name: Affiliate MailPoet Integration
 * Plugin URI: http://www.tipsandtricks-hq.com/wordpress-affiliate-platform-plugin-simple-affiliate-program-for-wordpress-blogsite-1474
 * Description: This Addon allows you to add your affiliates to MailPoet newsletter list
 * Version: 1.1
 * Author: Tips and Tricks HQ
 * Author URI: http://www.tipsandtricks-hq.com/
 * Requires at least: 3.0
 */
if (!defined('ABSPATH'))
    exit;

if (!class_exists('AFFILIATE_MAILPOET_ADDON')) {

    class AFFILIATE_MAILPOET_ADDON {

        var $version = '1.1';
        var $db_version = '1.0';
        var $plugin_url;
        var $plugin_path;

        function __construct() {
            $this->define_constants();
            $this->includes();
            $this->loader_operations();
            //Handle any db install and upgrade task
            add_action('init', array(&$this, 'plugin_init'), 0);
        }

        function define_constants() {
            define('AFFILIATE_MAILPOET_ADDON_VERSION', $this->version);
            define('AFFILIATE_MAILPOET_ADDON_URL', $this->plugin_url());
            define('AFFILIATE_MAILPOET_ADDON_PATH', $this->plugin_path());
        }

        function includes() {
            //NOP
        }

        function loader_operations() {
            add_action('plugins_loaded', array(&$this, 'plugins_loaded_handler')); //plugins loaded hook		
        }

        function plugins_loaded_handler() {//Runs when plugins_loaded action gets fired
            $this->do_db_upgrade_check();
        }

        function do_db_upgrade_check() {
            //NOP
        }

        function plugin_init() {//Gets run with WP Init is fired
            add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );
            
            add_action('wp_affiliate_autoresponder_signup', array(&$this, 'wp_affiliate_autoresponder_signup_handler'));
            if (is_admin()) {
                add_action('admin_notices', array(&$this, 'aff_mailpoet_requirement_check'));
            }
        }

        function add_admin_menus()
	{
		//Add the menu
		include_once('aff-mailpoet-settings.php');
		$parent_slug = WP_AFF_PLATFORM_PATH.'wp_affiliate_platform1.php';
		$page_title = 'MailPoet Integration';
		$menu_title = 'MailPoet';
		add_submenu_page($parent_slug, $page_title, $menu_title, AFFILIATE_MANAGEMENT_PERMISSION, 'wp-aff-mailpoet', 'wp_aff_mailpoet_settings_menu');		
	}
        
        function wp_affiliate_autoresponder_signup_handler($signup_data) {
            $mailpoet_list_id = get_option('wp_aff_mailpoet_list_id'); //List ID where the affiliate will be signed up to. 
            
            $debug_data = $signup_data['email'] . "|" . $mailpoet_list_id . "|" . $signup_data['firstname'] . "|" . $signup_data['lastname'];
            wp_affiliate_log_debug("Mailpoet/Wysija newsletter integration. Debug data: " . $debug_data, true);          

            $subscriber_data = array(
              'email' => $signup_data['email'],
              'first_name' => $signup_data['firstname'],
              'last_name' => $signup_data['lastname'],
            );

            //List ID where the customer will be signed up to. 
            $mp_list_ids_arr = array_map('trim', explode(',', $mailpoet_list_id));            
            $lists = $mp_list_ids_arr;

            //Options if any
            $options = array(
              'send_confirmation_email' => true, // default: true
              'schedule_welcome_email' => true // default: true
            );

            try {
                $subscriber = \MailPoet\API\API::MP('v1')->addSubscriber($subscriber_data, $lists, $options);
            } catch (Exception $exception) {
                
            }
            
            wp_affiliate_log_debug("MailPoet signup complete!", true);
        }

        function aff_mailpoet_requirement_check() {
            if (!defined('WP_AFFILIATE_PLATFORM_VERSION')) {
                $msg = '<p>The MailPoet integration addon requires WP Affiliate plugin to be active. Please activate WP Affiliate plugin.</p>';
                echo '<div class="updated fade">' . $msg . '</div>';
                return;
            }
            if (version_compare(WP_AFFILIATE_PLATFORM_VERSION, '5.8.4', '<')) {
                $msg = '<p>The MailPoet integration requires a new function that was added to the Affiliate Plugin recently. Please upgrade your WP Affiliate plugin now to use the MailPoet integration addon.</p>';
                echo '<div class="updated fade">' . $msg . '</div>';
            }
        }

        function plugin_url() {
            if ($this->plugin_url)
                return $this->plugin_url;
            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }

        function plugin_path() {
            if ($this->plugin_path)
                return $this->plugin_path;
            return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
        }

    }

    //End of plugin class
}//End of class not exists check

$GLOBALS['AFFILIATE_MAILPOET_ADDON'] = new AFFILIATE_MAILPOET_ADDON();
