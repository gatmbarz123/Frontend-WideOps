<?php
$actions = $this->settings_obj;
$poll_actions = $this->polls_obj;

if (isset($_REQUEST['ays_submit'])) {
	$actions->store_data($_REQUEST);
}
if (isset($_GET['ays_poll_tab'])) {
	$ays_poll_tab = sanitize_text_field($_GET['ays_poll_tab']);
} else {
	$ays_poll_tab = 'tab1';
}
$db_data = $actions->get_db_data();

global $wp_roles;
$ays_users_roles = $wp_roles->role_names;

$mailchimp_res      = ($actions->ays_get_setting('mailchimp') === false) ? json_encode(array()) : $actions->ays_get_setting('mailchimp');
$mailchimp          = json_decode($mailchimp_res, true);
$mailchimp_username = isset($mailchimp['username']) ? esc_attr($mailchimp['username']) : '';
$mailchimp_api_key  = isset($mailchimp['apiKey']) ? esc_attr($mailchimp['apiKey']) : '';

$option_res         = ($actions->ays_get_setting('options') === false) ? json_encode(array()) : $actions->ays_get_setting('options');
$options            = json_decode($option_res, true);

$answers_sound = isset($options['answers_sound']) ? $options['answers_sound'] : '';

 //Poll title length
$poll_title_length = (isset($options['poll_title_length']) && intval($options['poll_title_length']) != 0) ? absint(intval($options['poll_title_length'])) : 5;

 //Poll Category title length
$poll_category_title_length = (isset($options['poll_category_title_length']) && intval($options['poll_category_title_length']) != 0) ? absint(intval($options['poll_category_title_length'])) : 5;

 //Poll Results title length
$poll_results_title_length = (isset($options['poll_results_title_length']) && intval($options['poll_results_title_length']) != 0) ? absint(intval($options['poll_results_title_length'])) : 5;

// Default Category for poll
$ays_default_cat =  isset($options['default_category']) && !empty($options['default_category']) ? explode("," , $options['default_category']) : array("1");

$poll_get_all_cats = $poll_actions->get_categories();

// Poll Types
$poll_default_types = array(
                        "choosing" => "Choosing",
                        "rating"   => "Rating",
                        "voting"   => "Voting",
                        "text"     => "Text"
                    );
$ays_default_type =  isset($options['default_type']) && $options['default_type'] != "" ? esc_attr($options['default_type']) : "choosing";

// Poll expired and unpublished message
$all_shortcode_message = isset($options['all_shortcode_message'])  && $options['all_shortcode_message'] != '' ? esc_attr($options['all_shortcode_message']) : '';

// Show result view
$poll_show_result_view = isset($options['show_result_view']) ? $options['show_result_view'] : 'standart';

// Animation Top 
$poll_animation_top = (isset($options['poll_animation_top']) && $options['poll_animation_top'] != '') ? absint(intval($options['poll_animation_top'])) : 100 ;
$options['poll_enable_animation_top'] = isset($options['poll_enable_animation_top']) ? $options['poll_enable_animation_top'] : 'on';
$poll_enable_animation_top = (isset($options['poll_enable_animation_top']) && $options['poll_enable_animation_top'] == "on") ? true : false;
//User History Page shortcode
$default_ays_poll_user_page_columns = array(
    'poll_name'  => 'Poll name',
    'vote_date'  => 'Vote Date',
    'vote_answer'=> 'Vote Answer'
);

// Fields placeholders | Start

$fields_placeholders_res      = ($actions->ays_get_setting('fields_placeholders') === false) ? json_encode(array()) : $actions->ays_get_setting('fields_placeholders');
$fields_placeholders          = json_decode( stripcslashes( $fields_placeholders_res ) , true);

$poll_fields_placeholder_name  = (isset($fields_placeholders['poll_fields_placeholder_name']) && $fields_placeholders['poll_fields_placeholder_name'] != '') ? stripslashes( esc_attr( $fields_placeholders['poll_fields_placeholder_name'] ) ) : 'Name';

$poll_fields_placeholder_email = (isset($fields_placeholders['poll_fields_placeholder_email']) && $fields_placeholders['poll_fields_placeholder_email'] != '') ? stripslashes( esc_attr( $fields_placeholders['poll_fields_placeholder_email'] ) ) : 'E-mail';

$poll_fields_placeholder_phone = (isset($fields_placeholders['poll_fields_placeholder_phone']) && $fields_placeholders['poll_fields_placeholder_phone'] != '') ? stripslashes( esc_attr( $fields_placeholders['poll_fields_placeholder_phone'] ) ) : 'Phone';

// Fields placeholders | End

// Fields labels | Start

$poll_fields_label_name  = (isset($fields_placeholders['poll_fields_label_name']) && $fields_placeholders['poll_fields_label_name'] != '') ? stripslashes( esc_attr( $fields_placeholders['poll_fields_label_name'] ) ) : 'Name';

$poll_fields_label_email = (isset($fields_placeholders['poll_fields_label_email']) && $fields_placeholders['poll_fields_label_email'] != '') ? stripslashes( esc_attr( $fields_placeholders['poll_fields_label_email'] ) ) : 'E-mail';

$poll_fields_label_phone = (isset($fields_placeholders['poll_fields_label_phone']) && $fields_placeholders['poll_fields_label_phone'] != '') ? stripslashes( esc_attr( $fields_placeholders['poll_fields_label_phone'] ) ) : 'Phone';


// Fields labels | End

// General CSS File
$options['poll_exclude_general_css'] = isset($options['poll_exclude_general_css']) ? esc_attr( $options['poll_exclude_general_css'] ) : 'off';
$poll_exclude_general_css = (isset($options['poll_exclude_general_css']) && esc_attr( $options['poll_exclude_general_css'] ) == "on") ? true : false;

// WP Editor height
$poll_wp_editor_height = (isset($options['poll_wp_editor_height']) && $options['poll_wp_editor_height'] != '' && $options['poll_wp_editor_height'] != 0) ? absint( sanitize_text_field($options['poll_wp_editor_height']) ) : 100 ;

?>
<div class="wrap" style="position:relative;">
    <div class="ays-poll-heading-box">
        <div class="ays-poll-wordpress-user-manual-box">
            <a href="https://ays-pro.com/wordpress-poll-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                <i class="ays_poll_fas ays_fa_file_text"></i>
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo __("View Documentation", "poll-maker"); ?></span>
            </a>
        </div>
    </div>
    <div class="container-fluid">
        <form method="post" class="ays-poll-general-settings-form" id="ays-poll-general-settings-form">
            <input type="hidden" name="ays_poll_tab" value="<?php echo htmlentities($ays_poll_tab); ?>" id="ays_poll_active_tab_results">
            <h1 class="wp-heading-inline">
				<?php
				echo __('Settings', "poll-maker");
				?>
            </h1>
			<?php
			if (isset($_REQUEST['status'])) {
                $actions->poll_settings_notices( sanitize_text_field( $_REQUEST['status'] ) );
			}
			?>
            <hr/>
            <div class="form-group ays-settings-wrapper">
                <div>
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1"
                           class="nav-tab <?php echo ($ays_poll_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
							<?php echo __("General", "poll-maker"); ?>
                        </a>
                        <a href="#tab2" data-tab="tab2"
                           class="nav-tab <?php echo ($ays_poll_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
							<?php echo __("Integrations", "poll-maker"); ?>
                        </a>
                        <a href="#tab3" data-tab="tab3"
                           class="nav-tab <?php echo ($ays_poll_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Shortcodes", "poll-maker"); ?>
                        </a>
                        <a href="#tab4" data-tab="tab4"
                           class="nav-tab <?php echo ($ays_poll_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Extra Shortcodes", "poll-maker"); ?>
                        </a>
                        <a href="#tab5" data-tab="tab5"
                           class="nav-tab <?php echo ($ays_poll_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Fields placeholders", "poll-maker"); ?>
                        </a>
                        <a href="#tab6" data-tab="tab6"
                           class="nav-tab <?php echo ($ays_poll_tab == 'tab6') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Message variables", "poll-maker"); ?>
                        </a>
                    </div>
                </div>
                <div class="ays-poll-tabs-wrapper">
                    <div id="tab1"
                         class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab1') ? 'ays-poll-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('General Settings', "poll-maker") ?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_poll_fa_question_circle"></i></strong>
                                <h5><?php echo __('Default parameters for Poll', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_disable_ip_storing">
                                        <?= __('Disable IP Storing', "poll-maker"); ?>
                                        <a class="ays_help" data-toggle="tooltip"
                                           data-placement="top"
                                           title="<?= __("After enabling this option, the IP address of the users will not be stored in the database.  Note: If this option is enabled, then the Limitation options connected with IP will not work.", "poll-maker"); ?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox"
                                           name="ays_disable_ip_storing"
                                           id="ays_disable_ip_storing"
                                           value="on" <?php echo (isset($options['disable_ip_storing']) && $options['disable_ip_storing'] == 'on') ? 'checked' : ''; ?>
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_answer_default_count">
                                        <?= __('Answer Default Count', "poll-maker"); ?>
                                        <a class="ays_help" data-toggle="tooltip"
                                           data-placement="top"
                                           title="<?= __("Define the default count for the answers which will be displayed in the Add new poll page. (This will work only with choosing type.)", "poll-maker"); ?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number"
                                           class="ays-text-input"
                                           name="ays_answer_default_count"
                                           min="2"
                                           id="ays_answer_default_count"
                                           value="<?php echo isset($options['answer_default_count']) ? $options['answer_default_count'] : 2; ?>"
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_default_cat">
                                        <?= __('Polls default category', "poll-maker"); ?>
                                        <a class="ays_help" data-toggle="tooltip"
                                           data-placement="top"
                                           title="<?= __("Choose the category of the poll which will be selected by default each time you create a poll by the Add New button.", "poll-maker"); ?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_poll_default_cat" name="ays_poll_default_cat[]" multiple>
                                            <?php
                                                $cat_content = "";
                                                $cat_selected = "";
                                                foreach($poll_get_all_cats as $key => $value){
                                                    $cat_id       = isset($value['id']) && $value['id'] != "" ? esc_attr($value['id']) : "";
                                                    $cat_title    = isset($value['title']) && $value['title'] != "" ? esc_attr($value['title']) : "";
                                                    $cat_selected = in_array( $cat_id , $ays_default_cat) ? "selected" : "";
                                                    $cat_content .= "<option value=".$cat_id." ".$cat_selected.">".$cat_title."</option>";
                                                }
                                                echo $cat_content;
                                            ?>
                                    </select>
                                </div>
                            </div> <!-- Default Category -->
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_default_type">
                                        <?= __('Polls default type', "poll-maker"); ?>
                                        <a class="ays_help" data-toggle="tooltip"
                                           data-placement="top"
                                           title="<?= __("Choose the type of the poll which will be selected by default each time you create a poll by the Add New button.", "poll-maker"); ?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_poll_default_type" name="ays_poll_default_type">
                                            <?php
                                                $type_content = "";
                                                $type_selected = "";
                                                foreach($poll_default_types as $type_key => $type_value){
                                                    $type_id       = isset($type_key) && $type_key != "" ? $type_key : "";
                                                    $type_title    = isset($type_value) && $type_value != "" ? $type_value : "";
                                                    $type_selected = ($ays_default_type == $type_id) ? "selected" : "";
                                                    $type_content .= "<option value=".$type_id." ".$type_selected.">".$type_title."</option>";
                                                }
                                                echo $type_content;
                                            ?>
                                    </select>
                                </div>
                            </div> <!-- Default Type -->
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_wp_editor_height">
                                        <?php echo __( "WP Editor height", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give the default value to the height of the WP Editor. It will apply to all WP Editors within the plugin on the dashboard.', "poll-maker"); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_poll_wp_editor_height" id="ays_poll_wp_editor_height" class="ays-text-input" value="<?php echo $poll_wp_editor_height; ?>">
                                </div>
                            </div><!-- WP Editor Height -->
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_poll_fa ays_poll_fa_music"></i></strong>
                                <h5><?php echo __('Poll answers sound', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_questions_default_type">
                                        <?php echo __( "Sound for answers", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The selected sound will be played in the poll if the Enable answers sound option is enabled from the particular poll settings.', "poll-maker"); ?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="ays-bg-music-container">
                                        <a class="add-poll-bg-music" href="javascript:void(0);"><?php echo __("Select sound", "poll-maker"); ?></a>
                                        <audio controls src="<?php echo $answers_sound; ?>"></audio>
                                        <input type="hidden" name="ays_poll_answers_sound" class="ays_poll_bg_music" value="<?php echo $answers_sound; ?>">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_poll_fas ays_poll_fa_text"></i></strong>
                                <h5><?php echo __('Excerpt words count in list tables', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_title_length">
                                        <?php echo __( "Polls list table", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the polls to be shown in the Polls List Table by putting your preferred count of words in the following field. For example: if you put 10,  you will see the first 10 words of each poll in the Polls page of your dashboard.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_poll_title_length" id="ays_poll_title_length" class="ays-text-input" value="<?php echo $poll_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_category_title_length">
                                        <?php echo __( "Categories list table", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the category to be shown in the Category List Table by putting your preferred count of words in the following field. For example: if you put 10,  you will see the first 10 words of each poll in the Polls page of your dashboard.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_poll_category_title_length" id="ays_poll_category_title_length" class="ays-text-input" value="<?php echo $poll_category_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_results_title_length">
                                        <?php echo __( "Results list table", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Results List Table by putting your preferred count of words in the following field. For example: if you put 10,  you will see the first 10 words of each poll in the Polls page of your dashboard.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_poll_results_title_length" id="ays_poll_results_title_length" class="ays-text-input" value="<?php echo $poll_results_title_length; ?>">
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_poll_fa ays_poll_fa_trash"></i></strong>
                                <h5><?php echo __('Erase Poll data', "poll-maker")?></h5>
                            </legend>
                            <?php if( isset( $_GET['del_stat'] ) ): ?>
                            <blockquote style="border-color:#46b450;background: rgba(70, 180, 80, 0.2);">
                                <?php echo __("Results up to a", "poll-maker") . " " . sanitize_text_field($_GET['mcount']) . " " . __("month ago deleted successfully.", "poll-maker"); ?>
                            </blockquote>
                            <hr>
                            <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_delete_results_by">
                                        <?php echo __( "Delete results older then 'X' the month", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify count of months and save changes. Attention! it will remove submissions older than specified months permanently.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" name="ays_delete_results_by" id="ays_delete_results_by" class="ays-text-input">
                                </div>
                                <div class="col-sm-4">
                                    <input type="submit" name="ays_submit" id="ays_submit" class="button button-primary ays-button" value="Apply changes">
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_poll_fas ays_poll_fa-code"></i></strong>
                                <h5><?php echo __('Animation Top', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_enable_animation_top">
                                        <?php echo __( "Enable animation", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable animation of the scroll offset of the poll container. It works when the poll container is visible on the screen partly and the user starts the poll and moves from one question to another.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_poll_enable_animation_top" id="ays_poll_enable_animation_top" value="on" <?php echo $poll_enable_animation_top ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_animation_top">
                                        <?php echo __( "Scroll offset(px)", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the scroll offset of the poll container after the animation starts. It works when the poll container is visible on the screen partly and the user starts the poll and moves from one question to another.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_poll_animation_top" id="ays_poll_animation_top" class="ays-text-input" value="<?php echo $poll_animation_top; ?>">
                                </div>
                            </div>                            
                        </fieldset> <!-- Animation Top -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_poll_fa ays_fa_file_code"></i></strong>
                                <h5><?php echo __('General CSS File', "poll-maker"); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_poll_exclude_general_css">
                                        <?php echo __( "Exclude general CSS file from home page", "poll-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If the option is enabled, then the poll general CSS file will not be applied to the home page. Please note, that if you have inserted the poll on the home page, then the option must be disabled so that the CSS File can normally work for that poll.', "poll-maker"); ?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_poll_exclude_general_css" id="ays_poll_exclude_general_css" value="on" <?php echo $poll_exclude_general_css ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- Exclude General CSS -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_poll_fa ays_poll_fa_globe"></i></strong>
                                <h5><?php echo __('Who will have permission to Poll menu', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Select user role", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Control and manage who can have access to the plugin from the dashboard.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select disabled name="ays_user_roles_poll[]" id="ays_user_roles_poll" class="ays_select2_pro_disabled" multiple>
                                                <option selected value="admin">Administrator</option>
                                            </select>
                                        </div>
                                        <blockquote style="margin-top: 5px;">
                                            <?php echo __( "Ability to manage Poll Maker plugin only for selected user roles.", "poll-maker" ); ?>
                                        </blockquote>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_poll_fas ays_poll_fa-check"></i></strong>
                                <h5><?php echo __("Auto approve user's request", "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                    <div class="pro_features pro_features_popup">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("Enable auto-approve", "poll-maker"); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/p8AcGSUeawY">
                                                <p>
                                                    <?php echo __("If you own a Membership website and want to grant access to your website visitors, to create polls from the front end, then, this feature is the best solution for you.", "poll-maker"); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("With the help of the Request Form shortcode, the users can create polls without having access to your Dashboard. Moreover, you can auto-approve users' requests to save more time.", "poll-maker"); ?>
                                                </p>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=pro-popup-auto-approve">
                                                <?php echo __("Upgrade PRO NOW", "poll-maker"); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_auto_approve">
                                                <?php echo __( "Enable auto-approve", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('If the option is enabled, the user requests from the Request Form shortcode will automatically be approved and added to the Polls page.', "poll-maker"); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="ays-poll-auto-approve">                                        
                                                <input type="checkbox" >
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-poll-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24.svg'?>">
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24_hover.svg'?>" class="ays-poll-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-poll-new-watch-video-button"><?php echo __("Watch Video", "poll-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div id="tab2"
                         class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab2') ? 'ays-poll-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Integrations', "poll-maker") ?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/mailchimp_logo.png" alt="">
                                <h5><?php echo __('MailChimp', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_mailchimp_username">
                                                <?php echo __('MailChimp Username', "poll-maker")?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   class="ays-text-input"
                                                   id="ays_mailchimp_username"
                                                   name="ays_mailchimp_username"
                                                   value="<?php echo $mailchimp_username; ?>"
                                            />
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_mailchimp_api_key">
                                                <?php echo __('MailChimp API Key', "poll-maker")?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   class="ays-text-input"
                                                   id="ays_mailchimp_api_key"
                                                   name="ays_mailchimp_api_key"
                                                   value="<?php echo $mailchimp_api_key; ?>"
                                            />
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo sprintf( __( "You can get your API key from your ", "poll-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "https://us20.admin.mailchimp.com/account/api/", __( "Account Extras menu", "poll-maker" ) ); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset><!-- Mailchimp integration -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/campaignmonitor_logo.png" alt="">
                                <h5><?php echo __('Campaign Monitor', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="ays_monitor_client">
                                                        Campaign Monitor <?= __('Client ID', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="ays-text-input"
                                                           name="ays_monitor_client"
                                                           value=""
                                                    >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="ays_monitor_api_key">
                                                        Campaign Monitor <?= __('API Key', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="ays-text-input"
                                                           name="ays_monitor_api_key"
                                                           value=""
                                                    >
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?= __("You can get your API key and Client ID from your Account Settings page", "poll-maker"); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset><!-- Campaign monitor integration PRO version!!! -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/zapier_logo.png" alt="">
                                <h5><?php echo __('Zapier', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="ays_zapier_hook">
                                                        <?= __('Zapier Webhook URL', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="ays-text-input"
                                                           name="ays_zapier_hook"
                                                           value=""
                                                    >
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php 
                                                    /* translators: anchor tag URL */
                                                    echo sprintf(__("If you do not have any ZAP created, go <a href='%s' target='_blank'>here</a>. Remember to choose Webhooks by Zapier as Trigger App.", "poll-maker"), "https://zapier.com/app/editor/"); 
                                                ?>
                                            </blockquote>
                                            <blockquote>
                                                <?= __("We will send you all data from poll information form with \"AysPoll\" key by POST method", "poll-maker"); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Zapier integration PRO version!!! -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/activecampaign_logo.png" alt="">
                                <h5><?php echo __('ActiveCampaign', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="ays_active_camp_url">
                                                        <?= __('API Access URL', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="ays-text-input"
                                                           name="ays_active_camp_url"
                                                           value=""
                                                    >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="ays_active_camp_api_key">
                                                        <?= __('API Access Key', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="ays-text-input"
                                                           name="ays_active_camp_api_key"
                                                           value=""
                                                    >
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?= __("Your API URL and Key can be found in your account on the My Settings page under the \"Developer\" tab", "poll-maker"); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Activecampaign integration PRO version!!! -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/slack_logo.png" alt="">
                                <h5><?php echo __('Slack', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <button id="slackInstructionsPopOver" type="button" class="btn btn-info"
                                                            data-toggle="popover"
                                                            title="<?= __("Slack Integration Setup Instructions", "poll-maker") ?>"><?= __("Instructions", "poll-maker") ?></button>
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="ays_slack_client">
                                                        <?= __('App Client ID', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="ays-text-input"
                                                           name="ays_slack_client"
                                                           value=""
                                                    >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label>
                                                        <?= __('Slack Authorization', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    
                                                        <span class="btn btn-success pointer-events-none">
                                                        <?= __("Authorized", "poll-maker") ?></span>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="ays_slack_secret">
                                                        <?= __('App Client Secret', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                           class="ays-text-input"
                                                           name="ays_slack_secret"
                                                           value="" >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label>
                                                        <?= __('App Access Token', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <button type="button"
                                                            class="btn btn-outline-secondary disabled"><?= __("Need Authorization", "poll-maker") ?>
                                                    </button>
                                                    <input type="hidden" id="ays_slack_token" name="ays_slack_token" value="">
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?= __("You can get your App Client ID and Client Secret from your App's the Basic Information page", "poll-maker"); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            </fieldset> <!-- Slack integration PRO version!!! -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/sheets_logo.png" alt="">
                                <h5><?php echo __('Google sheets', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <button id="googleInstructionsPopOver" type="button" class="btn btn-info" data-toggle="popover" title="" data-original-title="Google Integration Setup Instructions" aria-describedby="popover188544">Instructions</button>
                                                    <div class="d-none">
                                                        <p>1. <a href="https://console.developers.google.com/apis/credentials" target="_blank">Create</a> new Google Oauth cliend ID credentials (if you do not still have).</p>
                                                        <p>2. Choose the application type as <b>Web application</b></p>
                                                        <p>3. Add the following link in the <b>Authorized redirect URIs</b> field</p>
                                                        <p>
                                                            <code></code>
                                                        </p>
                                                        <p>4. Click on the <b>Create</b> button</p>
                                                        <p>5. Copy the <b>Your Cliend ID</b> from the opened popup and paste it in the Google Client ID field.Then click on the Sign In button to complete authorization</p>
                                                        <p>6. After the successful authorization,copy the <b>Your Client ID</b> and paste it in the Google Client ID field again. Also, copy the <b>Your Client Secret</b> and paste it in the Google Client Secret field.</p>
                                                        <p>7. Then click <b>Get token</b> button to get your token</p>
                                                        <p>8. If the token is given successfully, click on the <b>Save Changes</b> button.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="">
                                                        <?= __('Google Client ID', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input">
                                                </div>
                                            </div>
                                            <hr/>                               
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="">
                                                        <?= __('Google Sign in', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">                                       
                                                    <button type="button" class="btn btn-outline-secondary" >
                                                        <?= __("Sign in", "poll-maker") ?>
                                                    </button>                                       
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="">
                                                        <?= __('Google Client Secret', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input " readonly>
                                                </div>
                                            </div>                                
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="">
                                                        <?= __('Get token', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <button type="button" id=""
                                                                class="btn btn-outline-secondary">
                                                            <?= __("Get token", "poll-maker") ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Google sheet integration PRO version!!! -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/sendgrid_logo.png" alt="">
                                <h5><?php echo __('SendGrid', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="">
                                                        <?php echo __('SendGrid API Key', "poll-maker")?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" />
                                                </div>
                                            </div>
                                            <hr/>                                    
                                            <blockquote>
                                                <?php echo sprintf( __( "You can get your API key from ", "poll-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "https://app.sendgrid.com/settings/api_keys", "sendgrid.com" ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- SendGrid integration PRO version!!! -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/gamipress_logo.png" alt="">
                                <h5><?php echo __('GamiPress', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">     
                                            <blockquote>
                                                <?php echo __( "Install the GamiPress plugin to use the integration. Configure the settings from the Automatic Points Awards section from the GamiPres plugin.", "poll-maker" ); ?>
                                                <br>
                                                <?php echo __( "After enabling the integration, the Poll Maker will automatically be added to the event list.", "poll-maker" ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- GamiPress integration PRO version!!! -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/mad-mimi-logo-min.png" alt="">
                                <h5><?php echo __('Mad Mimi', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_poll_mad_mimi_user_name">
                                                        <?= __('Username', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input">
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_poll_mad_mimi_api_key">
                                                        <?= __('API Key', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset><!-- MAD MIMI PRO version!!!-->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/get_response.png" alt="">
                                <h5><?php echo __('GetResponse', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="">
                                                        <?= __('GetResponse API Key', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                            class="ays-text-input"
                                                    >
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo sprintf(__("You can get your API key from your <a href='#' target='_blank'>account</a>.", "poll-maker")); ?>
                                            </blockquote>
                                            <blockquote>
                                                <?php echo __("For security reasons, unused API keys expire after 90 days. When that happens, you’ll need to generate a new key.", "poll-maker") ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset><!-- GET RESPONSE -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/convertkit_logo.png" alt="">
                                <h5><?php echo __('ConvertKit', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <label for="">
                                                        ConvertKit <?= __('API Key', "poll-maker") ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        class="ays-text-input"
                                                    >
                                                </div>
                                            </div>                                
                                            <blockquote>
                                                <?php echo sprintf( __( "You can get your API Key from your ", "poll-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "#", "Account" ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset><!-- CONVERT KIT -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/aweber-logo.png" alt="">
                                <h5><?php echo __('Aweber', "poll-maker") ?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0">
                                <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group-row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <button id="aweberInstructionsPopOver" type="button" class="btn btn-info" data-original-title="Aweber Integration Setup Instructions" ><?php echo __('Instructions', "poll-maker"); ?></button>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_poll_aweber_client_id"><?php echo  __('Client ID', "poll-maker"); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_poll_aweber_client_id" name="ays_poll_aweber_client_id">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_poll_aweber_client_secret"><?php echo  __('Client Secret', "poll-maker"); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_poll_aweber_client_secret" name="ays_poll_aweber_client_secret">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                    <button type="submit" id="pollAweberOauth2" class="btn btn-outline-info"><?php echo __("Connect", "poll-maker"); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Aweber -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/mail_poet.png" alt="">
                                <h5><?php echo __('Mailpoet', "poll-maker") ?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0">
                                <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <blockquote class="error_message" style="width:95%;">
                                            <?php echo __('To enable the integration, please install the MailPoet plugin.' , "poll-maker");?>
                                        </blockquote>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Mailpoet -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/mycred_icon.png" alt="">
                                <h5><?php echo __('MyCred', "poll-maker") ?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0">
                                <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <blockquote class="error_message" style="width:95%;"> <?php echo __('To enable the integration, please install the MyCred plugin.' , "poll-maker"); ?> </blockquote>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- MyCred -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/integrations/klaviyo-logo.png" alt="">
                                <h5><?php echo __('Klaviyo', "poll-maker") ?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_poll_klaviyo_api_key"><?php echo __('API Key', "poll-maker")?></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_poll_klaviyo_api_key" >
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Klaviyo -->
                    </div>
                    <div id="tab3"
                         class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab3') ? 'ays-poll-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Shortcodes', "poll-maker") ?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                    <strong style="font-size:30px;">[ ]</strong>
                                    <h5 class="ays-subtitle"><?php echo __('Shortcode for all polls', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="all_ays_poll_shortcodes">
                                        <?php echo __("Shortcode for all polls ", "poll-maker"); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="all_ays_poll_shortcodes" id="all_ays_poll_shortcodes" onclick="this.setSelectionRange(0, this.value.length)"readonly="" class="ays-text-input" value='[ays_poll_all display="published/all"]'>                                        
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_poll_all_polls_shortcodes">
                                        <?php echo __("Message", "poll-maker"); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The notification message will be appeared when all polls of the given category expires or becomes unpublished.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input" name="ays_poll_all_polls_shortcodes" id="ays_poll_all_polls_shortcodes" value="<?php echo $all_shortcode_message;?>">                                        
                                </div>
                            </div>
                            <hr>
                            <blockquote>
                                <ul class="ays-poll-general-settings-blockquote-ul">
                                    <li>
                                        <?php echo __("Paste the shortcode into any of your posts to show all polls together.", "poll-maker"); ?>
                                    </li>
                                    <li>
                                        <?php
                                            /* translators: 1: opening b tag, 2: closing b tag */
                                            echo sprintf( __( "%1\$sDisplay%2\$s", "poll-maker" ) . ' - ' . __( 'Choose the method of displaying. Example: display="published".', "poll-maker" ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-poll-general-settings-ul'>
                                            <li>
                                                <?php
                                                    /* translators: 1: opening b tag, 2: closing b tag */
                                                    echo sprintf( __( "%1\$spublished%2\$s", "poll-maker" ) . ' - ' . __( 'If you set the method as published, it will show only published polls excluding expired polls.', "poll-maker" ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        /* translators: 1: opening b tag, 2: closing b tag */
                                                        __( "%1\$sall%2\$s", "poll-maker" ) . ' - ' . __( 'If you set the method as all, it will show all polls including expired polls.', "poll-maker" ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </blockquote>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                    <strong style="font-size:30px;">[ ]</strong>
                                    <h5 class="ays-subtitle"><?php echo __('Shortcode result by ID', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_poll_shortcodes_by_id">
                                        <?php echo __("Shortcode result by ID ", "poll-maker"); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="ays_poll_shortcodes_by_id" id="ays_poll_shortcodes_by_id" onclick="this.setSelectionRange(0, this.value.length)"readonly="" class="ays-text-input" value='[ayspoll_results id="Your Poll ID" recent="true/false"]'>
                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label>
                                        <?php echo __('Show results by', "poll-maker")?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select the way of displaying the results: Bar chart , Pie chart or Column Chart.', "poll-maker")?>">
                                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="d-flex">
                                        <div class="form-check form-check-inline ays_poll_loader">
                                            <label class="form-check-label ays_poll_check_label"
                                                    for="ays_poll_show_res_standart"> <?= __('Bar Chart', "poll-maker"); ?> </label>
                                            <input type="radio" id="ays_poll_show_res_standart" name="ays_poll_show_result_view"
                                                    value="standart" <?= ($poll_show_result_view == 'standart') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="form-check form-check-inline ays_poll_loader">
                                            <label class="form-check-label ays_poll_check_label"
                                                    for="ays_poll_show_res_pie_chart"> <?= __('Pie Chart', "poll-maker"); ?> </label>
                                            <input type="radio" id="ays_poll_show_res_pie_chart" name="ays_poll_show_result_view"
                                                    value="pie_chart" <?= ($poll_show_result_view == 'pie_chart') ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="form-check form-check-inline ays_poll_loader">
                                            <label class="form-check-label ays_poll_check_label"
                                                   for="ays_poll_show_res_column_chart"> <?= __('Column Chart', "poll-maker"); ?> </label>
                                            <input type="radio" id="ays_poll_show_res_column_chart" name="ays_poll_show_result_view"
                                                   value="column_chart" <?= ($poll_show_result_view == 'column_chart') ? 'checked' : ''; ?> />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <blockquote>
                                <?php echo __("This shortcode will help you to get the recent submitted poll. You will need to add the ID of the poll into the shortcode or choose the true option in the recent (in this case the ID will not be essential). By default it will be false.", "poll-maker"); ?>
                            </blockquote>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                    <strong style="font-size:30px;">[ ]</strong>
                                    <h5 class="ays-subtitle"><?php echo __('Recent Polls Settings', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_poll_shortcodes_recent_by_id">
                                        <?php echo __("Shortcode", "poll-maker"); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="ays_poll_shortcodes_recent_by_id" id="ays_poll_shortcodes_recent_by_id" onclick="this.setSelectionRange(0, this.value.length)"readonly="" class="ays-text-input" value='[ays_display_polls orderby="random/recent" count="5"]'>
                                        
                                </div>
                            </div>
                            <blockquote>
                                <ul class="ays-poll-general-settings-blockquote-ul">
                                    <li>
                                        <?php echo __("Copy the following shortcode, configure it based on your preferences and paste it into the post.", "poll-maker"); ?>
                                    </li>
                                    <ul class="ays-poll-general-settings-ul">
                                        <li>
                                            <?php
                                                /* translators: 1: opening b tag, 2: closing b tag */
                                                echo sprintf( __( "%1\$sRandom%2\$s", "poll-maker" ) . ' - ' . __( 'If you set the ordering method as random and gave a value to count option, then it will randomly display that given amount of polls from your created polls.', "poll-maker" ),
                                                    '<b>',
                                                    '</b>'
                                                );
                                            ?>
                                        </li>
                                        <li>
                                            <?php
                                                /* translators: 1: opening b tag, 2: closing b tag */
                                                echo sprintf( __( "%1\$sRecent%2\$s", "poll-maker" ) . ' - ' . __( 'If you set the ordering method as recent and gave a value to count option, then it will display that given amount of polls from your recently created polls.', "poll-maker" ),
                                                    '<b>',
                                                    '</b>'
                                                );
                                            ?>
                                        </li>
                                    </ul>
                                </ul>
                            </blockquote>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_poll_fa-list"></i></strong>
                                <h5><?php echo __('Poll Categories', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features pro_features_popup">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("Poll Categories", "poll-maker"); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/FQRFaszwTho">
                                                <p>
                                                    <?php echo __("Display all the polls of your desired category on one page with the Poll Categories Shortcode of the plugin.", "poll-maker"); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("By doing so, you will surely boost your website engagement and get instant feedback from poll takers.", "poll-maker"); ?>
                                                </p>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=pro-popup-poll-categories">
                                                <?php echo __("Upgrade PRO NOW", "poll-maker"); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <blockquote>
                                        <p style="margin:0;"><?php echo __( "Paste the shortcode into any of your posts to show all/random polls from the given category by list/grid view.", "poll-maker" ); ?></p>
                                    </blockquote>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_invidLead">
                                                <?php echo __( "Poll category shortcode", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post. Put the shortcode of your preferred category,  choose the method of displaying (all/random), and the design of the layout(list/grid). If you set the method as All, it will show all polls from the given category, if you set the method as Random, please give a value to count option too, and it will randomly display that given amount of polls from the given category.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_cat id="Your Poll Cat ID" display="all/random" count="5" layout="list/grid"]'>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-poll-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24.svg'?>">
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24_hover.svg'?>" class="ays-poll-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-poll-new-watch-video-button"><?php echo __("Watch Video", "poll-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('Global Leaderboard Settings', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features pro_features_popup">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("Global Leaderboard Settings", "poll-maker"); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/1DyCPpZIAR8">
                                                <p>
                                                    <?php echo __("Make the online voting process a competitive experience for your website visitors. Insert the Leaderboard shortcodes into your desired page or post to show the list of top users who passed the polls.", "poll-maker"); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("Website visitors will be more inclined to vote for polls, as their vote matters.", "poll-maker"); ?>
                                                </p>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=pro-popup-global-leaderboard-settings">
                                                <?php echo __("Upgrade PRO NOW", "poll-maker"); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <blockquote>
                                        <?php echo __( "Paste the shortcode into any of your posts to show the list of the top users who have passed your polls.", "poll-maker" ); ?>
                                    </blockquote>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_globLead">
                                                <?php echo __( "Global Leaderboard shortcode", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and paste it to any post/page to see the list of the top user’s who passed any poll.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input" value='[ays_poll_gleaderboard]'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_count">
                                                <?php echo __('Users count', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('How many users’ results will be shown in the leaderboard.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number"
                                                class="ays-text-input"                 
                                                name="ays_gleadboard_count">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_width">
                                                <?php echo __('Width', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('The width of the Leaderboard box. It accepts only numeric values. For 100% leave it blank.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number"
                                                class="ays-text-input"                 
                                                name="ays_gleadboard_width">
                                            <span style="display:block;" class="ays_poll_small_hint_text"><?php echo __("For 100% leave blank", "poll-maker");?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Users group by', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Select the way for grouping the results. If you want to make Leaderboard for logged in users, then choose ID. It will collect results by WP user ID. If you want to make Leaderboard for guests, then you need to choose Email and enable Information Form and Email, Name options from poll settings. It will group results by emails and display guests Names.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_poll_loader">
                                                <input type="radio" name="ays_gleadboard_orderby" value="id">
                                                <span><?php echo __( "ID", "poll-maker"); ?></span>
                                            </label>
                                            <label class="ays_poll_loader">
                                                <input type="radio" name="ays_gleadboard_orderby" value="email">
                                                <span><?php echo __( "Email", "poll-maker"); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_color">
                                                <?php echo __('Color', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Top color of the leaderboard', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="ays_gleadboard_color" data-alpha="true">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_custom_css">
                                                <?php echo __('Custom CSS', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="ays-textarea" name="ays_gleadboard_custom_css" cols="30"
                                                rows="10" style="height: 80px;"></textarea>
                                        </div>
                                    </div> <!-- Custom global leadboard CSS -->
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-poll-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24.svg'?>">
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24_hover.svg'?>" class="ays-poll-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-poll-new-watch-video-button"><?php echo __("Watch Video", "poll-maker"); ?></div>
                                    </div>
                                    <div class="ays-poll-center-big-main-button-box ays-poll-new-big-button-flex">
                                        <div class="ays-poll-center-big-watch-video-button-box">
                                            <div class="ays-poll-center-new-watch-video-demo-button">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24.svg'?>" class="ays-poll-new-button-img-hide">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24_hover.svg'?>" class="ays-poll-new-watch-video-button-hover">
                                                <?php echo __("Watch Video", "poll-maker"); ?>
                                            </div>
                                        </div>
                                        <div class="ays-poll-center-big-upgrade-button-box">
                                            <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                                <div class="ays-poll-center-new-big-upgrade-button">
                                                    <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>" class="ays-poll-new-button-img-hide">
                                                    <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "poll-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </fieldset>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('Global Leaderboard By Category Settings', "poll-maker")?></h5>
                            </legend>
                            <!-- shortcode  -->
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="">
                                                <?php echo __( "Shortcode", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Paste the shortcode into any of your posts or pages to show the list of the top users who have voted your polls. It will print the attempts count of each poll participant, as well.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"  value='[ays_poll_cat_gleaderboard id="Your Poll Category ID"]'>
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- users count  -->
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="">
                                                <?php echo __('Users count', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Specify how many users’ results to be shown on the leaderboard.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number"
                                                class="ays-text-input"
                                            />
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- witdth  -->
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="">
                                                <?php echo __('Width', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __("Set the width of the leaderboard's box. It accepts only numeric values. For 100%, leave the field blank.", "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number"
                                                class="ays-text-input"
                                            />
                                            <span style="display:block;" class="ays_poll_small_hint_text"><?php echo __("For 100% leave blank", "poll-maker");?></span>
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- users group by  -->
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Group users by', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Select the way for grouping the results.
                                                By ID - Choose by ID if you want to make a leaderboard for logged-in users. It will collect results by WordPress user ID.
                                                By  Email - Choose by email if you want to make the leaderboard for guests. In this case,  do not forget to activate the Information Form of the given polls.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_poll_loader">
                                                <input type="radio"/>
                                                <span><?php echo __( "ID", "poll-maker"); ?></span>
                                            </label>
                                            <label class="ays_poll_loader">
                                                <input type="radio"/>
                                                <span><?php echo __( "Email", "poll-maker"); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- color  -->
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="">
                                                <?php echo __('Color', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __("Choose the color of the leaderboard's box.", "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- custom css  -->
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_cat_gleadboard_custom_css">
                                                <?php echo __('Custom CSS', "poll-maker")?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Enter your own custom CSS code.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="ays-textarea" name="ays_poll_cat_gleadboard_custom_css" cols="30"
                                                rows="10" style="height: 80px;"></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <blockquote>
                                        <?php echo __( "Paste the shortcode into any of your posts or pages to show the list of the top users who have voted your polls. It will print the attempts count of each poll participant, as well.", "poll-maker" ); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-poll-center-big-main-button-box ays-poll-new-big-button-flex">
                                        <div class="ays-poll-center-big-main-button-box">
                                            <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                                <div class="ays-poll-center-new-big-upgrade-button">
                                                    <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>" class="ays-poll-new-button-img-hide">
                                                    <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "poll-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset><!-- Global leaderboard by category-->
                        <hr/>                     
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_poll_fa-poll"></i></strong>
                                <h5><?php echo __( 'Request Form', "poll-maker" )?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="">
                                                <?php echo __( "Shortcode", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it into your desired post. It will allow users to send a request for building a poll with simple settings (Poll title, question, answers). Find the list of the requests in the Requests page, which is located on the Poll Maker left navbar. For accepting the request, the admin needs to click on the Approve button next to the given poll.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_request_form]'>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                <hr/>
                                <blockquote>
                                    <p style="margin:0;"><?php echo __( "Ability to allow users to create a poll from the front-end.", "poll-maker" ); ?></p>
                                </blockquote>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User History Settings', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features pro_features_popup">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("User History Settings", "poll-maker"); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/195Y_5voqYU">
                                                <p>
                                                    <?php echo __("With the User History Settings Shortcode, you can display all votes of the current user on one page.", "poll-maker"); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("This is the best way to learn how the voting history of the current user has changed over time.", "poll-maker"); ?>
                                                </p>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=pro-popup-user-history">
                                                <?php echo __("Upgrade PRO NOW", "poll-maker"); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="">
                                                <?php echo __( "Shortcode", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('Paste the shortcode into any of your posts or pages to show the current user’s votes history. Each user will see individually presented content based on their taken polls.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_user_history]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "User history results table columns", "poll-maker" ); ?>
                                                <a class="ays_help ays-poll-zindex-for-pro-tooltip" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-history-table-wrap">
                                                <ul class="ays-show-user-history-table">
                                                    <?php
                                                        foreach ($default_ays_poll_user_page_columns as $key => $val) {
                                                            ?>
                                                            <li class="ays-user-history-option-row ui-state-default">
                                                                <input type="checkbox" id="ays_show_<?php echo $key; ?>" value="<?php echo $key; ?>" class="ays-checkbox-input"/>
                                                                <label for="ays_show_<?php echo $key; ?>">
                                                                    <?php echo $val; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-poll-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24.svg'?>">
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24_hover.svg'?>" class="ays-poll-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-poll-new-watch-video-button"><?php echo __("Watch Video", "poll-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset><!-- User history shortcode -->
                        <hr>
                        <!-- Show all result start -->
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('All Results Settings', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features pro_features_popup">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("All Results Settings", "poll-maker"); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/bvY8hVmIGOA">
                                                <p>
                                                    <?php echo __("With the help of the All Results Settings Shortcode, you can display all poll results on one page. By this, you will surely boost your website engagement.", "poll-maker"); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("The website visitors will see all the poll results on one page and pass them without any difficulties.", "poll-maker"); ?>
                                                </p>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=pro-popup-all-results-settings">
                                                <?php echo __("Upgrade PRO NOW", "poll-maker"); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_all_results">
                                                <?php echo __( "Shortcode", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and insert it to any post to show all results.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_all_results]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="">
                                                <?php echo __( "Show to guests too", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the All results table to guests as well. By default, it is displayed only for logged-in users. If this option is disabled, then only the logged-in users will be able to see the table. Note: Despite the fact of showing the table to the guests, the table will contain only info of the logged-in users.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" checked/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "All results tables columns", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.', "poll-maker")?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-poll-all-results-table-wrap">
                                                <ul class="ays-poll-all-results-table">
                                                    <li class="ays-poll-all-results-table-row ui-state-default">
                                                        <input type="checkbox" class="ays-checkbox-input" checked ?>
                                                        <label>User name</label>
                                                    </li>
                                                    <li class="ays-poll-all-results-table-row ui-state-default">
                                                        <input type="checkbox" class="ays-checkbox-input" checked ?>
                                                        <label>Poll name</label>
                                                    </li>
                                                    <li class="ays-poll-all-results-table-row ui-state-default">
                                                        <input type="checkbox" class="ays-checkbox-input" checked ?>
                                                        <label>Vote date</label>
                                                    </li>
                                                    <li class="ays-poll-all-results-table-row ui-state-default">
                                                        <input type="checkbox" class="ays-checkbox-input" checked ?>
                                                        <label>Vote answer</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-poll-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24.svg'?>">
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24_hover.svg'?>" class="ays-poll-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-poll-new-watch-video-button"><?php echo __("Watch Video", "poll-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <!-- Show all result end -->
                        <fieldset>
                            <legend>
                                    <strong style="font-size:30px;">[ ]</strong>
                                    <h5 class="ays-subtitle"><?php echo __('Frontend Statistics', "poll-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin: 0px;">
                                <div class="col-sm-12 only_pro" style="padding:10px;">
                                    <div class="pro_features pro_features_popup">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("Frontend Statistics", "poll-maker"); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/9ke489iHjHg">
                                                <p>
                                                    <?php echo __("Analytics is the most important part of conducting online polls. With the help of the Frontend Statistics Shortcode, you can display daily, weekly, monthly, and overall statistics of your polls.", "poll-maker"); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("This will help you decrease the workload as you will see the statistics of your users in one place.", "poll-maker"); ?>
                                                </p>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=pro-popup-frontend-statistics">
                                                <?php echo __("Upgrade PRO NOW", "poll-maker"); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row"> 
                                        <div class="col-sm-3">
                                            <label for="ays_poll_shortcodes_recent_by_id">
                                                <?php echo __("Shortcode", "poll-maker"); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="ays_poll_shortcodes_recent_by_id" onclick="this.setSelectionRange(0, this.value.length)"readonly="" class="ays-text-input" value='[ays_poll_frontend_statistics id="Your_Poll_ID"]'>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <ul class="ays-poll-general-settings-blockquote-ul">
                                            <li>
                                                <?php echo __("Copy and paste this shortcode into any post or page, and insert the Poll ID, which statistics you want to display.", "poll-maker"); ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/poll-maker" target="_blank" class="ays-poll-new-upgrade-button-link">
                                        <div class="ays-poll-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/locked_24x24.svg'?>">
                                                <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/unlocked_24x24.svg'?>" class="ays-poll-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-poll-new-upgrade-button"><?php echo __("Upgrade", "poll-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-poll-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24.svg'?>">
                                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL.'/images/icons/pro-features-icons/video_24x24_hover.svg'?>" class="ays-poll-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-poll-new-watch-video-button"><?php echo __("Watch Video", "poll-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset><!-- Frontend statistics shortcode -->
                    </div>
                    <div id="tab4" class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab4') ? 'ays-poll-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Extra Shortcodes', "poll-maker") ?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Extra shortcodes', "poll-maker"); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_passed_users_count">
                                                <?php echo __( "Passed users count", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it in posts. Insert the Poll ID to receive the number of participants of the poll.', "poll-maker"); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_passed_users_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_passed_users_count id="Your_Poll_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_user_first_name">
                                                <?php echo __( "Show User First Name", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's First Name. If the user is not logged-in, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_user_first_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_user_first_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_user_last_name">
                                                <?php echo __( "Show User Last Name", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Last Name. If the user is not logged-in, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_user_last_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_user_last_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_user_display_name">
                                                <?php echo __( "Show User Display name", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Display name. If the user is not logged-in, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_user_display_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_user_display_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_user_email">
                                                <?php echo __( "Show User Email", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Email. If the user is not logged-in, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_user_email" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_user_email]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_user_email">
                                                <?php echo __( "Show poll creation date", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Poll ID in the shortcode. It will show the creation date of the particular poll. If there is no poll available/found with that particular Poll ID, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_user_email" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_creation_date id="Your_Poll_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_user_passed_polls_count">
                                                <?php echo __( "Passed polls count per user", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the number of passed polls of the current user. For instance, the current user has passed 20 polls. If the user is not logged in shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_user_passed_polls_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_user_passed_polls_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_user_all_passed_polls_count">
                                                <?php echo __( "All passed polls count per user", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the total sum of how many times the particular user has passed all the polls. For instance, the current user has passed 20 polls 500 times in total. If the user is not logged in shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_user_all_passed_polls_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_user_all_passed_polls_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_category_description">
                                                <?php echo __( "Show poll categories descriptions", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Poll ID in the shortcode. It will show categories descriptions of the particular poll. If there is no poll available/found with that particular Poll ID, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_categories_descriptions" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_categories_descriptions id="Your_POLL_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_category_title">
                                                <?php echo __( "Show poll categories titles", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Poll ID in the shortcode. It will show categories titles of the particular poll. If there is no poll available/found with that particular Poll ID, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_categories_titles" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_categories_titles id="Your_POLL_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_current_author">
                                                <?php echo __( "Show current poll author", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Poll ID in the shortcode. It will show the current author of the particular poll. If there is no poll or questions available/found with that particular Poll ID, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_current_author" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_current_author id="Your_POLL_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_poll_answers_count">
                                                <?php echo __( "Show poll answers count", "poll-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Poll ID in the shortcode. It will show the total count of answers in particular poll. If there is no poll available/found with that particular Poll ID, the shortcode will be empty.", "poll-maker") ); ?>">
                                                    <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_poll_answers_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_poll_answers_count id="Your_POLL_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Extra shortcodes -->
                    </div>
                    <div id="tab5" class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab5') ? 'ays-poll-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Fields placeholders', "poll-maker")?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p style='margin-bottom:3px;'><?php echo __( 'If you make a change here, these words will not be translated either․', "poll-maker" ); ?>">
                                <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p>
                                <?php echo __( "With the help of this section, you can change the fields' placeholders of the Information form. Find the available fields in the User data tab of your polls.", "poll-maker" ); ?>
                                <span class="ays-poll-blockquote-span"><?php echo __( "Note: If you make a change here, these words will not be translated either.", "poll-maker" ); ?></span>
                            </p>

                        </blockquote>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-3">
                                <span class="ays-poll-title-fields-placeholders"><?php echo __( "Placeholders", "poll-maker" ); ?></span>
                            </div>
                            <div class="col-sm-7">
                                <span class="ays-poll-title-fields-placeholders"><?php echo __( "Labels", "poll-maker" ); ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_poll_fields_placeholder_name">
                                    <?php echo __( "Name", "poll-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ays_poll_fields_placeholder_name" name="ays_poll_fields_placeholder_name" class="ays-text-input ays-text-input-short"  value='<?php echo $poll_fields_placeholder_name; ?>'>
                                <span class="ays_poll_small_hint_text ays-poll-small-hint-fields-placeholders">Placeholder</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="ays_poll_fields_label_name" name="ays_poll_fields_label_name" class="ays-text-input ays-text-input-short"  value='<?php echo $poll_fields_label_name; ?>'>
                                <span class="ays_poll_small_hint_text ays-poll-small-hint-fields-placeholders">Label</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_poll_fields_placeholder_email">
                                    <?php echo __( "Email", "poll-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ays_poll_fields_placeholder_email" name="ays_poll_fields_placeholder_email" class="ays-text-input ays-text-input-short"  value='<?php echo $poll_fields_placeholder_email; ?>'>
                                <span class="ays_poll_small_hint_text ays-poll-small-hint-fields-placeholders">Placeholder</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="ays_poll_fields_label_email" name="ays_poll_fields_label_email" class="ays-text-input ays-text-input-short"  value='<?php echo $poll_fields_label_email; ?>'>
                                <span class="ays_poll_small_hint_text ays-poll-small-hint-fields-placeholders">Label</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_poll_fields_placeholder_phone">
                                    <?php echo __( "Phone", "poll-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ays_poll_fields_placeholder_phone" name="ays_poll_fields_placeholder_phone" class="ays-text-input ays-text-input-short"  value='<?php echo $poll_fields_placeholder_phone; ?>'>
                                <span class="ays_poll_small_hint_text ays-poll-small-hint-fields-placeholders">Placeholder</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="ays_poll_fields_label_phone" name="ays_poll_fields_label_phone" class="ays-text-input ays-text-input-short"  value='<?php echo $poll_fields_label_phone; ?>'>
                                <span class="ays_poll_small_hint_text ays-poll-small-hint-fields-placeholders">Label</span>
                            </div>
                        </div>
                    </div>
                    <div id="tab6" class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab6') ? 'ays-poll-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Message variables', "poll-maker")?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p><?php echo __( 'You can copy these variables and paste them in the following options from the poll settings', "poll-maker" ); ?>:</p>
                                <ul class='ays_tooltop_ul'>
                                    <li><?php echo __( 'Result Message', "poll-maker" ); ?></li>
                                    <li><?php echo __( 'Hide results', "poll-maker" ); ?></li>
                                </ul>">
                                <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p><?php echo __( "You can copy these variables and paste them in the following options from the poll settings", "poll-maker" ); ?>:</p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Result Message", "poll-maker" ); ?></p>                            
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Hide results", "poll-maker" ); ?></p>                            
                        </blockquote>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_name%%"  class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The name the user entered into information form", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">                                
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_email%%"  class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The E-mail the user entered into information form", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">                                
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_phone%%"  class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The phone the user entered into information form", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">   
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%poll_title%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The title of the poll.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%users_first_name%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's first name that was filled in their WordPress site during registration.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%users_last_name%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's last name that was filled in their WordPress site during registration.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%creation_date%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The creation date of the poll.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_poll_author%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "It will show the author of the current poll.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_roles%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's role(s) when logged-in. In case the user is not logged-in, the field will be empty.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_email%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's email that was filled in their WordPress profile.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_display_name%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's display name that was filled in their WordPress profile.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_nickname%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's nickname that was filled in their WordPress profile.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_ip_address%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's IP address.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%poll_pass_count%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The poll's pass count.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%passed_poll_count_per_user%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "Passed polls count per user.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_poll_page_link%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "Prints the webpage link where the current poll is posted.", "poll-maker"); ?>
                                    </span>
                                </p>
                                <hr class="ays-poll-message-variables-text-divider-for-mobile">
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_website%%" class='ays-poll-message-variables-inputs'/>
                                    </strong>
                                    <span class="ays-poll-message-variables-text-divider"> - </span>
                                    <span class="ays-poll-message-variables-hint-text">
                                        <?php echo __( "The user's website that was filled in their WordPress profile.", "poll-maker"); ?>
                                    </span>
                                </p>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="ays-settings-form-save-button-wrap">
			<?php
            $loader_iamge = "<span class='display_none'><img src=".POLL_MAKER_AYS_ADMIN_URL."/images/loaders/loading.gif></span>";
			wp_nonce_field('settings_action', 'settings_action');
            $save_bottom_attributes = array(
                'id' => 'ays-button-apply',
                'title' => 'Ctrl + s',
                'data-toggle' => 'tooltip',
                'data-delay'=> '{"show":"1000"}'
            );
			submit_button(__('Save changes', "poll-maker"), 'primary ays-button', 'ays_submit', false, $save_bottom_attributes);
            echo $loader_iamge;
			?>
            </div>
        </form>
    </div>
    <div class="ays-modal" id="pro-features-popup-modal">
        <div class="ays-modal-content">
            <!-- Modal Header -->
            <div class="ays-modal-header">
                <span class="ays-close-pro-popup">&times;</span>
                <!-- <h2></h2> -->
            </div>

            <!-- Modal body -->
            <div class="ays-modal-body">
                <div class="row">
                    <div class="col-sm-6 pro-features-popup-modal-left-section">
                    </div>
                    <div class="col-sm-6 pro-features-popup-modal-right-section">
                        <div class="pro-features-popup-modal-right-box">
                            <div class="pro-features-popup-modal-right-box-icon"><i class="ays_poll_fa ays_poll_fa-lock"></i></div>

                            <div class="pro-features-popup-modal-right-box-title"></div>

                            <div class="pro-features-popup-modal-right-box-content"></div>

                            <div class="pro-features-popup-modal-right-box-button">
                                <a href="#" class="pro-features-popup-modal-right-box-link" target="_blank"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="ays-modal-footer" style="display:none">
            </div>
        </div>
    </div>
</div>