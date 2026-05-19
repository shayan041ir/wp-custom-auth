<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCA_Form_Validator {

    public static function validate_login( $username, $password ) {
        $errors = [];

        if ( empty( $username ) ) {
            $errors[] = __( 'نام کاربری یا ایمیل الزامی است.', 'wp-custom-auth' );
        }

        if ( empty( $password ) ) {
            $errors[] = __( 'رمز عبور الزامی است.', 'wp-custom-auth' );
        }

        return $errors;
    }

    public static function validate_register( $username, $email, $password, $password2 ) {
        $errors = [];

        if ( empty( $username ) ) {
            $errors[] = __( 'نام کاربری الزامی است.', 'wp-custom-auth' );
        } elseif ( strlen( $username ) < 3 ) {
            $errors[] = __( 'نام کاربری باید حداقل ۳ کاراکتر باشد.', 'wp-custom-auth' );
        } elseif ( ! validate_username( $username ) ) {
            $errors[] = __( 'نام کاربری شامل کاراکترهای غیرمجاز است.', 'wp-custom-auth' );
        } elseif ( username_exists( $username ) ) {
            $errors[] = __( 'این نام کاربری قبلاً ثبت شده است.', 'wp-custom-auth' );
        }

        if ( empty( $email ) ) {
            $errors[] = __( 'ایمیل الزامی است.', 'wp-custom-auth' );
        } elseif ( ! is_email( $email ) ) {
            $errors[] = __( 'فرمت ایمیل صحیح نیست.', 'wp-custom-auth' );
        } elseif ( email_exists( $email ) ) {
            $errors[] = __( 'این ایمیل قبلاً ثبت شده است.', 'wp-custom-auth' );
        }

        if ( empty( $password ) ) {
            $errors[] = __( 'رمز عبور الزامی است.', 'wp-custom-auth' );
        } elseif ( strlen( $password ) < 8 ) {
            $errors[] = __( 'رمز عبور باید حداقل ۸ کاراکتر باشد.', 'wp-custom-auth' );
        } elseif ( ! preg_match( '/[A-Z]/', $password ) || ! preg_match( '/[0-9]/', $password ) ) {
            $errors[] = __( 'رمز عبور باید شامل حداقل یک حرف بزرگ و یک عدد باشد.', 'wp-custom-auth' );
        }

        if ( $password !== $password2 ) {
            $errors[] = __( 'تکرار رمز عبور مطابقت ندارد.', 'wp-custom-auth' );
        }

        return $errors;
    }
}
