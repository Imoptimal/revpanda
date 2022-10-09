<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

if (!class_exists('Revpanda_WP_uninstall')) {
    class Revpanda_WP_uninstall
    {
        public function delete_plugin_db_data()
        {
            // Delete database added by plugin
            global $wpdb;
            $table_name = $wpdb->prefix . 'revpanda';
            $wpdb->query("DROP TABLE IF EXISTS {$table_name}");
            // Delete option added by plugin
            $option_name = 'wp_revpanda_db_version';
            delete_option($option_name);
            // for site options in Multisite
            delete_site_option($option_name);
        }

        public function __construct()
        {
            $this->delete_plugin_db_data();
        }
    }
    $wp_revpanda_uninstall = new Revpanda_WP_uninstall();
}