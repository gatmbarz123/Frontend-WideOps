<?php
$plus_icon_svg = "<span class=''><img src='". POLL_MAKER_AYS_ADMIN_URL ."/images/icons/add-new.svg'></span>";
?>

<div class="wrap ays-poll-list-table ays_poll_categories_list_table">
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
echo sprintf('<a href="?page=%s&action=%s" class="page-title-action button-primary ays-poll-add-new-cat-button ays-poll-add-new-button-new-design"> %s ' . __('Add New', "poll-maker") . '</a>', esc_attr($_REQUEST['page']), 'add', $plus_icon_svg);
?>
    </h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder ays-poll-maker-categories-list-tables">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <?php
                            $this->cats_obj->prepare_items();
                            $search = __("Search", "poll-maker");
                            $this->cats_obj->search_box($search, $this->plugin_name);
                            $this->cats_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
