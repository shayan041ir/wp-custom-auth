<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCA_Music_Preferences {
    
    public static function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wca_music_preferences';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            user_type varchar(20) NOT NULL,
            genres text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    
    public static function save_preferences( $user_id, $user_type, $genres ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wca_music_preferences';
        
        return $wpdb->insert(
            $table_name,
            [
                'user_id'   => $user_id,
                'user_type' => sanitize_text_field( $user_type ),
                'genres'    => maybe_serialize( $genres )
            ],
            ['%d', '%s', '%s']
        );
    }
    
    public static function get_all_genres() {
        return [
            'pop'           => __( 'پاپ', 'wp-custom-auth' ),
            'rock'          => __( 'راک', 'wp-custom-auth' ),
            'jazz'          => __( 'جز', 'wp-custom-auth' ),
            'classical'     => __( 'کلاسیک', 'wp-custom-auth' ),
            'hip-hop'       => __( 'هیپ‌هاپ', 'wp-custom-auth' ),
            'electronic'    => __( 'الکترونیک', 'wp-custom-auth' ),
            'blues'         => __( 'بلوز', 'wp-custom-auth' ),
            'country'       => __( 'کانتری', 'wp-custom-auth' ),
            'reggae'        => __( 'رگی', 'wp-custom-auth' ),
            'folk'          => __( 'فولک', 'wp-custom-auth' ),
            'metal'         => __( 'متال', 'wp-custom-auth' ),
            'soul'          => __( 'سول', 'wp-custom-auth' ),
            'rnb'           => __( 'آر اند بی', 'wp-custom-auth' ),
            'latin'         => __( 'لاتین', 'wp-custom-auth' ),
            'indie'         => __( 'ایندی', 'wp-custom-auth' ),
            'alternative'   => __( 'آلترناتیو', 'wp-custom-auth' ),
            'punk'          => __( 'پانک', 'wp-custom-auth' ),
            'disco'         => __( 'دیسکو', 'wp-custom-auth' ),
            'funk'          => __( 'فانک', 'wp-custom-auth' ),
            'ambient'       => __( 'امبینت', 'wp-custom-auth' ),
            'techno'        => __( 'تکنو', 'wp-custom-auth' ),
            'house'         => __( 'هاوس', 'wp-custom-auth' ),
            'trance'        => __( 'ترنس', 'wp-custom-auth' ),
            'dubstep'       => __( 'دابستپ', 'wp-custom-auth' ),
            'trap'          => __( 'ترپ', 'wp-custom-auth' ),
            'gospel'        => __( 'گاسپل', 'wp-custom-auth' ),
            'opera'         => __( 'اپرا', 'wp-custom-auth' ),
            'world'         => __( 'موسیقی جهان', 'wp-custom-auth' ),
            'persian-pop'   => __( 'پاپ ایرانی', 'wp-custom-auth' ),
            'persian-traditional' => __( 'سنتی ایرانی', 'wp-custom-auth' )
        ];
    }
}

// Ajax handler برای ذخیره ثبت‌نام کامل
add_action( 'wp_ajax_wca_complete_registration', 'wca_complete_registration_handler' );
add_action( 'wp_ajax_nopriv_wca_complete_registration', 'wca_complete_registration_handler' );

function wca_complete_registration_handler() {
    check_ajax_referer( 'wca_nonce', 'nonce' );
    
    $user_type = sanitize_text_field( $_POST['user_type'] ?? '' );
    $email = sanitize_email( $_POST['email'] ?? '' );
    $password = $_POST['password'] ?? '';
    $username = sanitize_user( $_POST['username'] ?? '' );
    $dob = sanitize_text_field( $_POST['dob'] ?? '' );
    $genres = array_map( 'sanitize_text_field', $_POST['genres'] ?? [] );
    
    // اعتبارسنجی
    if ( empty( $email ) || empty( $password ) || empty( $username ) ) {
        wp_send_json_error( ['message' => __( 'لطفاً تمام فیلدها را پر کنید.', 'wp-custom-auth' )] );
    }
    
    if ( username_exists( $username ) || email_exists( $email ) ) {
        wp_send_json_error( ['message' => __( 'نام کاربری یا ایمیل قبلاً ثبت شده است.', 'wp-custom-auth' )] );
    }
    
    // ایجاد کاربر
    $user_id = wp_create_user( $username, $password, $email );
    
    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( ['message' => $user_id->get_error_message()] );
    }
    
    // ذخیره متادیتا
    update_user_meta( $user_id, 'user_type', $user_type );
    update_user_meta( $user_id, 'date_of_birth', $dob );
    
    // ذخیره سلیقه موسیقی
    WCA_Music_Preferences::save_preferences( $user_id, $user_type, $genres );
    
    // ورود خودکار
    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id );
    
    // ارسال ایمیل خوش‌آمدگویی
    WCA_Mailer::send_welcome_email( $user_id );
    
    $redirect = apply_filters( 'wca_register_redirect', home_url(), $user_id );
    
    wp_send_json_success( [
        'message' => __( 'ثبت‌نام با موفقیت انجام شد!', 'wp-custom-auth' ),
        'redirect' => $redirect
    ]);
}
