<?php
$action   = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';
$heading  = '';

$id       = (isset($_GET['poll_category'])) ? absint(intval($_GET['poll_category'])) : null;
$default_message = 'The polls that belong to this category are expired or unpublished';
$category = array(
	'id'          => '',
	'title'       => '',
	'description' => '',
	'options'     => json_encode(array(
		'allow_skip'  => 'allow',
		'next_text'   => 'Next',
		'exp_message' => $default_message,
	)),
);
$loader_iamge = '';

switch ( $action ) {
	case 'add':
        $heading = __('Add new category', "poll-maker");
        $loader_iamge = "<span class='display_none'><img src=".POLL_MAKER_AYS_ADMIN_URL."/images/loaders/loading.gif></span>";
		break;
	case 'edit':
        $heading  = __('Edit category', "poll-maker");
        $loader_iamge = "<span class='display_none'><img src=".POLL_MAKER_AYS_ADMIN_URL."/images/loaders/loading.gif></span>";
		$category = $this->cats_obj->get_poll_category($id);
		break;
    default:
        break;
}

$next_poll_cat_id = "";
if ( isset( $id ) && !is_null( $id ) ) {
    $next_poll_cat_data = $this->get_next_or_prev_row_by_id( $id, "next", "ayspoll_categories" );
    $next_poll_cat_id = (isset( $next_poll_cat_data['id'] ) && $next_poll_cat_data['id'] != "") ? absint( $next_poll_cat_data['id'] ) : null;
}

$prev_poll_cat_id = "";
if ( isset( $id ) && !is_null( $id ) ) {
    $prev_poll_cat_data = $this->get_next_or_prev_row_by_id( $id, "prev", "ayspoll_categories" );
    $prev_poll_cat_id = (isset( $prev_poll_cat_data['id'] ) && $prev_poll_cat_data['id'] != "") ? absint( $prev_poll_cat_data['id'] ) : null;
}

$settings_options = $this->settings_obj->ays_get_setting('options');
if($settings_options){
    $settings_options = json_decode($settings_options, true);
}else{
    $settings_options = array();
}

$cat_opt = ( isset( $category['options'] ) && $category['options'] ) != '' ? $category['options'] : '';
$cat_opt = json_decode($cat_opt, true);

if (isset($_POST['ays_submit'])) {
	$this->cats_obj->add_edit_poll_category($_POST, $id);
} elseif (isset($_POST['ays_apply'])) {
	$this->cats_obj->add_edit_poll_category($_POST, $id, 'apply');
}

// Category expired message
$default_message = 'The polls that belong to this category are expired or unpublished';
$exp_message = (isset($cat_opt['exp_message']) && $cat_opt['exp_message'] != '') ? stripslashes(esc_attr($cat_opt['exp_message'])) : $default_message;

// Category previous button
$previous_button = isset($cat_opt['previous_text']) && $cat_opt['previous_text'] != '' ? esc_attr($cat_opt['previous_text']) : 'Previous';

//Category title
$category_title  = ( isset( $category['title'] ) && $category['title'] != '' ) ? stripslashes( esc_attr( $category['title'] ) ) : '';

//Category description
$category_description  = ( isset( $category['description'] ) && $category['description'] != '' ) ? stripslashes( esc_attr( $category['description']) ) : '';

// // WP Editor height
$poll_wp_editor_height = (isset($settings_options['poll_wp_editor_height']) && $settings_options['poll_wp_editor_height'] != '') ? absint( sanitize_text_field($settings_options['poll_wp_editor_height']) ) : 100 ;
?>
<div class="wrap">
    <div class="ays-poll-heading-box">
        <div class="ays-poll-wordpress-user-manual-box">
            <a href="https://ays-pro.com/wordpress-poll-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                <i class="ays_poll_fas ays_fa_file_text"></i>
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo __("View Documentation", "poll-maker"); ?></span>
            </a>
        </div>
    </div>
    <div class="container-fluid">
        <h1><?= $heading; ?></h1>
        <hr/>
        <form class="ays-poll-category-form" id="ays-poll-category-form" method="post">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for='ays-title'>
						<?= __('Title', "poll-maker"); ?>
                        <a class="ays_help"
                           data-toggle="tooltip"
                           data-placement="top"
                           title="<?= __('Write the name of the category.', "poll-maker"); ?>">
                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input class='ays-text-input ays-text-input-short'
                           id='ays-title'
                           name='ays_title'
                           type='text'
                           value='<?= $category_title; ?>'>
                </div>
            </div>
            <hr>
            <div class='ays-field'>
                <label for='ays-description'>
					<?= __('Description', "poll-maker"); ?>
                    <a class="ays_help"
                       data-toggle="tooltip"
                       data-placement="top"
                       title="<?= __('Provide more information about the poll category.', "poll-maker"); ?>">
                        <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                    </a>
                </label>
				<?php
				$content = $category_description;
				$editor_id = 'ays-description';
				$settings  = array(
					'editor_height' => $poll_wp_editor_height,
					'textarea_name' => 'ays_description',   
					'editor_class'  => 'ays-textarea',
					'media_buttons' => false
				);
				wp_editor($content, $editor_id, $settings);
				?>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for='ays-poll-skip'><?= __('Allow to skip polls', "poll-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" data-placement="top"
                           title="<?= __('If this option is enabled, the “Next” button will be available and the user can skip the poll and go forward.', "poll-maker"); ?>">
                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="checkbox" name="ays_poll_allow_skip" id="ays-poll-skip"
                           value="allow" <?= isset($cat_opt['allow_skip']) && $cat_opt['allow_skip'] == 'allow' ? 'checked' : ''; ?>>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for='ays_poll_next_text'><?= __('Next button text', "poll-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" data-placement="top" title="<?= __('Write your preferred text for the “Next” button.', "poll-maker"); ?>">
                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input class='ays-text-input ays-text-input-short' id='ays_poll_next_text' name='ays_poll_next_text'
                           type='text'
                           value='<?= empty($cat_opt['next_text']) ? 'Next' : $cat_opt['next_text']; ?>'>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for='ays_poll_previous_text'><?= __('Previous button text', "poll-maker"); ?><a
                                class="ays_help" data-toggle="tooltip" data-placement="top"
                                title="<?= __("Write your preferred text for the “Previous” button.", "poll-maker"); ?>">
                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                        </a></label>
                </div>
                <div class="col-sm-9">
                    <input class='ays-text-input ays-text-input-short'
                           id='ays_poll_previous_text'
                           name='ays_poll_previous_text'
                           type='text'
                           value='<?php echo $previous_button ?>'>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for='ays_poll_cat_message'><?= __('Message when no active polls found', "poll-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" data-placement="top" title="<?= __('The message will appear when all polls with this category are expired or unpublished.', "poll-maker"); ?>">
                            <i class="ays_poll_fas ays_poll_fa-info-circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <?php
                    $exp_message_content = $exp_message;
                    $editor_id = 'ays_poll_cat_message';
                    $settings  = array(
                        'editor_height' => $poll_wp_editor_height,
                        'textarea_name' => 'ays_poll_cat_message',   
                        'editor_class'  => 'ays-textarea',
                        'media_buttons' => false
                    );
                    wp_editor($exp_message_content, $editor_id, $settings);
                    ?>
                    <!-- <input class='ays-text-input ays-text-input-short' id='ays_poll_cat_message' name='ays_poll_cat_message' type='text' value='<?php // echo $exp_message; ?>'> -->
                </div>
            </div>
            <hr>
			<?php
			?>
            <div class="form-group row ays-poll-button-box">
                <div class="col-sm-8 ays-poll-button-first-row" style="padding: 0;">
                <?php
                    wp_nonce_field('poll_category_action', 'poll_category_action');
                    $other_attributes = array('id' => 'ays-button-cat');
                    submit_button(__('Save and close', "poll-maker"), 'primary', 'ays_submit', false, $other_attributes);
                    $save_bottom_attributes = array(
                        'id' => 'ays-button-apply',
                        'title' => 'Ctrl + s',
                        'data-toggle' => 'tooltip',
                        'data-delay'=> '{"show":"1000"}'
                    );
                    submit_button(__('Save', "poll-maker"), '', 'ays_apply', false, $save_bottom_attributes);
                    echo $loader_iamge;
                ?>
                </div>
                <div class="col-sm-4 ays-poll-button-second-row">
                <?php
                    if ( $prev_poll_cat_id != "" && !is_null( $prev_poll_cat_id ) ) {

                        $other_attributes = array(
                            'id' => 'ays-poll-category-next-button',
                            'data-message' => __( 'Are you sure you want to go to the previous poll category page?', "poll-maker"),
                            'href' => sprintf( '?page=%s&action=%s&poll_category=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $prev_poll_cat_id ) )
                        );
                        submit_button(__('Previous Poll Category', "poll-maker"), 'button ays-poll-next-prev-button-class ays-button ays-poll-category-prev-button', 'ays_poll_category_prev_button', false, $other_attributes);
                    }
                ?>
                <?php
                    if ( $next_poll_cat_id != "" && !is_null( $next_poll_cat_id ) ) {

                        $other_attributes = array(
                            'id' => 'ays-poll-category-next-button',
                            'data-message' => __( 'Are you sure you want to go to the next poll category page?', "poll-maker"),
                            'href' => sprintf( '?page=%s&action=%s&poll_category=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $next_poll_cat_id ) )
                        );
                        submit_button(__('Next Poll Category', "poll-maker"), 'button ays-poll-next-prev-button-class ays-button', 'ays_poll_category_next_button', false, $other_attributes);
                    }
                ?>
                </div>
            </div>
        </form>
    </div>
</div>