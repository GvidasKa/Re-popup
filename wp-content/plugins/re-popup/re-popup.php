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
            wp_enqueue_script('replugin-script', plugins_url('/assets/admin/replugin-script.js', __FILE__ ), array ( 'jquery' ), '1.0');
            $arr = array(
                'ajaxurl' => admin_url('admin-ajax.php')
            );
            wp_localize_script('replugin-script','obj',$arr );
            wp_enqueue_script('replugin-script');
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



    add_action( 'wp_ajax_create_popup', 'create_popup' );

    function create_popup() {
        require( dirname(__FILE__) . '/../../../wp-load.php' );
        global $table_prefix, $wpdb; // wordpress database

        $table_name = $table_prefix . 'repopup';

        // Checking is file is uplaoded
        if( !empty( $_FILES['file'] ) ) {

            // $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05, for multisite works good as well
            // $wordpress_upload_dir['url'] the absolute URL to the same folder, actually we do not need it, just to show the link to file
            $wordpress_upload_dir = wp_upload_dir();

            $i = 1; // number of tries when the file with the same name is already exists

            $profilepicture = $_FILES['file'];
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
            $new_file_mime = mime_content_type($profilepicture['tmp_name']);

            if ($profilepicture['error'])
                die($profilepicture['error']);

            if ($profilepicture['size'] > wp_max_upload_size())
                die('It is too large than expected.');

            if (!in_array($new_file_mime, get_allowed_mime_types()))
                die('WordPress doesn\'t allow this type of uploads.');

            while (file_exists($new_file_path)) {
                $i++;
                $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
            }


            if (move_uploaded_file($profilepicture['tmp_name'], $new_file_path)) {


                $upload_id = wp_insert_attachment(array(
                    'guid' => $new_file_path,
                    'post_mime_type' => $new_file_mime,
                    'post_title' => preg_replace('/\.[^.]+$/', '', $profilepicture['name']),
                    'post_content' => '',
                    'post_status' => 'inherit'
                ), $new_file_path);

                // wp_generate_attachment_metadata() won't work if you do not include this file
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Generate and save the attachment metas into the database
                wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

            }
            $pathToImage = substr($new_file_path, strpos($new_file_path, "/repopup") + 0);
            $data = array( 'title' => $_POST['title'], 'image' => $pathToImage, 'text' => $_POST['text']);
        } else{
            $data = array( 'title' => $_POST['title'], 'image' => '', 'text' => $_POST['text']);
        }

        $format = array('%s', '%s','%s');
        $wpdb->insert($table_name, $data, $format);

        $results = $wpdb->get_results("SELECT * FROM $table_name");
        foreach($results as $result){
            echo "<tr>
            <td><input type=\"checkbox\"  tableid='{$result->ID}' class='delete'></td>
            <td>{$result->ID}</td>
            <td>{$result->title}</td>
            <td><button tableid='{$result->ID}'>Edit</button></td>
        </tr>";
        }
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    add_action( 'wp_ajax_edit_popup', 'edit_popup' );
    function edit_popup() {
        global $table_prefix, $wpdb; // wordpress database

        $table_name = $table_prefix . 'repopup';

        $results = $wpdb->get_row("SELECT * FROM $table_name WHERE ID = {$_POST['id']}");
        echo json_encode($results);

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    add_action( 'wp_ajax_update_popup', 'update_popup' );
    function update_popup() {
        global $table_prefix, $wpdb; // wordpress database

        $table_name = $table_prefix . 'repopup';

        if( !empty( $_FILES['file'] ) ) {

            // $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05, for multisite works good as well
            // $wordpress_upload_dir['url'] the absolute URL to the same folder, actually we do not need it, just to show the link to file
            $wordpress_upload_dir = wp_upload_dir();

            $i = 1; // number of tries when the file with the same name is already exists

            $profilepicture = $_FILES['file'];
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
            $new_file_mime = mime_content_type($profilepicture['tmp_name']);

            if ($profilepicture['error'])
                die($profilepicture['error']);

            if ($profilepicture['size'] > wp_max_upload_size())
                die('It is too large than expected.');

            if (!in_array($new_file_mime, get_allowed_mime_types()))
                die('WordPress doesn\'t allow this type of uploads.');

            while (file_exists($new_file_path)) {
                $i++;
                $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
            }


            if (move_uploaded_file($profilepicture['tmp_name'], $new_file_path)) {


                $upload_id = wp_insert_attachment(array(
                    'guid' => $new_file_path,
                    'post_mime_type' => $new_file_mime,
                    'post_title' => preg_replace('/\.[^.]+$/', '', $profilepicture['name']),
                    'post_content' => '',
                    'post_status' => 'inherit'
                ), $new_file_path);

                // wp_generate_attachment_metadata() won't work if you do not include this file
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Generate and save the attachment metas into the database
                wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

            }
            $pathToImage = substr($new_file_path, strpos($new_file_path, "/repopup") + 0);
            $wpdb->update($table_name, array('id'=>$_POST['id'], 'title'=>$_POST['title'], 'image'=>$pathToImage, 'text'=>$_POST['text']), array('id'=>$_POST['id']));
        } else{
            $wpdb->update($table_name, array('id'=>$_POST['id'], 'title'=>$_POST['title'], 'image'=>'', 'text'=>$_POST['text']), array('id'=>$_POST['id']));
        }

        $results = $wpdb->get_results("SELECT * FROM $table_name");
        foreach($results as $result) {
            echo "<tr>
            <td><input type=\"checkbox\"  tableid='{$result->ID}' class='delete'></td>
            <td>{$result->ID}</td>
            <td>{$result->title}</td>
            <td><button tableid='{$result->ID}'>Edit</button></td>
        </tr>";
        }
        wp_die(); // this is required to terminate immediately and return a proper response
    }


    add_action( 'wp_ajax_delete_popups', 'delete_popups' );
    function delete_popups() {
        global $table_prefix, $wpdb; // wordpress database

        $table_name = $table_prefix . 'repopup';
        foreach($_POST['ids'] as $id){
            $wpdb->delete( $table_name, array( 'id' => $id ) );
        }

        $results = $wpdb->get_results("SELECT * FROM $table_name");
        foreach($results as $result) {
            echo "<tr>
            <td><input type=\"checkbox\"  tableid='{$result->ID}' class='delete'></td>
            <td>{$result->ID}</td>
            <td>{$result->title}</td>
            <td><button tableid='{$result->ID}'>Edit</button></td>
        </tr>";
        }

        wp_die(); // this is required to terminate immediately and return a proper response
    }
}