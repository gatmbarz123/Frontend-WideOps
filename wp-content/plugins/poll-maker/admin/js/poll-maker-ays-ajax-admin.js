(function ($) {
    'use strict';
   
    // Quick poll submit function
    $(document).find('#ays-save-quick-poll').on('click', function (e) {
        var $this = $(this);
        var title = $(document).find('#ays-quick-poll-title').val();

        $this.attr('disabled', true);
        $this.addClass('quick-poll-save-disabled');
        if (title == '') {
            swal.fire({
                type: 'error',
                html: "<h2>Poll title can't be empty.</h2>",
                onAfterClose: function() {
                    $this.removeClass('quick-poll-save-disabled');
                    $this.attr('disabled', false);
                }
            });

            return false;
        }

        var questions = $(document).find('#ays-quick-poll-question').val();
        var answersArr = $(document).find('.quick_poll_answer');

        for (var i = 0; i < answersArr.length; i++) {
            if (answersArr.eq(i).val() == '') {
                swal.fire({
                    type: 'error',
                    html: '<h2>You must fill all answers</h2>',
                    onAfterClose: function() {
                        $this.removeClass('quick-poll-save-disabled');
                        $this.attr('disabled', false);
                    }
                });
                return false;
            } else {
                answersArr[i] = answersArr.eq(i).val();
            }
        }

        // var allowAnonimity = $(document).find('#allow_anonimity_switch').is(':checked') ? 1 : 0;
        var allowMultivote = $(document).find('#allow_multivote_switch').is(':checked') ? 'on' : 'off';

        if (allowMultivote == 'on') {
            var multivote_min_count = $(document).find('#quick-poll-multivote-min-count').val();
            var multivote_max_count = $(document).find('#quick-poll-multivote-max-count').val();
        } else {
            var multivote_min_count = 1;
            var multivote_max_count = 1;
        }

        var wp_nonce = $(document).find('#ays_poll_ajax_quick_poll_nonce').val();

        var showTitle = $(document).find('#quick-poll-show-title').is(':checked') ? 'on' : 'off';

        var quickPollFormData = $('#ays-quick-poll-form').serializeFormJSON();
        quickPollFormData.action = 'ays_poll_maker_quick_start';
        quickPollFormData['quick-poll-show-title'] = showTitle;
        quickPollFormData._ajax_nonce = wp_nonce;

        $.ajax({
            url: apm_ajax_obj.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: quickPollFormData,
            success: function (response) {
                $(document).find('div.ays-poll-preloader').css('display', 'none');

                if (response.status == true) {
                    $(document).find('#ays-quick-poll-form')[0].reset();
                    $(document).find('#ays-poll-quick-create .ays-modal-content').addClass('animated bounceOutRight');
                    $(document).find('#ays-poll-quick-create').modal('hide');
                    swal({
                        title: '<strong>Great job</strong>',
                        type: 'success',
                        html: '<p>Your Poll is Created!<br>Copy the generated shortcode and paste it into any post or page to display Poll.</p><input type="text" id="quick_poll_shortcode" onClick="this.setSelectionRange(0, this.value.length)" readonly value="[ays_poll id=&quot;' + response.poll_id + '&quot;]" /><p style="margin-top:1rem;">For more detailed configuration visit <a href="admin.php?page=poll-maker-ays&action=edit&poll=' + response.poll_id + '">edit poll page.</a></p>',
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText: '<i class="ays_poll_fas ays_poll_fa_thumbs_up "></i> Done',
                        confirmButtonAriaLabel: 'Thumbs up, Done',
                        onAfterClose: function() {
                            $(document).find('#ays-poll-quick-create').removeClass('animated bounceOutRight');
                            $(document).find('#ays-poll-quick-create').css('display', 'none');
                            window.location.href = "admin.php?page=poll-maker-ays";
                            location.reload();
                        }
                    })
                }
            }
        })
    
    })

})(jQuery)
