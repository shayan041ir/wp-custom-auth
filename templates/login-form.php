<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wca-wrapper" id="wca-login-wrapper">
    <div class="wca-card">
        <h2 class="wca-title"><?php esc_html_e( 'ورود به حساب', 'wp-custom-auth' ); ?></h2>

        <div class="wca-messages" id="wca-login-messages" role="alert" aria-live="polite"></div>

        <form id="wca-login-form" class="wca-form" novalidate>
            <?php wp_nonce_field( 'wca_nonce', 'wca_nonce_field' ); ?>

            <div class="wca-field">
                <label for="wca-login-username">
                    <?php esc_html_e( 'نام کاربری یا ایمیل', 'wp-custom-auth' ); ?>
                    <span aria-hidden="true">*</span>
                </label>
                <input
                    type="text"
                    id="wca-login-username"
                    name="username"
                    class="wca-input"
                    autocomplete="username"
                    required
                    aria-required="true"
                />
            </div>

            <div class="wca-field">
                <label for="wca-login-password">
                    <?php esc_html_e( 'رمز عبور', 'wp-custom-auth' ); ?>
                    <span aria-hidden="true">*</span>
                </label>
                <div class="wca-password-wrap">
                    <input
                        type="password"
                        id="wca-login-password"
                        name="password"
                        class="wca-input"
                        autocomplete="current-password"
                        required
                        aria-required="true"
                    />
                    <button type="button" class="wca-toggle-pass" aria-label="<?php esc_attr_e( 'نمایش/پنهان رمز', 'wp-custom-auth' ); ?>">
                        👁
                    </button>
                </div>
            </div>

            <div class="wca-field wca-checkbox-field">
                <label>
                    <input type="checkbox" name="remember" value="true" />
                    <?php esc_html_e( 'مرا به خاطر بسپار', 'wp-custom-auth' ); ?>
                </label>
            </div>

            <button type="submit" class="wca-btn" id="wca-login-btn">
                <?php esc_html_e( 'ورود', 'wp-custom-auth' ); ?>
            </button>
        </form>

        <p class="wca-switch-link">
            <?php esc_html_e( 'حساب ندارید؟', 'wp-custom-auth' ); ?>
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'register' ) ) ); ?>">
                <?php esc_html_e( 'ثبت‌نام کنید', 'wp-custom-auth' ); ?>
            </a>
        </p>
    </div>
</div>
    