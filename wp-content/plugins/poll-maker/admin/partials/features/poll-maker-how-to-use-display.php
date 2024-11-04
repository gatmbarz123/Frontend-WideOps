<?php 

$add_new_url = sprintf('?page=%s&action=%s', "poll-maker", 'add');
$poll_page_url = sprintf('?page=%s', "poll-maker");

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
    <div class="ays-poll-maker-htu-header">
        <h1 class="ays-poll-maker-wrapper ays_heart_beat">
            <?php echo esc_html(get_admin_page_title()); ?> <i class="ays_fa ays_poll_fa_heart_o animated"></i>
        </h1>
    </div>

    <div class="ays-poll-faq-main">
        <h2><?php echo __("How to create a simple poll in 4 steps with the help of the Poll Maker plugin.", "poll-maker" ); ?></h2>
        <fieldset style="border:1px solid #ccc; padding:10px;width:fit-content; margin:0 auto;">
            <div class="ays-poll-ol-container">
                <ol>
                    <li><?php echo __( "Go to the", "poll-maker" ) . ' <strong><a href="'. $poll_page_url .'" target="_blank">'. __( "Poll" , "poll-maker" ) .'</a></strong> ' .  __( "page", "poll-maker" ); ?>,</li>
                    <li><?php echo __( "Create a new poll by clicking on the", "poll-maker" ) . ' <strong><a href="'. $add_new_url .'" target="_blank">'. __( "Add New" , "poll-maker" ) .'</a></strong> ' .  __( "button", "poll-maker" ); ?>,</li>
                    <li><?php echo __( "Fill out the information.", "poll-maker" ); ?></li>
                    <li><?php echo __( "Copy the", "poll-maker" ) . ' <strong>'. __( "shortcode" , "poll-maker" ) .'</strong> ' .  __( "of the poll and paste it into any post․", "poll-maker" ); ?></li>
                </ol>
            </div>
            <div class="ays-poll-p-container">
                <p><?php echo __("Congrats! You have already created your first poll." , "poll-maker"); ?></p>
            </div>
        </fieldset>
        <br>
        <div class="ays-poll-community-wrap">
            <div class="ays-poll-community-title">
                <h4><?php echo __( "Community", "poll-maker" ); ?></h4>
            </div>
            <div class="ays-poll-community-youtube-video">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/RDKZXFmG6Pc" loading="lazy" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="ays-poll-community-container">
                <div class="ays-poll-community-item">
                    <div>
                        <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="ays-poll-community-item-cover">
                            <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/icons/poll-maker-how-to-use-youtube.svg';?> " class="ays-poll-community-item-img">
                        </a>
                    </div>
                    <h3 class="ays-poll-community-item-title"><?php echo __( "YouTube community", "poll-maker" ); ?></h3>
                    <p class="ays-poll-community-item-desc"><?php echo __("Our YouTube community  guides you to step by step tutorials about our products and not only...", "poll-maker"); ?></p>
                    <div class="ays-poll-community-item-footer">
                        <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="button"><?php echo __( "Subscribe", "poll-maker" ); ?></a>
                    </div>
                </div>
                <div class="ays-poll-community-item">
                    <a href="https://wordpress.org/support/plugin/poll-maker/" target="_blank" class="ays-poll-community-item-cover" style="color: #0073aa;">
                        <img src="<?php echo POLL_MAKER_AYS_ADMIN_URL . '/images/icons/poll-maker-how-to-use-wordpress.svg';?> " class="ays-poll-community-item-img">
                    </a>
                    <h3 class="ays-poll-community-item-title"><?php echo __( "Best Free Support", "poll-maker" ); ?></h3>
                    <p class="ays-poll-community-item-desc"><?php echo __( "With the Free version, you get a lifetime usage for the plugin, however, you will get new updates and support for only 1 month.", "poll-maker" ); ?></p>
                    <div class="ays-poll-community-item-footer">
                        <a href="https://wordpress.org/support/plugin/poll-maker/" target="_blank" class="button"><?php echo __( "Join", "poll-maker" ); ?></a>
                    </div>
                </div>
                <div class="ays-poll-community-item">
                    <a href="https://ays-pro.com/contact" target="_blank" class="ays-poll-community-item-cover" style="color: #ff0000;">
                        <i class="ays-poll-community-item-img ays_poll_fas ays_fa_users" aria-hidden="true"></i>
                    </a>
                    <h3 class="ays-poll-community-item-title"><?php echo __( "Premium support", "poll-maker" ); ?></h3>
                    <p class="ays-poll-community-item-desc"><?php echo __( "Get 12 months updates and support for the Business package and lifetime updates and support for the Developer package.", "poll-maker" ); ?></p>
                    <div class="ays-poll-community-item-footer">
                        <a href="https://ays-pro.com/contact" target="_blank" class="button"><?php echo __( "Contact", "poll-maker" ); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="ays-poll-asked-questions">
            <h4><?php echo __("FAQs" , "poll-maker"); ?></h4>
            <div class="ays-poll-asked-question">
                <div class="ays-poll-asked-question__header">
                    <div class="ays-poll-asked-question__title">
                        <h4><strong><?php echo __("How do I change the design of the poll?" , "poll-maker"); ?></strong></h4>
                    </div>
                    <div class="ays-poll-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-poll-asked-question__body">
                    <p>
                        <?php 
                            /* translators: 1: opening strong tag, 2: closing strong tag, 3: opening strong tag, 4: closing strong tag, 5: opening strong tag, 6: closing strong tag */
                            echo sprintf( 
                                __( "To do that, please go to the %1\$sStyles%2\$s tab of the given poll. The plugin suggests you 7 awesome ready-to-use themes. After choosing your preferred theme, you can customize it with 15+ style options to create attractive polls that people love to vote on, including %3\$smain color, background image, background gradient, box-shadow, answers hover%4\$s and etc. Moreover, you can use the %5\$sCustom CSS%6\$s written field to fully match your preferred design for your website and brand.", "poll-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-poll-asked-question">
                <div class="ays-poll-asked-question__header">
                    <div class="ays-poll-asked-question__title">
                        <h4><strong><?php echo __( "Can I organize anonymous polls?" , "poll-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-poll-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-poll-asked-question__body">
                    <p>
                        <?php 
                            /* translators: 1: opening strong tag, 2: closing strong tag, 3: opening strong tag, 4: closing strong tag */
                            echo sprintf( 
                                __( "%1\$sYes!%2\$s Please go to the Settings tab of the given poll, and find the %3\$sAllow anonymity%4\$s option there. Enable it, and it will allow participants to respond to your polls without ever revealing their identities, even if they are registered on your website. After enabling the option, the wp _user and User IP will not be stored in the database. A giant step toward democracy!", "poll-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            );
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-poll-asked-question">
                <div class="ays-poll-asked-question__header">
                    <div class="ays-poll-asked-question__title">
                        <h4><strong><?php echo __( "How do I limit access to the poll?", "poll-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-poll-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-poll-asked-question__body">
                    <p>
                        <?php 
                            /* translators: 1: opening strong tag, 2: closing strong tag, 3: opening strong tag, 4: closing strong tag, 5: opening strong tag, 6: closing strong tag, 7: opening strong tag, 8: closing strong tag, 9: opening strong tag, 10: closing strong tag */
                            echo sprintf( 
                                __( "To do that, please go to the %1\$sLimitation%2\$s tab of the given poll. The plugin suggests two methods to prevent repeat voting from the same person. Those are %3\$sLimit the user to rate only once by IP%4\$s or %5\$sLimit the user to rate only once by User ID.%6\$s The other awesome functionality that the plugin suggests is %7\$sOnly for logged in users%8\$s to enable access to the poll those, who have logged in. This option will allow you to precisely target your respondents, and not receive unnecessary votes from others, who have not logged in. Moreover, with the help of the %9\$sOnly selected user role%10\$s option, you can select your preferred user role for example administrator, editor, subscriber, customer and etc.", "poll-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            );
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-poll-asked-question">
                <div class="ays-poll-asked-question__header">
                    <div class="ays-poll-asked-question__title">
                        <h4><strong><?php echo __( "Can I know more about my respondents?", "poll-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-poll-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-poll-asked-question__body">
                    <p>
                        <?php 
                            /* translators: 1: opening strong tag, 2: closing strong tag, 3: opening strong tag, 4: closing strong tag, 5: opening strong tag, 6: closing strong tag, 7: opening strong tag, 8: closing strong tag, 9: opening strong tag, 10: closing strong tag */
                            echo sprintf( 
                                __( "%1\$sYou are in a right place!%2\$s You just need to enable the %3\$sInformation Form%4\$s from the %5\$sUser Data%6\$s tab of the given poll, create your preferred %7\$scustom fields%8\$s in the %9\$sCustom Fields%10\$s page from the plugin left navbar, and come up with a clear picture of who your poll participants are, where they live, what their lifestyle and personality are like, etc.", "poll-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            );
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-poll-asked-question">
                <div class="ays-poll-asked-question__header">
                    <div class="ays-poll-asked-question__title">
                        <h4><strong><?php echo __( "Can I get notified every time a vote is submitted?", "poll-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-poll-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-poll-asked-question__body">
                    <p>
                        <?php 
                            /* translators: 1: opening strong tag, 2: closing strong tag, 3: opening strong tag, 4: closing strong tag, 5: opening strong tag, 6: closing strong tag */
                            echo sprintf( 
                                __( "%1\$sYou can!%2\$s To enable it, please go to the %3\$sEmail%4\$s tab of the given poll. There you will find the %5\$sResults notification by email%6\$s option. After enabling the option, the admin(or your provided email) will receive an email notification about votes at each time.", "poll-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-poll-asked-question">
                <div class="ays-poll-asked-question__header">
                    <div class="ays-poll-asked-question__title">
                        <h4><strong><?php echo __( "Will I lose the data after the upgrade?", "poll-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-poll-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-poll-asked-question__body">
                    <p>
                        <?php 
                            /* translators: 1: opening strong tag, 2: closing strong tag, 3: opening anchor tag, 4: closing anchor tag */
                            echo sprintf( 
                                __( "%1\$sNope!%2\$s All your content and assigned settings of the plugin will remain unchanged even after switching to the Pro version. You don’t need to redo what you have already built with the free version. For the detailed instruction, please take a look at our %3\$supgrade guide%4\$s", "poll-maker" ),
                                '<strong>',
                                '</strong>',
                                '<a href="https://ays-pro.com/wordpress-poll-maker-user-manual#frag_poll_upgrade" target="_blank">',
                                '</a>'
                            );
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <p class="ays-poll-faq-footer">
            <?php echo __( "For more advanced needs, please take a look at our" , "poll-maker" ); ?> 
            <a href="https://ays-pro.com/wordpress-poll-maker-user-manual" target="_blank"><?php echo __( "Poll Maker plugin User Manual." , "poll-maker" ); ?></a>
            <br>
            <?php echo __( "If none of these guides help you, ask your question by contacting our" , "poll-maker" ); ?>
            <a href="https://ays-pro.com/contact" target="_blank"><?php echo __( "support specialists." , "poll-maker" ); ?></a> 
            <?php echo __( "and get a reply within a day." , "poll-maker" ); ?>
        </p>
    </div>
</div>

<script>
    var acc = document.getElementsByClassName("ays-poll-asked-question__header");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        
        var panel = this.nextElementSibling;
        
        
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
          this.children[1].children[0].style.transform="rotate(0deg)";
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
          this.children[1].children[0].style.transform="rotate(180deg)";
        } 
      });
    }
</script>


