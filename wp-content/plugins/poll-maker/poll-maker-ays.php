<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ays-pro.com/
 * @since             1.0.0
 * @package           Poll_Maker_Ays
 *
 * @wordpress-plugin
 * Plugin Name:       Poll Maker
 * Plugin URI:        https://ays-pro.com/wordpress/poll-maker/
 * Description:       Create amazing online polls and conduct interactive elections super easily and quickly.
 * Version:           5.4.8
 * Author:            Poll Maker Team
 * Author URI:        https://ays-pro.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       poll-maker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('POLL_MAKER_AYS_VERSION', '5.4.8');
define('POLL_MAKER_AYS_NAME', 'poll-maker-ays');

if (!defined('POLL_MAKER_AYS_DIR')) {
	define('POLL_MAKER_AYS_DIR', plugin_dir_path(__FILE__));
}

if (!defined('POLL_MAKER_AYS_BASE_URL')) {
	define('POLL_MAKER_AYS_BASE_URL', plugin_dir_url(__FILE__));
}
if (!defined('POLL_MAKER_AYS_ADMIN_URL')) {
	define('POLL_MAKER_AYS_ADMIN_URL', plugin_dir_url(__FILE__) . 'admin');
}

if (!defined('POLL_MAKER_AYS_PUBLIC_URL')) {
	define('POLL_MAKER_AYS_PUBLIC_URL', plugin_dir_url(__FILE__) . 'public');
}

if( ! defined( 'POLL_MAKER_AYS_BASENAME' ) ) {
    define( 'POLL_MAKER_AYS_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-poll-maker-ays-activator.php
 */
function activate_poll_maker_ays() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-poll-maker-ays-activator.php';
	Poll_Maker_Ays_Activator::ays_poll_update_db_check();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-poll-maker-ays-deactivator.php
 */
function deactivate_poll_maker_ays() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-poll-maker-ays-deactivator.php';
	Poll_Maker_Ays_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_poll_maker_ays');
register_deactivation_hook(__FILE__, 'deactivate_poll_maker_ays');

add_action('plugins_loaded', 'activate_poll_maker_ays');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-poll-maker-ays.php';

/**
 * The Gutenberg block registration.
 */
require plugin_dir_path(__FILE__) . 'poll/poll-maker-block.php';


if (!function_exists('array_column')) {
	function array_column( array $array, $columnKey, $indexKey = null ) {
		$result = array();
		foreach ( $array as $subArray ) {
			if (!is_array($subArray)) {
				continue;
			} elseif (is_null($indexKey) && array_key_exists($columnKey, $subArray)) {
				$result[] = $subArray[$columnKey];
			} elseif (array_key_exists($indexKey, $subArray)) {
				if (is_null($columnKey)) {
					$result[$subArray[$indexKey]] = $subArray;
				} elseif (array_key_exists($columnKey, $subArray)) {
					$result[$subArray[$indexKey]] = $subArray[$columnKey];
				}
			}
		}

		return $result;
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_poll_maker_ays() {
	add_action('admin_notices', 'poll_maker_admin_notice');
	$plugin = new Poll_Maker_Ays();
	$plugin->run();

}

function poll_maker_activation_redirect_method( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=' . POLL_MAKER_AYS_NAME ) ) );
    }
}

function poll_maker_admin_notice() {
	if (isset($_GET['page']) && strpos($_GET['page'], POLL_MAKER_AYS_NAME) !== false) {
		?>

        <div class="ays-notice-banner">
            <div class="navigation-bar">
                <div id="navigation-container">
                    <div class="ays-poll-logo-container-upgrade">
                        <div class="logo-container">
                            <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" style="box-shadow: none;">
                                <img  class="poll-logo" src="<?php echo esc_attr(POLL_MAKER_AYS_ADMIN_URL) . '/images/icons/icon-poll-128x128.png'; ?>" alt="<?php echo __( "Poll Maker", "poll-maker" ); ?>" title="<?php echo __( "Poll Maker", "poll-maker" ); ?>"/>
                            </a>
                        </div>
                        <div class="ays-poll-upgrade-container">
                            <a href="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=poll-top-banner-upgrade-button" target="_blank" class="poll-maker-upgrade-to-pro">
                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/icons/lightning-white.svg' ?>" class="poll-maker-upgrade-white-icon">
                                <span><?php echo __( "Upgrade", "poll-maker" ); ?></span>
                            </a>
                            <span class="ays-poll-logo-container-one-time-text"><?php echo __( "One-time payment", "poll-maker" ); ?></span>
                        </div>
                    </div>
                    <ul id="menu">
                            <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://poll-plugin.com/wordpress-poll-plugin-free-demo/" target="_blank">Demo</a></li>
                            <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://wordpress.org/support/plugin/poll-maker/" target="_blank">Free Support</a></li>
                            <li class="modile-ddmenu-xs make_a_suggestion"><a class="ays-btn" href="https://ays-demo.com/poll-maker-plugin-survey/" target="_blank">Make a Suggestion</a></li>
                            <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://wordpress.org/support/plugin/poll-maker/" target="_blank">Contact us</a></li>
                            <li class="modile-ddmenu-md">
                                <a class="toggle_ddmenu" href="javascript:void(0);"><i class="ays_poll_fa ays_fa_ellipsis_h"></i></a>
                                <ul class="ddmenu" data-expanded="false">
                                    <li><a class="ays-btn" href="https://poll-plugin.com/wordpress-poll-plugin-free-demo/" target="_blank">Demo</a></li>
                                   <li><a class="ays-btn" href="https://wordpress.org/support/plugin/poll-maker/" target="_blank">Free Support</a></li>
                                    <li><a class="ays-btn" href="https://wordpress.org/support/plugin/poll-maker/" target="_blank">Contact us</a></li>
                                </ul>
                            </li>
                            <li class="modile-ddmenu-sm">
                            <a class="toggle_ddmenu" href="javascript:void(0);"><i class="ays_poll_fa ays_fa_ellipsis_h"></i></a>
                            <ul class="ddmenu" data-expanded="false">                               
                                <li><a class="ays-btn" href="https://poll-plugin.com/wordpress-poll-plugin-free-demo/" target="_blank">Demo</a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/poll-maker/" target="_blank">Free Support</a></li>
                                <li class="make_a_suggestion"><a class="ays-btn" href="https://ays-demo.com/poll-maker-plugin-survey/" target="_blank">Make a Suggestion</a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/poll-maker/" target="_blank">Contact us</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Ask a question box start -->
        <div class="ays_ask_question_content">
            <div class="ays_ask_question_content_inner">
                <a href="https://wordpress.org/support/plugin/poll-maker" class="ays_poll_question_link" target="_blank">
                    <span class="ays-ask-question-content-inner-question-mark-text">?</span>
                    <span class="ays-ask-question-content-inner-hidden-text">Ask a question</span>
                </a>
            </div>
        </div>
        <!-- Ask a question box end -->
        
<?php
	}
}

run_poll_maker_ays();