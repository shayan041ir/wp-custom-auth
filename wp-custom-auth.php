<?php
/**
 * Plugin Name: WP Custom Auth
 * Plugin URI:  https://zaraskinclinic.com
 * Description: پلاگین ورود و ثبت‌نام سفارشی با ایمیل، نام کاربری و رمز عبور
 * Version:     1.0.0
 * Author:      shayan rezayi
 * Text Domain: wp-custom-auth
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WCA_VERSION',     '1.0.0' );
define( 'WCA_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'WCA_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'WCA_PLUGIN_FILE', __FILE__ );

require_once WCA_PLUGIN_DIR . 'includes/class-form-validator.php';
require_once WCA_PLUGIN_DIR . 'includes/class-mailer.php';
require_once WCA_PLUGIN_DIR . 'includes/class-auth-handler.php';

class WP_Custom_Auth {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init',                  [ $this, 'load_textdomain' ] );
        add_action( 'wp_enqueue_scripts',    [ $this, 'enqueue_assets' ] );
        add_shortcode( 'wca_login',          [ $this, 'render_login' ] );
        add_shortcode( 'wca_register',       [ $this, 'render_register' ] );
        add_action( 'wp_ajax_nopriv_wca_login',    [ 'WCA_Auth_Handler', 'handle_login' ] );
        add_action( 'wp_ajax_nopriv_wca_register', [ 'WCA_Auth_Handler', 'handle_register' ] );
        add_action( 'wp_ajax_wca_logout',          [ 'WCA_Auth_Handler', 'handle_logout' ] );
        register_activation_hook( WCA_PLUGIN_FILE, [ $this, 'activate' ] );
        register_deactivation_hook( WCA_PLUGIN_FILE, [ $this, 'deactivate' ] );
    }

    public function load_textdomain() {
        load_plugin_textdomain(
            'wp-custom-auth',
            false,
            dirname( plugin_basename( WCA_PLUGIN_FILE ) ) . '/languages'
        );
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'wca-style',
            WCA_PLUGIN_URL . 'assets/css/auth-style.css',
            [],
            WCA_VERSION
        );
        wp_enqueue_script(
            'wca-script',
            WCA_PLUGIN_URL . 'assets/js/auth-script.js',
            [ 'jquery' ],
            WCA_VERSION,
            true
        );
        wp_localize_script( 'wca-script', 'wca_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'wca_nonce' ),
        ]);
    }

    public function render_login() {
        if ( is_user_logged_in() ) {
            return '<p>' . sprintf(
                __( 'شما وارد شده‌اید. <a href="%s">خروج</a>', 'wp-custom-auth' ),
                wp_logout_url( home_url() )
            ) . '</p>';
        }
        ob_start();
        include WCA_PLUGIN_DIR . 'templates/login-form.php';
        return ob_get_clean();
    }

    public function render_register() {
        if ( is_user_logged_in() ) {
            return '<p>' . __( 'شما قبلاً ثبت‌نام کرده‌اید.', 'wp-custom-auth' ) . '</p>';
        }
        ob_start();
        include WCA_PLUGIN_DIR . 'templates/register-form.php';
        return ob_get_clean();
    }

    public function activate() {
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }
}

WP_Custom_Auth::get_instance();
