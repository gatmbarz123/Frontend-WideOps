<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Poll_Maker_Ays
 * @subpackage Poll_Maker_Ays/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Poll_Maker_Ays
 * @subpackage Poll_Maker_Ays/admin
 * @author     Poll Maker Team <info@ays-pro.com>
 */
class Poll_Maker_Ays_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $polls_obj;
	private $cats_obj;
	private $results_obj;
	private $each_results_obj;
    private $settings_obj;
	private $answer_results_obj;
	private $capability;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
		$per_page_array = array(
            'polls_per_page',
            'poll_cats_per_page',
            'poll_results_per_page',
        );
        foreach($per_page_array as $option_name){
            add_filter('set_screen_option_'.$option_name, array(__CLASS__, 'set_screen'), 10, 3);
        }
	}

	/**
	 * Register the styles for the admin menu area.
	 *
	 * @since    1.5.5
	 */
	public function admin_menu_styles() {
		echo "
        <style>
            #adminmenu a.toplevel_page_poll-maker-ays div.wp-menu-image img {
                width: 28px;
                padding-top: 2px;
            }

            #adminmenu li.toplevel_page_poll-maker-ays ul.wp-submenu.wp-submenu-wrap li:last-child a {
                color: #68A615;
                font-weight: bold;
            }

            .apm-badge {
                position: relative;
                top: -1px;
                right: -3px;
            }

            .apm-badge.badge-danger {
                color: #fff;
                background-color: #ca4a1f;
            }

            .apm-badge.badge {
                display: inline-block;
                vertical-align: top;
                margin: 1px 0 0 2px;
                padding: 0 5px;
                min-width: 7px;
                height: 17px;
                border-radius: 11px;
                font-size: 9px;
                line-height: 17px;
                text-align: center;
                z-index: 26;
            }

            .wp-first-item .apm-badge {
                display: none;
            }

            .apm-badge.badge.apm-no-results {
                display: none;
            }
        </style>
		";
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {
		wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . '-sweetalert-css', plugin_dir_url(__FILE__) .  'css/poll-maker-sweetalert2.min.css', array(), $this->version, 'all');
		if (false === strpos($hook_suffix, $this->plugin_name)) {
			return;
		}
		// wp_enqueue_style('wp-color-picker');
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Poll_Maker_Ays_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Poll_Maker_Ays_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		// You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
        wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui' );

		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('ays_poll_animate.css', plugin_dir_url(__FILE__) . 'css/animate.min.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name . '-font-awesome', plugin_dir_url(__FILE__) . 'css/poll-maker-font-awesome-all.css', array(), $this->version, 'all');
		wp_enqueue_style('ays_poll_bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name.'-jquery-datetimepicker', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');
		wp_enqueue_style('ays-poll-select2', plugin_dir_url(__FILE__) . 'css/select2.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/poll-maker-ays-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name.'popup-layer', plugin_dir_url(__FILE__) . 'css/poll-maker-ays-admin-popup-layer.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name . '-dropdown', plugin_dir_url(__FILE__) .  '/css/dropdown.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-transition', plugin_dir_url(__FILE__) .  '/css/transition.min.css', array(), $this->version, 'all');


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {
		wp_enqueue_script( $this->plugin_name . '-dropdown-min', plugin_dir_url(__FILE__) . '/js/dropdown.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-transition-min', plugin_dir_url(__FILE__) . '/js/transition.min.js', array('jquery'), $this->version, true);
		global $wp_version;
		if (false !== strpos($hook_suffix, "plugins.php")){
			wp_enqueue_script('sweetalert-js-poll', plugin_dir_url(__FILE__) . 'js/sweetalert2.all.min.js', array('jquery'), $this->version, true);

			wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, true);
			wp_localize_script($this->plugin_name . '-admin', 'apm_admin_ajax_obj', array('ajaxUrl' => admin_url('admin-ajax.php')));
		}

		$version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.5';
        $versionCompare = $this->versionCompare($version1, $operator, $version2);
        if ($versionCompare) {	
            wp_enqueue_script( $this->plugin_name.'-wp-load-scripts', plugin_dir_url(__FILE__) . 'js/ays-wp-load-scripts.js', array(), $this->version, true);
        }	
		
		if (false === strpos($hook_suffix, $this->plugin_name)) {
			return;
		}

		$poll_banner_date = $this->ays_poll_update_banner_time();

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Poll_Maker_Ays_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Poll_Maker_Ays_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name.'-wp-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.min.js', array('wp-color-picker'), $this->version, true);
		wp_enqueue_script('ays_poll_popper', plugin_dir_url(__FILE__) . 'js/popper.min.js', array('jquery'), $this->version, true);
		wp_enqueue_script('ays_poll_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, true);
		wp_enqueue_script('ays_poll_select2', plugin_dir_url(__FILE__) . 'js/select2.min.js', array('jquery'), $this->version, true);
		wp_enqueue_script('sweetalert-js-poll', plugin_dir_url(__FILE__) . 'js/sweetalert2.all.min.js', array('jquery'), $this->version, true);
		wp_enqueue_script( $this->plugin_name."-jquery.datetimepicker.js", plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script('ays-poll-admin-js', plugin_dir_url(__FILE__) . 'js/poll-maker-ays-admin.js', array('jquery', 'wp-color-picker'),  $this->version, true);		
		wp_enqueue_script( $this->plugin_name . '-charts-google', plugin_dir_url(__FILE__) . 'js/google-chart.js', array('jquery'), $this->version, false);
		wp_localize_script('ays-poll-admin-js', 'pollLangObj', array(
			'pollBannerDate' => $poll_banner_date,
			'errorMsg' => __('Error', "poll-maker"),
            'somethingWentWrong' => __('Maybe something went wrong.', "poll-maker"),
            'add' => __('Add', "poll-maker"),
            'answersMinCount' => __('Sorry minimum count of answers should be 2', "poll-maker"),
            'copied' => __('Copied!', "poll-maker"),
            'clickForCopy' => __('Click for copy.', "poll-maker"),
			'areYouSure' => __('Are you sure you want to redirect to another poll? Note that the changes made in this poll will not be saved.', "poll-maker"),
			'deleteAnswer' => __('Are you sure you want to delete this answer?', "poll-maker"),
			'youCanUuseThisShortcode' => __('Your Poll is Created!<br>Copy the generated shortcode and paste it into any post or page to display Poll', "poll-maker"),
            'greateJob' => __('Great job', "poll-maker"),
            'formMoreDetailed' => __('For more detailed configuration visit', "poll-maker"),
            'done' => __('Done', "poll-maker"),
            'thumbsUpGreat' => __('Thumbs up, Done', "poll-maker"),
        ) );

		wp_localize_script('ays-poll-admin-js', 'poll', array(
            'ajax' => admin_url('admin-ajax.php'),
            'pleaseEnterMore' => __('Please select more', "poll-maker"),
            'urlImg' => (POLL_MAKER_AYS_ADMIN_URL . '/images/'),
        ));

		wp_enqueue_script( $this->plugin_name . '-quick-start-js', plugin_dir_url(__FILE__) . 'js/poll-maker-poll-quick-start.js', array('jquery'), $this->version, true);
		wp_enqueue_script( $this->plugin_name . '-admin-ajax', plugin_dir_url(__FILE__) . 'js/poll-maker-ays-ajax-admin.js', array('jquery'), $this->version, true);
		wp_localize_script($this->plugin_name . '-admin-ajax', 'apm_ajax_obj', array('ajaxUrl' => admin_url('admin-ajax.php')));

		$color_picker_strings = array(
			'clear' => __('Clear', "poll-maker"),
			'clearAriaLabel' => __('Clear color', "poll-maker"),
			'defaultString' => __('Default', "poll-maker"),
			'defaultAriaLabel' => __('Select default color', "poll-maker"),
			'pick' => __('Select Color', "poll-maker"),
			'defaultLabel' => __('Color value', "poll-maker"),
		);
		wp_localize_script( $this->plugin_name.'-wp-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );
	}

	/**
	 * De-register JavaScript files for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function disable_scripts($hook_suffix) {
        if (false !== strpos($hook_suffix, $this->plugin_name)) {
            if (is_plugin_active('ai-engine/ai-engine.php')) {
                wp_deregister_script('mwai');
                wp_deregister_script('mwai-vendor');
                wp_dequeue_script('mwai');
                wp_dequeue_script('mwai-vendor');
            }
        }
	}

	public function codemirror_enqueue_scripts($hook) {
        if(strpos($hook, $this->plugin_name) !== false){
            if(function_exists('wp_enqueue_code_editor')){
                $cm_settings['codeEditor'] = wp_enqueue_code_editor(array(
                    'type' => 'text/css',
                    'codemirror' => array(
                        'inputStyle' => 'contenteditable',
                        'theme' => 'cobalt',
                    )
                ));

                wp_enqueue_script('wp-theme-plugin-editor');
                wp_localize_script('wp-theme-plugin-editor', 'cm_settings', $cm_settings);

                wp_enqueue_style('wp-codemirror');
            }
        }
	}
	
	public function versionCompare($version1, $operator, $version2) {
   
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
       
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
       
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
       
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }

	public function add_plugin_admin_menu() {

		/*
		 * Check unread results
		 *
		 */
		global $wpdb;
		$sql            = "SELECT COUNT(unread) FROM {$wpdb->prefix}ayspoll_reports WHERE unread=1";
		$unread_results = $wpdb->get_var($sql);
		$show           = $unread_results > 0 ? '' : "apm-no-results";
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */

		$menu_item = ($unread_results == 0) ? 'Poll Maker' : 'Poll Maker' . '<span class="apm-badge badge badge-danger '.$show.'">' . $unread_results . '</span>';

		$this->capability = $this->poll_maker_capabilities();
        $capability = $this->poll_maker_capabilities();
		$hook_poll = add_menu_page(
			'Poll Maker', 
			$menu_item,
			$capability,
			$this->plugin_name, 
			array($this,'display_plugin_polls_page'),
			POLL_MAKER_AYS_ADMIN_URL . '/images/icons/icon-poll-maker-128x128.svg',
			'6.33'
		);

		add_action("load-$hook_poll", array($this, 'screen_option_polls'));
		add_action("load-$hook_poll", array($this, 'add_tabs'));

		$hook_results_each = add_submenu_page(
			'all_results_slug',
			__('Results per poll', "poll-maker"),
			__('Results per poll', "poll-maker"),
			$capability,
			$this->plugin_name . '-results-each',
			array($this, 'display_plugin_results_each_page')
		);
		add_action("load-$hook_results_each", array($this, 'screen_option_each_results'));

	}


	public function add_plugin_polls_submenu() {
		$capability = $this->poll_maker_capabilities();

		$hook_polls = add_submenu_page(
			$this->plugin_name,
			__('All Polls', "poll-maker"),
			__('All Polls', "poll-maker"),
			$capability,
			$this->plugin_name,
			array($this, 'display_plugin_polls_page')
		);
		add_action("load-$hook_polls", array($this, 'screen_option_polls'));
		add_action("load-$hook_polls", array($this, 'add_tabs'));
	}

	public function add_plugin_add_new_poll_submenu() {
		$capability = $this->poll_maker_capabilities();

		$hook_polls = add_submenu_page(
			$this->plugin_name,
			__('Add new', "poll-maker"),
			__('Add new', "poll-maker"),
			$capability,
			$this->plugin_name . '-add-new',
			array($this, 'display_plugin_add_new_poll_page')
		);
	}

	public function add_plugin_categories_submenu() {
		$capability = $this->poll_maker_capabilities();

		$hook_cats = add_submenu_page(
			$this->plugin_name,
			__('Categories', "poll-maker"),
			__('Categories', "poll-maker"),
			$capability,
			$this->plugin_name . '-cats',
			array($this, 'display_plugin_cats_page')
		);
		add_action("load-$hook_cats", array($this, 'screen_option_cats'));
		add_action("load-$hook_cats", array($this, 'add_tabs'));
	}

	public function add_plugin_results_submenu() {
		/*
		 * Check unread results
		 *
		 */
		global $wpdb;
		$sql            = "SELECT COUNT(unread) FROM {$wpdb->prefix}ayspoll_reports WHERE unread=1";
		$unread_results = $wpdb->get_var($sql);
		$show           = $unread_results > 0 ? '' : "apm-no-results";

		$capability = $this->poll_maker_capabilities();

		$hook_results = add_submenu_page(
			$this->plugin_name,
			__('Results', "poll-maker"),
			__('Results', "poll-maker") . " <span class=\"apm-badge badge badge-danger $show\">$unread_results</span>",
			$capability,
			$this->plugin_name . '-results',
			array($this, 'display_plugin_results_page')
		);
		add_action("load-$hook_results", array($this, 'screen_option_results'));
		add_action("load-$hook_results", array($this, 'add_tabs'));

		$hook_all_results = add_submenu_page(
            'all_results_slug',
            __('Results', "poll-maker"),
            $capability,
            $this->capability,
            $this->plugin_name . '-all-results',
            array($this, 'display_plugin_all_results_page')
		);
		
		add_action("load-$hook_all_results", array($this, 'screen_option_all_poll_results'));

		add_filter('parent_file', array($this,'poll_maker_select_submenu'));
	}

	public function add_plugin_formfields_submenu() {

		$hook_formfields = add_submenu_page(
			$this->plugin_name,
			__('Custom Fields', "poll-maker"),
			__('Custom Fields', "poll-maker"),
			'manage_options',
			$this->plugin_name . '-formfields',
			array($this, 'display_plugin_formfields_page')
		);
		add_action("load-$hook_formfields", array($this, 'add_tabs'));
	}

	public function add_plugin_general_settings_submenu() {
		$hook_settings = add_submenu_page($this->plugin_name,
			__('General Settings', "poll-maker"),
			__('General Settings', "poll-maker"),
			'manage_options',
			$this->plugin_name . '-settings',
			array($this, 'display_plugin_settings_page')
		);
		add_action("load-$hook_settings", array($this, 'screen_option_settings'));
		add_action("load-$hook_settings", array($this, 'add_tabs'));
	}

	public function add_plugin_how_to_use_submenu() {
		$hook_pro_features = add_submenu_page(
			$this->plugin_name,
			__('How to use', "poll-maker"),
			__('How to use', "poll-maker"),
			'manage_options',
			$this->plugin_name . '-how-to-use',
			array($this, 'display_plugin_how_to_use_page')
		);
		add_action("load-$hook_pro_features", array($this, 'add_tabs'));
	}

	public function add_plugin_pro_features_submenu() {
		$hook_pro_features = add_submenu_page(
			$this->plugin_name,
			__('PRO features', "poll-maker"),
			__('PRO features', "poll-maker"),
			'manage_options',
			$this->plugin_name . '-pro-features',
			array($this, 'display_plugin_pro_features_page')
		);
		add_action("load-$hook_pro_features", array($this, 'add_tabs'));
	}

	public function add_plugin_featured_plugins_submenu(){
        $hook_pro_features = add_submenu_page( $this->plugin_name,
            __('Our products', "poll-maker"),
            __('Our products', "poll-maker"),
            'manage_options',
            $this->plugin_name . '-featured-plugins',
            array($this, 'display_plugin_featured_plugins_page') 
        );
		add_action("load-$hook_pro_features", array($this, 'add_tabs'));
    }

	public function	display_poll_creation_popup() {
		$is_challange_enabled = get_option('ays_poll_maker_poll_creation_challange', false);

		if (!$is_challange_enabled) {
			return;
		}

		if (isset($_GET['page']) && strpos($_GET['page'], POLL_MAKER_AYS_NAME) !== false) {
			?>
			<div class="poll-maker-challenge">
				<div class="poll-maker-challenge-list-block">
					<i class="fa fa-times-circle list-block-button poll-maker-challenge-cancel" aria-hidden="true" title="Cancel challenge"></i>
					<ul class="poll-maker-challenge-list">
						<li class="poll-maker-challenge-step-item"><?php echo __('Add a New Poll', "poll-maker"); ?></li>
						<li class="poll-maker-challenge-step-item"><?php echo __('Name Your Poll', "poll-maker"); ?></li>
						<li class="poll-maker-challenge-step-item"><?php echo __('Add Options', "poll-maker"); ?></li>
						<li class="poll-maker-challenge-step-item"><?php echo __('Save the Poll', "poll-maker"); ?></li>
						<li class="poll-maker-challenge-step-item"><?php echo __('Copy the Shortcode', "poll-maker"); ?></li>
						<li class="poll-maker-challenge-step-item"><?php echo __('Embed in a Page', "poll-maker"); ?></li>
					</ul>
				</div>
				<div class="poll-maker-challenge-block-timer">
					<img src="<?php echo POLL_MAKER_AYS_ADMIN_URL ?>/images/icons/poll-maker-logo.png" alt="Poll Maker logo">
					<h3>Poll Maker</h3>
				</div>
			</div>
			<?php
		}
	}

	public function delete_challenge_box() {
		delete_option('ays_poll_maker_poll_creation_challange');
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links( $links ) {
		/*
		 *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 */
		$settings_link = array(
			'<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', "poll-maker") . '</a>',
			'<a href="https://poll-plugin.com/wordpress-poll-plugin-free-demo/" target="_blank">' . __('Demo', "poll-maker") . '</a>',			
			'<a href="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=plugins-buy-now-button" class="ays-poll-upgrade-plugin-btn" style="font-weight:bold;color:#01A32A;" target="_blank">' . __('Upgrade 50% Sale', "poll-maker") . '</a>',
		);

		return array_merge($settings_link, $links);

	}

	public function add_poll_row_meta( $links, $file ) {
        if ( POLL_MAKER_AYS_BASENAME == $file ) {
            $row_meta = array(
                'ays-poll-support'			=> '<a href="' . esc_url( 'https://wordpress.org/support/plugin/poll-maker/' ) . '" target="_blank">' . esc_html__( 'Free Support', "poll-maker" ) . '</a>',
                'ays-poll-documentation'	=> '<a href="' . esc_url( 'https://ays-pro.com/wordpress-poll-maker-user-manual' ) . '" target="_blank">' . esc_html__( 'Documentation', "poll-maker" ) . '</a>',
                'ays-poll-rate-us'			=> '<a href="' . esc_url( 'https://wordpress.org/support/plugin/poll-maker/reviews/?rate=5#new-post' ) . '" target="_blank">' . esc_html__( 'Rate us', "poll-maker" ) . '</a>',
                'ays-poll-video-tutorial'	=> '<a href="' . esc_url( 'https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA' ) . '" target="_blank">' . esc_html__( 'Video tutorial', "poll-maker" ) . '</a>',
                );

            return array_merge( $links, $row_meta );
        }
        return $links;
    }

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_polls_page() {
		$action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

		switch ( $action ) {
			case 'add':
				include_once 'partials/poll-maker-ays-polls-actions.php';
				break;
			case 'edit':
				include_once 'partials/poll-maker-ays-polls-actions.php';
				break;
			default:
				include_once 'partials/poll-maker-ays-admin-display.php';
		}
	}

	public function display_plugin_add_new_poll_page() {
		$add_new_poll_url = admin_url('admin.php?page=' . $this->plugin_name . '&action=add');
		wp_redirect($add_new_poll_url);
	}

	public function display_plugin_cats_page() {
		$action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

		switch ( $action ) {
			case 'add':
				include_once 'partials/categories/actions/poll-maker-ays-categories-actions.php';
				break;
			case 'edit':
				include_once 'partials/categories/actions/poll-maker-ays-categories-actions.php';
				break;
			default:
				include_once 'partials/categories/poll-maker-ays-categories-display.php';
		}
	}

	public function display_plugin_results_page() {
		include_once 'partials/results/poll-maker-ays-results-display.php';
	}

	public function display_plugin_results_each_page() {
		include_once 'partials/results/poll-maker-ays-each-results-display.php';
	}

	public function display_plugin_formfields_page() {
		include_once 'partials/features/poll-maker-formfields_page-display.php';
	}

	public function display_plugin_pro_features_page() {
		include_once 'partials/features/poll-maker-pro-features-display.php';
	}

	public function display_plugin_how_to_use_page() {
		include_once 'partials/features/poll-maker-how-to-use-display.php';
	}

	public function display_plugin_featured_plugins_page(){
        include_once('partials/features/poll-maker-featured-display.php');
    }

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function screen_option_polls() {
		$option = 'per_page';
		$args   = array(
			'label'   => __('Polls', "poll-maker"),
			'default' => 20,
			'option'  => 'polls_per_page',
		);

		add_screen_option($option, $args);
		$this->polls_obj = new Polls_List_Table($this->plugin_name);
        $this->settings_obj = new Poll_Maker_Settings_Actions($this->plugin_name);

    }

	public function screen_option_cats() {
		$option = 'per_page';
		$args   = array(
			'label'   => __('Categories', "poll-maker"),
			'default' => 5,
			'option'  => 'poll_cats_per_page',
		);

		add_screen_option($option, $args);
		$this->cats_obj = new Pma_Categories_List_Table($this->plugin_name);
		$this->settings_obj = new Poll_Maker_Settings_Actions($this->plugin_name);
	}

	public function screen_option_results() {
		$option = 'per_page';
		$args   = array(
			'label'   => __('Results', "poll-maker"),
			'default' => 50,
			'option'  => 'poll_results_per_page',
		);

		add_screen_option($option, $args);
		$this->results_obj = new Pma_Results_List_Table($this->plugin_name);
		// $this->answer_results_obj = new Poll_Answer_Results($this->plugin_name);
	}

	public function screen_option_each_results() {
		$option = 'per_page';
		$args   = array(
			'label'   => __('Results per poll', "poll-maker"),
			'default' => 50,
			'option'  => 'poll_each_results_per_page',
		);

		add_screen_option($option, $args);
		$this->each_results_obj = new Pma_Each_Results_List_Table($this->plugin_name);
	}

	public function register_poll_ays_widget() {
		global $wpdb;
		$poll_table = esc_sql($wpdb->prefix . "ayspoll_polls");
		$sql = "SELECT COUNT(*) FROM ".$poll_table;

		$c = $wpdb->get_var($sql);
		if ($c == 0) {
			return;
		} else {
			register_widget('Poll_Maker_Widget');
		}
	}

	public function poll_maker_el_widgets_registered() {
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
		// We check if the Elementor plugin has been installed / activated.
        if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
            // get our own widgets up and running:
            // copied from widgets-manager.php
            if ( class_exists( 'Elementor\Plugin' ) ) {
                if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
                    $elementor = Elementor\Plugin::instance();
                    if ( isset( $elementor->widgets_manager ) ) {
						if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
							if ( method_exists( $elementor->widgets_manager, 'register' ) ) {
								$widget_file   = 'plugins/elementor/poll_maker_elementor.php';
								$template_file = locate_template( $widget_file );
								if ( !$template_file || !is_readable( $template_file ) ) {
									$template_file = POLL_MAKER_AYS_DIR.'pb_templates/poll_maker_elementor.php';
								}
								if ( $template_file && is_readable( $template_file ) ) {
									require_once $template_file;
									Elementor\Plugin::instance()->widgets_manager->register( new Elementor\Widget_Poll_Maker_Elementor() );
								}
							}
						} else {
							if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
								$widget_file = 'plugins/elementor/poll_maker_elementor.php';
								$template_file = locate_template( $widget_file );
								if ( !$template_file || !is_readable( $template_file ) ) {
									$template_file = POLL_MAKER_AYS_DIR.'pb_templates/poll_maker_elementor.php';
								}
								if ( $template_file && is_readable( $template_file ) ) {
									require_once $template_file;
									Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Poll_Maker_Elementor() );
								}
							}
						}
                    }
                }
            }
        }
    }

	public function apm_deactivate_plugin_option() {
		$request_value  = esc_sql( sanitize_text_field( $_REQUEST['upgrade_plugin'] ) );
		$upgrade_option = get_option('ays_poll_maker_upgrade_plugin');
		if ($upgrade_option === '') {
			add_option('ays_poll_maker_upgrade_plugin', $request_value);
		} else {
			update_option('ays_poll_maker_upgrade_plugin', $request_value);
		}
		ob_end_clean();
		$ob_get_clean = ob_get_clean();
		echo json_encode(array('option' => get_option('ays_poll_maker_upgrade_plugin')));
		wp_die();
	}

	public function apm_show_results() {
		global $wpdb;
		$results_table = $wpdb->prefix . "ayspoll_reports";
		// $polls_obj     = new Polls_List_Table($this->plugin_name);
		if (isset($_POST['action']) && $_POST['action'] == 'apm_show_results') {

			$id         = isset($_POST['result']) ? absint($_POST['result']) : 0;
			$is_details = isset($_POST['is_details']) && absint($_POST['is_details']) > 0 ? true : false;
			$row        = '';
			$wpdb->update($results_table,
				array('unread' => 0),
				array('id' => $id),
				array('%d'),
				array('%d')
			);
			if ($id > 0 && $is_details) {
				$result = $wpdb->get_row("SELECT * FROM $results_table WHERE id=$id", "ARRAY_A");
				$multivote_res = false;
				$result['multi_answer_id'] = json_decode($result['multi_answer_ids']);
				if (isset($result['multi_answer_id']) && count($result['multi_answer_id']) > 1) {
					$multivote_res = true;
				}
				$multivote_answers = array();
				if ($multivote_res) {
					foreach ($result['multi_answer_id'] as $m_key => $m_val) {
						$multi_answer    = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_answers WHERE id=".$m_val, "ARRAY_A");
						$multivote_answers[] = $multi_answer['answer'];
					}
					$answ_poll_id = $multi_answer['poll_id'];
				} else {
					$answer     = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_answers WHERE id={$result['answer_id']}", "ARRAY_A");
					$multivote_answers[] = $answer['answer'];
					$answ_poll_id = $answer['poll_id'];
				}

				$poll       = $this->get_poll_by_id($answ_poll_id);
				$user_ip    = $result['user_ip'];
				$info = ($result['other_info'] == '' || $result['other_info'] === null || $result['other_info'] === 0) ? array() : json_decode($result['other_info'], true);

				$time       = $result['vote_date'];
				$user_email = $result['user_email'];
				$country = '';
				$region = '';
				$city = '';
            	$json    = isset($user_ip) && $user_ip != '' ? json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json")) : null;

				if ($json !== null) {
					$country = isset($json->country) && $json->country != '' ? $json->country : '';
					$region = isset($json->region) && $json->region != '' ? $json->region : '';
					$city = isset($json->city) && $json->city != '' ? $json->city : '';
				}
				$from    = "$city, $region, $country, $user_ip";
				$row     = '<tr><td colspan="4"><h1>' . __('Poll Information', "poll-maker") . "</h1></td></tr>
                    <tr class='ays_result_element'>
                        <td>". __('Poll Title', "poll-maker")."</td>
                        <td>{$poll['title']}</td>
                        <td></td>
                        <td></td>
                    </tr>";
				$row     .= "<tr class='ays_result_element'>
                        <td>". __('Poll Type', "poll-maker")."</td>
                        <td>" . ucfirst($poll['type']) . "</td>
                        <td></td>
                        <td></td>
                    </tr>";
				switch ( $poll['type'] ) {
					case 'choosing':
						$row .= "<tr class='ays_result_element'>
                        <td>". __('Answer', "poll-maker")."</td>
                        <td>" . (in_array($poll['answers'][0]['answer'], $multivote_answers) ? "<b><em>" . stripslashes($poll['answers'][0]['answer']) . "</em></b>" : stripslashes($poll['answers'][0]['answer'])) . "</td>
                        <td></td>
                        <td></td>
                    </tr>";
						foreach ( $poll['answers'] as $index => $ans ) {
							if ($index == 0) {
								continue;
							}
							$row .= "<tr class='ays_result_element'>
                            <td></td>
                            <td>" . (in_array($ans['answer'], $multivote_answers) ? "<b><em>" . stripslashes($ans['answer']) . "</em></b>" : stripslashes($ans['answer'])) . "</td>
                            <td></td>
                            <td></td>
                        </tr>";
						}
						break;
					case 'text':
						$row .= "<tr class='ays_result_element'>
							<td>". __('Answer', "poll-maker")."</td>
							<td><b><em>" . $answer['answer'] . "</em></b></td>
							<td></td>
							<td></td>
						</tr>";
						break;
					case 'rating':
						$row .= "<tr class='ays_result_element'>
                            <td>". __('Answer', "poll-maker")."</td>
                            <td><div class='apm-rating-res'>";
						if ($poll['view_type'] == 'star') {
							foreach ( $poll['answers'] as $ans ) {
								$row .= "<i class='" . ($ans['answer'] <= $answer['answer'] ? "ays_poll_fas" : "ays_poll_far") . " ays_poll_fa-star'></i>";
							}
						} elseif ('emoji') {
							$emoji = array(
								"ays_poll_fa-dizzy",
								"ays_poll_fa-smile",
								"ays_poll_fa-meh",
								"ays_poll_fa-frown",
								"ays_poll_fa-tired",
							);
							foreach ( $poll['answers'] as $i => $ans ) {
								$index = (count($poll['answers']) / 2 - $i + 1.5);
								$row   .= "<i class='" . ($ans['answer'] == $answer['answer'] ? "ays_poll_fas " : "ays_poll_far ") . $emoji[$index] . "'></i>";
							}
						}
						$row .= "</div></td>
                            <td></td>
                            <td></td>
                        </tr>";
						break;
					case 'voting':
						$row   .= "<tr class='ays_result_element'>
                            <td>". __('Answer', "poll-maker")."</td>
                            <td><div class='apm-rating-res'>";
						$icons = array(
							'hand'  => array(
								"ays_poll_fa-thumbs-up",
								"ays_poll_fa-thumbs-down",
							),
							'emoji' => array(
								"ays_poll_fa-smile",
								"ays_poll_fa-frown",
							),
						);
						$view  = $poll['view_type'];
						$row   .= "<i class='" . (1 == $answer['answer'] ? "ays_poll_fas " : "ays_poll_far ") . $icons[$view][0] . "'></i>
                        <i class='" . (-1 == $answer['answer'] ? "ays_poll_fas " : "ays_poll_far ") . $icons[$view][1] . "'></i>";
						$row   .= "</div></td>
                            <td></td>
                            <td></td>
                        </tr>";
						break;
				}
				$row .= "<tr class='ays_result_element'>
                        <td>". __('Answer Datetime', "poll-maker")."</td>
                        <td>" . (date('H:i:s d.m.Y', strtotime($time))) . "</td>
                        <td></td>
                        <td></td>
                    </tr>";
				$row .= "<tr class='hr-line'><td colspan='4'><hr></td></tr>";
				$row .= '<tr><td colspan="4"><h1>' . __('User Information', "poll-maker") . "</h1></td></tr>";
					if ($json !== null) {
                    	$row .= "<tr class='ays_result_element'>
		                            <td>". __('User IP', "poll-maker")."</td>
		                            <td>$from</td>
		                            <td></td>
		                            <td></td>
		                        </tr>";
                    }
                if(!empty($user_email)){
                	$row .= "<tr class='ays_result_element'>
		                        <td>". __('User E-mail', "poll-maker")."</td>
		                        <td>$user_email</td>
		                        <td></td>
		                        <td></td>
		                	 </tr>";
            	}
				foreach ( $info as $key => $value ) {
					if ( ($key == 'not_show_user_id') || ($key == 'email' && !empty($user_email)) ) {
						continue;
					}

					$row .= "<tr class='ays_result_element'>
                            <td>". $key ."</td>
                            <td>". $value ."</td>
                            <td></td>
                            <td></td>
                        </tr>";
				}
			}
			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode([
				"status" => true,
				"rows"   => $row,
			]);
			wp_die();
		}
	}

	public function get_poll_by_id( $id, $decode = true ) {
		global $wpdb;

		$sql  = "SELECT * FROM {$wpdb->prefix}ayspoll_polls WHERE id=" . absint(intval($id));
		$poll = $wpdb->get_row($sql, 'ARRAY_A');
		if (empty($poll)) {
			return array();
		}
		$sql             = "SELECT * FROM {$wpdb->prefix}ayspoll_answers WHERE poll_id=" . absint(intval($id)) . " ORDER BY id ASC";
		$poll['answers'] = $wpdb->get_results($sql, 'ARRAY_A');

		if ($decode) {
			$json               = $poll['styles'];
			$poll['styles']     = json_decode($json, true);
			$poll['categories'] = trim($poll['categories'], ',');
			$cats               = explode(',', $poll['categories']);
			$poll['categories'] = !empty($cats) ? $cats : [];
			$all_fields         = $this->get_all_formfields();
			if (isset($poll['styles']['fields'])) {
				$poll['fields'] = array();
				$fields         = explode(',', $poll['styles']['fields']);
				foreach ( $fields as $field ) {
					$index = array_search($field, array_column($all_fields, 'slug'));
					if ($index !== false) {
						$poll['fields'][] = $all_fields[$index];
					}
				}
			}
			if (isset($poll['styles']['required_fields'])) {
				$poll['required_fields'] = array();
				$fields                  = explode(',', $poll['styles']['required_fields']);
				foreach ( $fields as $field ) {
					$index = array_search($field, array_column($all_fields, 'slug'));
					if ($index !== false) {
						$poll['required_fields'][] = $all_fields[$index];
					}
				}
			}
		}

		return $poll;
	}

	public function get_all_formfields() {
		global $wpdb;
		$all = array(
			array(
				"id"        => 0,
				"name"      => "Name",
				"type"      => "text",
				"slug"      => "apm-name",
				"published" => 1,
			),
			array(
				"id"        => 0,
				"name"      => "E-mail",
				"type"      => "email",
				"slug"      => "apm-email",
				"published" => 1,
			),
			array(
				"id"        => 0,
				"name"      => "Phone",
				"type"      => "tel",
				"slug"      => "apm_phone",
				"published" => 1,
			),
		);

		return $all;
	}

    public function screen_option_settings() {
		$this->polls_obj = new Polls_List_Table($this->plugin_name);
        $this->settings_obj = new Poll_Maker_Settings_Actions($this->plugin_name);
    }

    public function display_plugin_settings_page() {
        include_once('partials/settings/poll-maker-settings.php');
    }

    public function ays_get_mailchimp_lists( $username, $api_key ) {
        if (!empty($api_key) && strpos($api_key, '-') !== false) {
            $api_postfix = explode("-", $api_key)[1];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://" . $api_postfix . ".api.mailchimp.com/3.0/lists",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_USERPWD        => "$username:$api_key",
                CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
	   //   CURLOPT_POSTFIELDS => "undefined=",
                CURLOPT_HTTPHEADER     => array(
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error";
            } else {
                return json_decode($response, true);
            }
        }

        return array();
    }

	public function ays_poll_create_author() {

		// Check for permissions.
		if ( !Poll_Maker_Data::check_user_capability() ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'results' => array()
            ));
            wp_die();
        }

		$search = isset($_REQUEST['search']) && $_REQUEST['search'] != '' ? sanitize_text_field($_REQUEST['search']) : null;
        $checked = isset($_REQUEST['val']) && $_REQUEST['val'] != '' ? sanitize_text_field($_REQUEST['val']) : null;
        $args = array(
            'fields' => array('ID', 'display_name', 'user_email', 'user_login', 'user_nicename')
        );

        if ($search !== null) {
            $args['search'] = '*' . esc_attr($search) . '*';
            $args['search_columns'] = array('ID', 'user_login', 'user_nicename', 'user_email', 'display_name');
        }
        
        $user_query = new WP_User_Query($args);
        $users = $user_query->get_results();
        $response = array(
            'results' => array()
        );

        if(empty($args)){
            $reports_users = '';
        }

        foreach ($users as $key => $user) {
            if ($checked !== null) {
                if ($user->ID == $checked) {
                    continue;
                }else{
                    $response['results'][] = array(
                        'id' => $user->ID,
                        'text' => $user->display_name
                    );
                }
            }else{
                $response['results'][] = array(
                    'id' => $user->ID,
                    'text' => $user->display_name,
                );
            }
        }     

        ob_end_clean();
        echo json_encode($response);
        wp_die();
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

	public static function is_classic_editor_plugin_active() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
            return true;
        }

        return false;
    }

	public static function is_active_gutenberg() {
        // Gutenberg plugin is installed and activated.
        $gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );
        // Block editor since 5.0.
        $block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

        if ( ! $gutenberg && ! $block_editor ) {
            return false;
        }

        if ( self::is_classic_editor_plugin_active() ) {
            $editor_option       = get_option( 'classic-editor-replace' );
            $block_editor_active = array( 'no-replace', 'block' );

            return in_array( $editor_option, $block_editor_active, true );
        }

        return true;
    }

    public static function ays_restriction_string($type, $x, $length){
        $output = "";
        switch($type){
            case "char":                
                if(strlen($x)<=$length){
                    $output = $x;
                } else {
                    $output = substr($x,0,$length) . '...';
                }
                break;
            case "word":
                $res = explode(" ", $x);
                if(count($res)<=$length){
                    $output = implode(" ",$res);
                } else {
                    $res = array_slice($res,0,$length);
                    $output = implode(" ",$res) . '...';
                }
            break;
        }
        return $output;
    }

    public static function get_listtables_title_length( $listtable_name ) {
        global $wpdb;

        $settings_table = $wpdb->prefix . "ayspoll_settings";
        $sql = "SELECT meta_value FROM ".$settings_table." WHERE meta_key = 'options'";
        $result = $wpdb->get_var($sql);
        $options = ($result == "") ? array() : json_decode($result, true);

        $listtable_title_length = 5;
        if(! empty($options) ){
            switch ( $listtable_name ) {
                case 'polls':
                    $listtable_title_length = (isset($options['poll_title_length']) && intval($options['poll_title_length']) != 0) ? absint(intval($options['poll_title_length'])) : 5;
                    break;      
                case 'categories':
                    $listtable_title_length = (isset($options['poll_category_title_length']) && intval($options['poll_category_title_length']) != 0) ? absint(intval($options['poll_category_title_length'])) : 5;
                    break;      
                case 'results':
                    $listtable_title_length = (isset($options['poll_results_title_length']) && intval($options['poll_results_title_length']) != 0) ? absint(intval($options['poll_results_title_length'])) : 5;
                    break;      
                default:
                    $listtable_title_length = 5;
                    break;
            }
            return $listtable_title_length;
        }
        return $listtable_title_length;
    }

	public function poll_maker_select_submenu($file) {
        global $plugin_page;
        if ("poll-maker-ays-results-each" == $plugin_page) {
            $plugin_page = $this->plugin_name."-results";
        }

        return $file;
    }

    protected function poll_maker_capabilities(){
        global $wpdb;
		$sql    = "SELECT meta_value FROM {$wpdb->prefix}ayspoll_settings WHERE `meta_key` = 'user_roles'";
		$result = $wpdb->get_var($sql);
		
        $capability = 'manage_options';
        if($result !== null){
            $ays_user_roles = json_decode($result, true);
            if(is_user_logged_in()){
                $current_user = wp_get_current_user();
                $current_user_roles = $current_user->roles;
                $ishmar = 0;
                foreach($current_user_roles as $r){
                    if(in_array($r, $ays_user_roles)){
                        $ishmar++;
                    }
                }
                if($ishmar > 0){
                    $capability = "read";
                }
            }
        }
        return $capability;
	}

	public function get_next_or_prev_row_by_id( $id, $type = "next", $table = "ayspoll_polls" ) {
        global $wpdb;

        if ( is_null( $table ) || empty( $table ) ) {
            return null;
        }

        $ays_table = esc_sql( $wpdb->prefix . $table );

        $where = array();
        $where_condition = "";

        $id     = (isset( $id ) && $id != "" && absint($id) != 0) ? absint( sanitize_text_field( $id ) ) : null;
        $type   = (isset( $type ) && $type != "") ? sanitize_text_field( $type ) : "next";

        if ( is_null( $id ) || $id == 0 ) {
            return null;
        }

        switch ( $type ) {
			case 'prev':
                $where[] = ' `id` < ' . $id . ' ORDER BY `id` DESC ';
            break;
            case 'next':
            default:
                $where[] = ' `id` > ' . $id;
                break;
        }

        if( ! empty($where) ){
            $where_condition = " WHERE " . implode( " AND ", $where );
        }

        $sql = "SELECT `id` FROM {$ays_table} ". $where_condition ." LIMIT 1;";
        $results = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $results;

    }
	
	public function poll_maker_admin_footer($a){
        if(isset($_REQUEST['page'])){
            if(false !== strpos($_REQUEST['page'], $this->plugin_name)){
                ?>
				<div class="ays-poll-footer-support-box">
                    <span class="ays-poll-footer-link-row"><a href="https://wordpress.org/support/plugin/poll-maker/" target="_blank"><?php echo __( "Support", "poll-maker"); ?></a></span>
                    <span class="ays-poll-footer-slash-row">/</span>
                    <span class="ays-poll-footer-link-row"><a href="https://ays-pro.com/wordpress-poll-maker-user-manual" target="_blank"><?php echo __( "Docs", "poll-maker"); ?></a></span>
                    <span class="ays-poll-footer-slash-row">/</span>
                    <span class="ays-poll-footer-link-row"><a href="https://ays-demo.com/poll-maker-plugin-survey/" target="_blank"><?php echo __( "Suggest a Feature", "poll-maker"); ?></a></span>
                </div>
                <p style="font-size:13px;text-align:center;font-style:italic;">
                    <span style="margin-left:0px;margin-right:10px;" class="ays_heart_beat"><i class="ays_fa ays_poll_fa_heart_o animated"></i></span>
                    <span><?php echo __( "If you love our plugin, please do big favor and rate us on WordPress.org", "poll-maker"); ?></span> 
                    <a target="_blank" class="ays-rated-link" href='http://bit.ly/3l5I2iG'>
                    	<span class="ays-dashicons ays-dashicons-star-empty"></span>
                    	<span class="ays-dashicons ays-dashicons-star-empty"></span>
                    	<span class="ays-dashicons ays-dashicons-star-empty"></span>
                    	<span class="ays-dashicons ays-dashicons-star-empty"></span>
                    	<span class="ays-dashicons ays-dashicons-star-empty"></span>
                    </a>
                    <span class="ays_heart_beat"><i class="ays_fa ays_poll_fa_heart_o animated"></i></span>
                </p>
            <?php
            }
        }
    }

	// Sales baner function 
    public function ays_poll_sale_baner(){
		if(isset($_POST['ays_poll_sale_btn_black_friday'])){
			$sale_date = sanitize_text_field($_POST['ays_poll_sale_btn_black_friday']);
			update_option('ays_poll_sale_notification_'.$sale_date, 1); 
			update_option('ays_poll_sale_date_'.$sale_date, current_time( 'mysql' ));
		}

		// if (isset($_POST['ays_poll_sale_btn']) && isset( $_POST[$this->plugin_name . '-sale-banner'] ) 
		//    && wp_verify_nonce( $_POST[$this->plugin_name . '-sale-banner'], $this->plugin_name . '-sale-banner' ) && current_user_can('manage_options')) {
		// 	$sale_date = 'plugin_sale';
        //     update_option('ays_poll_sale_btn_'.$sale_date, 1); 
        //     update_option('ays_poll_sale_date_'.$sale_date, current_time( 'mysql' ));
        // }

		if(isset($_POST['ays_poll_sale_btn_poll_countdown_for_two_months'])){	
			$sale_date = sanitize_text_field($_POST['ays_poll_sale_btn_poll_countdown_for_two_months']);		
			$dismiss_two_months = true;
			update_option('ays_poll_sale_notification_two_months_'.$sale_date, 1); 
			update_option('ays_poll_sale_date_'.$sale_date, current_time( 'mysql' ));
		}

		$one_day = 60*60*24; 
		$poll_sales = array(
			'plugin_sale'     => array(
									'status' => 'active',
									'time'   => ($one_day * 5),
								),
			'mega_bundle'     => array(
									'status' => 'inactive',
									'time'   => ($one_day * 5),
								),
			'new_mega_bundle' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 7),
								),
			'small_spring' 	 => array(
									'status' => 'inactive',
									'time'   => ($one_day * 7),
								),
			'mega_bundle_new' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 7),
								),
			'business_bundle' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 7),
								),
			'black_friday' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 5),
								),
			'winter_bundle' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 5),
								),
			'poll_countdown' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 5),
								),
			'halloween_bundle' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 5),
								),
			'christmas_message' => array(
									'status' => 'inactive',
									'time'   => ($one_day * 7),
								),
		);

		if (isset($_GET['page']) && strpos($_GET['page'], POLL_MAKER_AYS_NAME) !== false) {
			foreach($poll_sales as $sale => $status){
				$ays_poll_sale_date = '';
				if(isset($status['status']) && $status['status'] == 'active'){
					$ays_poll_sale_date = get_option('ays_poll_sale_date_'.$sale);
					$ays_poll_two_months_flag = intval(get_option('ays_poll_sale_notification_two_months_'.$sale));
					$current_date = current_time( 'mysql' );
					$date_diff = strtotime($current_date) -  intval(strtotime($ays_poll_sale_date)) ;
					$val = isset($status['time']) ? $status['time'] : $one_day * 5;
					if($ays_poll_two_months_flag > 0){
						$val = $one_day * 60;
					}

					$days_diff = $date_diff / $val;
					if(intval($days_diff) > 0 ){
						update_option('ays_poll_sale_notification_'.$sale, 0); 
						update_option('ays_poll_sale_btn_'.$sale, 0); 
						update_option('ays_poll_sale_notification_two_months_'.$sale, 0); 
					}
					$ays_poll_flag = intval(get_option('ays_poll_sale_notification_'.$sale));
					$ays_poll_flag += intval(get_option('ays_poll_sale_btn_'.$sale));
					$ays_poll_flag += $ays_poll_two_months_flag;
					if($ays_poll_flag == 0){
						$ays_poll_sale_message = 'ays_poll_sale_message_'.$sale;
						$this->ays_poll_sale_message_poll_pro();
						// $this->ays_poll_sale_message_poll_pro_2024();
					}
				}
			}
		}
	}

	public function ays_poll_dismiss_button(){

        $data = array(
            'status' => false,
        );

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ays_poll_dismiss_button') { 
            if( (isset( $_REQUEST['_ajax_nonce'] ) && wp_verify_nonce( $_REQUEST['_ajax_nonce'], POLL_MAKER_AYS_NAME . '-sale-banner' )) && current_user_can( 'manage_options' )){
				$sale_date = 'plugin_sale';
                update_option('ays_poll_sale_btn_'.$sale_date, 1);
                update_option('ays_poll_sale_date_'.$sale_date, current_time( 'mysql' ));
                $data['status'] = true;
            }
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode($data);
        wp_die();

    }

	// Poll plugin sale  
	// public function ays_poll_sale_message($flag){
    //     if($flag == 0){
        	
	// 		<div id="ays-poll-dicount-month-main" class="notice notice-success is-dismissible ays_poll_dicount_info" >
	// 			<div id="ays_poll_dicount_banner" class="ays_poll_dicount_month" style="display: flex;align-items: center;justify-content: space-between;">
	// 				<div style="display: flex; align-items: center;">
	// 					<div>
	// 						<a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-sale-banner-link"><img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/sale.png'; " style="width: 60px;"></a>
	// 					</div>
	// 					<div style="font-size:14px; padding: 12px;">
	// 						<strong>
	// 							<?php echo __( "Limited Time <span style='color:red;'>30%</span> SALE on <a href='https://ays-pro.com/wordpress/poll-maker' target='_blank'>Poll Maker</a> premium plans!",POLL_MAKER_AYS_NAME);   
	// 						</strong>
	// 						<br>
	// 						<strong style="font-size: 12px;">								
	// 								<?php echo __( "Welcome to summer! Start it right with all the powerful tools you need for creating an advanced interactive website. Hurry! Ends on June 30. <a href='https://ays-pro.com/wordpress/poll-maker' target='_blank'>Check it out!</a>",POLL_MAKER_AYS_NAME); 					
	// 						</strong>							
	// 						<form action="" method="POST">
	// 							<button class="btn btn-link ays-button" name="ays_poll_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>
	// 						</form>
	// 					</div>
	// 				</div>
	// 				<a href="https://ays-pro.com/wordpress/poll-maker" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="height: 32px; display: flex; align-items: center; font-weight: 500; " ><?php echo __('Buy Now !',POLL_MAKER_AYS_NAME); </a>
				
	// 			</div>
				
	// 		</div>		
	// 		<?php
	// 	}
	// }

	// Mega bundle sale
	public function ays_poll_sale_message_mega_bundle(){
		?>
		<div class="notice notice-success is-dismissible ays_poll_dicount_info" >
			<div id="ays_poll_dicount_banner" class="ays_poll_dicount_month" style="display: flex;align-items: center;justify-content: space-between;">
				<div style="display: flex; align-items: center;">
					<div>
						<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-poll-sale-banner-link"><img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/mega_bundle_logo_box.png';?> " style="width: 60px;"></a>
					</div>
					<div style="font-size:14px; padding:12px; width: 100%;">
						<strong>
							<?php echo __( "Limited Time <span style='color:red;'>50%</span> SALE on 3 Powerful Plugins (Quiz, Survey, Poll)!", "poll-maker");?>  
						</strong>
						<br>
						<strong style="font-size: 12px;">								
								<?php echo __( "Mega bundle offer for you! It consists of 3 different powerful plugins, each one allowing you to make your WordPress experience the best that could be.", "poll-maker");?>							
								<br>
								<?php echo __( "Hurry up! Ends on October 15. <a href='https://ays-pro.com/mega-bundle' target='_blank'>Check it out!</a>", "poll-maker");?>							
						</strong>							
						<form action="" method="POST">
							<button class="btn btn-link ays-button" name="ays_poll_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0" value='mega_bundle'>Dismiss ad</button>
						</form>
					</div>
				</div>
				<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="height: 32px; display: flex; align-items: center; font-weight: 500; " ><?php echo __('Buy Now !', "poll-maker");?> </a>				
			</div>
		</div>	
		<?php
	}

	// Business bundle sale
	public function ays_poll_sale_message_business_bundle(){
		?>
		<div class="notice notice-success is-dismissible ays_poll_dicount_info" >
			<div id="ays_poll_dicount_banner" class="ays_poll_dicount_month" style="display: flex;align-items: center;justify-content: space-between;">
				<div style="display: flex; align-items: center;">
					<div>
						<a href="https://ays-pro.com/business-bundle" target="_blank" class="ays-poll-sale-banner-link"><img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/business_bundle_logo.png';?> " style="width: 60px;"></a>
					</div>
					<div style="font-size:14px; padding:12px; width: 100%;">
						<strong>
							<?php echo __( "Limited Time <span style='color:red;'>50%</span> SALE on 13 Powerful Plugins!", "poll-maker");?>  
						</strong>
						<br>
						<strong style="font-size: 12px;">								
								<?php echo __( "Business bundle offer for you! It consists of 13 different powerful plugins, each one allowing you to make your WordPress experience the best that could be.", "poll-maker");?>							
								<br>
								<?php echo __( "Hurry up! Ends on October 15. <a href='https://ays-pro.com/business-bundle' target='_blank'>Check it out!</a>", "poll-maker");?>							
						</strong>							
						<form action="" method="POST">
							<button class="btn btn-link ays-button" name="ays_poll_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0" value='business_bundle'>Dismiss ad</button>
						</form>
					</div>
				</div>
				<a href="https://ays-pro.com/business-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="height: 32px; display: flex; align-items: center; font-weight: 500; " ><?php echo __('Buy Now !', "poll-maker");?> </a>				
			</div>
		</div>	
		<?php
	}

	// New Mega bundle sale
	public function ays_poll_sale_message_new_mega_bundle(){
        $content = array();
			$content[] = '<div id="ays-poll-dicount-month-main" class="notice notice-success is-dismissible ays_survey_dicount_info">';
				$content[] = '<div id="ays-poll-dicount-month" class="ays_survey_dicount_month">';
					$content[] = '<a href="https://bit.ly/3keKTm9" target="_blank" class="ays-poll-sale-banner-link"><img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

					$content[] = '<div class="ays-poll-dicount-wrap-box">';

						$content[] = '<strong style="font-weight: bold;">';
							$content[] = __( "Limited Time <span style='color:#E85011;'>55%</span> SALE on <br><a href='https://bit.ly/3keKTm9' class='ays-poll-sale-banner-link-for-text' target='_blank'><span style='color:#E85011;'>Mega Bundle</span></a> (Quiz + Survey + Poll)!", "poll-maker" );
						$content[] = '</strong>';

						$content[] = '<br>';

						$content[] = '<strong>';
								$content[] = __( "Hurry up! Ends on November 15. <a href='https://bit.ly/3keKTm9' target='_blank'>Check it out!</a>", "poll-maker" );
						$content[] = '</strong>';
						
						$content[] = '<form action="" method="POST">';
							$content[] = '<button class="btn btn-link ays-button" value="new_mega_bundle" name="ays_poll_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
						$content[] = '</form>';
							
					$content[] = '</div>';

					$content[] = '<div class="ays-poll-dicount-wrap-box">';

						$content[] = '<div id="ays-poll-maker-countdown-main-container">';
							$content[] = '<div class="ays-poll-maker-countdown-container">';

								$content[] = '<div id="ays-poll-countdown">';
									$content[] = '<ul>';
										$content[] = '<li><span id="ays-poll-countdown-days"></span>days</li>';
										$content[] = '<li><span id="ays-poll-countdown-hours"></span>Hours</li>';
										$content[] = '<li><span id="ays-poll-countdown-minutes"></span>Minutes</li>';
										$content[] = '<li><span id="ays-poll-countdown-seconds"></span>Seconds</li>';
									$content[] = '</ul>';
								$content[] = '</div>';

								$content[] = '<div id="ays-poll-countdown-content" class="emoji">';
									$content[] = '<span>🚀</span>';
									$content[] = '<span>⌛</span>';
									$content[] = '<span>🔥</span>';
									$content[] = '<span>💣</span>';
								$content[] = '</div>';

							$content[] = '</div>';
						$content[] = '</div>';
							
					$content[] = '</div>';

					$content[] = '<a href="https://bit.ly/3keKTm9" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="height: 32px; display: flex; align-items: center; font-weight: 500; " >' . __( 'Buy Now !', "poll-maker" ) . '</a>';
				$content[] = '</div>';
			$content[] = '</div>';

        $content = implode( '', $content );
        echo $content;
    }

	// Black friday bundle
	public function ays_poll_sale_message_black_friday(){
        $content = array();

            $content[] = '<div id="ays-poll-dicount-black-friday-month-main" class="notice notice-success is-dismissible ays_poll_dicount_info">';
                $content[] = '<div id="ays-poll-dicount-black-friday-month" class="ays_poll_dicount_month">';
                    $content[] = '<div class="ays-poll-dicount-black-friday-box">';
                        $content[] = '<div class="ays-poll-dicount-black-friday-wrap-box ays-poll-dicount-black-friday-wrap-box-80" style="width: 70%;">';
                            $content[] = '<div class="ays-poll-dicount-black-friday-title-row">' . __( 'Limited Time', "poll-maker" ) .' '. '<a href="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=black-friday-sale-banner" class="ays-poll-dicount-black-friday-button-sale" target="_blank">' . __( 'Sale', "poll-maker" ) . '</a>' . '</div>';
                            $content[] = '<div class="ays-poll-dicount-black-friday-title-row">' . __( 'Poll Maker', "poll-maker" ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-poll-dicount-black-friday-wrap-box ays-poll-dicount-black-friday-wrap-text-box">';
                            $content[] = '<div class="ays-poll-dicount-black-friday-text-row">' . __( '20% off', "poll-maker" ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-poll-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<div id="ays-poll-countdown-main-container">';
                                $content[] = '<div class="ays-poll-countdown-container">';
                                    $content[] = '<div id="ays-poll-countdown" style="display: block;">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><span id="ays-poll-countdown-days">0</span>' . __( 'Days', "poll-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-poll-countdown-hours">0</span>' . __( 'Hours', "poll-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-poll-countdown-minutes">0</span>' . __( 'Minutes', "poll-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-poll-countdown-seconds">0</span>' . __( 'Seconds', "poll-maker" ) . '</li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';
                                    $content[] = '<div id="ays-poll-countdown-content" class="emoji" style="display: none;">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-poll-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=black-friday-sale-banner" class="ays-poll-dicount-black-friday-button-buy-now" target="_blank">' . __( 'Get Your Deal', "poll-maker" ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-poll-dismiss-buttons-container-for-form-black-friday">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-poll-dismiss-buttons-content">';
							if( current_user_can( 'manage_options' ) ){
								$content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn_black_friday" style="" value="black_friday">' . __( 'Dismiss ad', "poll-maker" ) . '</button>';
                                $content[] = wp_nonce_field( $this->plugin_name . '-sale-banner' ,  $this->plugin_name . '-sale-banner' );
							}
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

        $content = implode( '', $content );

        echo $content;
    }

	// Winter bundle
	public function ays_poll_sale_message_winter_bundle(){
		$content = array();
		$content[] = '<div id="ays-poll-winter-dicount-main">';
			$content[] = '<div id="ays-poll-dicount-month-main" class="notice notice-success is-dismissible ays_poll_dicount_info">';
				$content[] = '<div id="ays-poll-dicount-month" class="ays_poll_dicount_month">';
					$content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-poll-sale-banner-link"><img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/3.png"></a>';

					$content[] = '<div class="ays-poll-dicount-wrap-box">';

						$content[] = '<strong>';
							$content[] = __( "The BIGGEST <span class='ays-poll-dicount-wrap-color' style='color:#000;'>50%</span> SALE on <br><span><a href='https://ays-pro.com/mega-bundle' target='_blank' class='ays-poll-dicount-wrap-color ays-poll-dicount-wrap-text-decoration' style='display:block;color:#000'>Christmas Bundle</a></span> (Quiz+Survey+Poll)!", "poll-maker" );
						$content[] = '</strong>';

						$content[] = '<br>';

						$content[] = '<strong>';
						$content[] = __( "Hurry up! Ending on. <a href='https://ays-pro.com/mega-bundle' target='_blank' style='color:#000;'>Check it out!</a>", "poll-maker" );
						$content[] = '</strong>';
							
					$content[] = '</div>';

					$content[] = '<div class="ays-poll-dicount-wrap-box">';

						$content[] = '<div id="ays-poll-maker-countdown-main-container">';
							$content[] = '<div class="ays-poll-maker-countdown-container">';

								$content[] = '<div id="ays-poll-countdown">';
									$content[] = '<ul>';
										$content[] = '<li><span id="ays-poll-countdown-days"></span>days</li>';
										$content[] = '<li><span id="ays-poll-countdown-hours"></span>Hours</li>';
										$content[] = '<li><span id="ays-poll-countdown-minutes"></span>Minutes</li>';
										$content[] = '<li><span id="ays-poll-countdown-seconds"></span>Seconds</li>';
									$content[] = '</ul>';
								$content[] = '</div>';

								$content[] = '<div id="ays-poll-countdown-content" class="emoji">';
									$content[] = '<span>🚀</span>';
									$content[] = '<span>⌛</span>';
									$content[] = '<span>🔥</span>';
									$content[] = '<span>💣</span>';
								$content[] = '</div>';

							$content[] = '</div>';

							$content[] = '<form action="" method="POST">';
								$content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn_winter" style="height: 32px;color:#000"" value="winter_bundle">Dismiss ad</button>';
								$content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn_winter_for_two_months" style="height: 32px; padding-left: 0;color:#000"" value="winter_bundle">Dismiss ad for 2 months</button>';
							$content[] = '</form>';

						$content[] = '</div>';
							
					$content[] = '</div>';

					$content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-poll-button-top-buy-now" target="_blank">' . __( 'Buy Now !', "poll-maker" ) . '</a>';
				$content[] = '</div>';
			$content[] = '</div>';
		$content[] = '</div>';
		$content = implode( '', $content );
		echo $content;
    }

	public function ays_poll_sale_message_poll_pro(){
		$content = array();

		$content[] = '<div id="ays-poll-dicount-month-main" class="notice notice-success is-dismissible ays_poll_dicount_info">';
			$content[] = '<div id="ays-poll-dicount-month" class="ays_poll_dicount_month">';

				$content[] = '<div class="ays-poll-dicount-wrap-box ays-poll-dicount-wrap-text-box">';
					$content[] = '<div>';
						$content[] = '<span class="ays-poll-new-poll-pro-title">';
							$content[] = __( "<span>Limited time 50% SALE on <a href='https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=sale-banner' target='_blank' style='color:#ffffff; text-decoration: underline;'>Poll Maker</a></span>", "poll-maker" );
						$content[] = '</span>';
						$content[] = '</br>';
						$content[] = '<div class="ays-poll-new-poll-pro-mobile-image-display-block display_none">';
							$content[] = '<span class="ays-poll-sale-baner-mega-bundle-sale-text">50%</span>';
							$content[] = '<img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/line.webp" style="width: 70px;">';
						$content[] = '</div>';
						$content[] = '<span class="ays-poll-new-poll-pro-desc">';
							$content[] = '<img class="ays-poll-new-poll-pro-guaranteeicon" src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/poll-maker-guaranteeicon.svg" style="width:30px;margin-right:5px">';
								$content[] = __( "30 Days Money Back Guarantee", "poll-maker" );
						$content[] = '</span>';
					$content[] = '</div>';
					$content[] = '<div style="display:flex;flex-wrap:wrap;width:min-content;">';
						$content[] = '<span class="ays-poll-sale-baner-mega-bundle-sale-text">50%</span>';
						$content[] = '<img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/line.webp" class="ays-poll-new-mega-bundle-mobile-image-display-none" style="width: 70px;">';
					$content[] = '</div>';
					$content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-poll-dismiss-buttons-container-for-form">';

						$content[] = '<form action="" method="POST">';
							$content[] = '<div id="ays-poll-dismiss-buttons-content">';
								if( current_user_can( 'manage_options' ) ){
									$content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
									$content[] = wp_nonce_field( POLL_MAKER_AYS_NAME . '-sale-banner' ,  POLL_MAKER_AYS_NAME . '-sale-banner' );
								}
							$content[] = '</div>';
						$content[] = '</form>';
						
					$content[] = '</div>';
						
				$content[] = '</div>';

				$content[] = '<div class="ays-poll-dicount-wrap-box ays-poll-dicount-wrap-countdown-box">';

					$content[] = '<div id="ays-poll-maker-countdown-main-container">';
						$content[] = '<div class="ays-poll-maker-countdown-container">';

							$content[] = '<div id="ays-poll-countdown">';

                                    $content[] = '<div>';
                                        $content[] = __( "Offer ends in:", "poll-maker" );
                                    $content[] = '</div>';

									$content[] = '<ul>';
                                        $content[] = '<li><span id="ays-poll-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-poll-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-poll-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-poll-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';

							$content[] = '</div>';

							$content[] = '<div id="ays-poll-countdown-content" class="emoji">';
								$content[] = '<span>🚀</span>';
								$content[] = '<span>⌛</span>';
								$content[] = '<span>🔥</span>';
								$content[] = '<span>💣</span>';
							$content[] = '</div>';

						$content[] = '</div>';
					$content[] = '</div>';

				$content[] = '</div>';

				$content[] = '<div class="ays-poll-dicount-wrap-box ays-poll-dicount-wrap-button-box">';
					$content[] = '<a href="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=sale-banner" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now', "poll-maker" ) . '</a>';
					$content[] = '<span class="ays-poll-dicount-one-time-text">';
						$content[] = __( "One-time payment", "poll-maker" );
					$content[] = '</span>';
				$content[] = '</div>';
			$content[] = '</div>';
		$content[] = '</div>';
		$content = implode( '', $content );
		echo $content;
    }

	// New Poll Pro 2024
	public function ays_poll_sale_message_poll_pro_2024() {
		$content = array();
            $content[] = '<div id="ays-poll-new-mega-bundle-dicount-month-main-2024" class="notice notice-success is-dismissible ays_poll_dicount_info">';
                $content[] = '<div id="ays-poll-dicount-month" class="ays_poll_dicount_month">';

                    $content[] = '<div class="ays-poll-discount-box-sale-image"></div>';
                    $content[] = '<div class="ays-poll-dicount-wrap-box ays-poll-dicount-wrap-text-box">';

                        $content[] = '<div class="ays-poll-dicount-wrap-text-box-texts">';
                            $content[] = '<div>
                                            <a href="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=top-banner-50" target="_blank" style="color:#30499B;">
                                            <span class="ays-poll-new-mega-bundle-limited-text">Limited</span> Offer for Poll Maker </a> <br> 
                                          </div>';
                        $content[] = '</div>';

                        $content[] = '<div style="font-size: 17px;">';
                            $content[] = '<img style="width: 24px;height: 24px;" src="' . esc_attr(POLL_MAKER_AYS_ADMIN_URL) . '/images/icons/guarantee-new.png">';
                            $content[] = '<span style="padding-left: 4px; font-size: 14px; font-weight: 600;"> 30 Day Money Back Guarantee</span>';
                            
                        $content[] = '</div>';

                       

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-poll-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-poll-dismiss-buttons-content">';
                                    if( current_user_can( 'manage_options' ) ){
                                        $content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0; color: #30499B;
                                        ">Dismiss ad</button>';
                                        $content[] = wp_nonce_field( $this->plugin_name . '-sale-banner' ,  $this->plugin_name . '-sale-banner' );
                                    }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-poll-dicount-wrap-box ays-poll-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-poll-maker-countdown-main-container">';
                            $content[] = '<div class="ays-poll-maker-countdown-container">';

                                $content[] = '<div id="ays-poll-countdown">';

                                    $content[] = '<div style="font-weight: 500;">';
                                        $content[] = __( "Offer ends in:", "poll-maker" );
                                    $content[] = '</div>';

                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-poll-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-poll-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-poll-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-poll-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-poll-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-poll-dicount-wrap-box ays-poll-dicount-wrap-button-box">';
                        $content[] = '<a href="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=top-banner-50" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', "poll-maker" ) . '</a>';
                        $content[] = '<span >' . __( 'One-time payment', "poll-maker" ) . '</span>';
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo html_entity_decode(esc_html( $content ));
	}

	public function add_tabs() {
		$screen = get_current_screen();
	
		if ( ! $screen) {
			return;
		}
        
        $title   = __( 'General Information:', "poll-maker");
        $content_text = '<div>
							<span>The WordPress Poll Maker plugin is here to help you quickly create advanced-level online polls and make your WordPress website more interactive. Use it to conduct elections, surveys and etc. Easily generate poll types like;</span>
						</div>
						<br>
        				<div>
							<span><strong>Choosing</strong> – create many options and let your users choose, or add their custom answers.</span>
						</div>
        				<div>
							<span><strong>Rating</strong> – with this poll type, the visitors will be able to weigh via a 1-5 star rating system or emojis via the graphical interface.</span>
						</div>
        				<div>
							<span><strong>Voting</strong> – make the participants evaluate your product by using like/dislike buttons or smiley/frown emojis.</span>
						</div>
        				<div>
							<span><strong>Versus</strong> – Select two statements or images that are opposed to each other, and make your users choose the perfect one.</span>
						</div>
        				<div>
							<span><strong>Range</strong> – the users will be able to choose the answer across the 0-100 scale.</span>
						</div>
        				<div>
							<span><strong>Text</strong> – with this poll type the visitors should write down their own answers on the text boundaries.</span>
						</div>
        				<div>
							<span><strong>Dropdown</strong> – the users will choose the multiple-choice answers from a list of answers appeared in a dropdown form.</span>
						</div>
						<br>
        				<div>
							<span>Increase engagement of your website with the integrated,  formatting, image, audio, video poll question types feature.</span>
						</div>';

        $sidebar_content = '<p><strong>' . __( 'For more information:', "poll-maker") . '</strong></p>' .
                            '<p>
                                <a href="https://www.youtube.com/watch?v=RDKZXFmG6Pc" target="_blank">' . __( 'YouTube video tutorials' , "poll-maker" ) . '</a>
                            </p>' .
                            '<p>
                                <a href="https://ays-pro.com/wordpress-poll-maker-user-manual" target="_blank">' . __( 'Documentation', "poll-maker" ) . '</a>
                            </p>' .
                            '<p>
                                <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank">' . __( 'Poll Maker plugin pro version', "poll-maker" ) . '</a>
                            </p>' .
                            '<p>
                                <a href="https://poll-plugin.com/wordpress-poll-plugin-pro-demo/" target="_blank">' . __( 'Poll Maker plugin demo', "poll-maker" ) . '</a>
                            </p>';


        $content =  '<h2>' . __( 'Poll Maker Information', "poll-maker") . '</h2>';
		$content .= '<div>' . $content_text . '</div>';

        $help_tab_content = array(
            'id'      => 'survey_maker_help_tab',
            'title'   => $title,
            'content' => $content
        );
        
		$screen->add_help_tab($help_tab_content);

		$screen->set_help_sidebar($sidebar_content);
	}
	
	public static function ays_poll_sale_message_small_spring(){
		$content = array();

		$content[] = '<div id="ays-poll-dicount-month-main" class="notice notice-success is-dismissible ays_poll_dicount_info">';
			$content[] = '<div id="ays-poll-dicount-month" class="ays_poll_dicount_month">';
				$content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-poll-sale-banner-link"><img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

				$content[] = '<div class="ays-poll-dicount-wrap-box">';
					$content[] = '<p>';
						$content[] = '<strong>';
							$content[] = __( "Spring is here! <span class='ays-poll-dicount-wrap-color'>50%</span> SALE on <span><a href='https://ays-pro.com/mega-bundle' target='_blank' class='ays-poll-dicount-wrap-color ays-poll-dicount-wrap-text-decoration'>Mega Bundle</a></span><span style='display: block;'>Quiz + Survey + Poll</span>", "poll-maker" );
						$content[] = '</strong>';
					$content[] = '</p>';
				$content[] = '</div>';

				$content[] = '<div class="ays-poll-dicount-wrap-box">';

					$content[] = '<div id="ays-poll-countdown-main-container">';

						$content[] = '<form action="" method="POST" class="ays-poll-btn-form">';
							$content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn_small_spring" style="height: 32px; margin-left: 0;padding-left: 0" value="small_spring">Dismiss ad</button>';
							$content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn_spring_small_for_two_months" style="height: 32px; padding-left: 0" value="small_spring">Dismiss ad for 2 months</button>';
						$content[] = '</form>';

					$content[] = '</div>';
						
				$content[] = '</div>';

				$content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', "poll-maker" ) . '</a>';
			$content[] = '</div>';
		$content[] = '</div>';

		$content = implode( '', $content );
		echo $content;
    }

    public function ays_poll_sale_message_poll_countdown(){
        $content = array();

        $content[] = '<div id="ays-poll-dicount-month-main" class="notice notice-success is-dismissible ays_poll_dicount_info">';
            $content[] = '<div id="ays-poll-dicount-month" class="ays_poll_dicount_month">';
                $content[] = '<a href="https://ays-pro.com/great-bundle" target="_blank" class="ays-poll-sale-banner-link" style="display:none;"><img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

                	$content[] = '<a href="https://ays-pro.com/wordpress/poll-maker" class="ays-poll-sale-banner-link" target="_blank"><img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/icons/icon-128x128.png"></a>';

                $content[] = '<div class="ays-poll-dicount-wrap-box">';

                    $content[] = '<strong style="font-weight: bold;">';
                        $content[] = __( "Limited Time <span class='ays-poll-dicount-wrap-color'>20%</span> SALE on <br><span><a href='https://ays-pro.com/wordpress/poll-maker/' target='_blank' class='ays-poll-dicount-wrap-color ays-poll-dicount-wrap-text-decoration' style='display:block;'>Poll Maker Premium Versions</a></span>", "poll-maker" );
                    $content[] = '</strong>';

                    $content[] = '<strong>';
                         $content[] = __( "Hurry up! <a href='https://ays-pro.com/wordpress/poll-maker' target='_blank'>Check it out!</a>", "poll-maker" );
                    $content[] = '</strong>';
                            
                $content[] = '</div>';

                $content[] = '<div class="ays-poll-dicount-wrap-box">';

                    $content[] = '<div id="ays-poll-maker-countdown-main-container">';
                        $content[] = '<div class="ays-poll-maker-countdown-container">';

                            $content[] = '<div id="ays-poll-countdown">';
                                $content[] = '<ul>';
                                    $content[] = '<li><span id="ays-poll-countdown-days"></span>days</li>';
                                    $content[] = '<li><span id="ays-poll-countdown-hours"></span>Hours</li>';
                                    $content[] = '<li><span id="ays-poll-countdown-minutes"></span>Minutes</li>';
                                    $content[] = '<li><span id="ays-poll-countdown-seconds"></span>Seconds</li>';
                                $content[] = '</ul>';
                            $content[] = '</div>';

                            $content[] = '<div id="ays-poll-countdown-content" class="emoji">';
                                $content[] = '<span>🚀</span>';
                                $content[] = '<span>⌛</span>';
                                $content[] = '<span>🔥</span>';
                                $content[] = '<span>💣</span>';
                            $content[] = '</div>';

                        $content[] = '</div>';

                    $content[] = '</div>';
                            
                $content[] = '</div>';

	            $content[] = '<div class="ays-poll-dicount-wrap-box ays-buy-now-button-box">';
	                $content[] = '<a href="https://ays-pro.com/wordpress/poll-maker" class="button button-primary ays-buy-now-button" id="ays-button-top-buy-now" target="_blank" style="" >' . __( 'Buy Now !', "poll-maker" ) . '</a>';
	            $content[] = '</div>';

            $content[] = '</div>';

            $content[] = '<div style="position: absolute;right: 0;bottom: 1px;" class="ays-poll-dismiss-buttons-container-for-form">';
                $content[] = '<form action="" method="POST">';
                    $content[] = '<div id="ays-poll-dismiss-buttons-content">';
                        $content[] = '<button class="btn btn-link ays-button" name="ays_poll_sale_btn_poll_countdown" style="height: 32px; margin-left: 0;padding-left: 0; color:#979797" value="poll_countdown">Dismiss ad</button>';
                    $content[] = '</div>';
                $content[] = '</form>';
            $content[] = '</div>';

        $content[] = '</div>';

	    $content = implode( '', $content );
	    echo $content;      
    }

    public static function ays_poll_halloween_bundle(){
            $content = array();

            $content[] = '<div id="ays-poll-dicount-month-main-helloween" class="notice notice-success is-dismissible ays_poll_dicount_info">';
                $content[] = '<div id="ays-poll-dicount-month-helloween" class="ays_poll_dicount_month_helloween">';
                    $content[] = '<div class="ays-poll-dicount-wrap-box-helloween-limited">';

                        $content[] = '<p>';
                            $content[] = __( "Limited Time 
                            <span class='ays-poll-dicount-wrap-color-helloween' style='color:#b2ff00;'>20%</span> 
                            <span>
                                SALE on
                            </span> 
                            <br>
                            <span style='' class='ays-poll-helloween-bundle'>
                                <a href='https://ays-pro.com/wordpress/poll-maker?utm_source=poll-maker-free&utm_medium=dashboard&utm_campaign=halloween-sale-banner' target='_blank' class='ays-poll-dicount-wrap-color-helloween ays-poll-dicount-wrap-text-decoration-helloween' style='display:block; color:#b2ff00;margin-right:6px;'>
                                    Poll Maker
                                </a>
                            </span>", "poll-maker" );
                        $content[] = '</p>';
                        $content[] = '<p>';
                                $content[] = __( "Hurry up! 
                                                <a href='https://ays-pro.com/wordpress/poll-maker?utm_source=easy-form-free&utm_medium=dashboard&utm_campaign=halloween-sale-banner' target='_blank' style='color:#ffc700;'>
                                                    Check it out!
                                                </a>", "poll-maker" );
                        $content[] = '</p>';
                            
                    $content[] = '</div>';
                    $content[] = '<div class="ays-poll-helloween-bundle-buy-now-timer">';
                        $content[] = '<div class="ays-poll-dicount-wrap-box-helloween-timer">';
                            $content[] = '<div id="ays-poll-maker-countdown-main-container" class="ays-poll-countdown-main-container-helloween">';
                                $content[] = '<div class="ays-poll-countdown-container-helloween">';
                                    $content[] = '<div id="ays-poll-countdown">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><p><span id="ays-poll-countdown-days"></span><span>DAYS</span></p></li>';
                                            $content[] = '<li><p><span id="ays-poll-countdown-hours"></span><span>HOURS</span></p></li>';
                                            $content[] = '<li><p><span id="ays-poll-countdown-minutes"></span><span>MINS</span></p></li>';
                                            $content[] = '<li><p><span id="ays-poll-countdown-seconds"></span><span>SECS</span></p></li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';

                                    $content[] = '<div id="ays-poll-countdown-content" class="emoji">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';

                                $content[] = '</div>';

                            $content[] = '</div>';
                                
                        $content[] = '</div>';
                        $content[] = '<div class="ays-poll-dicount-wrap-box ays-buy-now-button-box-helloween">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/poll-maker?utm_source=poll-maker-free&utm_medium=dashboard&utm_campaign=halloween-sale-banner" class="button button-primary ays-buy-now-button-helloween" id="ays-button-top-buy-now-helloween" target="_blank" style="" >' . __( 'Buy Now !', "poll-maker" ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';

                $content[] = '</div>';
                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-poll-dismiss-buttons-container-for-form-helloween">';
                    $content[] = '<form action="" method="POST">';
                    	$content[] = '<div id="ays-poll-dismiss-buttons-content-helloween">';
							if( current_user_can( 'manage_options' ) ){
								$content[] = '<button class="btn btn-link ays-button-helloween" name="ays_poll_sale_btn_halloween_bundle" style="">Dismiss ad</button>';
								$content[] = wp_nonce_field( POLL_MAKER_AYS_NAME . '-sale-banner' ,  POLL_MAKER_AYS_NAME . '-sale-banner' );
							}
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
             $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
    }

    // Christmas banner
    public static function ays_poll_sale_message_christmas_message(){
            $content = array();

            $content[] = '<div id="ays-poll-dicount-christmas-month-main" class="notice notice-success is-dismissible ays_poll_dicount_info">';
                $content[] = '<div id="ays-poll-dicount-christmas-month" class="ays_poll_dicount_month">';
                    $content[] = '<div class="ays-poll-dicount-christmas-box">';
                        $content[] = '<div class="ays-poll-dicount-christmas-wrap-box ays-poll-dicount-christmas-wrap-box-80">';
                            $content[] = '<div class="ays-poll-dicount-christmas-title-row">' . __( 'Limited Time', "poll-maker" ) .' '. '<a href="https://ays-pro.com/wordpress/poll-maker" class="ays-poll-dicount-christmas-button-sale" target="_blank">' . __( '20%', "poll-maker" ) . '</a>' . ' SALE</div>';
                            $content[] = '<div class="ays-poll-dicount-christmas-title-row">' . __( 'Poll Maker Plugin', "poll-maker" ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-poll-dicount-christmas-wrap-box" style="width: 25%;">';
                            $content[] = '<div id="ays-poll-maker-countdown-main-container">';
                                $content[] = '<div class="ays-poll-countdown-container">';
                                    $content[] = '<div id="ays-poll-countdown" style="display: block;">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><span id="ays-poll-countdown-days"></span>' . __( 'Days', "poll-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-poll-countdown-hours"></span>' . __( 'Hours', "poll-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-poll-countdown-minutes"></span>' . __( 'Minutes', "poll-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-poll-countdown-seconds"></span>' . __( 'Seconds', "poll-maker" ) . '</li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';
                                    $content[] = '<div id="ays-poll-countdown-content" class="emoji" style="display: none;">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-poll-dicount-christmas-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/poll-maker" class="ays-poll-dicount-christmas-button-buy-now" target="_blank">' . __( 'BUY NOW!', "poll-maker" ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-poll-dismiss-buttons-container-for-form-christmas">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-poll-dismiss-buttons-content-christmas">';
                            $content[] = '<button class="btn btn-link ays-button-christmas" name="ays_poll_sale_btn" style="">' . __( 'Dismiss ad', "poll-maker" ) . '</button>';
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
    }

	/**
     * Recursive sanitation for an array
     * 
     * @param $array
     *
     * @return mixed
     */
    public static function recursive_sanitize_text_field($array) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field( $value );
            }
        }

        return $array;
    }

    public static function get_max_id( $table ) {
        global $wpdb;
        $db_table = $wpdb->prefix . 'ayspoll_'.$table;;

        $sql = "SELECT MAX(id) FROM {$db_table}";

        $result = intval( $wpdb->get_var( $sql ) );

        return $result;
    }

    public function ays_poll_generate_message_vars_html( $poll_message_vars ) {
        $content = array();
        $var_counter = 0; 

        $content[] = '<div class="ays-poll-message-vars-box">';
            $content[] = '<div class="ays-poll-message-vars-icon">';
                $content[] = '<div>';
                    $content[] = '<i class="ays_poll_fa ays_fa_link"></i>';
                $content[] = '</div>';
                $content[] = '<div>';
                    $content[] = '<span>'. __("Message Variables" , "poll-maker") .'</span>';
                    $content[] = '<a class="ays_help" data-toggle="tooltip" data-html="true" title="'. __("Insert your preferred message variable into the editor by clicking." , "poll-maker") .'">';
                        $content[] = '<i class="fas fa-info-circle"></i>';
                    $content[] = '</a>';
                $content[] = '</div>';
            $content[] = '</div>';
            $content[] = '<div class="ays-poll-message-vars-data">';
                foreach($poll_message_vars as $var => $var_name){
                    $var_counter++;
                    $content[] = '<label class="ays-poll-message-vars-each-data-label">';
                        $content[] = '<input type="radio" class="ays-poll-message-vars-each-data-checker" hidden id="ays_poll_message_var_count_'. $var_counter .'" name="ays_poll_message_var_count">';
                        $content[] = '<div class="ays-poll-message-vars-each-data">';
                            $content[] = '<input type="hidden" class="ays-poll-message-vars-each-var" value="'. $var .'">';
                            $content[] = '<span>'. $var_name .'</span>';
                        $content[] = '</div>';
                    $content[] = '</label>';
                }
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_poll_update_banner_time(){

        $date = time() + ( 3 * 24 * 60 * 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
        // $date = time() + ( 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS); // for testing | 1 min
        $next_3_days = date('M d, Y H:i:s', $date);

        $ays_poll_banner_time = get_option('ays_poll_banner_time');

        if ( !$ays_poll_banner_time || is_null( $ays_poll_banner_time ) ) {
            update_option('ays_poll_banner_time', $next_3_days ); 
        }

        $get_ays_poll_banner_time = get_option('ays_poll_banner_time');

        $val = 60*60*24*0.5; // half day
        // $val = 60; // for testing | 1 min

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($get_ays_poll_banner_time));

        $days_diff = $date_diff / $val;
        if(intval($days_diff) > 0 ){
            update_option('ays_poll_banner_time', $next_3_days);
			$get_ays_poll_banner_time = get_option('ays_poll_banner_time');
        }

        return $get_ays_poll_banner_time;
    }

	public static function ays_poll_check_if_current_image_exists($image_url) {
        global $wpdb;

        $res = true;
        if( !isset($image_url) ){
            $res = false;
        }

        if ( isset($image_url) && !empty( $image_url ) ) {

            $re = '/-\d+[Xx]\d+\./';
            $subst = '.';

            $image_url = preg_replace($re, $subst, $image_url, 1);

            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
            if ( is_null( $attachment ) || empty( $attachment ) ) {
                $res = false;
            }
        }

        return $res;
    }

	public function ays_poll_maker_quick_start() {
		global $wpdb;
		error_reporting(0);

		// Run a security check.
        check_ajax_referer( 'poll-maker-ajax-quick-poll-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

		// Check for permissions.
        if ( !Poll_Maker_Data::check_user_capability() ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'poll_id' => 0
            ));
            wp_die();
        }

		$polls_table = esc_sql($wpdb->prefix . "ayspoll_polls");
		$answers_table = esc_sql($wpdb->prefix . "ayspoll_answers");

		$title = stripslashes( sanitize_text_field($_REQUEST['ays-poll-title']) );
		$question = wp_kses_post( $_REQUEST['ays_poll_question'] );

		$answers = self::recursive_sanitize_text_field($_REQUEST['ays-poll-answers']);
		
		$allow_multivote = isset($_REQUEST['allow_multivote_switch']) && $_REQUEST['allow_multivote_switch'] == 'on' ? "on" : "off";
		$allow_not_vote = isset($_REQUEST['allow-not-vote']) && 'on' == $_REQUEST['allow-not-vote'] ? 1 : 0;
		$show_author = isset($_REQUEST['quick-poll-show_poll_author']) && 1 == $_REQUEST['quick-poll-show_poll_author'] ? 1 : 0;
		$show_title = isset($_REQUEST['quick-poll-show-title']) && $_REQUEST['quick-poll-show-title'] == 'off' ? 0 : 1;
		$show_creation_date = isset($_REQUEST['quick-poll-show-creation-date']) && $_REQUEST['quick-poll-show-creation-date'] == 'on' ? 1 : 0;
		$hide_results = isset($_REQUEST['quick-poll-hide-results']) && 1 == $_REQUEST['quick-poll-hide-results'] ? 1 : 0;
		$randomize_answers = isset($_REQUEST['quick-poll-randomize-answers']) && $_REQUEST['quick-poll-randomize-answers'] == 'on' ? 'on' : 'off';
		$enable_restart_button = isset($_REQUEST['quick-poll-enable-restart-button']) && $_REQUEST['quick-poll-enable-restart-button'] == 'on' ? 1 : 0;

		if	($allow_multivote === 'on') {
			$multivote_min_count = sanitize_text_field($_REQUEST['quick-poll-multivote-min-count']);
			$multivote_max_count = sanitize_text_field($_REQUEST['quick-poll-multivote-max-count']);
		} else {
			$multivote_min_count = 1;
			$multivote_max_count = 1;
		}
		
		$create_date = current_time( 'mysql' );

        $user_id = get_current_user_id();
		$user = get_userdata($user_id);
		$author = array(
			'id' => $user->ID,
			'name' => $user->data->display_name
		);
		$options = json_encode(
			array(
			"poll_version" 					=> POLL_MAKER_AYS_VERSION,
			"poll_enable_copy_protection" 	=> "off",
			"poll_question_text_to_speech" 	=> "off",
			"main_color" 					=> "#0C6291",
			"text_color" 					=> "#0C6291",
			"icon_color" 					=> "#0C6291",
			"bg_color" 						=> "#FBFEF9",
			"answer_bg_color" 				=> "#FBFEF9",
			"answer_border_side" 			=> "all_sides",
			"title_bg_color" 				=> "",
			"icon_size" 					=> 24,
			"width" 						=> 600,
			"width_for_mobile" 				=> 0,
			"btn_text" 						=> "Vote",
			"see_res_btn_text" 				=> "See Results",
			"border_style" 					=> "ridge",
			"border_radius" 				=> "0",
			"border_width" 					=> "1",
			"box_shadow_color" 				=> "#000000",
			"enable_box_shadow" 			=> "off",
			"enable_answer_style" 			=> "on",
			"bg_image" 						=> false,
			"info_form" 					=> 0,
			"fields" 						=> "apm-name,apm-email,apm_phone",
			"required_fields" 				=> "apm-name,apm-email,apm_phone",
			"info_form_title" 				=> "<h5>Please fill out the form:<\/h5>\n",
			"hide_results" 					=> $hide_results,
			"hide_result_message" 			=> 0,
			"hide_results_text" 			=> "<p>Thanks for your answer!<\/p>\n",
			"result_message"				=> "",
			"allow_not_vote"				=> $allow_not_vote,
			"show_social"					=> 0,
			"poll_social_buttons_heading"	=> "",
			"poll_show_social_ln"			=> "on",
			"poll_show_social_fb"			=> "on",
			"poll_show_social_tr" 			=> "on",
			"poll_show_social_vk" 			=> "off",
			"enable_social_links" 			=> "off",
			"poll_social_links_heading" 	=> "",
			"social_links" => array("linkedin_link" => "",
				"facebook_link" 	=> "",
				"twitter_link" 		=> "",
				"vkontakte_link" 	=> "",
				"youtube_link" 		=> ""),
			"load_effect" 					=> "load_gif",
			"load_gif" 						=> "plg_pro1",
			"custom_load" 					=> false,
			"limit_users" 					=> 0,
			"limit_users_method" 			=> "ip",
			"limitation_message" 			=> "<p>You have already voted<\/p>\n",
			"redirect_url" 					=> false,
			"redirection_delay" 			=> 0,
			"user_role" 					=> "",
			"enable_restriction_pass" 		=> 0,
			"restriction_pass_message" => "<p>You don\\'t have permissions for passing the poll<\/p>\n",
			"enable_logged_users" => 0,
			"enable_logged_users_message" => "<p>You must sign in for passing the poll<\/p>\n",
			"notify_email_on" => 0,
			"notify_email" => "",
			"published" => 1,
			"enable_pass_count" => "on",
			"result_sort_type" => "none",
			"create_date" => $create_date,
			"redirect_users" => 0,
			"redirect_after_vote_url" => false,
			"redirect_after_vote_delay" => 0,
			"activeInterval" => "2022-09-17",
			"deactiveInterval" => "2022-09-17",
			"activeIntervalSec" => "",
			"deactiveIntervalSec" => "",
			"active_date_message" => "<p>The poll has expired!<\/p>\n",
			"active_date_message_soon" => "<p style=\\\"text-align =>  center;\\\">The poll will be available soon!<\/p>\n",
			"vote_reason" => 0,
			"show_chart_type" => "google_bar_chart",
			"active_date_check" => "",
			"enable_restart_button" => $enable_restart_button,
			"enable_vote_btn" => 1,
			"show_votes_count" => 1,
			"attempts_count" => "1",
			"poll_main_url" => '',
			"show_create_date" => $show_creation_date,
			"show_author" => $show_author,
			"author" => $author,
			"show_res_percent" => 1,
			"show_result_btn_schedule" => 0,
			"ays_poll_show_timer" => 0,
			"show_bottom_timer" => 0,
			"ays_show_timer_type" => "countdown",
			"show_login_form" => "off",
			"poll_allow_answer" => 0,
			"poll_allow_answer_require" => 1,
			"versus_icon_type" => "default",
			"versus_icon_position" => "center",
			"versus_answers_label" => 0,
			"result_in_rgba" => 0,
			"enable_mailchimp" => "off",
			"enable_background_gradient" => "off",
			"background_gradient_color_1" => "#103251",
			"background_gradient_color_2" => "#607593",
			"poll_gradient_direction" => "vertical",
			"redirect_after_submit" => 0,
			"mailchimp_list" => "",
			"poll_direction" => "ltr",
			"poll_allow_multivote" => $allow_multivote,
			"multivote_answer_min_count" => $multivote_min_count,
			"poll_allow_multivote_count" => $multivote_max_count,
			"monitor_list" => "",
			"enable_monitor" => "off",
			"slack_conversation" => "",
			"enable_slack" => "off",
			"active_camp_list" => "",
			"active_camp_automation" => "",
			"enable_active_camp" => "off",
			"enable_zapier" => "off",
			"randomize_answers" => $randomize_answers,
			"enable_asnwers_sound" => "off",
			"enable_password" => "off",
			"password_poll" => "",
			"poll_password_message" => "Please enter password",
			"poll_enable_password_visibility" => "off",
			"background_size" => "cover",
			"disable_answer_hover" => 0,
			"custom_class" => "",
			"enable_poll_title_text_shadow" => "off",
			"poll_title_text_shadow" => "rgba(255,255,255,0)",
			"poll_title_text_shadow_x_offset" => 2,
			"poll_title_text_shadow_y_offset" => 2,
			"poll_title_text_shadow_z_offset" => 0,
			"poll_bg_image_position" => "center center",
			"poll_bg_img_in_finish_page" => "off",
			"ays_add_post_for_poll" => "off",
			"show_answer_message" => "off",
			"show_answers_caption" => "on",
			"enable_vote_limitation" => "off",
			"vote_limitation" => "",
			"limitation_time_period" => "minute",
			"enable_tackers_count" => "off",
			"tackers_count" => "",
			"ays_enable_mail_user" => "off",
			"vote_notification_email_msg" => "",
			"poll_answer_icon_check" => "off",
			"answers_icon" => "radio",
			"buttons_size" => "medium",
			"buttons_font_size" => "17",
			"poll_buttons_mobile_font_size" => "17",
			"buttons_left_right_padding" => "20",
			"buttons_top_bottom_padding" => "10",
			"buttons_border_radius" => "3",
			"redirect_after_submit_drpdwn" => 0,
			"user_add_answer_dropdown" => 0,
			"enable_google_sheets" => "off",
			"spreadsheet_id" => "",
			"enable_view_more_button" => "off",
			"poll_view_more_button_count" => 0,
			"poll_min_height" => "",
			"answer_sort_type" => "default",
			"answer_font_size" => "16",
			"poll_answer_font_size_mobile" => "16",
			"show_passed_users" => "off",
			"logo_image" => "",
			"allow_collect_user_info" => "off",
			"poll_send_mail_type" => "custom",
			"poll_sendgrid_email_from" => "",
			"poll_sendgrid_email_name" => "",
			"poll_sendgrid_template_id" => "",
			"enable_limit_by_country" => "off",
			"limit_country" => "AD",
			"show_votes_before_voting" => "off",
			"show_votes_before_voting_by" => "by_count",
			"fake_votes" => "off",
			"dont_show_poll_cont" => "off",
			"see_result_button" => "on",
			"see_result_radio" => "ays_see_result_button",
			"loader_font_size" => "",
			"show_answers_numbering" => "none",
			"effect_message" => "",
			"enable_mad_mimi" => "off",
			"mad_mimi_list" => "",
			"poll_show_passed_users_count" => 3,
			"question_font_size" => 16,
			"question_font_size_mobile" => 16,
			"poll_question_image_height" => "",
			"poll_mobile_max_width" => "",
			"poll_title_font_size" => "18",
			"poll_title_font_size_mobile" => "20",
			"poll_title_alignment" => "center",
			"poll_enable_answer_image_after_voting" => "off",
			"poll_text_type_length_enable" => "off",
			"poll_text_type_limit_type" => "characters",
			"poll_text_type_limit_length" => "",
			"poll_text_type_limit_message" => "off",
			"poll_text_type_placeholder" => "Your answer",
			"poll_text_type_width" => "",
			"poll_text_type_width_type" => "percent",
			"poll_answer_padding" => 10,
			"poll_answer_margin" => 10,
			"answers_border" => "on",
			"answers_border_width" => 1,
			"answers_border_style" => "solid",
			"answers_border_color" => "#444",
			"poll_answer_enable_box_shadow" => "off",
			"answers_box_shadow_color" => "#000",
			"poll_answer_box_shadow_x_offset" => 0,
			"poll_answer_box_shadow_y_offset" => 0,"poll_answer_box_shadow_z_offset" => 10,
			"poll_answer_image_height" => 150,
			"poll_answer_image_height_for_mobile" => "150",
			"poll_answer_image_border_radius" => 0,
			"ans_img_caption_style" => "outside",
			"ans_img_caption_position" => "bottom",
			"answers_font_size" => 15,
			"poll_answer_object_fit" => "cover",
			"answers_grid_column" => 2,
			"poll_answer_border_radius" => 0,
			"enable_getResponse" => "off",
			"getResponse_list" => "",
			"enable_mailerLite" => "off",
			"mailerLite_group_id" => "",
			"enable_convertKit" => "off",
			"poll_convertKit_form_id" => "",
			"enable_mailpoet" => "off",
			"mailpoet_list" => "",
			"poll_logo_url" => "",
			"poll_enable_logo_url" => "off",
			"poll_logo_url_new_tab" => "off",
			"poll_send_mail_to_site_admin" => "on",
			"poll_email_configuration_from_email" => "",
			"poll_email_configuration_from_name" => "",
			"poll_email_configuration_from_subject" => "",
			"poll_email_configuration_replyto_email" => "",
			"poll_email_configuration_replyto_name" => "",
			"display_fields_labels" => "off",
			"autofill_user_data" => "off",
			"poll_create_author" => 1
		));

		$wpdb->insert($polls_table, array(
			"title" => $title,
			"question" => $question,
			"type" => "choosing",
			"view_type" => "list",
			"categories" => ",1,",
			"show_title" => $show_title,
			"styles" => $options,
			"theme_id" => 1,
		));

		$poll_id = $wpdb->insert_id;

		foreach ($answers as $answer_key => $answer) {
			$wpdb->insert($answers_table, array(
				"poll_id" => $poll_id,
				"answer" => $answer,
				"votes" => 0,
				"ordering" => (intval($answer_key) + 1),
				"user_added" => 0,
				"show_user_added" => 1,
			));
		}

		echo json_encode(array(
            'status' => true,
            'poll_id' => $poll_id
        ));
        wp_die();
	}

}