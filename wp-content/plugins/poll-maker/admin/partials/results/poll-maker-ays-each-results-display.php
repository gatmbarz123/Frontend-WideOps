<?php
    global $wpdb;
    $poll_id = isset($_GET['poll']) ? intval($_GET['poll']) : null;
    if($poll_id === null){
        wp_redirect( admin_url('admin.php') . '?page=' . $this->plugin_name . '-ays-results' );
    }

    if (isset($_REQUEST['ays_poll_tab_results'])) {
        $ays_poll_tab = sanitize_text_field($_REQUEST['ays_poll_tab_results']);
    } else {
        $ays_poll_tab = 'tab1';
    }
?>
<script>
    (function ($) {
        $('#adminmenu').find('li>a[href$="poll-maker-ays-results"]')
            .addClass('current')
            .parent()
            .addClass('current')
            .parent().parent()
            .removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu').addClass('wp-menu-open')
            .find('a.wp-has-submenu')
            .removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu').addClass('wp-menu-open');
    })(jQuery)
</script>
<div class="wrap ays_polls_each_results_list_table">
    <div class="ays-poll-heading-box">
        <div class="ays-poll-wordpress-user-manual-box">
            <a href="https://ays-pro.com/wordpress-poll-maker-user-manual" target="_blank"><?php echo __("View Documentation", "poll-maker"); ?></a>
        </div>
    </div>
    <h1 class="wp-heading-inline">
        <div class="ays-poll-each-results-heading-inline">
            <?php
            global $wpdb;
            $poll_id = isset($_GET['poll']) ? intval($_GET['poll']) : null;
            if($poll_id === null){
                wp_redirect( admin_url('admin.php') . '?page=' . $this->plugin_name . '-ays-results' );
            }

            $polls_table = $wpdb->prefix . 'ayspoll_polls';
            $sql = "SELECT * FROM {$polls_table} WHERE id =". $poll_id;
            $poll = $wpdb->get_row( $sql, 'ARRAY_A' );

            $user_id = get_current_user_id();

            echo sprintf('<a href="?page=%s" class="forArrow"><img src="' . POLL_MAKER_AYS_ADMIN_URL . '/images/icons/arrow-left.svg"></a>', esc_attr(rtrim($_REQUEST['page'], '-each')));
            echo esc_html(stripslashes($_GET['title']));
            ?>
        </div>
    </h1>
    <div class="nav-tab-wrapper">
        <a href="#statistics" class="ays-poll-google-chart nav-tab <?php echo ($ays_poll_tab == 'tab1') ? 'nav-tab-active' : ''; ?>" data-tab="tab1"><?= __('Statistics', "poll-maker"); ?></a>
        <a href="#poststuff" class="nav-tab <?php echo ($ays_poll_tab == 'tab2') ? 'nav-tab-active' : ''; ?>" data-tab="tab2"><?= __('Results', "poll-maker"); ?></a>
    </div>
    <div id="poststuff" class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab2') ? 'ays-poll-tab-content-active' : ''; ?>">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <input type="hidden" name="ays_poll_tab_results" value="<?php echo htmlentities($ays_poll_tab); ?>" id="ays_poll_active_tab_settings">
	                    <?php
	                        $this->each_results_obj->prepare_items();
                            $search = __("Search" , "poll-maker");
                            $this->each_results_obj->search_box($search, $this->plugin_name);
	                        $this->each_results_obj->display();         
	                    ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <div id="statistics" class="ays-poll-tab-content <?php echo ($ays_poll_tab == 'tab1') ? 'ays-poll-tab-content-active' : ''; ?>">
        <div class="results-apm" id="<?php echo 'pollResultId-' . $poll_id ?>">
        </div>
    </div>
    <div id="ays-results-modal" class="ays-modal">
        <div class="ays-modal-content">
            <div class="ays-poll-preloader">
                <img class="loader" src="<?php echo POLL_MAKER_AYS_ADMIN_URL; ?>/images/loaders/tail-spin.svg">
            </div>
            <div class="ays-modal-header">
                <span class="ays-close" id="ays-close-results">&times;</span>
                <h2><?php echo __("Details report", "poll-maker"); ?></h2>
            </div>
            <div class="ays-modal-body" id="ays-results-body">
                <table id="ays-results-table">
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    (function ($) {
        String.prototype.stripSlashes = function () {
            return this.replace(/\\(.)/mg, "$1");
        }

        <?php
            $poll_id = absint($_GET['poll']);
            $current_tab = $ays_poll_tab == 'tab1' ? 1 : 2;
            $chart_data = $this->each_results_obj->get_poll_data_chart($poll_id);
        ?>

        var pageTab = <?= $current_tab ?>;

        var pollId = <?php echo ($poll_id) ?>;
        var chart_data =  <?= !empty($chart_data) ? json_encode($chart_data) : 0 ?>;
        var resultStatistics = $('#pollResultId-' + pollId);

        var votesSum = 0;
        var votesMax = 0;
        var answer;
        for ( answer in chart_data) {
            votesSum = Math.abs(chart_data[answer].votes) + votesSum;
            if (+chart_data[answer].votes > votesMax) {
                votesMax = +chart_data[answer].votes;
            }
        }
        var answer2 = chart_data;

        var widths = [];
        for (var i = 0; i < chart_data.length; i++) {
            var answer = answer2[i];
            widths[i] = {};
            var width = (answer.votes * 100 / votesSum).toFixed(0);
            widths[i].width = width;
            widths[i].index = i;
        }

        var showChartType = chart_data[0]['show_chart_type'];
        var barChartType = (typeof showChartType != "undefined" && showChartType !== null && showChartType.length > 0 && showChartType != "") ? showChartType.replace(/"/g, '') : "default_bar_chart";

        if(barChartType == 'google_bar_chart') {
            var chartHeight = typeof chart_data[0]['show_chart_type_google_height'] != "undefined" && chart_data[0]['show_chart_type_google_height'] !== null && chart_data[0]['show_chart_type_google_height'].length > 0 && chart_data[0]['show_chart_type_google_height'] != '' ? chart_data[0]['show_chart_type_google_height'] : 400;
            var aysBarChartData = new Array(['', '']);
            for (var tox in widths) {
                var chartRealVotes = +answer2[widths[tox].index].votes;
                var answerTextVal   = answer2[widths[tox].index].answer;
                answerTextVal   = answerTextVal.replace(/\\/g, '');
                aysBarChartData.push([
                    answerTextVal,
                    parseInt(chartRealVotes)
                ]);
            }

            google.charts.load('current', {packages: ['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawBasic);

            function drawBasic() {
                var data = google.visualization.arrayToDataTable(aysBarChartData);

                var groupData = google.visualization.data.group(
                            data,
                            [{column: 0, modifier: function () {return 'total'}, type:'string'}],
                            [{column: 1, aggregation: google.visualization.data.sum, type: 'number'}]
                );

                var formatPercent = new google.visualization.NumberFormat({
                    pattern: '#%'
                });
        
                var formatShort = new google.visualization.NumberFormat({
                    pattern: 'short'
                });
                
                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1, {
                    calc: function (dt, row) {
                        if( groupData.getValue(0, 1) == 0 ){
                            return amount;
                        }
                        var amount =  formatShort.formatValue(dt.getValue(row, 1));
                        var percent = formatPercent.formatValue(dt.getValue(row, 1) / groupData.getValue(0, 1));
                        return amount + ' (' + percent + ')';
                    },
                    type: 'string',
                    role: 'annotation'
                }]);
                var options = {
                    maxWidth: '100%',
                    height: chartHeight,
                    legend: { position: 'none' },
                    axes: {
                        x: {
                            0: { side: 'bottom'}
                        }
                    },
                    bars: 'horizontal',
                    bar: { groupWidth: "90%" },
                };

                var chart = new google.visualization.BarChart(document.getElementById('pollResultId-' + pollId));
                if (pageTab == 1) {
                    chart.draw(view,options);
                    resizeChart(chart, data, options);
                } else {
                    var statisticsTab = document.querySelector("a[href='#statistics']");
                    statisticsTab.addEventListener('click', function() {
                        chart.draw(view,options);
                        resizeChart(chart, data, options);
                    })
                }
            }

        } else if(barChartType == "default_bar_chart") {
            for (var i = 0; i < chart_data.length; i++) {
                var answer = chart_data;

                var starIcons = '<i class="ays_poll_far ays_poll_fa-star"></i>';
                var emojiIcons = [
                    '<i class="ays_poll_far ays_poll_fa-dizzy"></i>',
                    '<i class="ays_poll_far ays_poll_fa-smile"></i>',
                    '<i class="ays_poll_far ays_poll_fa-meh"></i>',
                    '<i class="ays_poll_far ays_poll_fa-frown"></i>',
                    '<i class="ays_poll_far ays_poll_fa-tired"></i>',
                    ];
                var handIcons = [
                        '<i class="ays_poll_far ays_poll_fa-thumbs-up"></i>',
                        '<i class="ays_poll_far ays_poll_fa-thumbs-down"></i>'
                    ];
                
                // var percentColor = chart_data[0]['main_color'].replace(/"/g, '');
                // var resultColorsRgba = chart_data[0]['result_in_rgba'];
                var pollType = chart_data[0]['type'].replace(/"/g, '');
                var pollViewType = chart_data[0]['view_type'].replace(/"/g, '');
                var enableImageShowing = (chart_data[0]['show_answer_images'] != null) ?chart_data[0]['show_answer_images'].replace(/"/g, '') : '';

                var answerDiv = $('<div class="answer-title flex-apm"></div>')
                var answerBar = $('<div class="answer-percent" data-percent="'+widths[i].width+'"></div>');

                // if (resultColorsRgba) {
                //     answerBar.attr('style', 'background-color:'+hexToRgba(percentColor)+'  !important; border: 1px solid ' + percentColor +' !important;');
                // }
                // else{
                //     answerBar.attr('style', 'background-color:'+percentColor);
                // }

                answerBar.css({
                    width: '1%'
                });

                var answerText = '';
                var pollShowAnswerImage = false;
                switch (pollType) {
                    case 'choosing':
                        pollShowAnswerImage = (enableImageShowing == "on") ? true : false;
                        if(pollShowAnswerImage){
                            var answerImage = typeof answer[widths[i].index].answer_img != "undefined" || typeof (answer[widths[i].index].answer_img) != "" ? answer[widths[i].index].answer_img : "";
                            var answerImageBox = $("<div class='ays-poll-answers-image-box-empty-image'></div>");
                            var answerImageIsEmptyClass = "ays-poll-answers-box-no-image";
                            if(answerImage != ""){
                                answerImageIsEmptyClass = "ays-poll-answers-box";
                                answerImageBox = $("<div class='ays-poll-answers-image-box'><img src="+answerImage+" class='ays-poll-answers-current-image'></div>");
                            }
                            var answerTextAndPercent = $("<div class='ays-poll-answer-text-and-percent-box'></div>");
                            var answerMainDiv = $('<div class='+answerImageIsEmptyClass+'></div>');
                        }

                        answerText = $('<span class="answer-text"></span>');
                        var htmlstr = htmlstr = answer[widths[i].index].answer.stripSlashes();

                        answerText.html(htmlstr);
                        break;
                    case 'rating':
                        switch (pollViewType) {
                            case 'emoji':
                                answerText = emojiIcons[chart_data.length / 2 + 1.5 - widths[i].index];
                                break;

                            case 'star':
                                for (var j = 0; j <= widths[i].index; j++) {
                                    answerText += starIcons;
                                }
                                break;
                        }
                        answerText = $('<span class="answer-text">'+answerText+'</span>');
                        break;
                    case 'voting':
                        switch (pollViewType) {
                            case 'hand':
                                answerText = handIcons[widths[i].index];
                                break;

                            case 'emoji':
                                answerText = emojiIcons[2 * widths[i].index + 1];
                                break;
                        }
                        answerText = $('<span class="answer-text">'+answerText+'</span>');
                        break;
                }
            
                var answerVotes = $('<span class="answer-votes"></span>');
                answerVotes.text(answer[widths[i].index].votes);

                if(!pollShowAnswerImage){
                    answerDiv.append(answerText).append(answerVotes).appendTo(resultStatistics);
                    $(resultStatistics).append(answerBar);
                }
                else{
                    answerMainDiv.appendTo(resultStatistics);
                    answerTextAndPercent.appendTo(answerMainDiv);
                    answerDiv.append(answerText).append(answerVotes).appendTo(answerTextAndPercent);
                }
                answerBar.appendTo(answerTextAndPercent); 
            }
            resultStatistics.find('.answer-percent').each(function () {
                var percent = $(this).attr('data-percent');
                $(this).css({
                    width: (percent || 1) + '%'
                });
                var aaa = $(this);
                aaa.text(percent > 5 ? percent + '%' : '');
            });

            resultStatistics.parents('.ays_poll_category-container').find('.ays-poll-next-btn').prop('disabled', false);
            var vvv = resultStatistics.parents('.ays_poll_category-container').data("var");
            window['showNext'+vvv] = true;

            if (typeof(window['catIndex'+vvv]) !== 'undefined') {
                if (typeof(window['pollsGlobalPool'+vvv]) !== 'undefined') {
                    if (window['catIndex'+vvv] == window['pollsGlobalPool'+vvv].length-1) {
                        resultStatistics.parents('.ays_poll_category-container').find('.ays-poll-next-btn').prop('disabled', true);
                    }
                }
                if (window['catIndex'+vvv] == 0 && resultStatistics.parents('.ays_poll_category-container').find('.results-apm').length > 0) {
                    resultStatistics.parents('.ays_poll_category-container').find('.ays-poll-previous-btn').prop('disabled', true);
                }
            }
        }

        function hexToRgba(hex, alfa) {
            var c;
            if (alfa == null) {
                alfa = 1;
            }
            if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
                c= hex.substring(1).split('');
                if(c.length== 3){
                    c= [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c= '0x'+c.join('');
                return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+alfa+')';
            }
        }

    })(jQuery);
</script>