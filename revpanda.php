<?php

/**
 * Plugin Name: Revpanda - Test Project
 * Description:
 * Author:      Ivan Maljukanovic
 * Author URI:  https://imoptimal.com
 * Version:     1.0.0
 * Requires at least: 4.9.8
 * Requires PHP: 5.6
 * License: GNU General Public License v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Revpanda_WP')) {
    class Revpanda_WP
    {
        // Plugin version number
        private $version = '1.0.0';
        // Database table version number
        private $db_version = '1.0';
        public function __construct()
        {
            $this->init();
        }

        // Init function separated from default construct func
        public function init()
        {
            register_activation_hook(__FILE__, array($this, 'create_database_tables'));
            add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));
            add_filter('template_include', array($this, 'replace_homepage'));
            add_action('get_header', array($this, 'replace_header'));
            //add_action( 'wp_ajax_insert_into_database', array( $this, 'insert_into_database' ) );
        }

        /**
         * Creating custom database for plugin data (youtube videos retrieved via javascript)
         *
         * @since    1.0.0
         * */
        public function create_database_tables()
        {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = self::get_revpanda_table_name();
            $sql = "CREATE TABLE {$table_name} (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                a_database_values LONGTEXT NOT NULL,
                b_database_values INTEGER NOT NULL,
                c_database_values LONGTEXT NOT NULL,
                PRIMARY KEY  (id)) {$charset_collate};";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
            add_option('wp_how_to_favs_db_version', $this->db_version);
        }

        /**
         * Adding css and js files to frontend
         *
         * @since    1.0.0
         * */
        public function frontend_assets()
        {
            wp_enqueue_style(
                'wp-revpanda-style',
                plugin_dir_url(__FILE__) . 'style.css',
                [],
                $this->version
            );
            wp_enqueue_script(
                'wp-revpanda-script',
                plugin_dir_url(__FILE__) . 'script.js',
                [],
                $this->version,
                true
            );
            $plugin_folder_path = WP_PLUGIN_DIR . '/revpanda';
            wp_localize_script('wp-revpanda-script', 'plugin_data', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'plugin_folder_path' => $plugin_folder_path,
            ));
        }

        /**
         * Filter to replace the template of the homepage
         *
         * @since    1.0.0
         */
        public function replace_homepage($template)
        {
            if (is_front_page()) {
                return plugin_dir_path(__FILE__) . 'index.php';
            }
            return $template;
        }

        /**
         * Replace default header
         *
         * @since    1.0.0
         */
        public function replace_header()
        {
            if (is_front_page()) {
                include_once plugin_dir_path(__FILE__) . 'header-revpanda.php';
            }
        }

        /**
         * Get custom database table name for our test project
         *
         * @since    1.0.0
         * */
        public static function get_revpanda_table_name()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'revpanda';
            return $table_name;
        }

    }
    $wp_revpanda_object = new Revpanda_WP();
}