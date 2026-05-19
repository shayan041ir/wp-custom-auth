(function ($) {
    'use strict';

    /* ===== Utility: نمایش پیام ===== */
    function showMessage(wrapper, messages, type) {
        var $box = wrapper.find('.wca-messages');
        $box.removeClass('success error');

        if (Array.isArray(messages) && messages.length > 1) {
            var list = '<ul>' + messages.map(function (m) {
                return '<li>' + $('<div>').text(m).html() + '</li>';
            }).join('') + '</ul>';
            $box.html(list);
        } else {
            var msg = Array.isArray(messages) ? messages[0] : messages;
            $box.text(msg);
        }

        $box.addClass(type).show();
        $box[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /* ===== Utility: وضعیت دکمه ===== */
    function setLoading($btn, loading) {
        if (loading) {
            $btn.prop('disabled', true)
                .data('original-text', $btn.text())
                .html($btn.data('original-text') + '<span class="wca-spinner"></span>');
        } else {
            $btn.prop('disabled', false)
                .text($btn.data('original-text') || $btn.text());
        }
    }

    /* ===== Utility: نمایش/پنهان رمز ===== */
    $(document).on('click', '.wca-toggle-pass', function () {
        var $btn   = $(this);
        var $input = $btn.closest('.wca-password-wrap').find('input');
        var isPass = $input.attr('type') === 'password';
        $input.attr('type', isPass ? 'text' : 'password');
        $btn.text(isPass ? '🙈' : '👁');
        $btn.attr('aria-label', isPass ? 'پنهان کردن رمز' : 'نمایش رمز');
    });

    /* ===== قدرت رمز عبور ===== */
    function checkStrength(password) {
        var score = 0;
        if (password.length >= 8)  score++;
        if (password.length >= 12) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        return score;
    }

    var strengthLabels = ['', 'خیلی ضعیف', 'ضعیف', 'متوسط', 'قوی', 'خیلی قوی'];
    var strengthColors = ['', '#ef4444',   '#f97316', '#eab308', '#22c55e', '#16a34a'];

    $('#wca-reg-password').on('input', function () {
        var val   = $(this).val();
        var score = val.length ? checkStrength(val) : 0;
        var pct   = (score / 5) * 100;

        $('#wca-strength-fill').css({
            width:      pct + '%',
            background: strengthColors[score] || '#e5e7eb',
        });
        $('#wca-strength-text')
            .text(val.length ? strengthLabels[score] : '')
            .css('color', strengthColors[score] || '#6b7280');
    });

    /* ===== فرم ورود ===== */
    $('#wca-login-form').on('submit', function (e) {
        e.preventDefault();

        var $form    = $(this);
        var $wrapper = $('#wca-login-wrapper');
        var $btn     = $('#wca-login-btn');

        var data = {
            action:   'wca_login',
            nonce:    wca_ajax.nonce,
            username: $form.find('[name="username"]').val().trim(),
            password: $form.find('[name="password"]').val(),
            remember: $form.find('[name="remember"]').is(':checked') ? 'true' : 'false',
        };

        // اعتبارسنجی ساده سمت کلاینت
        if (!data.username || !data.password) {
            showMessage($wrapper, ['لطفاً تمام فیلدها را پر کنید.'], 'error');
            return;
        }

        setLoading($btn, true);

        $.post(wca_ajax.ajax_url, data)
            .done(function (res) {
                if (res.success) {
                    showMessage($wrapper, res.data.message, 'success');
                    setTimeout(function () {
                        window.location.href = res.data.redirect;
                    }, 1200);
                } else {
                    showMessage($wrapper, res.data.messages, 'error');
                    setLoading($btn, false);
                }
            })
            .fail(function () {
                showMessage($wrapper, ['خطای شبکه. لطفاً دوباره تلاش کنید.'], 'error');
                setLoading($btn, false);
            });
    });

    /* ===== فرم ثبت‌نام ===== */
    $('#wca-register-form').on('submit', function (e) {
        e.preventDefault();

        var $form    = $(this);
        var $wrapper = $('#wca-register-wrapper');
        var $btn     = $('#wca-register-btn');

        var username  = $form.find('[name="username"]').val().trim();
        var email     = $form.find('[name="email"]').val().trim();
        var password  = $form.find('[name="password"]').val();
        var password2 = $form.find('[name="password2"]').val();

        // اعتبارسنجی ساده سمت کلاینت
        var clientErrors = [];
        if (!username)              clientErrors.push('نام کاربری الزامی است.');
        if (!email)                 clientErrors.push('ایمیل الزامی است.');
        if (!password)              clientErrors.push('رمز عبور الزامی است.');
        if (password !== password2) clientErrors.push('تکرار رمز عبور مطابقت ندارد.');

        if (clientErrors.length) {
            showMessage($wrapper, clientErrors, 'error');
            return;
        }

        setLoading($btn, true);

        var data = {
            action:    'wca_register',
            nonce:     wca_ajax.nonce,
            username:  username,
            email:     email,
            password:  password,
            password2: password2,
        };

        $.post(wca_ajax.ajax_url, data)
            .done(function (res) {
                if (res.success) {
                    showMessage($wrapper, res.data.message, 'success');
                    $form[0].reset();
                    $('#wca-strength-fill').css('width', '0');
                    $('#wca-strength-text').text('');
                    setTimeout(function () {
                        window.location.href = res.data.redirect;
                    }, 1500);
                } else {
                    showMessage($wrapper, res.data.messages, 'error');
                    setLoading($btn, false);
                }
            })
            .fail(function () {
                showMessage($wrapper, ['خطای شبکه. لطفاً دوباره تلاش کنید.'], 'error');
                setLoading($btn, false);
            });
    });

})(jQuery);
