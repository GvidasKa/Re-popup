<?php
/**
 * @package RepopupPlugin
 */
/*
 * Plugin Name: Re Popup
 * Plugin URI: https://github.com/GvidasKa/Re-popup
 * Description: This is a popup plugin, which allows admin to create, read, update and delete pop-ups. These pop-ups appear when a visitor tries to leave the page I.e. user moves the mouse outside the window.
 * Version: 1.0.0
 * Author: Gvidas Kairys
 * Author URI: https://github.com/GvidasKa/
 * License: GPLv2 or later
 * Text Domain: re-popup
 */

defined('ABSPATH') or die;

if(!class_exists('RePopup')) {

    class RePopup
    {
        public $pluginLink;

        function __construct(){
            $this->pluginLink = plugin_basename(__FILE__);
        }

        public function register()
        {
            add_action('admin_enqueue_scripts', array($this, 'enqueue'));

            add_action('admin_menu', array($this, 'add_admin_pages'));

            add_filter("plugin_action_links_$this->pluginLink" ,array($this, 'settings_link'));
        }

        public function settings_link($links){
            // add custom settings link
            $settings_link = '<a href="options-general.php?page=re_popup">Settings</a>';
            array_push($links,$settings_link);
            return $links;
        }

        public function add_admin_pages(){
            add_menu_page('RePopup','RePopup','manage_options','re_popup',array($this, 'admin_index'),'dashicons-desktop',110);
        }

        public function admin_index(){
            //require template
            require_once plugin_dir_path(__FILE__) . 'templates/admin.php';

        }

        function enqueue()
        {
            //enqueue scripts
            wp_enqueue_style('replugin-style', plugins_url('/assets/admin/replugin-style.css', __FILE__));
        }

    }

    $rePopup = new RePopup();
    $rePopup->register();


    //activation
    require_once plugin_dir_path(__FILE__) . 'inc/re-popup-activate.php';
    register_activation_hook(__FILE__, array('RePopupActivate', 'activate'));

    //deactivation
    require_once plugin_dir_path(__FILE__) . 'inc/re-popup-deactivate.php';
    register_deactivation_hook(__FILE__, array('RePopupDeactivate', 'deactivate'));
}