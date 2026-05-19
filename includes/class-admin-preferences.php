<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCA_Admin_Preferences {
    
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __( 'سلیقه مشتریان', 'wp-custom-auth' ),
            __( 'سلیقه مشتریان', 'wp-custom-auth' ),
            'manage_options',
            'wca-music-preferences',
            [ $this, 'render_admin_page' ],
            'dashicons-playlist-audio',
            30
        );
    }
    
    public function enqueue_admin_assets( $hook ) {
        if ( $hook !== 'toplevel_page_wca-music-preferences' ) {
            return;
        }
        
        wp_enqueue_style( 'wca-admin-style', WCA_PLUGIN_URL . 'assets/css/admin-preferences.css', [], '2.0' );
    }
    
    public function render_admin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wca_music_preferences';
        
        $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC" );
        
        ?>
        <div class="wrap wca-admin-wrap">
            <h1><?php _e( 'سلیقه موسیقی مشتریان', 'wp-custom-auth' ); ?></h1>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e( 'شناسه', 'wp-custom-auth' ); ?></th>
                        <th><?php _e( 'نام کاربری', 'wp-custom-auth' ); ?></th>
                        <th><?php _e( 'ایمیل', 'wp-custom-auth' ); ?></th>
                        <th><?php _e( 'نوع کاربر', 'wp-custom-auth' ); ?></th>
                        <th><?php _e( 'سبک‌های موسیقی', 'wp-custom-auth' ); ?></th>
                        <th><?php _e( 'تاریخ ثبت', 'wp-custom-auth' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( $results ) : ?>
                        <?php foreach ( $results as $row ) : 
                            $user = get_userdata( $row->user_id );
                            $genres = maybe_unserialize( $row->genres );
                            $all_genres = WCA_Music_Preferences::get_all_genres();
                            $genre_names = array_map( function( $g ) use ( $all_genres ) {
                                return $all_genres[$g] ?? $g;
                            }, $genres );
                        ?>
                        <tr>
                            <td><?php echo esc_html( $row->id ); ?></td>
                            <td><?php echo $user ? esc_html( $user->user_login ) : '-'; ?></td>
                            <td><?php echo $user ? esc_html( $user->user_email ) : '-'; ?></td>
                            <td>
                                <span class="wca-badge wca-badge-<?php echo esc_attr( $row->user_type ); ?>">
                                    <?php echo $row->user_type === 'personal' ? __( 'شخصی', 'wp-custom-auth' ) : __( 'کسب‌وکار', 'wp-custom-auth' ); ?>
                                </span>
                            </td>
                            <td>
                                <div class="wca-genres-list">
                                    <?php echo implode( ', ', $genre_names ); ?>
                                </div>
                            </td>
                            <td><?php echo esc_html( date_i18n( 'Y/m/d H:i', strtotime( $row->created_at ) ) ); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                <?php _e( 'هیچ داده‌ای یافت نشد.', 'wp-custom-auth' ); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}

new WCA_Admin_Preferences();
