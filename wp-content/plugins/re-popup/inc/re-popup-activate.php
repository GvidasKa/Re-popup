<?php
/**
 * @package RepopupPlugin
 */

class RePopupActivate
{
    public static function activate() {

        global $table_prefix, $wpdb; // wordpress database

        $table_name = $table_prefix . 'repopup';

        #Check to see if the table exists already, if not, then create it
        if ($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE " . $table_name . "(
             id INT(6),
             title VARCHAR(1000) NOT NULL,
             image VARCHAR(1000) NOT NULL,
             text VARCHAR(1000) NOT NULL)
            $charset_collate";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        flush_rewrite_rules();
    }
}