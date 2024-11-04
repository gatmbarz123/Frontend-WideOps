(function ($) {
    $(document).ready(function () {

        $(document).find('#ays_create_quick_poll').on('click', function () {
            $('#ays-poll-quick-create').aysModal('show');
        });

        $(document).find('.ays-close-quick-create').on('click', hideModalContainer);
        $(document).find('#ays-poll-quick-create').on('click', hideModalContainer);

        $(document).find('#ays-poll-quick-create-content').on('click', function (e) {
            e.stopPropagation();
        });

        $(document).keydown(function (e) {
            // ESCAPE key pressed
            if ($('.ays_poll_modal').css('display') != 'none' && e.keyCode == 27) {
                hideModalContainer();
            }
        });

        $(document).find('.quick_poll_answer_remove').on('click', function () {
            var answerBoxesArr = $(document).find('.quick_poll_answer_box');
            var answerBoxesAmount = answerBoxesArr.length;

            if (answerBoxesAmount > 2) {
                $(this).parent().remove();
            } else {
                swal.fire({
                    type: 'info',
                    html: '<h2>Sorry minimum count of answers should be 2</h2>'
                });
            }
        })

        $(document).find('.quick_poll_add_option').on('click', function () {
            var answerBoxesArr = $(document).find('.quick_poll_answer_box');
            var lastAnswerBox = answerBoxesArr.eq(answerBoxesArr.length - 1);
            var lastInputID = lastAnswerBox.children('input').attr('data-id');

            var clonedElement = lastAnswerBox.clone(true);
            var clonedInputID = Number(lastInputID) + 1;
            clonedElement.children('input').attr('data-id', clonedInputID);
            clonedElement.children('input').val('');

            clonedElement.insertBefore($(this));
        })

        $(document).find('#allow_multivote_switch').on('change', function () {
            var multivoteSettingsDiv = $(document).find('.quick_poll_multivote_settings');
            var multivoteSettingsInputArr = multivoteSettingsDiv.children();

            if ($(this).is(':checked')) {
                multivoteSettingsDiv.css('visibility', 'visible');
                multivoteSettingsInputArr.css('visibility', 'visible');
            } else {
                multivoteSettingsInputArr.css('visibility', 'visible');
                multivoteSettingsInputArr.css('visibility', 'hidden');
                multivoteSettingsDiv.children().eq(0).val('');
                multivoteSettingsDiv.children().eq(1).val('');
            }
        })

        function hideModalContainer() {
            $(document).find('.ays_poll_modal').aysModal('hide');
        }

    });
})(jQuery);
