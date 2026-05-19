<div class="wca-form-container wca-dark-theme">
    <div class="wca-form-left">
        <div class="wca-logo">
            <div class="wca-logo-circle active"></div>
            <div class="wca-logo-circle active"></div>
            <div class="wca-logo-circle"></div>
            <div class="wca-logo-circle"></div>
        </div>
        <h2><?php _e( 'Essential informations', 'wp-custom-auth' ); ?></h2>
    </div>
    
    <div class="wca-form-right">
        <form id="wca-essential-form">
            <input type="email" name="email" placeholder="Email" required class="wca-input-dark">
            <input type="password" name="password" placeholder="Password" required class="wca-input-dark">
            <input type="text" name="username" placeholder="Username" required class="wca-input-dark">
            <input type="text" name="dob" placeholder="Date of birth       YYMMDD" class="wca-input-dark">
            
            <div class="wca-form-actions">
                <button type="button" class="wca-btn-back"><?php _e( 'Back', 'wp-custom-auth' ); ?></button>
                <button type="button" class="wca-btn-next"><?php _e( 'Next', 'wp-custom-auth' ); ?></button>
            </div>
        </form>
    </div>
</div>
