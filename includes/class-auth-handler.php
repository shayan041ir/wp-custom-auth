<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCA_Auth_Handler {

    /**
     * پردازش ورود کاربر
     */
    public static function handle_login() {
        check_ajax_referer( 'wca_nonce', 'nonce' );

        $username = isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '';
        $password = isset( $_POST['password'] ) ? $_POST['password'] : '';
        $remember = isset( $_POST['remember'] ) && $_POST['remember'] === 'true';

        $errors = WCA_Form_Validator::validate_login( $username, $password );

        if ( ! empty( $errors ) ) {
            wp_send_json_error( [ 'messages' => $errors ] );
        }

        // پشتیبانی از ورود با ایمیل یا نام کاربری
        if ( is_email( $username ) ) {
            $user = get_user_by( 'email', $username );
            if ( $user ) {
                $username = $user->user_login;
            }
        }

        $credentials = [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        ];

        $user = wp_signon( $credentials, is_ssl() );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( [
                'messages' => [ __( 'نام کاربری یا رمز عبور اشتباه است.', 'wp-custom-auth' ) ],
            ]);
        }

        wp_send_json_success( [
            'message'  => __( 'ورود موفق! در حال انتقال...', 'wp-custom-auth' ),
            'redirect' => apply_filters( 'wca_login_redirect', home_url(), $user ),
        ]);
    }

    /**
     * پردازش ثبت‌نام کاربر
     */
    public static function handle_register() {
        check_ajax_referer( 'wca_nonce', 'nonce' );

        if ( ! get_option( 'users_can_register' ) ) {
            wp_send_json_error( [
                'messages' => [ __( 'ثبت‌نام در این سایت غیرفعال است.', 'wp-custom-auth' ) ],
            ]);
        }

        $username  = isset( $_POST['username'] )  ? sanitize_user( wp_unslash( $_POST['username'] ) )        : '';
        $email     = isset( $_POST['email'] )     ? sanitize_email( wp_unslash( $_POST['email'] ) )           : '';
        $password  = isset( $_POST['password'] )  ? $_POST['password']                                        : '';
        $password2 = isset( $_POST['password2'] ) ? $_POST['password2']                                       : '';

        $errors = WCA_Form_Validator::validate_register( $username, $email, $password, $password2 );

        if ( ! empty( $errors ) ) {
            wp_send_json_error( [ 'messages' => $errors ] );
        }

        $user_id = wp_create_user( $username, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( [
                'messages' => [ $user_id->get_error_message() ],
            ]);
        }

        // ارسال ایمیل خوش‌آمدگویی
        WCA_Mailer::send_welcome_email( $user_id );

        // ورود خودکار بعد از ثبت‌نام
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );

        wp_send_json_success( [
            'message'  => __( 'ثبت‌نام موفق! در حال انتقال...', 'wp-custom-auth' ),
            'redirect' => apply_filters( 'wca_register_redirect', home_url(), $user_id ),
        ]);
    }

    /**
     * پردازش خروج کاربر
     */
    public static function handle_logout() {
        check_ajax_referer( 'wca_nonce', 'nonce' );
        wp_logout();
        wp_send_json_success( [ 'redirect' => home_url() ] );
    }
}
