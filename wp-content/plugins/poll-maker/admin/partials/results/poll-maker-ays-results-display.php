<?php 
    $active_tab   = (!empty($_GET['active-tab'])) ? esc_attr($_GET['active-tab']) : 'tab1';

    $this_poll_id = (isset($_GET['filter_answer'])) ? esc_attr($_GET['filter_answer']) : '';
    $result_obj = $this->results_obj;
    $poll_cats = $result_obj->get_categories();
    // $answer_actions = $this->answer_results_obj;
    $polls = $result_obj->get_polls();
    $last_id = 0;
    $content = '';
    $disable_chart_filter = '';
    $selected_poll_title = array();
    $latest_poll_id = 0;
    if((!empty($polls) && $this_poll_id != "0" && strpos($_SERVER['REQUEST_URI'], 'filter_answer=') == false) || !empty($polls) && $this_poll_id == "0"){
        $content = '<div><blockquote style="font-size: 17px;border-color:#f3ca03;">'.__("There are no selected polls." , "poll-maker").'</blockquote></div>';
    } else if(!empty($polls) && $this_poll_id != "0"){
        foreach($polls as $p_key => $p_value){
            $this_poll_id       = isset($p_value['id']) && $p_value['id'] != "" ? esc_attr($p_value['id']) : ""; 
            $this_poll_title    = isset($p_value['title']) && $p_value['title'] != "" ? esc_attr($p_value['title']) : "";
            $selected_poll_title[$this_poll_id] = $this_poll_title;
            $last_id = $p_key;
        }
        $last_id = isset($this_poll_id) && $this_poll_id != '' ? $this_poll_id : $polls[$last_id]['id'];
        $latest_poll_id = isset($_REQUEST['filter_answer']) && $_REQUEST['filter_answer'] != '' ? absint($_REQUEST['filter_answer']) : intval($last_id);
        $poll_answers = $result_obj->get_poll_answers($latest_poll_id);

        $all_answer_data = array();
        $votes_all_sum = 0;
        foreach($poll_answers as $key => $answers){
            $votes = isset($answers['votes']) && $answers['votes'] != '' ? intval($answers['votes']) : 0;
            $votes_all_sum += $votes;
        }
        $votes_sum = 0;
        foreach($poll_answers as $key => $answers){
            $answer_percent = "0";
            $votes = isset($answers['votes']) && $answers['votes'] != '' ? intval($answers['votes']) : 0;
            $votes_sum += $votes;
            if($votes_all_sum > 0 && $votes > 0){
                $answer_percent =  ceil(($votes * 100) / $votes_all_sum);
            }
            $all_answer_data[0] = array("Total votes" , "Total votes (".$votes_sum.")"); 
            $ans = ( isset($answers['answer']) && $answers['answer'] != '') ? strip_tags( stripslashes( $answers['answer'] ) ) : '';
            $all_answer_data[$key+1] = array($ans . " " . '('.$answer_percent . "%".')', $votes); 
        }
        $poll_js_title = $latest_poll_id > 0 ? $selected_poll_title[$latest_poll_id] : 0;
        wp_localize_script("ays-poll-admin-js", 'pollAnswerChartObj', array(
            'answerData' => $all_answer_data,
            'pollTitle'  => $poll_js_title
        ) );

    } else{
        $content = '<div><blockquote style="font-size: 17px;border-color:red;">'.__("There are no polls yet." , "poll-maker").'</blockquote></div>';
        $disable_chart_filter = "disabled";
    }

    // Poll get user ids 
    $poll_users = $this->results_obj->ays_poll_get_users();
?>

<div class="wrap ays-poll-results-list-table">
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
    <div class="ays-poll-export-import-box">
        <div class="only_pro" style="position: relative">
            <div class="pro_features pro_features_popup" style="background: none; box-shadow: none; color: #fff">
                <div class="pro-features-popup-conteiner">
                    <div class="pro-features-popup-title">
                        <?php echo __("Export Results", "poll-maker"); ?>
                    </div>
                    <div class="pro-features-popup-content" data-link="https://youtu.be/DYof2ejoDVQ">
                        <p>
                            <?php echo __("Analyzing poll results is the most important point of creating polls. The plugin supports CSV and XLSX file formats to export the results of your polls.", "poll-maker"); ?>
                        </p>
                        <p>
                            <?php echo __("Use the feature to analyze poll results and get a better understanding of your website visitors.", "poll-maker"); ?>
                        </p>
                    </div>
                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/poll-maker?utm_source=dashboard&utm_medium=poll-free&utm_campaign=pro-popup-export-results">
                        <?php echo __("Upgrade PRO NOW", "poll-maker"); ?>
                    </div>
                </div>
            </div>
            <div class="ays-poll-crown-img-container-main">
                <a href="https://ays-pro.com/wordpress/poll-maker/" target="_blank" class="ays-poll-crown-img-container">
                    <button class="disabled-button" style="color: #fff; float: right; margin-right: 5px; cursor: pointer; width: 77px; position: relative; display: flex; justify-content: space-between; align-items: center" title="<?=__('This property available only in PRO version', "poll-maker");?>" >
                        <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/icons/crown.png';?>">
                        <?=__('Export', "poll-maker");?>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <div class="ays-top-menu-wrapper">
        <div class="ays_menu_left" data-scroll="0"><i class="ays_poll_fas ays_poll_fa-left"></i></div>
        <div class="ays-top-menu">
            <div class="nav-tab-wrapper ays-poll-results-page-wrapper ays-top-tab-wrapper">
                <a href="#tab1" class="nav-tab <?php echo $active_tab == "tab1"  ? "nav-tab-active" : ""; ?>"><?= __('Results', "poll-maker"); ?></a>
                <a href="#tab2" class="nav-tab <?php echo $active_tab == "tab2"  ? "nav-tab-active" : ""; ?> ays_poll_answer_chart_active"><?= __('Answer Chart', "poll-maker"); ?></a>
                <a href="#tab3" class="nav-tab"><?= __('Statistics', "poll-maker"); ?></a>
                <a href="#tab4" class="nav-tab"><?= __('Global Leaderboard', "poll-maker"); ?></a>
                <a href="#tab5" class="nav-tab"><?= __('All Results', "poll-maker"); ?></a>
            </div>
        </div>
        <div class="ays_menu_right" data-scroll="-1"><i class="ays_poll_fas ays_poll_fa-right"></i></div>
    </div>
    <div id="tab1" class="ays-poll-tab-content <?php echo $active_tab == "tab1"  ? "ays-poll-tab-content-active" : ""; ?>">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <form method="POST" >
                            <?php
                                $this->results_obj->prepare_items();
                                $this->results_obj->display();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>

    <div id="tab2" class="ays-poll-tab-content <?php echo $active_tab == "tab2"  ? "ays-poll-tab-content-active" : ""; ?>">
        <div style="padding:10px 0;">                                
            <form method="get">
                <div>
                    <input type="hidden" name="page" value="poll-maker-ays-results">
                    <select name="filter_answer" <?php echo $disable_chart_filter; ?>>
                        <option value="0"><?php echo __("Select Poll", "poll-maker")?></option>
                        <?php
                            $selected_poll = "";
                            $opt_cont = "";
                            $selected_poll_title = array();
                                foreach($polls as $obj_key => $obj_value){
                                    $poll_id       = isset($obj_value['id']) && $obj_value['id'] != "" ? esc_attr($obj_value['id']) : ""; 
                                    $selected_poll = ($latest_poll_id == intval($obj_value['id'])) ? "selected" : "";
                                    $poll_title    = isset($obj_value['title']) && $obj_value['title'] != "" ? esc_attr($obj_value['title']) : "";
                                    
                                    $opt_cont     .= "<option value=".$poll_id." ".$selected_poll.">".$poll_title."</option>";
                                }
                                echo $opt_cont;                            
                        ?>
                    </select>
                    <input type="submit" value="Filter" class="button action" <?php echo $disable_chart_filter; ?>>                          
                    <input type="hidden" name="active-tab" value="tab2">
                </div>
                <div id="ays_poll_answer_chart" style = "width: 100%;">
                    <?php echo $content; ?>
                </div>
            </form>
        </div>
    </div>

    <div id="tab3" class="ays-poll-tab-content" style="padding-top: 18px;">
        <div class="form-group row" style="margin: 0px;">
            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                <div class="pro_features" style="justify-content:flex-end;">
                </div>
                <a href="https://ays-pro.com/wordpress/poll-maker/" target="_blank" title="<?=__('This property available only in PRO version', "poll-maker");?>">
                    <img src="<?=plugins_url() . '/poll-maker/admin/images/chart_screen.png';?>" alt="Statistics" style="width:100%" >
                </a>
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
    </div>
    <div id="tab4" class="ays-poll-tab-content" style="padding-top: 18px;">
        <div class="form-group row" style="margin: 0px;">
            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                <div class="pro_features" style="justify-content:flex-end;">
                </div>
                <div class='ays_lb_container'>
                    <ul class='ays_lb_ul' style='width: 100%;'>
                        <li class='ays_lb_li'>
                            <div class='ays_lb_pos'>Pos.</div>
                            <div class='ays_lb_user'><?php echo __("Name", "poll-maker")?></div>
                            <div class='ays_lb_score'><?php echo __("Attempts", "poll-maker")?></div>
                        </li>
                        <li class="ays_lb_li">
                            <div class="ays_lb_pos">1.</div>
                            <div class="ays_lb_user">admin</div>
                            <div class="ays_lb_score">2</div>
                        </li>
                    </ul>
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
    </div>
    <div id="tab5" class="ays-poll-tab-content" style="padding-top: 18px;">
        <div class="form-group row" style="margin: 0px;">
            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                <div class="pro_features" style="justify-content:flex-end;">
                </div>
                <table class="wp-list-table widefat fixed striped table-view-list">
                    <thead>
                        <tr>
                            <td id="" class="manage-column column-cb check-column">
                                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                <input id="cb-select-all-1" type="checkbox">
                            </td>
                            <th scope="col" id="" class="manage-column column-polls column-primary sortable asc" sortable asc>
                                <a href="javascript:void(0)">
                                    <span>Polls</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-answer sortable asc" >
                                <a href="javascript:void(0)">
                                    <span>Answer</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-user_ip sortable asc" >
                                <a href="javascript:void(0)">
                                    <span>User IP</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-wp_user sortable asc" >
                                <a href="javascript:void(0)">
                                    <span>WP User</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-user_email sortable asc >
                                <a href="javascript:void(0)">
                                    <span>User Email</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-user-name sortable asc" >
                                <a href="javascript:void(0)">
                                    <span>User Name</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" class="manage-column column-vote_date sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Vote Datetime</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" class="manage-column column-vote_reason sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Vote Reason</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" class="manage-column column-unread sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Read Status</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" class="check-column">
                                <input type="checkbox" class="" name="" value="3">
                            </th>
                            <td class="column- has-row-actions column-primary" data-colname="" >
                                <a href="javascript:void(0)" data-result="" class="">Default title</a>
                                <input type="hidden" value="1" class="">
                                <div class="row-actions">
                                    <span class="view-details"><a href="javascript:void(0);" data-result="3" >View details</a> | </span>
                                    <span class="delete"><a class="ays_confirm_del" data-message="this report" href="">Delete</a></span>
                                </div>
                                <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                                <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                            </td>
                            <td class="answer_id column-answer_id">12</td>
                            <td class="user_ip column-user_ip">::1</td>
                            <td class="user_id column-user_id">Guest</td>
                            <td class="user_email column-user_email">usermail@mail.com</td>
                            <td class="user_name column-user_name">User Name</td>
                            <td class="vote_date column-vote_date">17:43:00 21.12.2020</td>
                            <td class="vote_reason column-vote_reason">text</td>
                            <td class="read column-read" >
                                <div class="unread-result-badge" style="margin: 0;"></div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td id="" class="manage-column column-cb check-column">
                                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                <input id="" type="checkbox">
                            </td>
                            <th scope="col" id="" class="manage-column column-poll_id column-primary sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Polls</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-answer_id sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Answer</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-user_ip sortable asc">
                                <a href="javascript:void(0)">
                                    <span>User IP</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-user_id sortable asc">
                                <a href="javascript:void(0)">
                                    <span>WP User</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-user_email sortable asc">
                                <a href="javascript:void(0)">
                                    <span>User Email</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-user_name sortable asc">
                                <a href="javascript:void(0)">
                                    <span>User Name</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-vote_date sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Vote Datetime</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-vote_reason sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Vote Reason</span>
                                    <span class=""></span>
                                </a>
                            </th>
                            <th scope="col" id="" class="manage-column column-unread sortable asc">
                                <a href="javascript:void(0)">
                                    <span>Read Status</span>
                                    <span class=""></span>
                                </a>
                            </th>
                        </tr>
                    </tfoot>
                </table>
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
