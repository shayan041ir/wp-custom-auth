<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCA_Mailer {

    public static function send_welcome_email( $user_id ) {
        $user      = get_userdata( $user_id );
        $site_name = get_bloginfo( 'name' );
        $to        = $user->user_email;
        $subject   = sprintf( __( 'خوش آمدید به %s', 'wp-custom-auth' ), $site_name );

        $message  = sprintf( __( 'سلام %s،', 'wp-custom-auth' ), $user->display_name ) . "\n\n";
        $message .= sprintf( __( 'ثبت‌نام شما در %s با موفقیت انجام شد.', 'wp-custom-auth' ), $site_name ) . "\n";
        $message .= sprintf( __( 'نام کاربری: %s', 'wp-custom-auth' ), $user->user_login ) . "\n\n";
        $message .= sprintf( __( 'برای ورود به سایت کلیک کنید: %s', 'wp-custom-auth' ), home_url() ) . "\n";

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            sprintf( 'From: %s <%s>', $site_name, get_option( 'admin_email' ) ),
        ];

        wp_mail( $to, $subject, $message, $headers );
    }
}
