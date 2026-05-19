jQuery(document).ready(function($) {
    let currentStep = 1;
    let formData = {
        user_type: '',
        email: '',
        password: '',
        username: '',
        dob: '',
        genres: []
    };

    // مرحله 1: انتخاب نوع کاربر
    $('.wca-user-type-btn').on('click', function() {
        formData.user_type = $(this).data('type');
        goToStep(2);
    });

    // مرحله 2: اطلاعات ضروری
    $('.wca-step-2 .wca-btn-next').on('click', function() {
        const email = $('.wca-step-2 input[name="email"]').val();
        const password = $('.wca-step-2 input[name="password"]').val();
        const username = $('.wca-step-2 input[name="username"]').val();
        const dob = $('.wca-step-2 input[name="dob"]').val();

        if (!email || !password || !username) {
            showMessage('error', 'لطفاً تمام فیلدهای ضروری را پر کنید.');
            return;
        }

        if (!validateEmail(email)) {
            showMessage('error', 'فرمت ایمیل صحیح نیست.');
            return;
        }

        if (password.length < 6) {
            showMessage('error', 'رمز عبور باید حداقل ۶ کاراکتر باشد.');
            return;
        }

        formData.email = email;
        formData.password = password;
        formData.username = username;
        formData.dob = dob;

        goToStep(3);
    });

    // مرحله 3: ثبت نهایی
    $('#wca-music-form').on('submit', function(e) {
        e.preventDefault();

        const selectedGenres = [];
        $('.wca-step-3 input[name="genres[]"]:checked').each(function() {
            selectedGenres.push($(this).val());
        });

        if (selectedGenres.length === 0) {
            showMessage('error', 'لطفاً حداقل یک سبک موسیقی انتخاب کنید.');
            return;
        }

        formData.genres = selectedGenres;

        // ارسال درخواست Ajax
        $.ajax({
            url: wcaAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'wca_complete_registration',
                nonce: wcaAjax.nonce,
                ...formData
            },
            beforeSend: function() {
                $('.wca-btn-submit').prop('disabled', true).text('در حال پردازش...');
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.data.message);
                    setTimeout(function() {
                        window.location.href = response.data.redirect;
                    }, 1500);
                } else {
                    showMessage('error', response.data.message);
                    $('.wca-btn-submit').prop('disabled', false).text('Complete');
                }
            },
            error: function() {
                showMessage('error', 'خطایی رخ داده است. لطفاً دوباره تلاش کنید.');
                $('.wca-btn-submit').prop('disabled', false).text('Complete');
            }
        });
    });

    // دکمه‌های بازگشت
    $('.wca-btn-back').on('click', function() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    });

    // تابع تغییر مرحله
    function goToStep(step) {
        $('.wca-step').removeClass('active');
        $('.wca-step-' + step).addClass('active');

        $('.wca-progress-step').removeClass('active');
        for (let i = 1; i <= step; i++) {
            $('.wca-progress-step[data-step="' + i + '"]').addClass('active');
        }

        // انیمیشن لوگو
        $('.wca-logo-circle').removeClass('active');
        for (let i = 0; i < step; i++) {
            $('.wca-logo-circle').eq(i).addClass('active');
        }

        currentStep = step;
    }

    // تابع نمایش پیام
    function showMessage(type, message) {
        const $msg = $('.wca-message');
        $msg.removeClass('success error').addClass(type).text(message).fadeIn();
        
        setTimeout(function() {
            $msg.fadeOut();
        }, 5000);
    }

    // اعتبارسنجی ایمیل
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // انیمیشن چک‌باکس‌ها
    $('.wca-genre-checkbox').on('click', function() {
        $(this).toggleClass('checked');
    });
});
