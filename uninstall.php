<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

if (!class_exists('Revpanda_WP_uninstall')) {
    class Revpanda_WP_uninstall
    {
        // Drop a custom database table
        public function delete_db_table()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'revpanda';
            $wpdb->query("DROP TABLE IF EXISTS {$table_name}");
        }

        public function __construct()
        {
            $this->delete_db_table();
        }
    }
    $wp_revpanda_uninstall = new Revpanda_WP_uninstall();
}