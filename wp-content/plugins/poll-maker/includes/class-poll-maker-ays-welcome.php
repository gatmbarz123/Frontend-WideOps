<?php

class Poll_Maker_Ays_Welcome {

    /**
     * Hidden welcome page slug.
     *
     * @since 4.6.4
     */
    const SLUG = 'poll-maker-getting-started';

    /**
     * Primary class constructor.
     *
     * @since 4.6.4
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'hooks' ] );
    }

    public function hooks() {
		add_action( 'admin_menu', [ $this, 'register' ] );
		add_action( 'admin_head', [ $this, 'hide_menu' ] );
		add_action( 'admin_init', [ $this, 'redirect' ], 9999 );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

	/**
	 * Register the pages to be used for the Welcome screen (and tabs).
	 *
	 * These pages will be removed from the Dashboard menu, so they will
	 * not actually show. Sneaky, sneaky.
	 *
	 * @since 1.0.0
	 */
	public function register() {

        add_dashboard_page(
			esc_html__( 'Welcome to Poll Maker', "poll-maker" ),
			esc_html__( 'Welcome to Poll Maker', "poll-maker" ),
			'manage_options',
			self::SLUG,
			[ $this, 'output' ]
		);
	}

    /**
     * Removed the dashboard pages from the admin menu.
     *
     * This means the pages are still available to us, but hidden.
     *
     * @since 4.6.4
     */
    public function hide_menu() {

        remove_submenu_page( 'index.php', self::SLUG );
    }

    /**
     * Welcome screen redirect.
     *
     * This function checks if a new install or update has just occurred. If so,
     * then we redirect the user to the appropriate page.
     *
     * @since 4.6.4
     */
    public function redirect() {

        $current_page = isset( $_GET['page'] ) ? $_GET['page'] : '';

        // Check if we are already on the welcome page.
        if ( $current_page === self::SLUG ) {
            return;
        }

        $first_activation = get_option('ays_poll_maker_first_time_activation_page', false);

        if (isset($_GET['page']) && strpos($_GET['page'], POLL_MAKER_AYS_NAME) !== false && $first_activation) {
            wp_safe_redirect( admin_url( 'index.php?page=' . self::SLUG ) );
            exit;
        }
    }

    /**
     * Enqueue custom CSS styles for the welcome page.
     *
     * @since 4.6.4
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'poll-maker-ays-welcome-css', 
            POLL_MAKER_AYS_ADMIN_URL . '/css/poll-maker-ays-welcome.css',
            array(), false, 'all');
    }

    /**
	 * Register the JavaScript for the welcome page.
	 *
	 * @since 4.6.4
	 */
    public function enqueue_scripts() {

        wp_enqueue_script( 'poll-maker-ays-welcome', POLL_MAKER_AYS_ADMIN_URL . '/js/poll-maker-ays-welcome.js', array('jquery'), false, true);
    }

    /**
     * Getting Started screen. Shows after first install.
     *
     * @since 1.0.0
     */
    public function output() {
        ?>
            <style>
                #wpcontent  {
                    padding-left: 0 !important;
                    position: relative;
                }
            </style>
            <div id="poll-maker-welcome">
        
                <div class="poll-maker-welcome-container">
        
                    <div class="poll-maker-welcome-intro">
        
                        <div class="poll-maker-welcome-logo">
                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/icons/poll-maker-logo.png" alt="<?php echo __( 'Poll Maker Logo', "poll-maker" ); ?>">
                        </div>

                        <div class="poll-maker-welcome-close">
                            <a href="<?php echo admin_url( 'admin.php?page=' . POLL_MAKER_AYS_NAME ) ?> ">
                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/icons/close.svg" alt="<?php echo __( 'Close', "poll-maker" ); ?>">
                            </a>
                        </div>
                        <div class="poll-maker-welcome-block">
                            <h1><?php echo __( 'Welcome to Poll Maker', "poll-maker" ); ?></h1>
                            <h6><?php echo __( 'Thank you for choosing Poll Maker - the best poll and survey plugin for WordPress.', "poll-maker" ); ?></h6>
                        </div>
        
                        <a href="#" class="play-video" title="<?php esc_attr_e( 'Watch how to create your first poll', "poll-maker" ); ?>">
                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/ays-poll-welcome-video.png" alt="<?php echo __( 'Watch how to create your first poll', "poll-maker" ); ?>" class="poll-maker-welcome-video-thumbnail">
                        </a>
        
                        <div class="poll-maker-welcome-block">
        
                            <div class="poll-maker-welcome-button-wrap poll-maker-clear">
                                <div class="poll-maker-welcome-left">
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . POLL_MAKER_AYS_NAME . "&action=add") ); ?>" class="poll-maker-btn poll-maker-btn-block poll-maker-btn-lg poll-maker-btn-orange">
                                        <?php echo __( 'Create Your First Poll', "poll-maker" ); ?>
                                    </a>
                                </div>
                                <div class="poll-maker-welcome-right">
                                    <a href="<?php echo 'https://ays-pro.com/wordpress-poll-maker-user-manual'; ?>"
                                        class="poll-maker-btn poll-maker-btn-block poll-maker-btn-lg poll-maker-btn-grey" target="_blank" rel="noopener noreferrer">
                                        <?php echo __( 'Documentation', "poll-maker" ); ?>
                                    </a>
                                </div>
                            </div>
        
                        </div>
        
                    </div>
                </div>
            </div>
        <?php
        update_option('ays_poll_maker_first_time_activation_page', false);
    }
}
new Poll_Maker_Ays_Welcome();