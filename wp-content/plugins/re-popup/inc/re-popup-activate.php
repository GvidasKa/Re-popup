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
             ID int NOT NULL AUTO_INCREMENT,
             title VARCHAR(1000) NOT NULL,
             image VARCHAR(1000) NOT NULL,
             text VARCHAR(1000) NOT NULL,
             status INT(1) NOT NULL,
             PRIMARY KEY (ID));
            $charset_collate";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        flush_rewrite_rules();
    }
}