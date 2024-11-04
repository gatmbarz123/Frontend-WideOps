<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Poll_Maker_Ays
 * @subpackage Poll_Maker_Ays/admin/partials
 */

$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
$id = isset($_GET['poll']) ? absint($_GET['poll']) : null;
if ($action == 'duplicate' && $id != null) {
	$this->polls_obj->duplicate_poll($id);
}
$poll_max_id = Poll_Maker_Ays_Admin::get_max_id('polls');

$plus_icon_svg = "<span class=''><img src='". POLL_MAKER_AYS_ADMIN_URL ."/images/icons/add-new.svg'></span>";

$quick_poll_plugin_nonce = wp_create_nonce( 'poll-maker-ajax-quick-poll-nonce' );
?>

<div class="wrap ays-poll-list-table ays_polls_list_table">
    <div class="ays-poll-heading-box">
        <div class="ays-poll-wordpress-user-manual-box">
            <a href="https://ays-pro.com/wordpress-poll-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                <i class="ays_poll_fas ays_fa_file_text"></i>
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo __("View Documentation", "poll-maker"); ?></span>
            </a>
        </div>
    </div>
    <h1 class="wp-heading-inline">
		<?php
		echo esc_html(get_admin_page_title());
        ?>
    </h1>
    <div class='heading_buttons_container'>
        <div class='ays-poll-add-new-button-box'>
            <?php
                echo sprintf('<a href="?page=%s&action=%s" class="page-title-action button-primary ays-poll-add-new-button ays-poll-add-new-button-new-design"> %s ' . __('Add New', "poll-maker") . '</a>', esc_attr($_REQUEST['page']), 'add', $plus_icon_svg);
            ?>
        </div>
        <div class="create_quick_poll_container">
            <button class="create_quick_poll" id="ays_create_quick_poll" title="<?php echo __( "Create Quick Poll", "poll-maker" ); ?>"><img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/icons/icon-128x128.png' ?>" alt="Create Quick Poll"></button> 
        </div>
    </div>
    <div class="ays_poll_modal" id="ays-poll-quick-create" style='display:none'>
        <!-- Modal content -->
        <div class="ays_poll_modal_content ays-modal-content fadeInDown" id="ays-poll-quick-create-content">
            <div class="ays-modal-header">
                <h4><?php echo __('Build your poll in a few minutes', "poll-maker"); ?></h4>
                <span class="ays-close-quick-create">&times;</span>
            </div> 
            <div class="ays-modal-quick-poll-content">
                <form method="POST" id="ays-quick-poll-form">
                    <!-- Title  -->
                    <div class="ays-modal-poll-title-add">
                        <span><label for="ays-quick-poll-title"><?php echo __('Poll Title', "poll-maker"); ?></label></span>
                        <input type="text" name="ays-poll-title" id="ays-quick-poll-title" data-required="true">
                    </div>
                    <!-- Question -->
                    <div class="ays-modal-poll-question-add">
                        <span><label for="ays-quick-poll-question"><?php echo __('Question', "poll-maker"); ?></label></span>
                        <textarea name="ays_poll_question" id="ays-quick-poll-question" placeholder="<?php echo __('Ask a question*', "poll-maker"); ?>" rows="3"></textarea>
                    </div>
                    <!-- Answers -->
                    <div class="ays-modal-poll-answers-section">
                        <span><label for="quick_poll_answer-1"><?php echo __('Answers', "poll-maker"); ?></label></span>
                        <div class="quick_poll_answer_box">
                            <input class="quick_poll_answer" name="ays-poll-answers[]" data-id="1" placeholder="<?php echo __('Option*', "poll-maker"); ?>">
                            <button type="button" class="quick_poll_answer_remove"><img src="<?php echo (POLL_MAKER_AYS_ADMIN_URL . '/images/remove-normal.png')?>" alt="remove" width="20px"></button>
                        </div>
                        <div class="quick_poll_answer_box">
                            <input class="quick_poll_answer" name="ays-poll-answers[]" data-id="2" placeholder="<?php echo __('Option*', "poll-maker"); ?>">
                            <button type="button" class="quick_poll_answer_remove"><img src="<?php echo (POLL_MAKER_AYS_ADMIN_URL . '/images/remove-normal.png')?>" alt="remove" width="20px"></button>
                        </div>
                        <div class="quick_poll_answer_box">
                            <input class="quick_poll_answer" name="ays-poll-answers[]" data-id="3" placeholder="<?php echo __('Option*', "poll-maker"); ?>">
                            <button type="button" class="quick_poll_answer_remove"><img src="<?php echo (POLL_MAKER_AYS_ADMIN_URL . '/images/remove-normal.png')?>" alt="remove" width="20px"></button>
                        </div>
                        <button type="button" class="quick_poll_add_option"><div><span>+</span></div><?php echo __('Add an Option', "poll-maker"); ?></button>
                    </div>
                    <div class="quick_poll_divider"></div>
                    <!-- Settings -->
                    <div class="quick_poll_settings">
                        <h4><?php echo __('Settings', "poll-maker"); ?></h4>
                        <!-- Allow not to vote -->
                        <div>
                            <span><?php echo __('Allow not to vote', "poll-maker"); ?></span>
                            <input type="checkbox" id="allow_not_to_vote" name="allow-not-vote" >
                            <label for="allow_not_to_vote">Toggle</label>
                        </div>
                        <!-- Allow multivote -->
                        <div class='multivote-container'>
                            <div>
                                <span><?php echo __('Allow multivote', "poll-maker"); ?></span>
                                <input type="checkbox" id="allow_multivote_switch" name="allow_multivote_switch">
                                <label for="allow_multivote_switch">Toggle</label>
                            </div>
                            <!-- Multivote Settings -->
                            <div class="quick_poll_multivote_settings">
                                <input type="number" id="quick-poll-multivote-min-count" name="quick-poll-multivote-min-count" placeholder="<?php echo __('Min', "poll-maker"); ?>">
                                <input type="number" id="quick-poll-multivote-max-count" name="quick-poll-multivote-max-count" placeholder="<?php echo __('Max', "poll-maker"); ?>">
                            </div>
                        </div>
                        <!-- Show author -->
                        <div>
                            <span><?php echo __('Show author', "poll-maker"); ?></span>
                            <input type="checkbox" name="quick-poll-show_poll_author" id="quick-poll-show-poll-author" value="1"> 
                            <label for="quick-poll-show-poll-author">Toggle</label>
                        </div>
                        <!-- Show title -->
                        <div>
                            <span><?php echo __('Show title', "poll-maker"); ?></span>
                            <input type="checkbox" name="quick-poll-show-title" id="quick-poll-show-title" checked> 
                            <label for="quick-poll-show-title">Toggle</label>
                        </div>
                        <!-- Show creation date -->
                        <div>
                            <span><?php echo __('Show creation date', "poll-maker"); ?></span>
                            <input type="checkbox" name="quick-poll-show-creation-date" id="quick-poll-show-creation-date"> 
                            <label for="quick-poll-show-creation-date">Toggle</label>
                        </div>
                        <!-- Hide results -->
                        <div>
                            <span><?php echo __('Hide results', "poll-maker"); ?></span>
                            <input type="checkbox" name="quick-poll-hide-results" id="quick-poll-hide-results" value="1"> 
                            <label for="quick-poll-hide-results">Toggle</label>
                        </div>
                        <!-- Randomize answers -->
                        <div>
                            <span><?php echo __('Randomize answers', "poll-maker"); ?></span>
                            <input type="checkbox" name="quick-poll-randomize-answers" id="quick-poll-randomize-answers"> 
                            <label for="quick-poll-randomize-answers">Toggle</label>
                        </div>
                        <!-- Enable restart button -->
                        <div>
                            <span><?php echo __('Enable restart button', "poll-maker"); ?></span>
                            <input type="checkbox" name="quick-poll-enable-restart-button" id="quick-poll-enable-restart-button"> 
                            <label for="quick-poll-enable-restart-button">Toggle</label>
                        </div>
                    </div>
                    <!-- Save -->
                    <div class="quick_poll_save">
                        <input type="button" id="ays-save-quick-poll" value="<?php echo __('Save', "poll-maker"); ?>">
                        <input type="hidden" id="ays_poll_ajax_quick_poll_nonce" name="ays_poll_ajax_quick_poll_nonce" value="<?php echo $quick_poll_plugin_nonce; ?>">
                    </div>
                </form>
            </div> 
        </div>
    </div>

<!-- ///////////////   QUICK POLL END  ////////////// -->
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                <?php
                        $this->polls_obj->views();
                    ?>
                    <form method="post">
						<?php
                        $this->polls_obj->prepare_items();
                        $search = __("Search" , "poll-maker");
                        $this->polls_obj->search_box($search, $this->plugin_name);
						$this->polls_obj->display();
						?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <div class="ays-poll-create-poll-youtube-video-button-box">
            <?php echo sprintf( '<a href="?page=%s&action=%s" class="ays-poll-add-new-button-video  ays-poll-add-new-button-new-design"> %s ' . __('Add New', "poll-maker") . '</a>', esc_attr( $_REQUEST['page'] ), 'add', $plus_icon_svg);?>
    </div>
    <?php if($poll_max_id <= 3): ?>
        <div class="ays-poll-create-poll-video-box" style="margin: 10px auto 30px;">
            <div class="ays-poll-create-poll-title">
                <h4><?php echo __( "Create Your First Poll in Under One Minute", "poll-maker" ); ?></h4>
            </div>
            <div class="ays-poll-create-poll-youtube-video">
                <iframe width="560" height="315" class="ays-poll-youtube-video-responsive" src="https://www.youtube.com/embed/0dfJQdAwdL4" loading="lazy" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    <?php else: ?>
        <div class="ays-poll-create-poll-video-box" style="margin: auto;">
            <div class="ays-poll-create-poll-youtube-video">
                <a href="https://www.youtube.com/watch?v=0dfJQdAwdL4" target="_blank" title="YouTube video player" >
                    <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/icons/video_youtube_icon.svg' ?>" alt="How to create a Poll in Under One Minute">
                    <span>How to create a Poll in Under One Minute</span>
                </a>
            </div>
        </div>
    <?php endif ?>
</div>
