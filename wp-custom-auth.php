<?php
/**
 * Plugin Name: WP Custom Auth with Music Preferences
 * Description: سیستم ورود و ثبت‌نام چند مرحله‌ای با انتخاب سلیقه موسیقی
 * Version: 2.0
 * Author: Your Name
 * Text Domain: wp-custom-auth
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WCA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WCA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// بارگذاری کلاس‌ها
require_once WCA_PLUGIN_DIR . 'includes/class-auth-handler.php';
require_once WCA_PLUGIN_DIR . 'includes/class-form-validator.php';
require_once WCA_PLUGIN_DIR . 'includes/class-mailer.php';
require_once WCA_PLUGIN_DIR . 'includes/class-music-preferences.php';
require_once WCA_PLUGIN_DIR . 'includes/class-admin-preferences.php';

// بارگذاری استایل‌ها و اسکریپت‌ها
function wca_enqueue_assets() {
    wp_enqueue_style( 'wca-auth-style', WCA_PLUGIN_URL . 'assets/css/auth-style.css', [], '2.0' );
    wp_enqueue_style( 'wca-music-style', WCA_PLUGIN_URL . 'assets/css/music-preferences.css', [], '2.0' );
    
    wp_enqueue_script( 'wca-auth-script', WCA_PLUGIN_URL . 'assets/js/auth-script.js', ['jquery'], '2.0', true );
    wp_enqueue_script( 'wca-music-script', WCA_PLUGIN_URL . 'assets/js/music-preferences.js', ['jquery'], '2.0', true );
    
    wp_localize_script( 'wca-auth-script', 'wcaAjax', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'wca_nonce' )
    ]);
}
add_action( 'wp_enqueue_scripts', 'wca_enqueue_assets' );

// شورت‌کد فرم چند مرحله‌ای
function wca_multi_step_register_shortcode() {
    ob_start();
    ?>
    <div class="wca-multi-step-container">
        <div class="wca-progress-bar">
            <div class="wca-progress-step active" data-step="1">1</div>
            <div class="wca-progress-step" data-step="2">2</div>
            <div class="wca-progress-step" data-step="3">3</div>
        </div>
        
        <div class="wca-step-content">
            <div class="wca-step wca-step-1 active">
                <?php include WCA_PLUGIN_DIR . 'templates/user-type-form.php'; ?>
            </div>
            <div class="wca-step wca-step-2">
                <?php include WCA_PLUGIN_DIR . 'templates/essential-info-form.php'; ?>
            </div>
            <div class="wca-step wca-step-3">
                <?php include WCA_PLUGIN_DIR . 'templates/music-preferences-form.php'; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'wca_register_multi', 'wca_multi_step_register_shortcode' );

// شورت‌کد ورود (قبلی)
function wca_login_shortcode() {
    ob_start();
    include WCA_PLUGIN_DIR . 'templates/login-form.php';
    return ob_get_clean();
}
add_shortcode( 'wca_login', 'wca_login_shortcode' );

// فعال‌سازی پلاگین
function wca_activate() {
    WCA_Music_Preferences::create_table();
}
register_activation_hook( __FILE__, 'wca_activate' );

// بین‌المللی‌سازی
function wca_load_textdomain() {
    load_plugin_textdomain( 'wp-custom-auth', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wca_load_textdomain' );
