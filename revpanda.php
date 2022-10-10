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
            add_action('wp_ajax_insert_into_database', array($this, 'insert_into_database'));
            add_action('wp_ajax_nopriv_insert_into_database', array($this, 'insert_into_database'));
            add_action('wp_ajax_get_database_data', array($this, 'get_database_data'));
            add_action('wp_ajax_nopriv_get_database_data', array($this, 'get_database_data'));
            add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));
            add_filter('template_include', array($this, 'replace_homepage'));
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
            add_option('wp_revpanda_db_version', $this->db_version);
        }

        /**
         * Inserting data retrieved via javascript into custom database table
         *
         * @since    1.0.0
         * */
        public function insert_into_database()
        {
            // Security check - verify set nonce
            if (!wp_verify_nonce($_POST["post_revpanda_nonce"], "post_revpanda_nonce")) {
                exit("Access denied!");
            }
            global $wpdb;
            $table_name = self::get_revpanda_table_name();
            $data = $_POST["wp_revpanda_data"];
            $sanitized_field_a = sanitize_text_field($data[0]);
            $sanitized_field_b = sanitize_text_field($data[1]);
            $sanitized_field_c = sanitize_text_field($data[2]);
            $wpdb->insert($table_name, array(
                'a_database_values' => $sanitized_field_a,
                'b_database_values' => $sanitized_field_b,
                'c_database_values' => $sanitized_field_c,
            ));
        }

        /**
         * Get data from database table to be sent and displayed to end users (through javascript)
         *
         * @since    1.0.0
         * */
        public function get_database_data()
        {
            // Security check - verify set nonce
            if (!wp_verify_nonce($_GET["get_revpanda_nonce"], "get_revpanda_nonce")) {
                exit("Access denied!");
            }
            global $wpdb;
            $table_name = self::get_revpanda_table_name();
            $table_data = $wpdb->get_results("SELECT * FROM {$table_name}");
            $validated_data = [];
            if (!empty($table_data)) {
                foreach ($table_data as $item => $data) {
                    $validated_item = [];
                    $a_database_values = esc_html( $data->a_database_values);
                    $b_database_values = esc_html( $data->b_database_values);
                    $c_database_values = esc_html( $data->c_database_values);
                    $validated_item['a_database_values'] = $a_database_values;
                    $validated_item['b_database_values'] = $b_database_values;
                    $validated_item['c_database_values'] = $c_database_values;
                    array_push($validated_data, $validated_item);
                }
            }
            /*$json_validated_data = json_encode($validated_data);
            return $json_validated_data;*/
            wp_send_json_success($validated_data);
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
                ['jquery'],
                $this->version,
                true
            );
            //$database_data = $this->get_database_data();
            wp_localize_script('wp-revpanda-script', 'plugin_data', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'post_revpanda_nonce' => wp_create_nonce('post_revpanda_nonce'),
                'get_revpanda_nonce' => wp_create_nonce('get_revpanda_nonce'),
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