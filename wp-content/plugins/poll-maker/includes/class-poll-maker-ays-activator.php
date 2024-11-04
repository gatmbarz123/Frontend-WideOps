<?php
global $ays_poll_db_version;
$ays_poll_db_version = '1.8.7';
/**
 * Fired during plugin activation
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Poll_Maker_Ays
 * @subpackage Poll_Maker_Ays/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Poll_Maker_Ays
 * @subpackage Poll_Maker_Ays/includes
 * @author     Poll Maker Team <info@ays-pro.com>
 */
class Poll_Maker_Ays_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;
		$polls_table     = $wpdb->prefix . 'ayspoll_polls';
		$cats_table      = $wpdb->prefix . 'ayspoll_categories';
		$answers_table   = $wpdb->prefix . 'ayspoll_answers';
		$reports_table   = $wpdb->prefix . 'ayspoll_reports';
        $settings_table  = $wpdb->prefix . 'ayspoll_settings';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $polls_table (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				post_id INT(16) UNSIGNED DEFAULT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                question TEXT NOT NULL,
                type VARCHAR(32) NOT NULL,
                view_type VARCHAR(64) NOT NULL,
                categories VARCHAR(255) NOT NULL,
                image TEXT DEFAULT '',
                show_title INT(1) DEFAULT 1,
                styles TEXT DEFAULT '',
                custom_css TEXT DEFAULT '',
                theme_id INT(5) DEFAULT 1,
                PRIMARY KEY (id)
            )$charset_collate;";
		dbDelta($sql);
		$sql = "CREATE TABLE $cats_table (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                options TEXT DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";
		dbDelta($sql);
		$sql = "CREATE TABLE $answers_table (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                poll_id INT(11) UNSIGNED NOT NULL,
                answer TEXT DEFAULT '',
                votes INT(11) NOT NULL,
                ordering INT(11) NOT NULL DEFAULT 1,
                redirect TEXT DEFAULT '',
                user_added INT(1) DEFAULT 0,
                show_user_added INT(1) DEFAULT 1,
                answer_img TEXT DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";
		dbDelta($sql);
		$sql = "CREATE TABLE $reports_table (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                answer_id INT(11) UNSIGNED NOT NULL,
                user_ip VARCHAR(128) NOT NULL,
                user_id INT(11) DEFAULT 0,
                vote_date DATETIME NOT NULL,
				user_email VARCHAR(255) NOT NULL,
                unread  INT(1) DEFAULT 1,
                other_info  TEXT DEFAULT '',
				poll_id INT(11) UNSIGNED NOT NULL,
                multi_answer_ids  TEXT DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";
        dbDelta($sql);
            $sql = "CREATE TABLE $settings_table (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `meta_key` TEXT NULL DEFAULT NULL,
                `meta_value` TEXT NULL DEFAULT NULL,
                `note` TEXT NULL DEFAULT NULL,
                `options` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            )$charset_collate;";
		dbDelta($sql);

		// AV added Poll id for report table
		$answers_table = $wpdb->prefix . 'ayspoll_answers';
        $report_table = $wpdb->prefix . 'ayspoll_reports';

        $answ_id = $wpdb->get_results("SELECT DISTINCT answer_id FROM $report_table", 'ARRAY_A');
        if (isset($answ_id) && !empty($answ_id)) {
            $answer_ids = '';
            foreach ($answ_id as $key => $value) {
                if ($key == count($answ_id) - 1 ) {
                    $answer_ids .=  $value['answer_id'];
                }else{
                    $answer_ids .=  $value['answer_id'].',';
                }
            }
            $answ_poll_id = $wpdb->get_results("SELECT poll_id, id AS 'answ_id' FROM $answers_table WHERE id IN (".$answer_ids.")", 'ARRAY_A');

            if($answ_poll_id > 0){
                foreach ($answ_poll_id as $ap_key => $ap_value) {
                    $poll_result = $wpdb->update(
                        $report_table,
                        array(
                            'poll_id'     => $ap_value['poll_id']
                        ),
                        array('answer_id' => $ap_value['answ_id']),
                        array(
                            '%d'
                        ),
                        array('%d')
                    );
                }
            }
        }
	}

	private static function insert_default_values() {
		global $wpdb;
		$answers_table = $wpdb->prefix . 'ayspoll_answers';
		$polls_table   = esc_sql($wpdb->prefix . 'ayspoll_polls');
		$cats_table    = esc_sql($wpdb->prefix . 'ayspoll_categories');
		$settings_table = esc_sql($wpdb->prefix . "ayspoll_settings");
		$cat_count     = $wpdb->get_var("SELECT COUNT(*) FROM ".$cats_table);

		if ($cat_count == 0) {
			$wpdb->insert($cats_table, 
				array(
					'title' => 'Uncategorized', 
					'description' => 'Default poll category'
				),
				array( '%s', '%s' )
			);
		}

		$poll_create_author  = get_current_user_id();
        $user = get_userdata($poll_create_author);
        $poll_author = array();
        if ( ! is_null( $user ) && $user ) {
            $poll_author = array(
                'id' => $user->ID."",
                'name' => $user->data->display_name
            );
        }

		$author = json_encode($poll_author, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
		$author = str_replace('"', '\\"', $author);

		$polls_count = $wpdb->get_var("SELECT COUNT(*) FROM ".$polls_table);

		if ($polls_count == 0) {
			$wpdb->insert($polls_table, 
				array(
				'title'       => 'Default choosing',
				'description' => 'Default choosing type ',
				'question'    => 'Did you like our plugin?',
				'type'        => 'choosing',
				'categories'  => ',1,',
				'styles'      => '{"randomize_answers":"off","main_color":"#0C6291","text_color":"#0C6291","button_text_color":"#FBFEF9","button_bg_color":"#0C6291","icon_color":"#0C6291","icon_size":24,"width":0,"width_for_mobile":0,"poll_min_height":"","btn_text":"Vote","border_style":"ridge","border_radius":"0","border_width":"2","box_shadow_color":"#000000","poll_box_shadow_x_offset":0,"poll_box_shadow_y_offset":0,"poll_box_shadow_z_offset":15,"enable_background_gradient":"off","background_gradient_color_1":"#103251","background_gradient_color_2":"#607593","poll_gradient_direction":"vertical","poll_question_size_pc":"16","poll_question_size_mobile":"16","poll_question_image_height":"","poll_question_image_object_fit":"cover","poll_mobile_max_width":"","poll_buttons_size":"medium","poll_buttons_font_size":"17","poll_buttons_mobile_font_size":"17","poll_buttons_left_right_padding":"20","poll_buttons_top_bottom_padding":"10","poll_buttons_border_radius":"3","poll_buttons_width":"","enable_box_shadow":"off","bg_color":"#FBFEF9","answer_bg_color":"#FBFEF9","answer_border_side":"all_sides","answer_font_size":"16","poll_answer_font_size_mobile":"16","poll_answer_object_fit":"cover","poll_answer_padding":"10","poll_answer_margin":"10","poll_answer_border_radius":0,"poll_answer_icon_check":"off","poll_answer_icon":"radio","poll_answer_view_type":"list","poll_answer_enable_box_shadow":"off","poll_answer_box_shadow_color":"#000000","poll_answer_box_shadow_x_offset":0,"poll_answer_box_shadow_y_offset":0,"poll_answer_box_shadow_z_offset":10,"title_bg_color":"rgba(255,255,255,0)","poll_title_font_size":"20","poll_title_font_size_mobile":"20","bg_image":false,"enable_answer_style":"on","hide_results":0,"hide_result_message":0,"hide_results_text":"Thanks for your answer!","allow_not_vote":0,"show_social":0,"load_effect":"load_gif","load_gif":"plg_default","limit_users":0,"limitation_message":"","redirect_url":"","redirection_delay":0,"user_role":"","enable_restriction_pass":0,"restriction_pass_message":"","enable_logged_users":0,"enable_logged_users_message":"","notify_email_on":0,"notify_email":"","result_sort_type":"none","create_date":"' . current_time('mysql') . '","author":"' . $author . '","redirect_users":0,"redirect_after_vote_url":"","redirect_after_vote_delay":0,"published":1,"enable_pass_count":"on","activeInterval":"2019-05-30","activeIntervalSec":"","deactiveInterval":"2019-05-30","deactiveIntervalSec":"","active_date_message":"","active_date_check":"","enable_restart_button":0,"enable_vote_btn":1,"disable_answer_hover": 0,"logo_image":"","poll_enable_logo_url":"off","poll_logo_title":"","poll_logo_url":"","custom_class":"","enable_poll_title_text_shadow":"off","poll_title_text_shadow":"rgba(255,255,255,0)","poll_title_text_shadow_x_offset":2,"poll_title_text_shadow_y_offset":2,"poll_title_text_shadow_z_offset":0,"poll_allow_multivote":"off","multivote_answer_min_count":"1","poll_allow_multivote_count":"1","poll_direction":"ltr","show_create_date":0,"show_author":0,"ays_poll_show_timer":0,"ays_show_timer_type":"countdown","show_result_btn_see_schedule":"with_see","active_date_message_soon":"","show_result_btn_schedule":0,"dont_show_poll_cont":"off","see_res_btn_text":"See Results","enable_asnwers_sound":"off","poll_vote_reason":"off","enable_view_more_button":"off","poll_view_more_button_count":0,"answer_sort_type":"default","show_answers_numbering":"none","result_message":"","poll_social_buttons_heading":"","poll_show_social_ln":"on","poll_show_social_fb":"on","poll_show_social_tr":"on","poll_show_social_vk":"off","limit_users_method":"ip","show_votes_count":1,"show_res_percent":1,"show_login_form":"off","info_form":0,"fields":"apm_name,apm_email,apm_phone","required_fields":"apm_email","info_form_title":"","enable_mailchimp":"off","redirect_after_submit":0,"mailchimp_list":"","users_role":"[]","poll_bg_image_position":"center center","poll_bg_img_in_finish_page":"off","ays_add_post_for_poll":"off","result_in_rgba":"off","show_passed_users":"off","see_result_button":"on","see_result_radio":"ays_see_result_button","loader_font_size":"64","effect_message":"","poll_allow_collecting_users_data":"off","poll_every_answer_redirect_delay":"","poll_enable_answer_image_after_voting":"off","poll_enable_answer_redirect_delay":"off","poll_show_passed_users_count":3,"poll_allow_answer":"off","poll_allow_answer_require":"off","poll_answer_image_height":"150","poll_answer_image_height_for_mobile":"150","poll_answer_image_border_radius":"0","poll_title_alignment":"center","poll_text_type_length_enable":"off","poll_text_type_limit_type":"characters","poll_text_type_limit_length":"","poll_text_type_limit_message":"off","poll_text_type_placeholder":"Your answer","poll_text_type_width":"","poll_text_type_width_type":"percent","poll_enable_password":"off","poll_password":"","poll_enable_password_visibility":"off","poll_password_message":"","display_fields_labels":"off","autofill_user_data":"off","poll_logo_url_new_tab":"off","poll_create_author":' . $poll_create_author . ',"enable_social_links":"off","poll_social_links_heading":"","social_links":{"linkedin_link":"","facebook_link":"","twitter_link":"","vkontakte_link":"","youtube_link":""},"show_chart_type":"default_bar_chart","show_chart_type_google_height":"400","border_color":"#0C6291"}'
				),
				array( '%s', '%s', '%s', '%s', '%s', '%s' )
			);
			$last_insert = $wpdb->insert_id;
			$wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'It was a mistake'),array( '%d', '%s' ));
			$wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'There was nothing special'),array( '%d', '%s' ));
			$wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'Everything\'s ok'),array( '%d', '%s' ));
			$wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'I enjoyed it'),array( '%d', '%s' ));
			$wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'It\'s amazing'),array( '%d', '%s' ));

			// $wpdb->insert($polls_table, 
			// 	array(
			// 	'title'       => 'Default rating',
			// 	'description' => 'Default rating type ',
			// 	'question'    => 'Did you like our plugin?',
			// 	'type'        => 'rating',
			// 	'view_type'   => 'star',
			// 	'categories'  => ',1,',
			// 	'styles'      => '{"randomize_answers":"off","main_color":"#0C6291","text_color":"#0C6291","button_text_color":"#FBFEF9","button_bg_color":"#0C6291","icon_color":"#0C6291","icon_size":24,"width":0,"width_for_mobile":0,"poll_min_height":"","btn_text":"Vote","border_style":"ridge","border_radius":"0","border_width":"2","box_shadow_color":"#000000","poll_box_shadow_x_offset":0,"poll_box_shadow_y_offset":0,"poll_box_shadow_z_offset":15,"enable_background_gradient":"off","background_gradient_color_1":"#103251","background_gradient_color_2":"#607593","poll_gradient_direction":"vertical","poll_question_size_pc":"16","poll_question_size_mobile":"16","poll_question_image_height":"","poll_question_image_object_fit":"cover","poll_mobile_max_width":"","poll_buttons_size":"medium","poll_buttons_font_size":"17","poll_buttons_mobile_font_size":"17","poll_buttons_left_right_padding":"20","poll_buttons_top_bottom_padding":"10","poll_buttons_border_radius":"3","poll_buttons_width":"","enable_box_shadow":"off","bg_color":"#FBFEF9","answer_bg_color":"#FBFEF9","answer_border_side":"all_sides","answer_font_size":"16","poll_answer_font_size_mobile":"16","poll_answer_object_fit":"cover","poll_answer_padding":"10","poll_answer_margin":"10","poll_answer_border_radius":0,"poll_answer_icon_check":"off","poll_answer_icon":"radio","poll_answer_view_type":"list","poll_answer_enable_box_shadow":"off","poll_answer_box_shadow_color":"#000000","poll_answer_box_shadow_x_offset":0,"poll_answer_box_shadow_y_offset":0,"poll_answer_box_shadow_z_offset":10,"title_bg_color":"rgba(255,255,255,0)","poll_title_font_size":"20","poll_title_font_size_mobile":"20","bg_image":false,"enable_answer_style":"on","hide_results":0,"hide_result_message":0,"hide_results_text":"Thanks for your answer!","allow_not_vote":0,"show_social":0,"load_effect":"load_gif","load_gif":"plg_default","limit_users":0,"limitation_message":"","redirect_url":"","redirection_delay":0,"user_role":"","enable_restriction_pass":0,"restriction_pass_message":"","enable_logged_users":0,"enable_logged_users_message":"","notify_email_on":0,"notify_email":"","result_sort_type":"none","create_date":"' . current_time('mysql') . '","author":"' . $author . '","redirect_users":0,"redirect_after_vote_url":"","redirect_after_vote_delay":0,"published":1,"enable_pass_count":"on","activeInterval":"2019-05-30","activeIntervalSec":"","deactiveInterval":"2019-05-30","deactiveIntervalSec":"","active_date_message":"","active_date_check":"","enable_restart_button":0,"enable_vote_btn":1,"disable_answer_hover": 0,"logo_image":"","poll_enable_logo_url":"off","poll_logo_title":"","poll_logo_url":"","custom_class":"","enable_poll_title_text_shadow":"off","poll_title_text_shadow":"rgba(255,255,255,0)","poll_title_text_shadow_x_offset":2,"poll_title_text_shadow_y_offset":2,"poll_title_text_shadow_z_offset":0,"poll_allow_multivote":"off","multivote_answer_min_count":"1","poll_allow_multivote_count":"1","poll_direction":"ltr","show_create_date":0,"show_author":0,"ays_poll_show_timer":0,"ays_show_timer_type":"countdown","show_result_btn_see_schedule":"with_see","active_date_message_soon":"","show_result_btn_schedule":0,"dont_show_poll_cont":"off","see_res_btn_text":"See Results","enable_asnwers_sound":"off","poll_vote_reason":"off","enable_view_more_button":"off","poll_view_more_button_count":0,"answer_sort_type":"default","show_answers_numbering":"none","result_message":"","poll_social_buttons_heading":"","poll_show_social_ln":"on","poll_show_social_fb":"on","poll_show_social_tr":"on","poll_show_social_vk":"off","limit_users_method":"ip","show_votes_count":1,"show_res_percent":1,"show_login_form":"off","info_form":0,"fields":"apm_name,apm_email,apm_phone","required_fields":"apm_email","info_form_title":"","enable_mailchimp":"off","redirect_after_submit":0,"mailchimp_list":"","users_role":"[]","poll_bg_image_position":"center center","poll_bg_img_in_finish_page":"off","ays_add_post_for_poll":"off","result_in_rgba":"off","show_passed_users":"off","see_result_button":"on","see_result_radio":"ays_see_result_button","loader_font_size":"","effect_message":"","poll_allow_collecting_users_data":"off","poll_every_answer_redirect_delay":"","poll_enable_answer_image_after_voting":"off","poll_enable_answer_redirect_delay":"off","poll_show_passed_users_count":3,"poll_allow_answer":"off","poll_allow_answer_require":"off","poll_answer_image_height":"150","poll_answer_image_height_for_mobile":"150","poll_answer_image_border_radius":"0","poll_title_alignment":"center","poll_text_type_length_enable":"off","poll_text_type_limit_type":"characters","poll_text_type_limit_length":"","poll_text_type_limit_message":"off","poll_text_type_placeholder":"Your answer","poll_text_type_width":"","poll_text_type_width_type":"percent","poll_enable_password":"off","poll_password":"","poll_enable_password_visibility":"off","poll_password_message":"","display_fields_labels":"off","autofill_user_data":"off","poll_logo_url_new_tab":"off","enable_social_links":"off","poll_social_links_heading":"","social_links":{"linkedin_link":"","facebook_link":"","twitter_link":"","vkontakte_link":"","youtube_link":""},"show_chart_type":"default_bar_chart","border_color":"#0C6291"}'
			// 	),
			// 	array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
			// ); 
			// $last_insert = $wpdb->insert_id;
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => '1'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => '2'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => '3'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => '4'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => '5'),array( '%d', '%s' ));

			// $wpdb->insert($polls_table,
			// 	array(
			// 		'title'       => 'Demographic poll',
			// 		'description' => 'Demographic poll',
			// 		'question'    => 'Where are You from?',
			// 		'type'        => 'choosing',
			// 		'categories'  => ',1,',
			// 		'show_title'  => 1,
			// 		'styles'      => '{"randomize_answers":"off","main_color":"#FBFEF9","text_color":"#FBFEF9","button_text_color":"#222222","button_bg_color":"#FBFEF9","icon_color":"#FBFEF9","icon_size":24,"width":0,"width_for_mobile":0,"poll_min_height":"","btn_text":"Vote","border_style":"ridge","border_radius":"0","border_width":"","box_shadow_color":"","poll_box_shadow_x_offset":0,"poll_box_shadow_y_offset":0,"poll_box_shadow_z_offset":15,"enable_background_gradient":"off","background_gradient_color_1":"#103251","background_gradient_color_2":"#607593","poll_gradient_direction":"vertical","poll_question_size_pc":"16","poll_question_size_mobile":"16","poll_question_image_height":"","poll_question_image_object_fit":"cover","poll_mobile_max_width":"","poll_buttons_size":"medium","poll_buttons_font_size":"17","poll_buttons_mobile_font_size":"17","poll_buttons_left_right_padding":"20","poll_buttons_top_bottom_padding":"10","poll_buttons_border_radius":"3","poll_buttons_width":"","enable_box_shadow":"","bg_color":"#222222","answer_bg_color":"#222222","answer_border_side":"all_sides","answer_font_size":"16","poll_answer_font_size_mobile":"16","poll_answer_object_fit":"cover","poll_answer_padding":"10","poll_answer_margin":"10","poll_answer_border_radius":0,"poll_answer_icon_check":"off","poll_answer_icon":"radio","poll_answer_view_type":"list","poll_answer_enable_box_shadow":"off","poll_answer_box_shadow_color":"#000000","poll_answer_box_shadow_x_offset":0,"poll_answer_box_shadow_y_offset":0,"poll_answer_box_shadow_z_offset":10,"title_bg_color":"rgba(255,255,255,0)","poll_title_font_size":"20","poll_title_font_size_mobile":"20","bg_image":false,"enable_answer_style":"on","hide_results":0,"hide_result_message":0,"hide_results_text":"Thanks for your answer!","allow_not_vote":1,"show_social":1,"active_tab":"General","load_effect":"load_gif","load_gif":"plg_2","limit_users":0,"limitation_message":"","redirect_url":"","redirection_delay":0,"user_role":"","enable_restriction_pass":0,"restriction_pass_message":"","enable_logged_users":0,"enable_logged_users_message":"","notify_email_on":0,"notify_email":"","result_sort_type":"DESC","create_date":"' . current_time('mysql') . '","author":"' . $author . '","redirect_users":0,"redirect_after_vote_url":"","redirect_after_vote_delay":0,"published":1,"enable_pass_count":"on","activeInterval":"2019-05-30","activeIntervalSec":"","deactiveInterval":"2019-05-30","deactiveIntervalSec":"","active_date_message":"","active_date_check":"","enable_restart_button":1,"enable_vote_btn":0,"disable_answer_hover": 0,"logo_image":"","poll_enable_logo_url":"off","poll_logo_title":"","poll_logo_url":"","custom_class":"","enable_poll_title_text_shadow":"off","poll_title_text_shadow":"rgba(255,255,255,0)","poll_title_text_shadow_x_offset":2,"poll_title_text_shadow_y_offset":2,"poll_title_text_shadow_z_offset":0,"poll_allow_multivote":"off","multivote_answer_min_count":"1","poll_allow_multivote_count":"1","poll_direction":"ltr","show_create_date":0,"show_author":0,"ays_poll_show_timer":0,"ays_show_timer_type":"countdown","show_result_btn_see_schedule":"with_see","active_date_message_soon":"","show_result_btn_schedule":0,"dont_show_poll_cont":"off","see_res_btn_text":"See Results","enable_asnwers_sound":"off","poll_vote_reason":"off","enable_view_more_button":"off","poll_view_more_button_count":0,"answer_sort_type":"default","show_answers_numbering":"none","result_message":"","poll_social_buttons_heading":"","poll_show_social_ln":"on","poll_show_social_fb":"on","poll_show_social_tr":"on","poll_show_social_vk":"off","limit_users_method":"ip","show_votes_count":1,"show_res_percent":1,"show_login_form":"off","info_form":0,"fields":"apm_name,apm_email,apm_phone","required_fields":"apm_email","info_form_title":"","enable_mailchimp":"off","redirect_after_submit":0,"mailchimp_list":"","users_role":"[]","poll_bg_image_position":"center center","poll_bg_img_in_finish_page":"off","ays_add_post_for_poll":"off","result_in_rgba":"off","show_passed_users":"off","see_result_button":"on","see_result_radio":"ays_see_result_button","loader_font_size":"","effect_message":"","poll_allow_collecting_users_data":"off","poll_every_answer_redirect_delay":"","poll_enable_answer_image_after_voting":"off","poll_enable_answer_redirect_delay":"off","poll_show_passed_users_count":3,"poll_allow_answer":"off","poll_allow_answer_require":"off","poll_answer_image_height":"150","poll_answer_image_height_for_mobile":"150","poll_answer_image_border_radius":"0","poll_title_alignment":"center","poll_text_type_length_enable":"off","poll_text_type_limit_type":"characters","poll_text_type_limit_length":"","poll_text_type_limit_message":"off","poll_text_type_placeholder":"Your answer","poll_text_type_width":"","poll_text_type_width_type":"percent","poll_enable_password":"off","poll_password":"","poll_enable_password_visibility":"off","poll_password_message":"","display_fields_labels":"off","autofill_user_data":"off","poll_logo_url_new_tab":"off","enable_social_links":"off","poll_social_links_heading":"","social_links":{"linkedin_link":"","facebook_link":"","twitter_link":"","vkontakte_link":"","youtube_link":""},"show_chart_type":"default_bar_chart","border_color":"#FBFEF9"}',
			// 		'theme_id'    => 2
			// 	),
			// 	array( '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d' )
			// );
			// $last_insert = $wpdb->insert_id;
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'Asia'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'Africa'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'Europe'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'North America'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'South America'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => 'Australia/Oceania'),array( '%d', '%s' ));
			// $wpdb->insert($answers_table, array('poll_id' => $last_insert, 'answer' => '<b>Antarctica</b>'),array( '%d', '%s' ));
		}

		$metas = array(
            "mailchimp",
            "options",
			"fields_placeholders"
        );
        
        foreach($metas as $meta_key){
			$meta_val = esc_sql($meta_key);
			$sql = "SELECT COUNT(*) FROM ".$settings_table." WHERE meta_key = %s";
			$result = $wpdb->get_var(
	                    $wpdb->prepare( $sql, $meta_val)
	                  );
			if(intval($result) == 0){
		        $result = $wpdb->insert(
	                $settings_table,
	                array(
	                    'meta_key'    => $meta_val,
	                    'meta_value'  => "",
	                    'note'        => "",
	                    'options'     => ""
	                ),
	                array( '%s', '%s', '%s', '%s' )
	            );
	        }

		}
	}

	public static function ays_poll_update_db_check() {
		global $ays_poll_db_version;
		$is_plugin_downloaded = get_option('ays_poll_db_version', false) === false;

		if ($is_plugin_downloaded) {
			update_option('ays_poll_maker_first_time_activation_page', true);
			update_option('ays_poll_maker_poll_creation_challange', true);
		}

		if (get_site_option('ays_poll_db_version') != $ays_poll_db_version) {
			self::activate();
			update_option('ays_poll_db_version', $ays_poll_db_version);
			self::insert_default_values();
		}
	}
}