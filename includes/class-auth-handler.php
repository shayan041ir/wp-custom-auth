<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCA_Auth_Handler {
    
    public function __construct() {
        add_action( 'wp_ajax_wca_login', [ $this, 'handle_login' ] );
        add_action( 'wp_ajax_nopriv_wca_login', [ $this, 'handle_login' ] );
        
        add_action( 'wp_ajax_wca_register', [ $this, 'handle_register' ] );
        add_action( 'wp_ajax_nopriv_wca_register', [ $this, 'handle_register' ] );
    }
    
    public function handle_login() {
        check_ajax_referer( 'wca_nonce', 'nonce' );
        
        $username = sanitize_user( $_POST['username'] ?? '' );
        $password = $_POST['password'] ?? '';
        $remember = isset( $_POST['remember'] );
        
        if ( empty( $username ) || empty( $password ) ) {
            wp_send_json_error( ['message' => __( 'لطفاً نام کاربری و رمز عبور را وارد کنید.', 'wp-custom-auth' )] );
        }
        
        $creds = [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember
        ];
        
        $user = wp_signon( $creds, is_ssl() );
        
        if ( is_wp_error( $user ) ) {
            wp_send_json_error( ['message' => __( 'نام کاربری یا رمز عبور اشتباه است.', 'wp-custom-auth' )] );
        }
        
        $redirect = apply_filters( 'wca_login_redirect', home_url(), $user->ID );
        
        wp_send_json_success( [
            'message' => __( 'ورود موفقیت‌آمیز بود!', 'wp-custom-auth' ),
            'redirect' => $redirect
        ]);
    }
    
    public function handle_register() {
        check_ajax_referer( 'wca_nonce', 'nonce' );
        
        $email = sanitize_email( $_POST['email'] ?? '' );
        $password = $_POST['password'] ?? '';
        $username = sanitize_user( $_POST['username'] ?? '' );
        
        if ( empty( $email ) || empty( $password ) || empty( $username ) ) {
            wp_send_json_error( ['message' => __( 'لطفاً تمام فیلدها را پر کنید.', 'wp-custom-auth' )] );
        }
        
        if ( username_exists( $username ) || email_exists( $email ) ) {
            wp_send_json_error( ['message' => __( 'نام کاربری یا ایمیل قبلاً ثبت شده است.', 'wp-custom-auth' )] );
        }
        
        $user_id = wp_create_user( $username, $password, $email );
        
        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( ['message' => $user_id->get_error_message()] );
        }
        
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );
        
        WCA_Mailer::send_welcome_email( $user_id );
        
        $redirect = apply_filters( 'wca_register_redirect', home_url(), $user_id );
        
        wp_send_json_success( [
            'message' => __( 'ثبت‌نام با موفقیت انجام شد!', 'wp-custom-auth' ),
            'redirect' => $redirect
        ]);
    }
}

new WCA_Auth_Handler();
