<div class="wca-form-container wca-dark-theme">
    <div class="wca-form-left">
        <div class="wca-logo">
            <div class="wca-logo-circle active"></div>
            <div class="wca-logo-circle active"></div>
            <div class="wca-logo-circle active"></div>
            <div class="wca-logo-circle"></div>
        </div>
        <h2><?php _e( 'سلیقه موسیقی شما چیست؟', 'wp-custom-auth' ); ?></h2>
        <p><?php _e( 'سبک‌های مورد علاقه خود را انتخاب کنید', 'wp-custom-auth' ); ?></p>
    </div>
    
    <div class="wca-form-right">
        <form id="wca-music-form">
            <div class="wca-genres-grid">
                <?php 
                $genres = WCA_Music_Preferences::get_all_genres();
                foreach ( $genres as $key => $label ) : 
                ?>
                <label class="wca-genre-checkbox">
                    <input type="checkbox" name="genres[]" value="<?php echo esc_attr( $key ); ?>">
                    <span class="wca-checkbox-custom"></span>
                    <span class="wca-genre-label"><?php echo esc_html( $label ); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            
            <div class="wca-form-actions">
                <button type="button" class="wca-btn-back"><?php _e( 'Back', 'wp-custom-auth' ); ?></button>
                <button type="submit" class="wca-btn-submit"><?php _e( 'Complete', 'wp-custom-auth' ); ?></button>
            </div>
        </form>
        
        <div class="wca-message"></div>
    </div>
</div>
