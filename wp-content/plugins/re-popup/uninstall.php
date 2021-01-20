<?php
/**
 * Trigger this file on Plugin uninstall
 *
 * * @package RepopupPlugin
 */

defined('WP_UNINSTALL_PLUGIN') ? '' : die;

global $wpdb;

$table_name = $wpdb->prefix . 'repopup';
$wpdb->query( "DROP TABLE IF EXISTS  $table_name " );