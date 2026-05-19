<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wca-wrapper" id="wca-register-wrapper">
    <div class="wca-card">
        <h2 class="wca-title"><?php esc_html_e( 'ایجاد حساب کاربری', 'wp-custom-auth' ); ?></h2>

        <div class="wca-messages" id="wca-register-messages" role="alert" aria-live="polite"></div>

        <form id="wca-register-form" class="wca-form" novalidate>
            <?php wp_nonce_field( 'wca_nonce', 'wca_nonce_field' ); ?>

            <div class="wca-field">
                <label for="wca-reg-username">
                    <?php esc_html_e( 'نام کاربری', 'wp-custom-auth' ); ?>
                    <span aria-hidden="true">*</span>
                </label>
                <input
                    type="text"
                    id="wca-reg-username"
                    name="username"
                    class="wca-input"
                    autocomplete="username"
                    required
                    aria-required="true"
                    minlength="3"
                />
            </div>

            <div class="wca-field">
                <label for="wca-reg-email">
                    <?php esc_html_e( 'ایمیل', 'wp-custom-auth' ); ?>
                    <span aria-hidden="true">*</span>
                </label>
                <input
                    type="email"
                    id="wca-reg-email"
                    name="email"
                    class="wca-input"
                    autocomplete="email"
                    required
                    aria-required="true"
                />
            </div>

            <div class="wca-field">
                <label for="wca-reg-password">
                    <?php esc_html_e( 'رمز عبور', 'wp-custom-auth' ); ?>
                    <span aria-hidden="true">*</span>
                </label>
                <div class="wca-password-wrap">
                    <input
                        type="password"
                        id="wca-reg-password"
                        name="password"
                        class="wca-input"
                        autocomplete="new-password"
                        required
                        aria-required="true"
                        minlength="8"
                    />
                    <button type="button" class="wca-toggle-pass" aria-label="<?php esc_attr_e( 'نمایش/پنهان رمز', 'wp-custom-auth' ); ?>">
                        👁
                    </button>
                </div>
                <div class="wca-strength-bar" id="wca-strength-bar">
                    <div class="wca-strength-fill" id="wca-strength-fill"></div>
                </div>
                <small class="wca-strength-text" id="wca-strength-text"></small>
            </div>

            <div class="wca-field">
                <label for="wca-reg-password2">
                    <?php esc_html_e( 'تکرار رمز عبور', 'wp-custom-auth' ); ?>
                    <span aria-hidden="true">*</span>
                </label>
                <div class="wca-password-wrap">
                    <input
                        type="password"
                        id="wca-reg-password2"
                        name="password2"
                        class="wca-input"
                        autocomplete="new-password"
                        required
                        aria-required="true"
                    />
                    <button type="button" class="wca-toggle-pass" aria-label="<?php esc_attr_e( 'نمایش/پنهان رمز', 'wp-custom-auth' ); ?>">
                        👁
                    </button>
                </div>
            </div>

            <button type="submit" class="wca-btn" id="wca-register-btn">
                <?php esc_html_e( 'ثبت‌نام', 'wp-custom-auth' ); ?>
            </button>
        </form>

        <p class="wca-switch-link">
            <?php esc_html_e( 'حساب دارید؟', 'wp-custom-auth' ); ?>
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'login' ) ) ); ?>">
                <?php esc_html_e( 'وارد شوید', 'wp-custom-auth' ); ?>
            </a>
        </p>
    </div>
</div>
