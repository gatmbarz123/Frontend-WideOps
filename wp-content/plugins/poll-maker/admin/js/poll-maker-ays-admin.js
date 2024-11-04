(function ($) {
    'use strict';

    window.FontAwesomeConfig = {
        autoReplaceSvg: false
    };

    $('.apm-unread').each(function () {
        var tr = $(this).parent().parent();
        tr.find('td').each(function () {
            $(this).css('color', '#dc3545');
        })
    });

    $.fn.serializeFormJSON = function () {
        var o = {},
            a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $.fn.goToNormal = function() {
        $('html, body').animate({
            scrollTop: this.offset().top - 200 + 'px'
        }, 'normal');
        return this; // for chaining...
    }

    $.fn.aysModal = function(action){
        let $this = $(this);

        var current_popup_id_attr = $this.attr('id');
        var current_popup_id = "";
        var current_popup_class = "";

        if (current_popup_id_attr && current_popup_id_attr != "") {
            current_popup_id = "overlay-" + current_popup_id_attr;
            current_popup_class = "." + current_popup_id;
        }

        switch(action){
            case 'hide':
                $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
                var removeIframe = $this.find('.ays-modal-body iframe');
                if ( removeIframe.length > 0 ) {
                    $this.find('.ays-modal-body iframe').remove();
                }
                setTimeout(function(){
                    $(document.body).removeClass('modal-open');
                    $(document).find('.ays-modal-backdrop'+ current_popup_class).remove();
                    $this.hide();
                }, 250);
            break;
            case 'hide_remove_video':
                $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
                $this.find('.ays-modal-body iframe').remove();
                setTimeout(function(){
                    $(document.body).removeClass('modal-open');
                    $(document).find('.ays-modal-backdrop'+ current_popup_class).remove();
                    $this.hide();
                }, 250);
            break;
            case 'show_flex':
                $this.css('display', 'flex');
                $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
                $(document).find('.modal-backdrop').remove();
                $(document.body).append('<div class="ays-modal-backdrop '+ current_popup_id +'"></div>');
                $(document.body).addClass('modal-open');
            case 'show': 
            default:
                $this.show();
                $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
                $(document).find('.modal-backdrop').remove();
                $(document.body).append('<div class="ays-modal-backdrop '+ current_popup_id +'"></div>');
                $(document.body).addClass('modal-open');
            break;
        }
    }

    $(document).find('.ays_poll_aysDropdown').aysDropdown();
    $(document).find('[data-toggle="dropdown"]').dropdown();

    $('[data-toggle="tooltip"]').tooltip();

    // copy shortcode
    $(document).find('.ays-poll-copy-image').on('click', function(){
        var _this = this;
        var input = $(_this).parent().find('input.ays-poll-shortcode-input');
        var length = input.val().length;

        input[0].focus();
        input[0].setSelectionRange(0, length);
        document.execCommand('copy');

        $(_this).attr('data-original-title', pollLangObj.copied);
        $(_this).tooltip('show');
    });

    $(document).find('.ays-poll-copy-image').on('mouseleave', function(){
        var _this = this;

        $(_this).attr('data-original-title', pollLangObj.clickForCopy);
    });

    // Users limits
    if ($('#apm_limit_users').prop('checked')) {
        $('.if-limit-users ').fadeIn();
    }
    
    $('#ays_enable_restriction_pass').on('change', function () {
        if ($(this).prop('checked')) {
            if(!$('#ays_enable_logged_users').prop('checked')) {
                $('#ays_enable_logged_users').trigger('click');
            }
        } else {
            $('.if-users-roles').fadeOut();
        }
    });

    if ($('#ays_enable_logged_users').prop('checked')) {
        $('.if-logged-in').fadeIn();
    }

    if ($('#ays_enable_restriction_pass').prop('checked')) {
        $('.if-users-roles').fadeIn();
    }
    
    $('#ays_enable_logged_users').on('change', function () {
        if (!$(this).prop('checked')) {
            $('#ays_enable_restriction_pass').prop('checked', false);
            $('.if-users-roles').fadeOut();
        }
    });

    // Email notification
    if ($('#ays_notify_by_email_on').prop('checked')) {
        $('.if-notify-email').fadeIn();
    }
    $('#ays_notify_by_email_on').on('change', function () {
        $('.if-notify-email').fadeToggle();
    });

    // Redirect after vote
    if ($('#ays_redirect_after_vote').prop('checked')) {
        $('.if-redirect-after-vote').fadeIn();
    }
    $('#ays_redirect_after_vote').on('change', function () {
        $('.if-redirect-after-vote').fadeToggle();
    });

    // Answer Sound
    if ($('#ays_enable_asnwers_sound').prop('checked')) {
        $('.if_answer_sound').fadeIn();
    }
    $('#ays_enable_asnwers_sound').on('change', function () {
        $('.if_answer_sound').fadeToggle();
    });

    //Hide results
    $('.if-ays-poll-hide-results').css("display", "flex").hide();
    if ($('#ays-poll-hide-results').prop('checked')) {
        $('.if-ays-poll-hide-results').fadeIn();
    }
    $('#ays-poll-hide-results').on('change', function () {
        // $('.if-ays-poll-hide-results').fadeToggle();
        if ($('#ays-poll-hide-results').prop('checked')) {
            $('#ays-poll-allow-not-vote').prop('checked', false);
        }
    });

    //Allow not to vote
    $('#ays-poll-allow-not-vote').on('change', function () {
        if ($('#ays-poll-allow-not-vote').prop('checked')) {
            $('#ays-poll-hide-results').prop('checked', false);
            $('.if-ays-poll-hide-results').fadeOut();
        }
    });

    //Loading effect
    $('.if-loading-gif').css("display", "flex").hide();
    if ($('#ays-poll-load-effect').val() == 'load_gif') {
        $('.if-loading-gif').fadeIn();
    }
    if ($('#ays-poll-load-effect').val() == 'message') {
        $('.if-loading-message').fadeIn();
    }
    $('#ays-poll-load-effect').on('change', function () {
        var effect = $(this).val();
        var sizeCont = $(document).find(".ays_load_gif_cont");
        var sizeContLine = $(document).find(".ays_line_changeing");
        if (effect == 'load_gif') {
            $('.if-loading-gif').fadeIn();
            $('.if-loading-message').hide();
            if(sizeCont.hasClass("display_none")){
                sizeCont.removeClass("display_none");
            }
            if(sizeContLine.hasClass("ays_hr_display_none")){
                sizeContLine.removeClass("ays_hr_display_none");
            }
        }
        else if(effect == 'message'){
            $('.if-loading-message').fadeIn();
            $('.if-loading-gif').hide();
        }
         else {
            $('.if-loading-gif').fadeOut();
            $('.if-loading-message').fadeOut();
            if(!sizeCont.hasClass("display_none")){
                sizeCont.addClass("display_none");
                sizeContLine.addClass("ays_hr_display_none");
            }
            if(!sizeContLine.hasClass("ays_hr_display_none")){
                sizeContLine.addClass("ays_hr_display_none");
            }
        }
    });

    //User data form
    $(".ays-poll-sel-fields input[type='checkbox']").on('change', function () {
        var id = $(this).val();
        if ($(this).prop('checked')) {
            $('#ays-poll-box-rfield-' + id).fadeIn();
        } else {
            $('#ays-poll-box-rfield-' + id).find('input').prop('checked', false);
            $('#ays-poll-box-rfield-' + id).fadeOut();
        }
    });

    $(document).find('#ays_user_roles_poll').select2({
        placeholder: 'Select role'
    });

    $('.apm-cat-select2').select2({
        placeholder: 'Select category'
    });
    $('select.ays-select').not('.ays-select-search').select2({
        minimumResultsForSearch: -1
    });
    $('.ays-select-search').select2();

    $(document).on('click', 'a.add-question-image', function (e) {
        openMediaUploader(e, $(this));
    });
    $(document).on('click', 'a.add-bg-image', function (e) {
        openMediaUploaderBg(e, $(this));
    });
    $(document).on('click', 'a.add-logo-image', function (e) {
        openMediaUploaderLogo(e, $(this));
    });

    $(document).on('click', '.ays-remove-question-img', function () {
        $(this).parent().find('img#ays-poll-img').attr('src', '');
        $(this).parent().find('input#ays-poll-image').val('').trigger('change');
        $(this).parent().fadeOut();
        $(document).find('.ays-field label a.add-question-image').text('Add Image');
        $('.ays-poll-img').remove();
    });
    $(document).on('click', '.ays-remove-bg-img', function () {
        $('img#ays-poll-bg-img').attr('src', '');
        $('input#ays-poll-bg-image').val('').trigger('change');
        $('.ays-poll-bg-image-container').parent().fadeOut(300);
        $(this).parents(".form-group.row").find('a.add-bg-image').html('Add Image');
        $('.box-apm').css('background-image', 'unset');
        $('#ays-poll-background-image-options').fadeOut(300);
        if ($(document).find('#ays-enable-background-gradient').prop('checked')) {
            toggleBackgrounGradient();
        }
    });
    var themes = [
        'personal',
        {
            'name': 'light',
            'mainColor': '#0C6291',
            'textColor': '#0C6291',
            'buttonTextColor': '#FBFEF9',
            'buttonBgColor': '#0C6291',
            'iconColor': '#0C6291',
            'bgColor': '#FBFEF9',
            'answerBgColor': '#FBFEF9',
            'titleBgColor': 'rgba(255,255,255,0)',
            'borderColor': '#0C6291',
        },
        {
            'name': 'dark',
            'mainColor': '#FBFEF9',
            'textColor': '#FBFEF9',
            'buttonTextColor': '#222222',
            'buttonBgColor': '#FBFEF9',
            'iconColor': '#FBFEF9',
            'bgColor': '#222222',
            'answerBgColor': '#222222',
            'titleBgColor': 'rgba(255,255,255,0)',
            'borderColor': '#FBFEF9',
        },
        {
            'name': 'minimal',
            'mainColor': '#7a7a7a',
            'textColor': '#424242',
            'buttonTextColor': '#424242',
            'buttonBgColor': 'rgba(0,0,0,0)',
            'iconColor': '#999A9C',
            'bgColor'  : 'rgba(0,0,0,0)',
            'answerBgColor': 'rgba(0,0,0,0)',
            'titleBgColor': 'rgba(255,255,255,0)',
            'borderColor': '#7a7a7a',
        },
    ];

    $(document).on('click', '.delete a[href]', function(){
        return confirm('Do you want to delete?');
    });

    function ays_youtube_parser(url){
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        var match = url.match(regExp);
        return (match&&match[7].length==11)? match[7] : false;
    }
    
    function openMediaUploader(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            if (attachment.type != 'image') {
                return alert('Please load image file');
            }
            element.text('Edit Image');
            $('.ays-poll-question-image-container').fadeIn();
            $('img#ays-poll-img').attr('src', attachment.url);
            $('input#ays-poll-image').val(attachment.url).trigger('change');
            $('.apm-img-box').empty().fadeIn().append("<img class='ays-poll-img' src='"+attachment.url+"'>");
        }).open();
        return false;
    }

    function openMediaUploaderBg(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            var attachmentUrl = attachment.url;
            if (attachment.type != 'image') {
                return alert('Please load image file');
            }
            element.text('Edit Image');
            $('.ays-poll-bg-image-container').parent().fadeIn();
            $('img#ays-poll-bg-img').attr('src', attachmentUrl);
            $('input#ays-poll-bg-image').val(attachmentUrl).trigger('change');
            $('.box-apm').css('background-image', "url('"+ attachmentUrl +"')");
            $('#ays-poll-background-image-options').show();
        }).open();
        return false;
    }

    function openMediaUploaderLogo(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            var attachmentUrl = attachment.url;
            if (attachment.type != 'image') {
                return alert('Please load image file');
            }
            element.text('Edit Image');
            $('.ays-poll-logo-image-container').parent().fadeIn();
            $('img#ays-poll-logo-img').attr('src', attachmentUrl);
            $('input#ays-poll-logo-image').val(attachmentUrl).trigger('change');
            $(document).find(".box-apm").css('position' , 'relative');
            var imageLiveContainer = $(document).find(".ays_live_logo_container");
            if(imageLiveContainer.hasClass("display_none")){
                imageLiveContainer.removeClass("display_none");
            }
            imageLiveContainer.show();
            imageLiveContainer.html("<img src="+attachmentUrl+" width='55' height='55' class='ays_live_image_preview ays_poll_logo_image_main'>");
            var getClass = $(document).find(".box-apm");
            if(!getClass.hasClass("ays_logo_cont_image_on")){
                getClass.addClass("ays_logo_cont_image_on");
            }
            $(document).find(".ays-poll-toggle-image-url-box").removeClass("display_none");
            $(document).find(".ays-poll-toggle-image-title-box").removeClass("display_none");
        }).open();
        return false;
    }

    $(document).find(".add-logo-remove-image").on("click" , function(){
        $(document).find("#ays-poll-logo-img").attr("src" , '');
        $(document).find("#ays-poll-logo-image").val('').trigger('change');
        $(document).find(".ays_logo_image_remove").hide();
        $(document).find(".box-apm").css('position' , 'static');
        $(document).find(".ays_live_logo_container").hide();
        $(document).find(".ays_live_image_preview").attr("src" , '');
        $(document).find("a.add-logo-image").html("Add Image");
        $(document).find(".box-apm").removeClass("ays_logo_cont_image_on");
        $(this).parents(".ays_toggle_parent").find(".ays-poll-toggle-image-url-box").addClass("display_none");
        $(this).parents(".ays_toggle_parent").find(".ays-poll-toggle-image-title-box").addClass("display_none");
    });
    

    $('#ays-poll-vote-type').on('change', function () {
        var $this = $(this);
        var thisType = 'voting';
        var thisCount = $this.length;
        var thisVal = $this.val();
        livePrevToChoosing(thisType , thisCount , thisVal);
        switch ($(this).val()) {
            case 'hand':
                $('#vote-res').removeClass().addClass('ays_poll_far ays_poll_fa-thumbs-up');
                break;
            case 'emoji':
                $('#vote-res').removeClass().addClass('ays_poll_fas ays_poll_fa-smile');
                break;
            default:
                break;
        }
    });
    $('#ays-poll-rate-type').on('change', function () {
        var val = $('#ays-poll-rate-value').val();
        var pollT = 'rating';
        livePrevToChoosing(pollT , parseInt(val) , $(this).val());
        switch ($(this).val()) {
            case 'star':
                $('#rate-res').removeClass().addClass('ays_poll_fas ays_poll_fa-star');
                break;
            case 'emoji':
                $('#rate-res').removeClass().addClass('ays_poll_fas ays_poll_fa-smile');
                break;
            default:
                break;
        }
    });

    function rateType() {
        var val = $('#ays-poll-rate-value').val();
        $('#ays-poll-rate-value').empty();
        for (var i = 10; i > 2; i--) {
            if ($('#ays-poll-rate-type').val() == 'emoji' && (i == 4 || i > 5)) continue;
            if (i == 9 || i == 8 || i == 6 ) continue;
            var selected = '';
            if (i == val) {
                selected = 'selected';
            }
            var option = $("<option value="+i+" "+ selected +">"+i+"</option>");
            $('#ays-poll-rate-value').append(option)
        }
        $('#ays-poll-rate-value').show();
    }

    rateType();
    $('#ays-poll-rate-type').on('change', rateType);
    $('#ays-poll-rate-value').on('change', function(){
        var $thisType = $('#ays-poll-rate-type').val();
        var $thisVal = $(this).val();
        var $thisCType = 'rating';
        livePrevToChoosing($thisCType , parseInt($thisVal) , $thisType);
    });

    $(document).on('click','#add-answer' ,function(){
        var answersTable = $(document).find('#ays-answers-table');
        var answersTableTbody = answersTable.find('tbody');

        var aysRedirectChekbox = $(document).find('input[type="checkbox"]#ays_redirect_after_submit');
        var aysRedirectChekboxClass = '';
        if(aysRedirectChekbox.prop('checked') == false){
            aysRedirectChekboxClass = 'ays_poll_display_none';
        }
        var latestTr = answersTable.find(".ays_poll_enter_key");
        var latestTrClass = "";
        if(latestTr.length > 0){
            latestTrClass = "ays_poll_enter_key";
            if(latestTr.hasClass("ays_poll_enter_key")){
                latestTr.removeClass("ays_poll_enter_key");
            }
        }
        var answersCount = $(document).find('#ays_poll_answers_count').val();
        var id = 1 + parseInt(answersCount);
        var content = '';
        var liveContent = '';
        var liveAnsCont = $(document).find(".apm-answers");
        var checkAnsHover = $(document).find("#ays_answer_checker").val();
        liveContent += "<div class='apm-choosing answer-' data-id="+id+" data-lid="+id+">";
        liveContent += "<input type='radio' name='answer' >";
        liveContent += "<label class='ays_label_poll "+checkAnsHover+" ays_label_font_size'></label>";
        liveContent += "</div>";
        content += '<tr class="ays-answer-row ui-state-default">';
            content += '<td class="ays-sort"><div class="ays_poll_move_arrows"></div></td>';
            content += '<td class="ays-choosing-answer-container">';
                content += '<input type="text" class="ays-text-input ays-answer-value '+latestTrClass+'" name="ays-poll-answers[]" data-id="'+id+'" data-lid='+id+'>';
                content += '<input type="hidden" name="ays-poll-answers-ids[]" data-id="'+id+'" value="0">';
            content += '</td>';
            content += '<td class="ays-answer-redirect-row '+ aysRedirectChekboxClass +' ">';
                content += '<input type="text" class="ays-text-input ays_redirect_active" id="ays_submit_redirect_url_'+id+'" name="ays_submit_redirect_url[]" />';
            content += '</td>';
            content += '<td>';
                content += '<label class="ays-label" for="ays-answer">';
                    content += '<a class="ays-poll-add-answer-image add-answer-image-icon"></a>';
                content += '</label>';
                content += '<div class="ays-poll-answer-image-container" style="display:none;">';
                    content += '<span class="ays-poll-remove-answer-img"></span>';
                    content += '<img src="" class="ays-poll-answer-img"/>';
                    content += '<input type="hidden" name="ays-poll-answers-images[]" class="ays-poll-answer-image-path" value=""/>';
                content += '</div>';
            content += '</td>';
            content += '<td>';
                content += '<a href="javascript:void(0)" class="ays-delete-answer" data-id='+id+' data-lid='+id+'  data-press-key='+latestTrClass+'>';
                content += '</a>';
            content += '</td>';
        content += '</tr>';
        liveAnsCont.append(liveContent);
        answersTableTbody.append(content);
        var appendedTr = answersTableTbody.find(".ays_poll_enter_key");
        appendedTr.focus();

        var answersRow = $(document).find('#ays-answers-table tbody tr.ays-answer-row');
        var index = 1;
        if (answersRow.length > 0) {
            answersRow.each(function () {
                if ($(this).hasClass('even')) {
                    $(this).removeClass('even');
                }
                var className = ((index % 2) === 0) ? 'even' : '';
                index++;
                $(this).addClass(className);
            });
        }
        
        $(document).find('#ays_poll_answers_count').val(id);
    });

    $(document).on('click', '.remove-answer', function () {
        var childId = $(this).attr("data-id");
        $(document).find('#ays_submit_redirect_url_'+childId).parent().parent().remove();
        $(this).parent().remove();
    });

    $(document).on('click', '.ays-delete-answer', function () {
        var confirmAnswerDelete = window.confirm(pollLangObj.deleteAnswer);
        if(confirmAnswerDelete) {
            var $this = $(this);
            var index = 1;
            var rowCount = $('tr.ays-answer-row').length;
            if (rowCount > 2) {
                $this.parents("tr").css({
                    'animation-name': 'slideOutLeft',
                    'animation-duration': '.3s'
                });
                var currentElement = "ays_poll_enter_key";
                setTimeout(function(){
                    $this.parent('td').parent('tr.ays-answer-row').remove();
                    $(document).find('tr.ays-answer-row').each(function () {
                        if ($(this).hasClass('even')) {
                            $(this).removeClass('even');
                        }
                        var className = ((index % 2) === 0) ? 'even' : '';
                        $(this).addClass(className);
                        $(this).find('span.ays-radio').find('input').attr('id', 'ays-correct-answer-' + index);
                        $(this).find('span.ays-radio').find('input').val(index);
                        $(this).find('span.ays-radio').find('label').attr('for', 'ays-correct-answer-' + index);
                        index++;
                    });
                    
                        var latestAnswer = $(document).find("table#ays-answers-table input[name='ays-poll-answers[]']").last();
                        var oldValue  = latestAnswer.val();
                        latestAnswer.val(" ").trigger('change');
                        latestAnswer.val(oldValue);
                        latestAnswer.addClass(currentElement);
                        latestAnswer.focus();
                },300);
                var $thisDataId = $this.data("lid");
                $(document).find(".apm-choosing").each(function(){
                    var livePrevDataId = $(this).data("lid");
                    if(livePrevDataId == $thisDataId){
                        $(this).remove();
                    }
                });
            }
            else {
                swal({
                    type: 'warning',
                    text: pollLangObj.answersMinCount
                });
            }
        }


    });

    $(document).on('mouseover', '.remove-answer', function () {
        $(this).removeClass('ays_poll_fas').addClass('ays_poll_far');
    });
    $(document).on('mouseout', '.remove-answer', function () {
        $(this).removeClass('ays_poll_far').addClass('ays_poll_fas');
    });

    $(document).find('.nav-tab-wrapper a.nav-tab').on('click', function (e) {
        var elemenetID = $(this).attr('href');
        var activeTab = $(this).attr('data-title');
        var activeTabData = $(this).attr('data-tab');

        $(document).find('.nav-tab-wrapper a.nav-tab').each(function () {
            if ($(this).hasClass('nav-tab-active')) {
                $(this).removeClass('nav-tab-active');
            }
        });
        $(this).addClass('nav-tab-active');
        $(document).find('.ays-poll-tab-content').each(function () {
            $(this).css('display', 'none');
        });
        $('#ays_poll_active_tab').val(activeTab);
        $(document).find('#ays_poll_active_tab_settings').val(activeTabData);
        $(document).find('#ays_poll_active_tab_results').val(activeTabData);
        $('.ays-poll-tab-content' + elemenetID).css('display', 'block');
        e.preventDefault();
    });
    $('.button-primary#ays-button').on('click', function () {
        $('#ays_poll_active_tab').val("General");
    });
    $('.button-primary#ays-button-top').on('click', function () {
        $('#ays_poll_active_tab').val("General");
    });

    // Submit buttons disableing with loader
    var subButtons = '.button#ays-button-top,.button#ays-button-top-apply,.button#ays-button,.button#ays-button-apply,input#ays_submit';
    $(subButtons).on('click', function () {        
        var $this = $(this);
        submitOnce($this);
    });

    $(document).find(".button#ays-button-cat").on("click" , function(){
        var catTitle = $(document).find("#ays-title").val();
        if(catTitle != ''){
            var $this = $(this);
            subButtons += ',.button#ays-button-cat';
            submitOnce($this);
        }
    });

    function submitOnce(subButton){
        var subLoader = subButton.siblings(".display_none");
        subLoader.removeClass("display_none");
        subLoader.css("padding-left" , "8px");
        subLoader.css("display" , "inline-flex");
        subLoader.css("align-items" , "center");
        setTimeout(function() {
            $(subButtons).attr('disabled', true);
        }, 50);
        setTimeout(function() {
            $(subButtons).attr('disabled', false);
            subLoader.addClass("display_none");
        }, 5000);
    }

    function checkTheme() {
        var themeId = $(this).find('input').val() || 0;
        $('.ays_poll_theme_image_div label').each(function () {
            $(this).removeClass('apm_active_theme');
        });
        $(this).find('label').addClass('apm_active_theme');  
        $('.apm-themes-row').attr('data-themeId',themeId);
        if ($('#ays_poll_show_answers_icon').prop('checked')) {
            switch(parseInt(themeId)){
                case 3:
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');  
                    break;
                default:
                    var iconsVal = $('input[name="ays_poll_answer_icon"]:checked').val();
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $('.ays_label_poll').addClass('ays_poll_answer_icon_'+iconsVal);
                    break;
            }
        }else{
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
        }

        answerStyleChange(themeId);

        textColorChange({
            color: themes[themeId].textColor
        });
        buttonTextColorChange({
            color: themes[themeId].buttonTextColor
        });
        buttonBgColorChange({
            color: themes[themeId].buttonBgColor
        });
        mainColorChange({
            color: themes[themeId].mainColor
        });
        bgColorChange({
            color: themes[themeId].bgColor
        });
        answerBgColorChange({
            color: themes[themeId].answerBgColor
        });
        titleBgColorChange({
            color: themes[themeId].titleBgColor
        });
        iconColorChange({
            color: themes[themeId].iconColor
        });
        borderColorChange({
            color: themes[themeId].borderColor
        });
        $('#ays-poll-text-color').parent().parent().prev().css({
            'background-color': themes[themeId].textColor
        });
        $('#ays-poll-text-color').val(themes[themeId].textColor);

        $('#ays-poll-button-text-color').parent().parent().prev().css({
            'color': themes[themeId].bgColor
        });
        $('#ays-poll-button-bg-color').parent().parent().prev().css({
            'background-color': themes[themeId].mainColor
        });
        $('#ays-poll-button-text-color').val(themes[themeId].bgColor);
            
        $('#ays-poll-main-color').parent().parent().prev().css({
            'background-color': themes[themeId].mainColor
        });
        $('#ays-poll-main-color').val(themes[themeId].mainColor);

        $('#ays-poll-bg-color').parent().parent().prev().css({
            'background-color': themes[themeId].bgColor
        });
        $('#ays-poll-bg-color').val(themes[themeId].bgColor);

        $('#ays-poll-answer-bg-color').parent().parent().prev().css({
            'background-color': themes[themeId].answerBgColor
        });
        $('#ays-poll-answer-bg-color').val(themes[themeId].answerBgColor);

        $('#ays-poll-title-bg-color').parent().parent().prev().css({ 
            'background-color': themes[themeId].titleBgColor
        });
        $('#ays-poll-title-bg-color').val(themes[themeId].titleBgColor);

        $('#ays-poll-icon-color').parent().parent().prev().css({
            'background-color': themes[themeId].iconColor
        });
        $('#ays-poll-icon-color').val(themes[themeId].iconColor);
    }

    //checkTheme();
    $('.ays_poll_theme_image_div:not(.apm-pro-feature)').on('click', checkTheme);

    function checkType(type) {
        var checkType = $('#type_choosing').prop('checked');
        $('.ays_poll_type_image_div label').each(function () {
            $(this).removeClass('apm_active_type');
        });
        $(this).find('label').addClass('apm_active_type');

        var pollTypes = type;
        var pollTypeCount = 0;
        var pollVotingType = '';
        if(pollTypes == 'rating'){
            pollVotingType = $('#ays-poll-rate-type').val();
            pollTypeCount = $('#ays-poll-rate-value').val();
            $(document).find('.ays_poll_option_only_for_rating_voting_types').show();
        }
        else if(pollTypes == 'voting'){
            pollVotingType = $("#ays-poll-vote-type").val();
            pollTypeCount  = $("#ays-poll-vote-type").length;
            $(document).find('.ays_poll_option_only_for_rating_voting_types').show();
        }
        else if(pollTypes == 'text'){
            pollVotingType = $("input[name='ays_poll_text_type']:checked").val();
        }
        livePrevToChoosing(pollTypes ,pollTypeCount, pollVotingType);

        $('[class|="if"].poll-type-block ').hide().find('select, input:not([type="hidden"]):not(.ays_redirect_active)').attr('data-required', false);
        $('.if-' + pollTypes).css('display', 'flex').find('select, input:not([type="hidden"]):not(.ays_redirect_active)').attr('data-required', true);
        $(document).find('#ays_poll_question_text_max_length').attr('data-required', false);
        $(document).find('#ays_poll_text_type_placeholder').attr('data-required', false);
        $(document).find('#ays_poll_text_type_width').attr('data-required', false);
        $('.if-choosing').find('select, input:not([type="hidden"]):not(.ays_redirect_active)').attr('data-required', false);
        checkHr(checkType ,pollTypes);

        if(type == 'text'){
            ays_tmce_setContent('<p style="text-align: center;">Thank you!</p>','ays_result_message');

            // Set alignment default value to center
            $(document).find('#apm-dir-center').prop('checked', true);
        }

        if (type != 'choosing') {
            $(document).find('.ays_poll_option_only_for_choosing_type').hide();
            
            if (type != 'text') {
                $(document).find('.ays_poll_option_for_choosing_type.ays_poll_option_for_text_type').hide();
            }
        }
    }

    function ays_tmce_setContent(content, editor_id, textarea_id) {
        if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
        if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
        if ( $(document).find('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE && tinyMCE.get(editor_id) ) {
          return tinyMCE.get(editor_id).setContent(content);
        }else{
          return $(document).find('#'+textarea_id).val(content);
        }
    }

    var pollType = $(document).find('#poll_choose_type_first').val();
    $('.if-' + pollType).css('display', 'flex').find('select, input:not([type="hidden"]):not(.ays_redirect_active)').attr('data-required', true);
    $('.if-choosing').find('select, input:not([type="hidden"]):not(.ays_redirect_active)').attr('data-required', false);
    $(document).find('#ays_poll_question_text_max_length').attr('data-required', false);
    $(document).find('#ays_poll_text_type_placeholder').attr('data-required', false);
    $(document).find('#ays_poll_text_type_width').attr('data-required', false);
   
    $('.ays_poll_type_image_div:not(.apm-pro-feature)').on('click', checkType);

    function livePrevToChoosing(type , countSel , rateType){
        if (typeof countSel == 'undefined') {
            countSel = 0;
        }
        if (typeof rateType == 'undefined') {
            countSel = '';
        }
        $(document).find('.box-apm').removeClass().addClass('box-apm '+type+'-poll');
        var content  = '';
        var contText = '';
        var checkAnsHover = $(document).find("#ays_answer_checker").val();
        var cClass   = '';
        var contText = '';
        switch(type){
            case "choosing":
                $(document).find(".ays-answer-value").each(function(index){
                    var image = $(this).parents("tr").find(".ays-poll-answer-img").attr("src");
                    var imageContainer = '';
                    var labelContainerClass = '';
                    var imageTextClass = '';
                    if(image != ""){
                        imageContainer = '<div><img src="'+image+'" class="ays-poll-answer-image-live"></div>';
                        labelContainerClass = 'ays_poll_label_without_padding';
                        imageTextClass = 'ays_poll_label_text_with_padding';
                    }
                    cClass    = "ays_label_poll "+checkAnsHover+" ays_label_font_size";
                    contText  = $(this).val();
                    content  += "<div class='apm-"+type+" ays-poll-field ays_poll_list_view_item answer- ' data-id="+index+" data-lid="+index+"> ";
                    content  += "<input type='radio' name='answer' id='radio-"+index+"-' value='"+index+"'>";
                    content  += "<label class='"+cClass+" "+labelContainerClass+" ays-poll-answer-more-options' for='radio-"+index+"-' >"+imageContainer+" <div><span class='ays-poll-each-answer "+imageTextClass+"'>"+contText+"</span></div></label>";
                    content  += "</div>";
                });
                break;
            case "rating":
                var emoji = new Array(
                    "<i class='ays_poll_far ays_poll_fa-dizzy'></i>",
                    "<i class='ays_poll_far ays_poll_fa-smile'></i>",
                    "<i class='ays_poll_far ays_poll_fa-meh'></i>",
                    "<i class='ays_poll_far ays_poll_fa-frown'></i>",
                    "<i class='ays_poll_far ays_poll_fa-tired'></i>"
                );
                if(rateType == 'emoji'){
                    var valueIndex = countSel;
                    if(countSel == 4){
                        valueIndex = 5;
                    }
                    for(var i = 0; i < valueIndex; i++){
                    content += "<div class='apm-"+type+" answer- ' data-id="+i+">";
                    content += "<input type='radio' name='answer' id='radio-"+i+"-' value='"+i+"'>"
                    content += "<label class="+cClass+" for='radio-"+i+"-' >"+emoji[(valueIndex / 2) - i + 1.5]+"</label>"
                    content += "</div>";
                    }
                }
                else if(rateType == 'star'){
                    for(var i = 0; i < countSel; i++){
                    contText = "<i class='ays_poll_far ays_poll_fa-star'></i>";
                    content += "<div class='apm-"+type+" answer- ' data-id="+i+">";
                    content += "<input type='radio' name='answer' id='radio-"+i+"-' value='"+i+"'>"
                    content += "<label class="+cClass+" for='radio-"+i+"-' >"+contText+"</label>"
                    content += "</div>";
                    }
                }
                break;
            case "voting":
                var emojiThumb;
                var toUp = '';
                var toDown = '';
                if(rateType == "hand"){
                    toUp = 'thumbs-up'; 
                    toDown = 'thumbs-down';
                }
                else if(rateType == "emoji"){
                    toUp = 'smile';
                    toDown = 'frown';
                }
                var emojiThumb = new Array(
                    '<i class="ays_poll_far ays_poll_fa-'+toUp+'"></i>',
                    '<i class="ays_poll_far ays_poll_fa-'+toDown+'"></i>'
                );
                for(var i = 0 ; i <= countSel ; i++){
                    content += "<div class='apm-"+type+" answer- ' data-id="+i+">";
                    content += "<input type='radio' name='answer' id='radio-"+i+"-' value='"+i+"'>"
                    content += "<label class="+cClass+" for='radio-"+i+"-' >"+emojiThumb[i]+"</label>"
                    content += "</div>";
                }
                break;
            case "text":
                var textType = rateType;
                var textPlaceholder     = $(document).find("#ays_poll_text_type_placeholder");
                var textPlaceholderVal  = textPlaceholder.val();
                switch(textType){
                    case "short_text":
                        content += "<div class='ays-poll-maker-"+type+"-live-preview' >";
                            content += "<input type='text' id='ays_poll_text_type_short_live' placeholder='"+textPlaceholderVal+"' readonly class='ays-poll-text-type-fields'>"
                            content += "<label for='ays_poll_text_type_short_live'></label>"
                        content += "</div>";
                        break;
                    case "paragraph":
                        content += "<div class='ays-poll-maker-"+type+"-live-preview'>";
                            content += "<textarea id='ays_poll_text_type_paragraph_live' readonly placeholder='"+textPlaceholderVal+"' class='ays-poll-text-type-fields'></textarea>"
                            content += "<label for='ays_poll_text_type_paragraph_live'></label>"
                        content += "</div>";
                        break;
                }
                break;
        }
        $(document).find(".apm-answers").html(content);
    }
    function goToPro() {
        window.open(
            'https://ays-pro.com/wordpress/poll-maker/',
            '_blank'
        );
        return false;
    }

    $(document).on('click', "[id|='select2'] li[id$='-pro']", goToPro);
    $('.ays_poll_theme_image_div.apm-pro-feature').on('click', goToPro);
    $('.apm-loader.apm-pro-feature').on('click', goToPro);
    $('.apm-pro-feature-link').on('click', goToPro);

    //**********************/
    //LIVE PREVIEW
    //*********************/
    var themeId = $('.apm_active_theme+input').val() || 0;

    $(document).find('#ays-poll-main-color').wpColorPicker({
        defaultColor: themes[themeId].mainColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].mainColor});
            mainColorChange(ui);
        }
    });
    $(document).find('#ays-poll-text-color').wpColorPicker({
        defaultColor: themes[themeId].textColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].textColor});
            textColorChange(ui);
        }
    });
    $(document).find('#ays-poll-button-text-color').wpColorPicker({
        defaultColor: themes[themeId].buttonTextColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].buttonTextColor});
            buttonTextColorChange(ui);
        }
    });
    $(document).find('#ays-poll-button-bg-color').wpColorPicker({
        defaultColor: themes[themeId].buttonBgColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].buttonBgColor});
            buttonBgColorChange(ui);
        }
    });
    $(document).find('#ays-poll-icon-color').wpColorPicker({
        defaultColor: themes[themeId].iconColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].iconColor});
            iconColorChange(ui);
        }
    });
    $(document).find('#ays-poll-bg-color').wpColorPicker({
        defaultColor: themes[themeId].bgColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].bgColor});
            bgColorChange(ui);
        }
    });
    $(document).find('#ays-poll-answer-bg-color').wpColorPicker({
        defaultColor: themes[themeId].answerBgColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].answerBgColor});
            answerBgColorChange(ui);
        }
    });
    $(document).find('#ays-poll-title-bg-color').wpColorPicker({
        defaultColor: themes[themeId].titleBgColor,
        change: function(event, ui) {
            $(this).wpColorPicker({defaultColor: themes[$('.apm_active_theme+input').val()].titleBgColor});
            titleBgColorChange(ui);
        }
    });
    $(document).find('#ays-poll-box-shadow-color').wpColorPicker({
        defaultColor: '#000000',
        change: function(event, ui) {
            boxShadowColorChange(ui);
        }
    });


    $(document).find('#ays-background-gradient-color-1').wpColorPicker({
        defaultColor: '#103251',
        change: function(event, ui) {
            toggleBackgrounGradient();
        }
    });
    $(document).find('#ays-background-gradient-color-2').wpColorPicker({
        defaultColor: '#607593',
        change: function(event, ui) {
            toggleBackgrounGradient();
        }
    });
    
    $(document).find('#ays-poll-border-color').wpColorPicker({
        defaultColor: '#0C6291',
        change: function(event, ui) {
            borderColorChange(ui);
        }
    });
    $(document).find('#ays_poll_title_text_shadow_color').wpColorPicker({
        defaultColor: 'rgba(255,255,255,0)',
        change: function(event, ui) {
            textShadowChange(ui);
        }
    });
    $(document).find('.ays-title-text-shadow-coord-change').on('change', function(){
        var color =  $(document).find('#ays_poll_title_text_shadow_color').val();
        textShadowChange(color);
    });

    $(document).find('#ays_poll_enable_title_text_shadow').on('change', function(){
        var color =  $(document).find('#ays_poll_title_text_shadow_color').val();
        var checkedShadow = $(this).prop('checked');
        if(checkedShadow){
            textShadowChange(color);
        }else{
            $(document).find(".apm-title-box > h5").css("text-shadow" , "unset");
        }
    });

    $(document).find('#ays_poll_answers_box_shadow_color').wpColorPicker({
        defaultColor: '#0C6291',
        change: function(event, ui) {
        }
    });
    if($(document).find('#ays-poll-bg-image').val() != ''){
        $('.box-apm').css('background-image', 'url('+ $(document).find('#ays-poll-bg-image').val() +')');
        $('.box-apm').css('background-position', $(document).find('#ays-poll-bg-image-pos').val());
    }


    function mainColorChange(ui) {
        var color = ui.color.toString();
        document.documentElement.style.setProperty('--theme-main-color', color);
        $('#ays-poll-main-color')
            .attr('value', color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function textColorChange(ui) {
        var color = ui.color.toString();
        document.documentElement.style.setProperty('--theme-text-color', color);
        $('#ays-poll-text-color')
            .attr('value', color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function buttonTextColorChange(ui) {
        var color = ui.color.toString();
        $(document).find('input[type="button"].ays-poll-btn').css('color', color);
        $('#ays-poll-button-text-color')
            .attr('value', color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function buttonBgColorChange(ui) {
        var color = ui.color.toString();
        $(document).find('input[type="button"].ays-poll-btn').css('background-color', color);
        $('#ays-poll-button-bg-color')
            .attr('value', color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function iconColorChange(ui) {
        var color = ui.color.toString();
        document.documentElement.style.setProperty('--theme-icon-color', color);
        $('#ays-poll-icon-color')
            .attr('value', color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function bgColorChange(ui) {
        var color = ui.color.toString();
        document.documentElement.style.setProperty('--theme-bg-color', color);
        $('#ays-poll-bg-color')
            .attr('value', color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function borderColorChange(ui) {
        var color = ui.color.toString();
        $(document).find(".box-apm").css("border-color" , color);
        $('#ays-poll-border-color')
            .val(color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function textShadowChange(ui , isChecked) {
        if (typeof ui == 'object') {
            var color = ui.color.toString();
        }else{
            var color = ui;
        }
        var x_offset = $(document).find('input#ays_poll_title_text_shadow_x_offset').val() + "px ";
        var y_offset = $(document).find('input#ays_poll_title_text_shadow_y_offset').val() + "px ";
        var z_offset = $(document).find('input#ays_poll_title_text_shadow_z_offset').val() + "px ";

        var boxShadow = x_offset + y_offset + z_offset;
        if($(document).find('#ays_poll_enable_title_text_shadow').prop("checked")){
            $(document).find(".apm-title-box > h5").css("text-shadow" , boxShadow+" "+ color);
            
        }
        $('#ays_poll_title_text_shadow_color')
            .val(color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    var answer_border_side = $(document).find('#ays-poll-border-side').val();
    answerBorderSideChange(answer_border_side);

    function answerBorderSideChange(side) {
        var checked_answ = $(document).find( '#ays_poll_enable_answer_style').prop("checked");
        if (side == 'none' || !checked_answ) {
            $(document).find('.box-apm.choosing-poll label').each(function(){
                $(this)[0].style.border = 'none';
            });
        }else{
            switch(side) {
              case 'all_sides':
                $(document).find('.box-apm.choosing-poll label').each(function(){
                    $(this)[0].style.border = '1px solid';
                });
                break;
              case 'top':
                $(document).find('.box-apm.choosing-poll label').each(function(){
                    $(this)[0].style.border = 'none';
                    $(this)[0].style.borderTop = '1px solid';
                });
                break;
              case 'bottom':
                $(document).find('.box-apm.choosing-poll label').each(function(){
                    $(this)[0].style.border = 'none';
                    $(this)[0].style.borderBottom = '1px solid';
                });
                break;
              case 'left':
                $(document).find('.box-apm.choosing-poll label').each(function(){
                    $(this)[0].style.border = 'none';
                    $(this)[0].style.borderLeft = '1px solid';
                });
                break;
              case 'right':
                $(document).find('.box-apm.choosing-poll label').each(function(){
                    $(this)[0].style.border = 'none';
                    $(this)[0].style.borderRight = '1px solid';
                });
                break;
            }
        }
    }
    
    function answerStyleChange(theme_id) {
        if (theme_id == 3) {
            $('.box-apm').find('.apm-choosing').addClass('ays_poll_minimal_theme');
            $('.box-apm').find('.btn.ays-poll-btn').addClass('ays_poll_minimal_theme_btn');
            $('#ays_poll_enable_answer_style').prop('checked', false).trigger("change");
            $('#ays_poll_answers_box_shadow_enable').prop('checked', false).trigger("change");
            $('.ays_answer_style').hide();
            answerBorderSideChange("none");
            $('#ays-poll-border-width').val(0);
            document.documentElement.style.setProperty('--poll-border-width', "0px");
        }else{
            $('.box-apm').find('.apm-choosing').removeClass('ays_poll_minimal_theme');
            $('.box-apm').find('.btn.ays-poll-btn').removeClass('ays_poll_minimal_theme_btn');
            $('#ays_poll_enable_answer_style').prop('checked' , true);
            $('.ays_answer_style').show();
            answerBorderSideChange("all_sides");
        }        
    }

    function answerBgColorChange(ui) {
        if (typeof ui == 'object') {
            var color = ui.color.toString();
        }else{
            var color = ui;
        }

        if (color == 'transparent' || !$(document).find('#ays_poll_enable_answer_style').prop("checked")) {
            document.documentElement.style.setProperty('--theme-answer-bg-color', "initial");
        }else{
            document.documentElement.style.setProperty('--theme-answer-bg-color', color);
            $('#ays-poll-answer-bg-color')
                .attr('value', color)
                .parents('.wp-picker-container')
                .find('.color-alpha').css('background-color', color);
        }
        
    }

    function titleBgColorChange(ui) { //aray
        var color = ui.color.toString();
        document.documentElement.style.setProperty('--theme-title-bg-color', color);
        $('#ays-poll-title-bg-color')
            .attr('value', color)
            .parents('.wp-picker-container')
            .find('.color-alpha').css('background-color', color);
    }

    function boxShadowColorChange(ui) {
        if (typeof ui == 'object') {
            var color = ui.color.toString();
        }else{
            var color = ui;
        }
        
        if (color == 'transparent' || !$(document).find('#ays_poll_enable_box_shadow').prop("checked")) {
            document.documentElement.style.setProperty('--poll-box-shadow', "initial");
        }else{
            var x_offset = $(document).find('input#ays_poll_box_shadow_x_offset').val() + "px ";
            var y_offset = $(document).find('input#ays_poll_box_shadow_y_offset').val() + "px ";
            var z_offset = $(document).find('input#ays_poll_box_shadow_z_offset').val() + "px ";

            var boxShadow = x_offset + y_offset + z_offset;

            document.documentElement.style.setProperty('--poll-box-shadow', color + " " + boxShadow + " 1px");
            $('#ays-poll-box-shadow-color')
                .attr('value', color)
                .parents('.wp-picker-container')
                .find('.color-alpha').css('background-color', color);
            }
    }
    
    /* 
    ========================================== 
        Background Gradient 
    ========================================== 
    */
   function toggleBackgrounGradient() {
        if ($(document).find('#ays-enable-background-gradient').prop('checked') || $(document).find('input#ays-poll-bg-image').val() != '') {
            if($(document).find('#ays_poll_gradient_direction').val() != '') {
                var ays_poll_gradient_direction = $(document).find('#ays_poll_gradient_direction').val();
                switch(ays_poll_gradient_direction) {
                    case "horizontal":
                        ays_poll_gradient_direction = "to right";
                        break;
                    case "diagonal_left_to_right":
                        ays_poll_gradient_direction = "to bottom right";
                        break;
                    case "diagonal_right_to_left":
                        ays_poll_gradient_direction = "to bottom left";
                        break;
                    default:
                        ays_poll_gradient_direction = "to bottom";
                }

                if($(document).find('input#ays-poll-bg-image').val() != ''){
                    return false;
                }else{
                    $(document).find('.box-apm').css({'background-image': "linear-gradient(" + ays_poll_gradient_direction + ", " + $(document).find('input#ays-background-gradient-color-1').val() + ", " + $(document).find('input#ays-background-gradient-color-2').val()+")"
                    });
                }
            }
        }
        else{
            $(document).find('.box-apm').css('background-image' , "unset");
            return false;
        }
    }

    var ays_poll_box_gradient_color1_picker = {
            change: function (e) {
                setTimeout(function () {
                    toggleBackgrounGradient();
                }, 1);
            }
        };
        
    var ays_poll_box_gradient_color2_picker = {
        change: function (e) {
            setTimeout(function () {
                toggleBackgrounGradient();
            }, 1);
        }
    };

    function ays_poll_search_box_pagination(listTableClass, searchBox) {
        if($(document).find( "." + listTableClass ).length) {
            if($(document).find( "#" + searchBox ).length) {
                var search_string = $(document).find("#" + searchBox).val();
                if(search_string != "") {
                    $(document).find("."+ listTableClass +" .pagination-links a").each(function() {
                        if ( typeof this.href != "undefined" && this.href != "" ) {
                            if ( this.href.indexOf("&s=") < 0 ) {
                                this.href = this.href + "&s=" + search_string;
                            }
                        }
                    });
                }
            }
        }
    }

    $(document).find('#ays_poll_gradient_direction').on('change', function () {
        toggleBackgrounGradient();
    });

    $(document).find('#ays-background-gradient-color-1').wpColorPicker(ays_poll_box_gradient_color1_picker);
    $(document).find('#ays-background-gradient-color-2').wpColorPicker(ays_poll_box_gradient_color2_picker);

    toggleBackgrounGradient();
    $(document).find('input#ays-enable-background-gradient').on('change', function () {
        toggleBackgrounGradient();
    });

    $(document).on('click', '.ays-remove-poll-bg-img', function () {
        $(document).find('.box-apm').css({'background-image': 'none'});
        toggleBackgrounGradient();
    });

    $(document).find('#ays_poll_enable_answer_style').on('change', function () {
        var checkboxProp = $(this).prop('checked');
        var color = $(this).prop('checked') ? $(document).find('#ays-poll-answer-bg-color').val() : "transparent";
        var side = $(this).prop('checked') ? $(document).find('#ays-poll-border-side').val() : "none";
        answerBgColorChange(color);
        answerBorderSideChange(side);
        refreshLivePreview(checkboxProp);
    });

    $(document).find('#ays_poll_enable_box_shadow').on('change', function () {
        var color = $(this).prop('checked') ? $(document).find('#ays-poll-box-shadow-color').val() : "transparent";
        boxShadowColorChange(color);
    });

    $(document).find('input.ays-box-shadow-coord-change').on('change', function () {
        var color = $(document).find('#ays-poll-box-shadow-color').val();
        boxShadowColorChange(color);
    });

    $(document).find('#ays-enable-background-gradient').on('change', function () {
        var color = $(this).prop('checked') ? $(document).find('#ays-background-gradient-color-1').val() : "transparent";
        toggleBackgrounGradient(color);
    });

    $(document).find('#ays-enable-background-gradient').on('change', function () {
        var color = $(this).prop('checked') ? $(document).find('#ays-background-gradient-color-2').val() : "transparent";
        toggleBackgrounGradient(color);
    });

    $('#ays-poll-icon-size').on('change', function () {
        var val = (+$(this).val() > 10) ? $(this).val() : 10;
        $(this).val(val);
        var value = val + 'px';
        document.documentElement.style.setProperty('--poll-icons-size', value);
    });

    $('#ays-poll-width').on('change', function () {
        var val = +$(this).val();
        if (val === 0) {
            val = "100%";
        } else {
            val = (val > 249 ? val : 250) + "px";
        }
        document.documentElement.style.setProperty('--poll-width', val);
    });

    $('#ays-poll-btn-text').on('change', function () {
        var val = $(this).val() !== '' ? $(this).val() : "Vote";
        $('.ays-poll-btn').val(val)
    });

    $('#ays-poll-border-style').on('change', function () {
        var val = $(this).val();
        document.documentElement.style.setProperty('--poll-border-style', val);
    });

    $('#ays-poll-border-side').on('change', function () {
        var val = $(this).val();        
        
        switch(val) {
          case 'none':
            $(document).find('.box-apm.choosing-poll label').each(function(){
                $(this)[0].style.border = 'none';
            });
            break;
          case 'all_sides':
            $(document).find('.box-apm.choosing-poll label').each(function(){
                $(this)[0].style.border = '1px solid';
            });
            break;
          case 'top':
            $(document).find('.box-apm.choosing-poll label').each(function(){
                $(this)[0].style.border = 'none';
                $(this)[0].style.borderTop = '1px solid';
            });
            break;
          case 'bottom':
            $(document).find('.box-apm.choosing-poll label').each(function(){
                $(this)[0].style.border = 'none';
                $(this)[0].style.borderBottom = '1px solid';
            });
            break;
          case 'left':
            $(document).find('.box-apm.choosing-poll label').each(function(){
                $(this)[0].style.border = 'none';
                $(this)[0].style.borderLeft = '1px solid';
            });
            break;
          case 'right':
            $(document).find('.box-apm.choosing-poll label').each(function(){
                $(this)[0].style.border = 'none';
                $(this)[0].style.borderRight = '1px solid';
            });
            break;
        }
    });

    $(document).on('click', 'a.add-poll-bg-music', function (e) {
        openMusicMediaUploader(e, $(this));
    });

    $('#ays-poll-border-radius').on('change', function () {
        var val = $(this).val();
        var value = val + 'px';
        document.documentElement.style.setProperty('--poll-border-radius', value);
    });

    $('#ays-poll-border-width').on('change', function () {
        var val = $(this).val();
        var value = val + 'px';
        document.documentElement.style.setProperty('--poll-border-width', value);
    });

    $('#ays_custom_css').on('change', function () {
        var val = $(this).val();
        $('#apm-custom-css').html(val);
    });

    $('#show-title').on('change', function () {
        $('.apm-title-box').fadeToggle();
    });

    $('#ays-poll-title').on('change', function () {
        var val = $(this).val();
        $('.apm-title-box h5').text(val);
    });   
    var checkAnswerStyle = $(document).find("#ays_poll_enable_answer_style").prop("checked");
    $(document).on('change', '.ays-poll-question-font-size,#ays_poll_question_image_height,#ays_answers_view,#ays_poll_answer_img_height,#ays_poll_answer_image_border_radius,#ays_poll_image_background_size,#ays_poll_answers_padding,#ays_poll_answers_margin,#ays_poll_title_font_size,#ays_poll_title_alignment,#ays_poll_text_type_placeholder,#ays_poll_question_image_object_fit,#ays_poll_answers_box_shadow_enable,#ays_poll_answers_box_shadow_color,#ays_poll_answer_border_radius,#ays_poll_answer_box_shadow_x_offset,#ays_poll_answer_box_shadow_y_offset,#ays_poll_answer_box_shadow_z_offset' , function () {
        var checkAnswerStyleOnChange = $(document).find("#ays_poll_enable_answer_style").prop("checked");
        refreshLivePreview(checkAnswerStyleOnChange);
    });    

    //PRO features lightbox
    $('.open-lightbox').on('click', function (e) {
        e.preventDefault();
        var image = $(this).attr('href');
        $('html').addClass('no-scroll');
        $('.ays-poll-row ').append('<div class="lightbox-opened"><img src="' + image + '"></div>');
    });
    $('body').on('click', '.lightbox-opened', function () {
        $('html').removeClass('no-scroll');
        $('.lightbox-opened').remove();
    });

    $(document).on('change', '.ays_toggle', function (e) {
        var state = $(this).prop('checked');
        if ($(this).hasClass('ays_toggle_slide')) {
            switch (state) {
                case true:
                    $(this).parent().find('.ays_toggle_target').slideDown(250);
                    if($(this).parent().find('.ays_toggle_target').hasClass("display_none")){
                        $(this).parent().find('.ays_toggle_target').removeClass("display_none");
                    }
                    break;
                case false:
                    $(this).parent().find('.ays_toggle_target').slideUp(250);
                    break;
            }
        } else {
            switch (state) {
                case true:
                    $(this).parent().find('.ays_toggle_target').show(250);
                    break;
                case false:
                    $(this).parent().find('.ays_toggle_target').hide(250);
                    break;
            }
        }
    });

    $(document).on('change', '.ays_toggle_checkbox', function (e) {
        var state = $(this).prop('checked');
        var parent = $(this).closest('.ays_toggle_parent');

        if($(this).hasClass('ays_toggle_slide')){
            switch (state) {
                case true:
                    parent.find('.ays_toggle_target').slideDown(500);
                    break;
                case false:
                    parent.find('.ays_toggle_target').slideUp(500);
                    break;
            }
        }else{
            var targetEl = parent.find('.ays_toggle_target');
            for (let i = 0; i<targetEl.length; i++) {
                if(targetEl.eq(i).closest('.ays_toggle_parent')[0] != parent[0]) {
                    targetEl.splice(i, 1);
                    i--;
                }
            }

            switch (state) {
                case true:
                    targetEl.show(250);
                    break;
                case false:
                    targetEl.hide(250);
                    break;
            }
        }
    });

    $(document).on('click', '.ays_poll_block_with_hidden_row_show_row_block, .ays_poll_block_with_hidden_row_hide_row_block', toggleHiddenBlockRow)

    function toggleHiddenBlockRow() {
        var isShow = $(this).hasClass('ays_poll_block_with_hidden_row_show_row_block');
        var mainBlock = $(this).parents('.ays_poll_block_with_hidden_row');

        if (isShow) {
            $(mainBlock).find(".ays_poll_hidden_block_content").show(200);
            $(mainBlock).find(".ays_poll_hidden_hr_content").show(200);
        } else {
            $(mainBlock).find(".ays_poll_hidden_block_content").hide(200);
            $(mainBlock).find(".ays_poll_hidden_hr_content").hide(200);
        }
    }

    let toggle_ddmenu = $(document).find('.toggle_ddmenu');
        toggle_ddmenu.on('click', function () {
            let ddmenu = $(this).next();
            let state = ddmenu.attr('data-expanded');
            switch (state) {
                case 'true':
                    $(this).find('.ays_poll_fa').css({
                        transform: 'rotate(0deg)'
                    });
                    ddmenu.attr('data-expanded', 'false');
                    break;
                case 'false':
                    $(this).find('.ays_poll_fa').css({
                        transform: 'rotate(90deg)'
                    });
                    ddmenu.attr('data-expanded', 'true');
                    break;
        }
    });

    $(document).find('table.ays-answers-table tbody').sortable({
        handle: 'td.ays-sort',
        cursor: 'move',
        opacity: 0.8,
        tolerance: "pointer",
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
              // Set helper cell sizes to match the original sizes
              $(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        axis: 'y',
        revert: true,
        forcePlaceholderSize: true,
        forceHelperSize: true,
        start: function(event, ui) {
            $(this).find('th:nth-child(4)').addClass('ays_poll_display_none');
            $(this).find('th:nth-child(3)').addClass('ays_poll_display_none');
            if( $(this).find('td.ays-f-votes-row').hasClass('ays_poll_display_none')){
                $(this).find('td:nth-child(4)').addClass('ays_poll_display_none');
            }
            if( $(this).find('td.ays-answer-redirect-row').hasClass('ays_poll_display_none')){
                $(this).find('td:nth-child(3)').addClass('ays_poll_display_none');
            }
            ui.helper.css('display', 'table')
        },
        stop: function(event, ui) {
            ui.item.css('display', '');
        },
        update: function (event, ui) {
            var className = ui.item.attr('class').split(' ')[0];
            $('.ays-answers-table').find('tr.' + className).each(function (index) {
                var classEven = (((index + 1) % 2) === 0) ? 'even' : '';
                if ($(this).hasClass('even')) {
                    $(this).removeClass('even');
                }
                $(this).addClass(classEven);
                $(this).children("td:nth-child(2)").find(".ays-answer-value").attr('data-lid' , index).trigger('change');
                $(this).children("td:nth-child(5)").find(".ays-delete-answer").attr('data-lid' , index);
            });
            livePrevToChoosing('choosing');
        }
    });

    $(document).find('.poll-maker-challenge-cancel').on('click', function() {
        var challengeBox = $(this).closest('.poll-maker-challenge');

        $.ajax({
            url: poll.ajax,
            type: 'POST',
            data: { action: 'delete_challenge_box' },
            success: function(response) {
                challengeBox.remove();
            }
        });
    })

    $(document).find('#ays_poll_create_author').select2({
        placeholder: 'Select users',
        minimumInputLength: 1,
        allowClear: true,
        language: {
            // You can find all of the options in the language files provided in the
            // build. They all must be functions that return the string that should be
            // displayed.
            inputTooShort: function () {
                return poll.pleaseEnterMore;
            }
        },
        ajax: {
            url: poll.ajax,
            dataType: 'json',
            data: function (response) {
                var checkedUsers = $(document).find('#ays_poll_create_author').val();
                return {
                    action: 'ays_poll_create_author',
                    search: response.term,
                    val: checkedUsers,
                };
            },
        }
    });

    $(document).on("input", 'input', function (e) {
        if (e.keyCode == 13) {
            return false;
        }
    });
    // $(document).on("keydown", function (e) {
    //     if (e.target.nodeName == "TEXTAREA") {
    //         return true;
    //     }
    //     if (e.keyCode == 13) {
    //         return false;
    //     }
    //     if(e.keyCode === 27){
    //         $(document).find('.ays-modal').aysModal('hide');
    //         $(document).find('.ays-modal-backdrop').remove();
    //         return false;
    //     }
    // });

    $('.active_date_check').change(function () {
        if ($(this).prop('checked')) {
            $('.active_date').show(250);
        } else {
            $('.active_date').hide(250);
        }
    });

    //Each results
    if ($('.unread-result-badge.unread-result').length > 0) {
        $('.unread-result-badge.unread-result').each(function () {
            $(this).parent().parent().find('td').css('font-weight', 'bold')
        })
    }

    //Each results
    if ($('.ays_poll_unread').length > 0) {
        $('.ays_poll_unread').each(function () {
            $(this).parent().parent().find('td').css('font-weight', 'bold')
        })
    }

    $('#ays_poll_show_timer').change(function () {
        if ($(this).prop('checked')) {
            $('.ays_show_time').show(250);
        } else {
            $('.ays_show_time').hide(250);
        }
    });

    $('#show_result_btn_schedule').change(function () {
        if ($(this).prop('checked')) {
            $('.result_btn_see').show(250);
        } else {
            $('.result_btn_see').hide(250);
        }
    });

    //info_form
    $("#ays_poll_info_form").change(function () {
        if ($(this).prop('checked')) {
            $(this).parents(".form-group").find(".form-group").show(250);
        } else {
            $(this).parents(".form-group").find(".form-group").hide(250);
        }
    });

    $('#ays_users_roles').select2();
    $('#ays_user_roles').select2();
    $('[data-toggle="tooltip"]').tooltip();

    $(document).find('#ays-deactive, #ays-active, #ays_poll_change_creation_date').datetimepicker({
        controlType: 'select',
        oneLine: true,
        dateFormat: "yy-mm-dd",
        timeFormat: "HH:mm:ss"
    });

    // Codemirror 
    setTimeout(function(){
        if($(document).find('#ays_custom_css').length > 0){
            if(wp.codeEditor){
                wp.codeEditor.initialize($(document).find('#ays_custom_css'), cm_settings);
            }
        }
    }, 500);

    $(document).find('a[href="#tab2"]').on('click', function (e) {        
        setTimeout(function(){
            if($(document).find('#ays_custom_css').length > 0){
                var ays_custom_css = $(document).find('#ays_custom_css').html();
                if(wp.codeEditor){
                    $(document).find('#ays_custom_css').next('.CodeMirror').remove();
                    wp.codeEditor.initialize($(document).find('#ays_custom_css'), cm_settings);
                    $(document).find('#ays_custom_css').html(ays_custom_css).trigger('change');
                }
            }
        }, 500);
    });

    var unread_result_parent = $(document).find(".unread-result").parent().parent();

    if (unread_result_parent != undefined) {
        unread_result_parent.css({"font-weight":"bold"});
    }

    $(document).find('input[type="checkbox"]#ays_redirect_after_submit').on('change', function(e){
        var answerRedirectRow = $(document).find('#ays-answers-table .ays-answer-redirect-row');
        if($(this).prop('checked') == false){
            answerRedirectRow.addClass('ays_poll_display_none');
        }else{
            if (answerRedirectRow.hasClass('ays_poll_display_none')) {
                answerRedirectRow.removeClass('ays_poll_display_none');
            }
        }

    });
    
    $(document).on('change' ,"#ays_disable_answer_hover", disable_answer_hover);

    function disable_answer_hover(){
        var checkLabelHover = $(document).find('label.ays_label_poll');
        var checkCurrentHover = $(document).find("#ays_answer_checker");
        if(checkLabelHover.hasClass('ays_enable_hover') && !checkLabelHover.hasClass('disable_hover')){
            checkLabelHover.addClass('disable_hover');
            checkLabelHover.removeClass('ays_enable_hover');
            checkCurrentHover.val('disable_hover');
        }else{            
            checkLabelHover.addClass('ays_enable_hover');
            checkLabelHover.removeClass('disable_hover');
            checkCurrentHover.val('ays_enable_hover');
        }
    }

    $('#ays_add_postcat_for_poll').select2();

    function openMusicMediaUploader(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload music',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'audio'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            element.next().attr('src', attachment.url);
            element.parent().find('input.ays_poll_bg_music').val(attachment.url).trigger('change');
        }).open();
        return false;
    }

    $(document).find('#ays_poll_bg_image_position').on('change', function () {
        var pollContainer = $(document).find('.box-apm ,.choosing-poll');
        pollContainer.css({
            'background-position': $(this).val()
        });
    });
    
    $(document).find("#ays_answer_font_size,#ays_poll_answer_font_size_mobile").on("change" , function(){
        var thisSize = parseInt($(this).val());
        var detectType = $(this).data("device");
        if(thisSize < 10){
            thisSize = 10;
        }
        if(thisSize > 90){
            thisSize = 90;
        }
        $(this).val(thisSize);
        if(detectType != "mobile"){
            $(document).find('.ays_label_font_size').css("font-size" , thisSize + "px");
            $(document).find('.ays-poll-text-type-fields').css({ 'font-size': thisSize + "px" });
        }
    });

    
    refreshLivePreview(checkAnswerStyle);
    // function for Live Preview 
    function refreshLivePreview(checker){
        // Defaults
        var imageDefaultHeight    = "150";
        var imageDefaulObjFit     = "cover";
        var answerDefaultPadding  = "10";
        var answerDefaultGap      = "10";
        var answerDefaultFontSize = "16";
        var answerDefaultFontSizeMobile = "16";
        var titleDefaultFontSize  = "20";
        var answersBorderRadiusDefault = "0";

        // Boxes
        var imageBoxes       = $(document).find('.box-apm .ays-poll-answer-image-live');
        var answerLabelBoxes = $(document).find('.box-apm label.ays-poll-answer-more-options');
        var answerMainBoxes  = $(document).find('.box-apm .apm-choosing');
        // Answer Font size
        var answerFontSizeMain = $(document).find("#ays_answer_font_size");
        var answerFontSize = answerFontSizeMain.val();        
        // Answer Font size mobile
        var answerFontSizeMobileMain = $(document).find("#ays_poll_answer_font_size_mobile");
        // Border color 
        var borderColor = $(document).find("#ays-poll-border-color").val();
        // Image Height option        
        var imageHeight = $(document).find('#ays_poll_answer_img_height').val();
        // Image border radius option
        var imageBorderRadius = $(document).find('#ays_poll_answer_image_border_radius').val();
        // Answer Image object-fit
        var imageBgSizeSelect = $(document).find('#ays_poll_image_background_size');
        var imageBgSizeVal = imageBgSizeSelect.val();
        // Answer padding 
        var answersPadding = $(document).find('#ays_poll_answers_padding');
        var answersPaddingVal = answersPadding.val();
        // Answer Gap
        var answersMargin = $(document).find('#ays_poll_answers_margin');
        var answersMarginVal = answersMargin.val();
        // Answer Border radius
        var answersBorderRadiusMain = $(document).find('#ays_poll_answer_border_radius');
        var answersBorderRadius = answersBorderRadiusMain.val();
        // Question image height
        var questionImageHeight = $(document).find("#ays_poll_question_image_height").val();
        var questionHeight = questionImageHeight == "" ? "100%" : questionImageHeight+"px";
        // Question image object-fit
        var questionImageObjectFit = $(document).find("#ays_poll_question_image_object_fit").val();
        $(document).find(".box-apm .apm-img-box .ays-poll-img").css({"object-fit" : questionImageObjectFit});
        // Poll Answer view type
        var viewType          = $(document).find('#ays_answers_view').val();
        var answersCont       = $(document).find('.apm-answers');
        var answersField      = answersCont.find('.ays-poll-field');
        var checkTextType     = $(document).find('.box-apm.text-poll').length > 0 ? true : false;
        // Title font size
        var titleFontSizeBox     = $(document).find("#ays_poll_title_font_size");
        var titleFontSize        = titleFontSizeBox.val();
        // Poll text type placeholder
        var textPlaceholder     = $(document).find("#ays_poll_text_type_placeholder");
        var textPlaceholderVal  = textPlaceholder.val();
        $(document).find('.ays-poll-text-type-fields').attr('placeholder' , textPlaceholderVal);
        answersCont.find('.ays_label_poll').css({
            'display': 'flex',
        });
        // Title alignment
        var titleAlignment     = $(document).find("#ays_poll_title_alignment").val();

        if(parseInt(imageHeight) < 0){
            imageHeight = 0;
            $(document).find('#ays_poll_answer_img_height').val(imageHeight);
        }
        //

        //
        // Answer box shadow
        var answersBoxShadow = $(document).find('#ays_poll_answers_box_shadow_enable').prop('checked');
        var answersBoxShadowColor = $(document).find('#ays_poll_answers_box_shadow_color').val();
        var answersBoxShadowXOffset = $(document).find('#ays_poll_answer_box_shadow_x_offset').val();
        var answersBoxShadowYOffset = $(document).find('#ays_poll_answer_box_shadow_y_offset').val();
        var answersBoxShadowZOffset = $(document).find('#ays_poll_answer_box_shadow_z_offset').val();
        if(answersBoxShadow){
            $(document).find('.ays-poll-answers .ays-field>label,.ays-poll-answers .ays-field >.ays_answer_live_label,label.ays_label_poll').css({
                'box-shadow': answersBoxShadowXOffset+'px '+answersBoxShadowYOffset+'px '+answersBoxShadowZOffset+'px '+answersBoxShadowColor,
            });
        }else{
            $(document).find('.ays-poll-answers .ays-field>label,.ays-poll-answers .ays-field >.ays_answer_live_label,label.ays_label_poll').css({
                'box-shadow': 'none',
            });
        }


        if(parseInt(answersPaddingVal) < 0){
            answersPaddingVal = 0;
            answersPadding.val(answersPaddingVal);
        }

        if(parseInt(answersMarginVal) <= 0){
            answersMarginVal = 1;
            answersMargin.val(answersMarginVal);
        }
            
        if(!checker){
            imageBgSizeVal      = imageDefaulObjFit;
            answersPaddingVal   = answerDefaultPadding;
            imageHeight         = imageDefaultHeight;
            answersMarginVal    = answerDefaultGap;
            answerFontSize      = answerDefaultFontSize;
            answerFontSizeMain.val(answerDefaultFontSize);
            answerFontSizeMobileMain.val(answerDefaultFontSize);
            answersBorderRadius = answersBorderRadiusDefault;
            answersBorderRadiusMain.val(answersBorderRadiusDefault);
        }
        // Image Changes
        imageBoxes.css({
            'height': imageHeight+'px',
            'border-radius': imageBorderRadius+'px',
            'object-fit': imageBgSizeVal
        });

        // Answer changes
        answerLabelBoxes.css({
            'padding': answersPaddingVal+'px'
        });

        answerMainBoxes.css({
            'margin-bottom': answersMarginVal+'px'
        });

        // Question font size
        var pattern = /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i;
        var questnionFontSize;
        if( pattern.test(navigator.userAgent) ) {
            questnionFontSize = $(document).find("#ays_poll_answers_font_size_mobile").val();            
        }
        else{
            questnionFontSize = $(document).find("#ays_poll_answers_font_size_pc").val();
        }
        $(document).find('.ays_question,.ays_question p').css({ 'font-size': questnionFontSize+"px" });

        $(document).find('.ays_label_font_size').css("font-size" , answerFontSize + "px");
        $(document).find('.box-apm').css("border-color" , borderColor);
        
        $(document).find(".apm-img-box img.ays-poll-img").css({"height" : questionHeight});
        
        $(document).find('.ays-poll-answers .ays-field>label,.ays-poll-answers .ays-field >.ays_answer_live_label,label.ays_label_poll').css({
            'border-radius': +answersBorderRadius+"px",
        });
        
        if(titleFontSize < 0){
            titleFontSize = 0;
            titleFontSizeBox.val(0);
        }
        $(document).find('.apm-title-box h5').css({
            "font-size" : titleFontSize + "px",
            "text-align" : titleAlignment,
        });
        if(viewType == 'list'){
            $(document).find('.grid_column_count').hide(250);
            answersCont.removeClass('ays_poll_grid_view_container');
            answersCont.addClass('ays_poll_list_view_container');
            answersField.removeClass('ays_poll_grid_view_item');
            answersField.addClass('ays_poll_list_view_item');

            answersCont.find('.ays-poll-field.ays_poll_list_view_item').css({
                'margin-right': 0,
            });
            answersCont.find('.ays-poll-answer-image-live').css({
                'width': '220px',
            });
            answersCont.find('span.ays-poll-each-answer').css({
                'width': 'initial',
                'text-align': 'initial',
                'display': 'initial',
            });
            answersCont.find('.ays_label_poll').css({
                'align-items': 'center',
                'flex-direction': 'row',
            });    
        }else if(viewType == 'grid' && !checkTextType){
            $(document).find('.grid_column_count').show(250);
            answersCont.removeClass('ays_poll_list_view_container');
            answersCont.addClass('ays_poll_grid_view_container');
            answersField.removeClass('ays_poll_list_view_item');
            answersField.addClass('ays_poll_grid_view_item');

            answersCont.find('.ays-poll-answer-image-live').css({
                'width': '100%',
            });

            answersCont.find('span.ays-poll-each-answer').css({
                'width': '100%',
                'text-align': 'center',
                'display': 'inline-block',
            });

            var innerLabel = answersField.find("label.ays_poll_label_without_padding");
            if(innerLabel.length > 0){
                innerLabel.css({
                    'flex-direction': 'column',
                });
            }
        }
    }

    var heart_interval = setInterval(function () {
        $(document).find('.ays-poll-maker-wrapper i.ays_fa').toggleClass('ays_poll_pulse');
    }, 1000);

    $(document).on('change' , '.ays-answer-value', function(){
        livePrevToChoosing("choosing");
        var checkAnswerStyleOnChange = $(document).find("#ays_poll_enable_answer_style").prop("checked");
        refreshLivePreview(checkAnswerStyleOnChange);
    });

    $('#ays_poll_info_form').on('change', function () {
        var propCheck = $(this).prop("checked");
        var allowCollect = $(document).find(".ays_toggle_target_inverse");
        if(propCheck){
            allowCollect.find('#ays_allow_collecting_logged_in_users_data').prop("checked" , false);
            allowCollect.hide();
        }
        else{
            if(allowCollect.hasClass("display_none")){
                allowCollect.removeClass("display_none");
            }
            allowCollect.show();
        }
        $('.ays-poll-if-form-on').fadeToggle();
    });

    // Pro features
    $(document).find('#ays_answers_border_color').wpColorPicker({
        change: function(event, ui) {
        }
    });
    
    $(document).find("#ays_see_result_show").on("change" , function(){
        var checker = $(this).prop("checked");
        var buttonsCont = $(document).find("#ays_poll_show_hide_button");
        if(checker){
            buttonsCont.show(200);
            buttonsCont.css("display" , "flex");
        }
        else{
            buttonsCont.hide(200);
        }
    });

    $(document).find('.ays-poll-question-tab-all-filter-button-top, .ays-poll-question-tab-all-filter-button-bottom').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var parent = $this.parents('.tablenav');

        var searchForm = parent.parent();
        var searchInput = searchForm.find('input#poll-maker-ays-search-input')
        var searchValue = searchInput.val();

        var html_name = '';
        var top_or_bottom = 'top';

        if ( parent.hasClass('bottom') ) {
            top_or_bottom = 'bottom';
        }

        var catFilter = $(document).find('select[name="filterby-'+ top_or_bottom +'"]').val();
        var authorFilter = $(document).find('select[name="filterbyauthor-'+ top_or_bottom +'"]').val();
        var typeFilter = $(document).find('select[name="filterbytype-'+ top_or_bottom +'"]').val();
        var link = location.href;

        if (typeof catFilter != "undefined") {
            link = catFilterForListTable(link, searchValue, {
                what: 'filterby',
                value: catFilter
            });
        }
        if (typeof authorFilter != "undefined") {
            link = catFilterForListTable(link, searchValue, {
                what: 'filterbyauthor',
                value: authorFilter
            });
        }
        if (typeof typeFilter != "undefined") {
            link = catFilterForListTable(link,searchValue, {
                what: 'filterbytype',
                value: typeFilter
            });
        }
        document.location.href = link;
    })

    function catFilterForListTable(link, searchValue, options){
        if( options.value != '' ){
            options.value = "&" + options.what + "=" + options.value;
            var linkModifiedStart = link.split('?')[0];
            var linkModified = link.split('?')[1].split('&');
            for(var i = 0; i < linkModified.length; i++){
                if ( linkModified[i].split("=")[0] == "ays_result_tab" ) {
                    linkModified.splice(i, 1, "ays_result_tab=poststuff");
                }
                if(linkModified[i].split("=")[0] == options.what){
                    linkModified.splice(i, 1);
                }
            }
            linkModified = linkModified.join('&');
            if (typeof searchValue != 'undefined' && searchValue != '') {
                linkModified = linkModified + "&s=" + searchValue;
            }
            return linkModifiedStart + "?" + linkModified + options.value;
        }else{
            var linkModifiedStart = link.split('?')[0];
            var linkModified = link.split('?')[1].split('&');
            for(var i = 0; i < linkModified.length; i++){
                if(linkModified[i].split("=")[0] == options.what){
                    linkModified.splice(i, 1);
                }
            }
            linkModified = linkModified.join('&');
            if (typeof searchValue != 'undefined' && searchValue != '') {
                linkModified = linkModified + "&s=" + searchValue;
            }
            return linkModifiedStart + "?" + linkModified;
        }
    }

    $(document).find('input[type="submit"]#doaction, input[type="submit"]#doaction2').on('click', function(e) {
        showConfirmationIfDelete(e);
    })

    $(document).find("input#poll-maker-ays-search-input + input#search-submit").on("click", function (e) {
        var _this  = $(this);
        var parent = _this.parents('form');
        
        var search_input = parent.find('input#poll-maker-ays-search-input');
        var input_value  = search_input.val();

        var field = 's';
        var flag = false;
        var url = window.location.href;
        if(url.indexOf('?' + field + '=') != -1){
            flag = true;
        }
        else if(url.indexOf('&' + field + '=') != -1){
            flag = true;
        }

        if (flag) {
            if (typeof input_value != 'undefined' && input_value != "") {
                e.preventDefault();
                location.href=location.href.replace(/&s=([^&]$|[^&]*)/i, "&s="+input_value);
            }
        }
    });

    function showConfirmationIfDelete(e) {
        var $el = $(e.target);
        var elParent = $el.parent();
        var actionSelect = elParent.find('select[name="action"]');
        var action = actionSelect.val();

        if (action === 'bulk-delete') {
            e.preventDefault();
            var confirmDelete = confirm('Are you sure you want to delete?');

            if (confirmDelete) {
                var form = $el.closest('form');
                form.submit()
            }
        }
    }

    $(document).on('click', '.ays_polls_each_results_list_table th.manage-column a', function(e) {
        var href = $(this).attr('href');
        $(this).attr('href', href + '&ays_poll_tab_results=tab2');
    })

    $(document).on("click", "#ays-poll-dismiss-buttons-content .ays-button, #ays-poll-dismiss-buttons-content-helloween .ays-button-helloween", function(e){
        e.preventDefault();

        var $this = $(this);
        var thisParent  = $this.parents("#ays-poll-dismiss-buttons-content");
        var mainParent  = $this.parents("div.ays_poll_dicount_info");
        var closeButton = mainParent.find("button.notice-dismiss");
        var attr_plugin = $this.attr('data-plugin');
        var wp_nonce    = thisParent.find('#poll-maker-ays-sale-banner').val();

        var data = {
            action: 'ays_poll_dismiss_button',
            _ajax_nonce: wp_nonce,
        };

        $.ajax({
            url: poll.ajax,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                if( response.status ){
                    closeButton.trigger('click');
                } else {
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ pollLangObj.errorMsg +"</h2><br><h6>"+ pollLangObj.somethingWentWrong +"</h6>"
                    }).then(function(res) {
                        closeButton.trigger('click');
                    });
                }
            },
            error: function(){
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ pollLangObj.errorMsg +"</h2><br><h6>"+ pollLangObj.somethingWentWrong +"</h6>"
                }).then(function(res) {
                    closeButton.trigger('click');
                });
            }
        });
    });

    $(document).keydown(function(event) {
        var editButton = $(document).find("input#ays-button-top-apply,input#ays-button-apply,input#ays_submit");
        if (!(event.which == 83 && event.ctrlKey) && !(event.which == 19)){
            return true;  
        }
        editButton.trigger("click");
        event.preventDefault();
        return false;
    });

    function aysPollGetCookie(c_name) {
        if (document.cookie.length > 0) {
            var c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                var c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return "";
    }
    // Check new added Poll start
    var createdNewPoll = aysPollGetCookie('ays_poll_created_new');
    if(createdNewPoll && createdNewPoll > 1){
        var url = new URL(window.location.href);

        // Get a specific GET parameter by name
        var parameterValue = url.searchParams.get("action");
        var htmlDefaultText = '<p style="margin-top:1rem;">'+ pollLangObj.formMoreDetailed +' <a href="admin.php?page=poll-maker&action=edit&id=' + createdNewPoll + '">'+ pollLangObj.editPollPage +'</a>.</p>';

        swal({
            title: '<strong>' + pollLangObj.greateJob + '</strong>',
            type: 'success',
            html: '<p>' + pollLangObj.youCanUuseThisShortcode + '</p><input type="text" id="ays-poll-create-new" onClick="this.setSelectionRange(0, this.value.length)" readonly value="[ays_poll id=\'' + createdNewPoll + '\']" />',
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: '<i class="ays_poll_fas ays_poll_fa_thumbs_up"></i> '+ pollLangObj.done,
            confirmButtonAriaLabel: pollLangObj.thumbsUpGreat,
        });
        aysPollDeleteCookie('ays_poll_created_new');
    }

    function aysPollDeleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    // Select default category for poll
    $(document).find("#ays_poll_default_type").select2();

    // Check if submit button clicked for unsaved changes confirmation box
    var subButtons = '.button#ays-button-top, .button#ays-button-top-apply, .button#ays-button, .button#ays-button-apply, .button#ays-button-cat, .button#ays_submit, .button#ays_poll_cancel_top, .button#ays_poll_cancel';
    $(document).on('click', subButtons ,function () {
        var $this = $(this);
        $this.addClass('ays-submit-button-clicked');
    });

    var aysUnsavedChanges = false;
    var formInputs = '#ays-poll-form .ays-poll-tab-content input, #ays-poll-form .ays-poll-tab-content select, ' +
            '#ays-poll-form .ays-poll-tab-content textarea, #ays-poll-category-form input, ' +
            '#ays-poll-category-form select, #ays-poll-category-form textarea, ' +
            '#ays-poll-general-settings-form input, #ays-poll-general-settings-form select, ' +
            '#ays-poll-general-settings-form textarea';

    $(document).on('change input', formInputs, function() {
        aysUnsavedChanges = true;
    });

    $(window).on('beforeunload', function(event) {
        var saveButtons = $(document).find('.button#ays-button-top, .button#ays-button-top-apply, .button#ays-button, .button#ays-button-apply, .button#ays-button-cat, .button#ays_submit, .button#ays_poll_cancel_top, .button#ays_poll_cancel');
        var savingButtonsClicked = saveButtons.filter('.ays-submit-button-clicked').length > 0;

        if (aysUnsavedChanges && !savingButtonsClicked) {
            event.preventDefault();
            event.returnValue = true;
        }
    });


    $(document).find("#ays_poll_default_cat").select2({
        multiple: true
    });

    // Create and Delete rows in Answers table
    $(document).on("keydown" , "input[name='ays-poll-answers[]']" , function(event) {
        var $thisValue = $(this).val();
        if (event.keyCode === 13) {
                if($(this).hasClass("ays_poll_enter_key")){
                    var editButton = $(document).find(".ays-click-once");
                    editButton.trigger("click");
                    event.preventDefault();
                }
                else{
                    var nextInput = $(this).parents("tr").next().find("td.ays-choosing-answer-container input:nth-child(1)");
                    nextInput.focus();
                    var oldValue  = nextInput.val();
                    nextInput.val(" ");
                    nextInput.val(oldValue);
                }
            }
        else if(event.keyCode === 8  && $thisValue == ""){     
            var editButton = $(this).parents("tr").find("a.ays-delete-answer");
            editButton.trigger("click");
            event.preventDefault();
        }

    });

    var drawChartLink = $(document).find(".ays_poll_answer_chart_active");
    if(drawChartLink.hasClass("nav-tab-active")){
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBasic);
    }
    
    $(document).find(".ays_poll_answer_chart_active").on("click" , function(){
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBasic);
    });

    // Write poll title immediately
    $(document).find('#ays-poll-title').on('input', function(e){
        var pollTitleVal = $(this).val();
        var pollTitle = aysPollstripHTML( pollTitleVal );
        $(document).find('.ays_poll_title_in_top').html( pollTitle );
    });

    function aysPollstripHTML( dirtyString ) {
        var container = document.createElement('div');
        var text = document.createTextNode(dirtyString);
        container.appendChild(text);

        return container.innerHTML; // innerHTML will be a xss safe string
    }
    //

    var theme_Ids = $('.apm-themes-row').attr('data-themeid');
    if ($('#ays_poll_show_answers_icon').prop('checked') && theme_Ids != 3) {
        if ($('input[name="ays_poll_answer_icon"]:checked').prop('checked')) {
            var iconVal = $('input[name="ays_poll_answer_icon"]:checked').val();
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
            $('.ays_label_poll').addClass('ays_poll_answer_icon_'+iconVal);
        }else{
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
        }
    }else{
        $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
        $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
    }

    // Answer Icon 
    $('#ays_poll_show_answers_icon').change(function () {
        var themeIds = $('.apm-themes-row').attr('data-themeid');
        if ($('#ays_poll_show_answers_icon').prop('checked')) {
            switch(parseInt(themeIds)){
                case 3:
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');  
                    break;
                default:
                    var icons_val = $('input[name="ays_poll_answer_icon"]:checked').val();
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $('.ays_label_poll').addClass('ays_poll_answer_icon_'+icons_val);
                    break;
            }            
        }else{
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
        }
    });

    $('.ays_poll_answ_icon').change(function () {
        if ($(this).prop('checked')) {
            var themesIds = $('.apm-themes-row').attr('data-themeid');
            switch(parseInt(themesIds)){
                case 3:
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');  
                    break;
                default:
                    var icon_val = $(this).val();
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
                    $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $('.ays_label_poll').addClass('ays_poll_answer_icon_'+icon_val);
            }
        }else{
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
            $('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');  
        }
    });

    $(document).find('strong.ays-poll-shortcode-box').on('mouseleave', function(){
        var _this = $(this);

        _this.attr( 'data-original-title', pollLangObj.clickForCopy );
    });
    
    // $(document).find("#ays_poll_buttons_size").select2({
    //     minimumResultsForSearch: -1
    // });

    // Button styles start
    // Size change
    $(document).find("#ays_poll_buttons_size").on("change" , function(){
        var buttonsSize = $(document).find('#ays_poll_buttons_size').val();
        var buttonsFontSize,
            buttonsLeftRightPadding,
            buttonsTopBottomPadding,
            buttonsBorderRadius;

        switch(buttonsSize){
            case "small":
                buttonsFontSize = 14;
                buttonsLeftRightPadding = 14;
                buttonsTopBottomPadding = 7;
                buttonsBorderRadius = 3;
            break;
            case "large":
                buttonsFontSize = 20;
                buttonsLeftRightPadding = 30;
                buttonsTopBottomPadding = 13;
                buttonsBorderRadius = 3;
            break;
            default:
                buttonsFontSize = 17;
                buttonsLeftRightPadding = 20;
                buttonsTopBottomPadding = 10;
                buttonsBorderRadius = 3;
            break;
        }

        $(document).find('#ays_poll_buttons_font_size').val(buttonsFontSize);
        $(document).find('#ays_poll_buttons_left_right_padding').val(buttonsLeftRightPadding);
        $(document).find('#ays_poll_buttons_top_bottom_padding').val(buttonsTopBottomPadding);
        $(document).find('#ays_poll_buttons_border_radius').val(buttonsBorderRadius);

        $(document).find('input[type="button"].ays-poll-btn').css({
                                                                    'font-size' : buttonsFontSize + 'px',
                                                                    'padding'   : buttonsTopBottomPadding+'px '+ buttonsLeftRightPadding+'px',
                                                                    'border-radius' : buttonsBorderRadius + 'px'            
                                                                });
    });

    // Buttons font size
    $(document).find("#ays_poll_buttons_font_size").on("change" , function(){
        var buttonFontSize = $(this).val();
        $(document).find('input[type="button"].ays-poll-btn').css('font-size', buttonFontSize + 'px');
    });

    $('.ays_buttons_padding').on('change', function () {
        var top_bottom = $(document).find('#ays_poll_buttons_top_bottom_padding').val();
        var left_top = $(document).find('#ays_poll_buttons_left_right_padding').val();
        $(document).find('input[type="button"].ays-poll-btn').css('padding', top_bottom + 'px ' + left_top +'px');
    });   

    $('#ays_poll_buttons_width').on('change', function () {
        var buttons_width = $(document).find('#ays_poll_buttons_width').val();
        if(buttons_width == ''){
            buttons_width = 'auto';
        }else{
            buttons_width = buttons_width + 'px';
        }
        
        $(document).find('input[type="button"].ays-poll-btn').css('width', buttons_width);
    });

    $('#ays_poll_buttons_border_radius').on('change', function () {
        var val = $(this).val();
        $(document).find('input[type="button"].ays-poll-btn').css('border-radius', val + 'px');
    });

    function checkHr(type , pollType) {
        var typeChecker = $(document).find(".ays_hr_on");
        var textTypeChecker = $(document).find(".ays_hr_on_text");
        var rangeType = $('#type_range').prop('checked');
        if(!type){
            typeChecker.hide();
        }
        else{
            typeChecker.show();
        }
        
        if(pollType == "text"){
            textTypeChecker.show();
        }
        else{
            textTypeChecker.hide();

        }
    
        if(rangeType){
            $(document).find(".ays_hr_check").hide();
        }
        else{
            $(document).find(".ays_hr_check").show();
        }
    }

    function check_allow_add_answers_show_up() {
        var aa_show = $(document).find('input[type="checkbox"]#ays_poll_allow_add_answers');
        let requireAdminApprovalCbState = $(document).find('input[type="checkbox"]#ays_poll_allow_answer_require').prop('checked');

        if(!requireAdminApprovalCbState){
            $(document).find('.ays_show_user_added').hide();
            $(document).find('.ays_show_user_added_hid').each(function () {
                $(this).parent('.ays-sortable-answers').find('.ays_show_user_added').prop('checked', false);
                // $(this).val(0);
            });
        }else{
            $(document).find('.ays_show_user_added').show();
            $(document).find('.ays_show_user_added_hid').each(function () {
                // $(this).val(1);
                // $(this).prop('checked', true);
                $(this).parent('.ays-sortable-answers').find('.ays_show_user_added').val(1);
            });
        }        
    }

    function toggleOptionsAccordion(arrowBtn) {
        var arrowSvg = arrowBtn.find('svg');
        var accordionMainContainer = arrowBtn.parents('.ays-poll-accordion-options-main-container');
        var accordionBody = accordionMainContainer.find('.ays-poll-accordion-body');

        arrowSvg.toggleClass('ays-poll-accordion-arrow-active');
        accordionBody.slideToggle();
    }

    $(document).find('input[type="checkbox"]#ays_poll_allow_add_answers').on('change', function(e){
        if($(this).prop('checked') == false){
            // $(document).find('.allow_add_answers_not_show_up').hide();
            // $(document).find('.allow_add_answers_not_show_up .ays_toggle_target').hide(250);
            $(document).find('#ays_poll_allow_answer_require').removeAttr('checked');
            check_allow_add_answers_show_up();
        }else{
            $(document).find('.allow_add_answers_not_show_up').show();
            check_allow_add_answers_show_up();
        }

    }); 

    $(document).find('input[type="checkbox"]#ays_poll_allow_answer_require').on('change', function(e){
        check_allow_add_answers_show_up();
    });

    // Options accordion effect start
    $(document).on('click', '.ays-poll-accordion-arrow-box', function() {
        toggleOptionsAccordion($(this));
    });
    // Options accordion effect end

    $(document).find('input[type="checkbox"].ays_show_user_added').on('change', function(e){
        let selectAllCb = $(document).find('input[type="checkbox"]#ays_poll_require_approve_select_all');
        if($(this).prop('checked') == true){
            $(this).parent().find('.ays_show_user_added_hid').val(1);
            var isAllChecked = 0;
            var allShowUserAddedCbArr = $(document).find('input[type="checkbox"].ays_show_user_added');
            for (i = 0; i < allShowUserAddedCbArr.length; i++) {
                if(allShowUserAddedCbArr.eq(i).prop('checked')) {
                    isAllChecked++;
                }
            }
            if (isAllChecked === allShowUserAddedCbArr.length && selectAllCb.prop('checked') === false) {
                selectAllCb.prop('checked', true);
            }
        }else{
            $(this).parent().find('.ays_show_user_added_hid').val(0);

            if (selectAllCb.prop('checked')) {
                selectAllCb.prop('checked', false);
            }
        }
    });

    $(document).find("#ays_poll_require_approve_select_all").on("change" , function(){
        if($(this).prop('checked') == false){
            $(document).find('.ays_show_user_added').each(function () {
                $(this).parent('.ays-sortable-answers').find('.ays_show_user_added').prop('checked', false);
                $(this).val(0);
                $(this).prop('checked' , false);
                $(document).find('.ays_show_user_added_hid').val(0);

            });
        }else{
            $(document).find('.ays_show_user_added').each(function () {
                $(this).val(1);
                $(this).prop('checked', true);
                $(this).parent('.ays-sortable-answers').find('.ays_show_user_added').val(1);
                $(document).find('.ays_show_user_added_hid').val(1);
            });
        }  
    });

    // Add answer image (Choosing type) start
    $(document).on('click', 'label.ays-label a.ays-poll-add-answer-image', function (e) {
        openAnswerMediaUploader(e, $(this));
    });

    // Select Image
    function openAnswerMediaUploader(e, element) {
        e.preventDefault();
        var addButton = element;
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            addButton.parents().eq(1).find('.ays-poll-add-answer-image').parent().css('display', 'none');
            addButton.parents('td').find('.ays-poll-answer-image-container').fadeIn();
            addButton.parents('td').find('img.ays-poll-answer-img').attr('src', attachment.url);
            addButton.parents('tr').find('input.ays-poll-answer-image-path').val(attachment.url).trigger("change");
            if(addButton.hasClass('ays-poll-add-answer-image')){
                addButton.parents('td').find('img').attr('src', attachment.url);
                addButton.parents('tr').find('input.ays-poll-answer-image').val(attachment.url);
            }
        }).open();
        return false;
    }

    // Remove Image
    $(document).on('click', '.ays-poll-remove-answer-img', function () {
        var $this = $(this);
        $this.parent().fadeOut();
        if($this.parent().hasClass('ays-poll-answer-image-container')){
            setTimeout(function(){
                $this.parents().eq(1).find('.ays-poll-add-answer-image').show();
                $this.parents('td').find('.ays-poll-add-answer-image').parent().css('display', 'block');
                $this.parents('td').find('.ays-poll-add-answer-image').css('display', 'inline-block');
                $this.parent().find('img.ays-poll-answer-img').attr('src', '');
                $this.parent().find('input.ays-poll-answer-image').val('');
                $this.parent().find('input.ays-poll-answer-image-path').val('').trigger("change");
            },300);
        }
    });    

    // Grid view type PRO
    $(document).find("#ays_answers_grid_column").on("change" , function(){
        var $this = $(this);
        if($this.val() != 2){
            $this.val(2);
            window.open("https://ays-pro.com/wordpress/poll-maker", '_blank');
        }
    });



    $(document).on('change', '.ays-poll-answer-image-path', function(){
        livePrevToChoosing('choosing');

        $(document).find('#ays-poll-border-side').trigger('change');
        $(document).find('#ays_answers_view').trigger('change');
    });

    $(document).find(".ays-poll-text-types-type").on("change" , function(){
        var $this = $(this);
        var textType = $this.val();
        livePrevToChoosing("text" , 0 , textType);
    });
    // Add answer image (Choosing type) end

    // Poll Responsive tabs start
    if($(document).find('.ays-top-menu').width() <= $(document).find('div.ays-top-tab-wrapper').width()){
        $(document).find('.ays_menu_left').css('display', 'flex');
        $(document).find('.ays_menu_right').css('display', 'flex');
    }
    $(window).resize(function(){
        if($(document).find('.ays-top-menu').width() < $(document).find('div.ays-top-tab-wrapper').width()){
            $(document).find('.ays_menu_left').css('display', 'flex');
            $(document).find('.ays_menu_right').css('display', 'flex');
        }else{
            $(document).find('.ays_menu_left').css('display', 'none');
            $(document).find('.ays_menu_right').css('display', 'none');
            $(document).find('div.ays-top-tab-wrapper').css('transform', 'translate(0px)');
        }
    });
    var menuItemWidths0 = [];
    var menuItemWidths = [];
    $(document).find('.ays-top-tab-wrapper .nav-tab').each(function(){
        var $this = $(this);
        menuItemWidths0.push($this.outerWidth());
    });

    for(var i = 0; i < menuItemWidths0.length; i+=2){
        if(menuItemWidths0.length <= i+1){
            menuItemWidths.push(menuItemWidths0[i]);
        }else{
            menuItemWidths.push(menuItemWidths0[i]+menuItemWidths0[i+1]);
        }
    }
    var menuItemWidth = 0;
    for(var i = 0; i < menuItemWidths.length; i++){
        menuItemWidth += menuItemWidths[i];
    }
    menuItemWidth = menuItemWidth / menuItemWidths.length;

    $(document).on('click', '.ays_menu_left', function(){
        var scroll = parseInt($(this).attr('data-scroll'));
        scroll -= menuItemWidth;
        if(scroll < 0){
            scroll = 0;
        }
        $(document).find('div.ays-top-tab-wrapper').css('transform', 'translate(-'+scroll+'px)');
        $(this).attr('data-scroll', scroll);
        $(document).find('.ays_menu_right').attr('data-scroll', scroll);
    });
    $(document).on('click', '.ays_menu_right', function(){
        var scroll = parseInt($(this).attr('data-scroll'));
        var howTranslate = $(document).find('div.ays-top-tab-wrapper').width() - $(document).find('.ays-top-menu').width();
        howTranslate += 7;
        if(scroll == -1){
            scroll = menuItemWidth;
        }
        scroll += menuItemWidth;
        if(scroll > howTranslate){
            scroll = Math.abs(howTranslate);
        }
        $(document).find('div.ays-top-tab-wrapper').css('transform', 'translate(-'+scroll+'px)');
        $(this).attr('data-scroll', scroll);
        $(document).find('.ays_menu_left').attr('data-scroll', scroll);
    });
    // Poll Responsive tabs end


    var checkCountdownIsExists = $(document).find('#ays-poll-maker-countdown-main-container');
    // var checkCountdownIsExists = $(document).find('#ays-poll-countdown-main-container');
    if ( checkCountdownIsExists.length > 0 ) {
        var second  = 1000,
            minute  = second * 60,
            hour    = minute * 60,
            day     = hour * 24;

        var pollCountdownEndTime = pollLangObj.pollBannerDate;
        var countDown_new = new Date(pollCountdownEndTime).getTime();
        if ( isNaN(countDown_new) || isFinite(countDown_new) == false ) {
            var AYS_POLL_MILLISECONDS = 3 * day;
            var countdownStartDate = new Date(Date.now() + AYS_POLL_MILLISECONDS);
            var pollCountdownEndTime = countdownStartDate.aysPollCustomFormat( "#YYYY#-#MM#-#DD# #hhhh#:#mm#:#ss#" );
            var countDown_new = new Date(pollCountdownEndTime).getTime();
        }

			aysPollBannerCountdown();

        var y = setInterval(function() {

            var now = new Date().getTime();
            var distance_new = countDown_new - now;

			aysPollBannerCountdown();

            var countDownDays    = document.getElementById("ays-poll-countdown-days");
            var countDownHours   = document.getElementById("ays-poll-countdown-hours");
            var countDownMinutes = document.getElementById("ays-poll-countdown-minutes");
            var countDownSeconds = document.getElementById("ays-poll-countdown-seconds");

            if(countDownDays !== null || countDownHours !== null || countDownMinutes !== null || countDownSeconds !== null){

                var countDownDays_innerText    = Math.floor(distance_new / (day));
                var countDownHours_innerText   = Math.floor((distance_new % (day)) / (hour));
                var countDownMinutes_innerText = Math.floor((distance_new % (hour)) / (minute));
                var countDownSeconds_innerText = Math.floor((distance_new % (minute)) / second);

                if( isNaN(countDownDays_innerText) || isNaN(countDownHours_innerText) || isNaN(countDownMinutes_innerText) || isNaN(countDownSeconds_innerText) ){
                    var headline  = document.getElementById("ays-poll-countdown-headline"),
                        countdown = document.getElementById("ays-poll-countdown"),
                        content   = document.getElementById("ays-poll-countdown-content");

                    // headline.innerText = "Sale is over!";
                    countdown.style.display = "none";
                    content.style.display = "block";

                    clearInterval(y);
                } else {
                    countDownDays.innerText    = countDownDays_innerText;
                    countDownHours.innerText   = countDownHours_innerText;
                    countDownMinutes.innerText = countDownMinutes_innerText;
                    countDownSeconds.innerText = countDownSeconds_innerText;
                }                
            }

            //do something later when date is reached
            if (distance_new < 0) {
                var headline  = document.getElementById("ays-poll-countdown-headline"),
                    countdown = document.getElementById("ays-poll-countdown"),
                    content   = document.getElementById("ays-poll-countdown-content");

              // headline.innerText = "Sale is over!";
              countdown.style.display = "none";
              content.style.display = "block";

              clearInterval(y);
            }
        }, 1000);
    }

    function aysPollBannerCountdown(){
        var now = new Date().getTime();
        var distance_new = countDown_new - now;

        var countDownDays    = document.getElementById("ays-poll-countdown-days");
        var countDownHours   = document.getElementById("ays-poll-countdown-hours");
        var countDownMinutes = document.getElementById("ays-poll-countdown-minutes");
        var countDownSeconds = document.getElementById("ays-poll-countdown-seconds");

        if((countDownDays !== null || countDownHours !== null || countDownMinutes !== null || countDownSeconds !== null) && distance_new > 0){

            var countDownDays_innerText    = Math.floor(distance_new / (day));
            var countDownHours_innerText   = Math.floor((distance_new % (day)) / (hour));
            var countDownMinutes_innerText = Math.floor((distance_new % (hour)) / (minute));
            var countDownSeconds_innerText = Math.floor((distance_new % (minute)) / second);

            if( isNaN(countDownDays_innerText) || isNaN(countDownHours_innerText) || isNaN(countDownMinutes_innerText) || isNaN(countDownSeconds_innerText) ){
                var headline  = document.getElementById("ays-poll-countdown-headline"),
                    countdown = document.getElementById("ays-poll-countdown"),
                    content   = document.getElementById("ays-poll-countdown-content");

                // headline.innerText = "Sale is over!";
                countdown.style.display = "none";
                content.style.display = "block";

                // clearInterval(y);
            } else {
                countDownDays.innerText    = countDownDays_innerText;
                countDownHours.innerText   = countDownHours_innerText;
                countDownMinutes.innerText = countDownMinutes_innerText;
                countDownSeconds.innerText = countDownSeconds_innerText;
            }

            // countDownDays.innerText     = Math.floor(distance_new / (day)).toLocaleString(undefined,{minimumIntegerDigits: 2})+" : ",
            // countDownHours.innerText    = Math.floor((distance_new % (day)) / (hour)).toLocaleString(undefined,{minimumIntegerDigits: 2})+" : ",
            // countDownMinutes.innerText  = Math.floor((distance_new % (hour)) / (minute)).toLocaleString(undefined,{minimumIntegerDigits: 2})+" : ",
            // countDownSeconds.innerText  = Math.floor((distance_new % (minute)) / second).toLocaleString(undefined,{minimumIntegerDigits: 2});
        }
    }

    $(document).on('click', '#ays-polls-next-button, #ays-polls-prev-button, .ays-poll-next-prev-button-class', function(e){
        e.preventDefault();
        var message = $(this).data('message');
        var confirm = window.confirm( message );
        if(confirm === true){
            window.location.replace($(this).attr('href'));
        }
    });

    // CHOOSE POLL TYPE FROM MODAL START
    $(document).find('.ays_poll_layer_box_blocks label.ays-poll-dblclick-layer:not(.ays-poll-type-pro-feature)').on('dblclick',function(){
        $(this).parents('.ays_poll_layer_container').find('.ays_poll_select_button_layer input.ays_poll_layer_button').trigger('click');
    });

    $(document).find('.ays-poll-content-type').on('change',function(){
        $(this).parents('.ays_poll_layer_container').find('.ays_poll_select_button_layer input.ays_poll_layer_button').prop('disabled',false).attr("data-type" , $(this).val());
        $(document).find('#poll_choose_type').val($(this).val());
    });

    $(document).find('.ays_poll_layer_button').on('click',function(){
        $('.ays_poll_layer_container').css({'position':'unset' , 'display':'none'});
        $(document).find('.ays-poll-show-result-chart-google').prop("checked", true);

        checkType($(this).attr("data-type"));
    })
    // CHOOSE POLL TYPE FROM MODAL END

    $(document).find('.ays-poll-open-polls-list').on('click', function(e){
        $(this).parents(".ays-poll-subtitle-main-box").find(".ays-poll-polls-data").toggle('fast');
    });

    $(document).find(".ays-poll-go-to-polls").on("click" , function(e){
        e.preventDefault();
        var confirmRedirect = window.confirm(pollLangObj.areYouSure);
        if(confirmRedirect){
            window.location = $(this).attr("href");
        }
    });

    $(document).find('#ays_poll_reset_to_default').on('click', function (e) {
        e.preventDefault();

        var themeId = $(document).find('.ays-poll-theme-item.apm_active_theme').next('input').val();
        
        $(document).find('.apm-themes-row').attr('data-themeId',themeId);
        if ($(document).find('#ays_poll_show_answers_icon').prop('checked')) {
            switch(parseInt(themeId)){
                case 3:
                    $(document).find('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $(document).find('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');  
                    break;
                default:
                    var iconsVal = $('input[name="ays_poll_answer_icon"]:checked').val();
                    $(document).find('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
                    $(document).find('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
                    $(document).find('.ays_label_poll').addClass('ays_poll_answer_icon_'+iconsVal);
                    break;
            }
        }else{
            $(document).find('.ays_label_poll').removeClass('ays_poll_answer_icon_checkbox');
            $(document).find('.ays_label_poll').removeClass('ays_poll_answer_icon_radio');
        }

        answerStyleChange(themeId);

        textColorChange({
            color: themes[themeId].textColor
        });
        buttonTextColorChange({
            color: themes[themeId].buttonTextColor
        });
        buttonBgColorChange({
            color: themes[themeId].buttonBgColor
        });
        mainColorChange({
            color: themes[themeId].mainColor
        });
        bgColorChange({
            color: themes[themeId].bgColor
        });
        answerBgColorChange({
            color: themes[themeId].answerBgColor
        });
        titleBgColorChange({
            color: themes[themeId].titleBgColor
        });
        iconColorChange({
            color: themes[themeId].iconColor
        });
        borderColorChange({
            color: themes[themeId].borderColor
        });
        $(document).find('#ays-poll-text-color').parent().parent().prev().css({
            'background-color': themes[themeId].textColor
        });
        $(document).find('#ays-poll-text-color').val(themes[themeId].textColor);

        $(document).find('#ays-poll-button-text-color').parent().parent().prev().css({
            'color': themes[themeId].buttonTextColor
        });
        $(document).find('#ays-poll-button-text-color').val(themes[themeId].buttonTextColor);

        $(document).find('#ays-poll-button-bg-color').parent().parent().prev().css({
            'background-color': themes[themeId].buttonBgColor
        });
        $(document).find('#ays-poll-button-bg-color').val(themes[themeId].buttonBgColor);
            
        $(document).find('#ays-poll-main-color').parent().parent().prev().css({
            'background-color': themes[themeId].mainColor
        });
        $(document).find('#ays-poll-main-color').val(themes[themeId].mainColor);

        $(document).find('#ays-poll-bg-color').parent().parent().prev().css({
            'background-color': themes[themeId].bgColor
        });
        $(document).find('#ays-poll-bg-color').val(themes[themeId].bgColor);

        $(document).find('#ays-poll-answer-bg-color').parent().parent().prev().css({
            'background-color': themes[themeId].answerBgColor
        });
        $(document).find('#ays-poll-answer-bg-color').val(themes[themeId].answerBgColor);

        $(document).find('#ays-poll-title-bg-color').parent().parent().prev().css({ 
            'background-color': themes[themeId].titleBgColor
        });
        $(document).find('#ays-poll-title-bg-color').val(themes[themeId].titleBgColor);

        $(document).find('#ays-poll-icon-color').parent().parent().prev().css({
            'background-color': themes[themeId].iconColor
        });
        $(document).find('#ays-poll-icon-color').val(themes[themeId].iconColor);

        $(document).find('input#ays-poll-bg-image').val('');
        $(document).find('#ays-poll-bg-img').attr('src', '').change();
        $(document).find('.ays-poll-bg-image-container').hide().change();
        $(document).find('#ays-poll-background-image-options').hide().change();
        $(document).find('.add-bg-image.button').text('Add Image');
        $(document).find('.add-bg-image.button').show().change();
        $(document).find('.box-apm.choosing-poll').css('background-image', '');
        $(document).find('#ays_poll_bg_image_position').val('center center').change();    
        $(document).find("#ays_poll_bg_img_in_finish_page").prop('checked' , false).change();

        if(themeId == 3){
            $(document).find('#ays-poll-border-side').val('none').change();
        }else{
            $(document).find('#ays-poll-border-side').val('all_sides').change();            
        }

        $(document).find('input#ays_answer_font_size').val('16').change();
        $(document).find('input#ays_poll_answer_font_size_mobile').val('16').change();
        $(document).find('input#ays_poll_answer_img_height').val('150').change();
        $(document).find('input#ays_poll_answer_image_height_for_mobile').val('150').change();
        $(document).find('input#ays_poll_answer_image_border_radius').val('0').change();
        $(document).find('#ays_poll_image_background_size').val('cover').change();
        $(document).find('input#ays_poll_answers_padding').val('10').change();
        $(document).find('input#ays_poll_answers_margin').val('10').change();
        $(document).find('input#ays_poll_answer_border_radius').val('0').change();

        $(document).find("#ays_poll_show_answers_icon").prop('checked' , false).change();

        $(document).find('#ays_answers_view').val('list').change();
        $(document).find("#ays_poll_answers_box_shadow_enable").prop('checked' , false).change();
        $(document).find('input#ays_poll_title_font_size').val('20').change();
        $(document).find('input#ays_poll_title_font_size_mobile').val('20').change();
        $(document).find('#ays_poll_title_alignment').val('center').change();
        $(document).find("#ays_poll_enable_title_text_shadow").prop('checked' , false).change();
        $(document).find('input#ays-poll-icon-size').val('24').change();
        $(document).find('input#ays-poll-width').val('0').change();
        $(document).find('input#ays_poll_width_for_mobile').val('0').change();
        $(document).find('input#ays_poll_min_height').val('').change();
        $(document).find('#ays-poll-border-style').val('ridge').change();
        $(document).find('input#ays-poll-border-radius').val('0').change();
        $(document).find('input#ays-poll-border-width').val('2').change();
        $(document).find("#ays_poll_enable_box_shadow").prop('checked' , false).change();
        $(document).find("#ays-enable-background-gradient").prop('checked' , false).change();
        $(document).find('input#ays_questions_font_size').val('16').change();
        $(document).find('input#ays_poll_answers_font_size_mobile').val('16').change();
        $(document).find('input#ays_poll_question_image_height').val('').change();
        $(document).find('#ays_poll_question_image_object_fit').val('cover').change();
        $(document).find('input#ays_poll_mobile_max_width').val('').change();
        $(document).find('#ays_poll_buttons_size').val('medium').change();
        $(document).find('input#ays_poll_buttons_font_size').val('17').change();
        $(document).find('input#ays_poll_buttons_mobile_font_size').val('17').change();
        $(document).find('input#ays_poll_buttons_left_right_padding').val('20').change();
        $(document).find('input#ays_poll_buttons_top_bottom_padding').val('10').change();
        $(document).find('input#ays_poll_buttons_border_radius').val('3').change();
        $(document).find('input#ays_poll_buttons_width').val('').change();
        $(document).find("#ays_disable_answer_hover").prop('checked' , false).change();

        $(document).find(".add-logo-remove-image.button").trigger('click');
        $(document).find('input#ays_poll_custom_class').val('').change();
        
        setTimeout(function(){
            if($(document).find('#ays_custom_css').length > 0){
                if(wp.codeEditor){
                    $(document).find('#ays_custom_css').next('.CodeMirror').remove();
                    $(document).find('#ays_custom_css').val('');
                    wp.codeEditor.initialize($(document).find('#ays_custom_css'), cm_settings);
                }
            }
        }, 100);

        $(document).find("#tab2").goToNormal();
    });

    $(document).on('mouseover', '.ays-dashicons', function(){
        var allRateStars = $(document).find('.ays-dashicons');
        var index = allRateStars.index(this);
        allRateStars.removeClass('ays-dashicons-star-filled').addClass('ays-dashicons-star-empty');
        for (var i = 0; i <= index; i++) {
            allRateStars.eq(i).removeClass('ays-dashicons-star-empty').addClass('ays-dashicons-star-filled');
        }
    });

    $(document).on('mouseleave', '.ays-rated-link', function(){
        $(document).find('.ays-dashicons').removeClass('ays-dashicons-star-filled').addClass('ays-dashicons-star-empty');                
    });

    var aysPollListTables = $(document).find('#wpcontent #wpbody div.wrap.ays-poll-list-table');

    if ( aysPollListTables.length > 0) {
        var listTableClass = "";
        var searchBox = "";
        if( aysPollListTables.hasClass('ays_polls_list_table') ){
            listTableClass = 'ays_polls_list_table';
            searchBox = 'poll-maker-ays-search-input';            
        } 
        else if( aysPollListTables.hasClass('ays_poll_categories_list_table') ){
            listTableClass = 'ays_poll_categories_list_table';
            searchBox = 'poll-maker-ays-search-input';
        }        
        else if( aysPollListTables.hasClass('ays_poll_results_list_table') ){
            listTableClass = 'ays_poll_results_list_table';
            searchBox = 'poll-maker-ays-search-input';
        }        

        if( listTableClass != "" && searchBox != "" ){
            ays_poll_search_box_pagination(listTableClass, searchBox);
        }
    }

    // Select message vars galleries page | Start
        $(document).find('.ays-poll-message-vars-icon').on('click', function(e){
            $(this).parents(".ays-poll-message-vars-box").find(".ays-poll-message-vars-data").toggle('fast');
        });
        
        $(document).on( "click" , function(e){
            if($(e.target).closest('.ays-poll-message-vars-box').length != 0){
            } 
            else{
                $(document).find(".ays-poll-message-vars-box .ays-poll-message-vars-data").hide('fast');
            }
        });

        $(document).find('.ays-poll-message-vars-each-data').on('click', function(e){
            var _this  = $(this);
            var parent = _this.parents('.ays-poll-desc-message-vars-parent');

            var textarea   = parent.find('textarea.ays-textarea');
            var textareaID = textarea.attr('id');

            var messageVar = _this.find(".ays-poll-message-vars-each-var").val();
            
            if ( parent.find("#wp-"+ textareaID +"-wrap").hasClass("tmce-active") ){
                window.tinyMCE.get(textareaID).setContent( window.tinyMCE.get(textareaID).getContent() + messageVar + " " );
            }else{
                $(document).find('#'+textareaID).append( " " + messageVar + " ");
            }
        });
        /* Select message vars galleries page | End */

    // Check poll result as read start
    $(document).find('.ays-show-results').on('click', function (e) {
        $(document).find('#ays-results-modal').fadeIn();
        $(document).find('div.ays-poll-preloader').css('display', 'flex');

        var $this = $(this);
        var readStatus = $this.parents('tr').find("td .unread-result-badge");

        $('li.toplevel_page_poll-maker-ays .apm-badge.badge-danger').each(function () {
            var $unreadCounter = $(this);

            if ($unreadCounter.text() != 0) {
                if(readStatus.hasClass('unread-result')){
                    var counter = +$unreadCounter.text();
                    counter--;
                    if(counter == 0){
                        $unreadCounter.hide();
                    }
                    $unreadCounter.text(counter);
                }
            }
            else{
                $unreadCounter.hide();
            }
        });
        
        if(readStatus.hasClass('unread-result')){
            readStatus.removeClass('unread-result');
        }

        $(this).css('font-weight' , 'normal');
        $(this).parent().css('font-weight' , 'normal');
        $(this).parent().siblings().css('font-weight' , 'normal');
        var result = $(this).data('result');
        var action = 'apm_show_results';
        $.ajax({
            url: poll.ajax,
            dataType: 'json',
            method: "post",
            data: {
                result: result,
                action: action,
                is_details: 1
            },
            success: function(response) {
                if (response.status === true) {
                    $('table#ays-results-table').html(response.rows);
                    $(document).find('div.ays-poll-preloader').css('display', 'none');
                }
            }
        });
        e.preventDefault();
    });
    // Check poll result as read end

    // Close results popup start
    $(document).find('#ays-close-results').on('click', function () {
        $(document).find('#ays-results-modal').fadeOut();
    });
    // Close results popup end

    // Pro features start
        $(document).on('click', '.pro_features_popup', function(e){
            e.preventDefault();
            var _this      = $(this);
            var popupModal = $(document).find('#pro-features-popup-modal');

            var popupModal_title       = _this.find('.pro-features-popup-title');
            var popupModal_title_text  = popupModal_title.text();

            var popupModal_content     = _this.find('.pro-features-popup-content').html();
            var popupModal_video_link  = _this.find('.pro-features-popup-content').attr("data-link");

            var popupModal_button      = _this.find('.pro-features-popup-button');
            var popupModal_button_text = popupModal_button.text();
            var popupModal_button_link = popupModal_button.attr("data-link");


            var leftSection  = popupModal.find('.ays-modal-body .pro-features-popup-modal-left-section');
            var rightSection = popupModal.find('.ays-modal-body .pro-features-popup-modal-right-section');

            rightSection.find('.pro-features-popup-modal-right-box-title').text(popupModal_title_text);
            rightSection.find('.pro-features-popup-modal-right-box-content').html(popupModal_content);
            rightSection.find('.pro-features-popup-modal-right-box-content').html(popupModal_content);

            rightSection.find('.pro-features-popup-modal-right-box-link').text(popupModal_button_text);
            rightSection.find('.pro-features-popup-modal-right-box-link').attr("href", popupModal_button_link);

            if ( typeof popupModal_video_link != "undefined" && popupModal_video_link != "") {
                var videoID = ays_youtube_parser(popupModal_video_link);
                var iframeHTML = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+ videoID +'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>';

                leftSection.html(iframeHTML);
            }

            popupModal.aysModal('show_flex');
        });

		$(document).on('click', '.ays-poll-new-watch-video-button-box, .ays-poll-center-big-watch-video-button-box', function(e){
            e.preventDefault();
            if( $(this).hasClass('ays-poll-center-big-watch-video-button-box') ){
                var _this = $(this).parent().parent().find('.pro_features.pro_features_popup');
            } else {
                var _this = $(this).parent().find('.pro_features.pro_features_popup');
            }
            var popupModal = $(document).find('#pro-features-popup-modal');
            var popupModal_title       = _this.find('.pro-features-popup-title');
            var popupModal_title_text  = popupModal_title.text();

            var popupModal_content     = _this.find('.pro-features-popup-content').html();
            var popupModal_video_link  = _this.find('.pro-features-popup-content').attr("data-link");

            var popupModal_button      = _this.find('.pro-features-popup-button');
            var popupModal_button_text = popupModal_button.text();
            var popupModal_button_link = popupModal_button.attr("data-link");


            var leftSection  = popupModal.find('.ays-modal-body .pro-features-popup-modal-left-section');
            var rightSection = popupModal.find('.ays-modal-body .pro-features-popup-modal-right-section');

            rightSection.find('.pro-features-popup-modal-right-box-title').text(popupModal_title_text);
            rightSection.find('.pro-features-popup-modal-right-box-content').html(popupModal_content);
            rightSection.find('.pro-features-popup-modal-right-box-content').html(popupModal_content);

            rightSection.find('.pro-features-popup-modal-right-box-link').text(popupModal_button_text);
            rightSection.find('.pro-features-popup-modal-right-box-link').attr("href", popupModal_button_link);

            if ( typeof popupModal_video_link != "undefined" && popupModal_video_link != "") {
                var videoID = ays_youtube_parser(popupModal_video_link);
                var iframeHTML = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+ videoID +'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>';

                leftSection.html(iframeHTML);
            }

            popupModal.aysModal('show_flex');
        });

        $(document).find('.ays-close-pro-popup').on('click', function () {
            $(this).parents('.ays-modal').aysModal('hide_remove_video');
        });
	// 	// Pro features end

})(jQuery);


function selectElementContents(el) {
    if (window.getSelection && document.createRange) {
        var _this = jQuery(document).find('strong.ays-poll-shortcode-box');
        var text      = el.textContent;
        var textField = document.createElement('textarea');

        textField.innerText = text;
        document.body.appendChild(textField);
        textField.select();
        document.execCommand('copy');
        textField.remove();
        var sel = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(el);
        sel.removeAllRanges();
        sel.addRange(range);
        _this.attr( "data-original-title", pollLangObj.copied );
        _this.attr( "title", pollLangObj.copied );
        _this.tooltip("show");
    } else if (document.selection && document.body.createTextRange) {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.select();
    }
}

Date.prototype.aysPollCustomFormat = function( formatString){
    var YYYY,YY,MMMM,MMM,MM,M,DDDD,DDD,DD,D,hhhh,hhh,hh,h,mm,m,ss,s,ampm,AMPM,dMod,th;
    YY = ((YYYY=this.getFullYear())+"").slice(-2);
    MM = (M=this.getMonth()+1)<10?('0'+M):M;
    MMM = (MMMM=["January","February","March","April","May","June","July","August","September","October","November","December"][M-1]).substring(0,3);
    DD = (D=this.getDate())<10?('0'+D):D;
    DDD = (DDDD=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"][this.getDay()]).substring(0,3);
    th=(D>=10&&D<=20)?'th':((dMod=D%10)==1)?'st':(dMod==2)?'nd':(dMod==3)?'rd':'th';
    formatString = formatString.replace("#YYYY#",YYYY).replace("#YY#",YY).replace("#MMMM#",MMMM).replace("#MMM#",MMM).replace("#MM#",MM).replace("#M#",M).replace("#DDDD#",DDDD).replace("#DDD#",DDD).replace("#DD#",DD).replace("#D#",D).replace("#th#",th);
    h=(hhh=this.getHours());
    if (h==0) h=24;
    if (h>12) h-=12;
    hh = h<10?('0'+h):h;
    hhhh = hhh<10?('0'+hhh):hhh;
    AMPM=(ampm=hhh<12?'am':'pm').toUpperCase();
    mm=(m=this.getMinutes())<10?('0'+m):m;
    ss=(s=this.getSeconds())<10?('0'+s):s;

    return formatString.replace("#hhhh#",hhhh).replace("#hhh#",hhh).replace("#hh#",hh).replace("#h#",h).replace("#mm#",mm).replace("#m#",m).replace("#ss#",ss).replace("#s#",s).replace("#ampm#",ampm).replace("#AMPM#",AMPM);
    // token:     description:             example:
    // #YYYY#     4-digit year             1999
    // #YY#       2-digit year             99
    // #MMMM#     full month name          February
    // #MMM#      3-letter month name      Feb
    // #MM#       2-digit month number     02
    // #M#        month number             2
    // #DDDD#     full weekday name        Wednesday
    // #DDD#      3-letter weekday name    Wed
    // #DD#       2-digit day number       09
    // #D#        day number               9
    // #th#       day ordinal suffix       nd
    // #hhhh#     2-digit 24-based hour    17
    // #hhh#      military/24-based hour   17
    // #hh#       2-digit hour             05
    // #h#        hour                     5
    // #mm#       2-digit minute           07
    // #m#        minute                   7
    // #ss#       2-digit second           09
    // #s#        second                   9
    // #ampm#     "am" or "pm"             pm
    // #AMPM#     "AM" or "PM"             PM
};

function drawBasic() {
    if(typeof pollAnswerChartObj != "undefined"){
        var data = google.visualization.arrayToDataTable(pollAnswerChartObj.answerData);
        var rowCount = data.getNumberOfRows();
        var multiply;
        if(rowCount < 8){
            multiply = 40;
        }
        else{
            multiply = 30;
        }
        var chartAreaHeight = rowCount * multiply;
        var chartHeight = chartAreaHeight + 80;
        
        /* === Old type === */
        // var options = {
        //     title: pollAnswerChartObj.pollTitle,
        //     width: '100%',
        //     fontSize: 15,
        //     height: chartHeight,
        //     chartArea: { 
        //         width: '75%',
        //         height: '80%'
        //     },
        //     hAxis: {
        //     minValue: 0
        //     }
        // };

        /* === New type === */
        var options = {
            title: pollAnswerChartObj.pollTitle,
            width: '100%',
            height: chartHeight,
            fontSize: 15,
            chartArea: { 
                width: '50%',
                height: '80%'
            },
            hAxis: {
                minValue: 0
            },
            annotations: {
                alwaysOutside: true
            },
            bars: 'horizontal',
            bar: { groupWidth: "50%" }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ays_poll_answer_chart'));
        // remove error
        // google.visualization.events.(chart, 'error', function (googleError) {
        //     google.visualization.errors.removeError(googleError.id);
        // });

        chart.draw(data, options);
        resizeChart(chart, data, options);
    }
}

function resizeChart(chart, data, options){
    
    //create trigger to resizeEnd event     
    jQuery(window).resize(function() {
        if(this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function() {
            jQuery(this).trigger('resizeEnd');
        }, 100);
    });

    //redraw graph when window resize is completed  
    jQuery(window).on('resizeEnd', function() {
        chart.draw(data, options);
    });
}