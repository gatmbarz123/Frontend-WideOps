(function ($) {
    // Open modal and play How To video.
    $( document ).on( 'click', '#poll-maker-welcome .play-video', function( event ) {
        event.preventDefault();

        var video = '<div class="poll-maker-welcome-video-container"><iframe height="400" loading="lazy" src="https://www.youtube.com/embed/0dfJQdAwdL4" frameborder="0" allowfullscreen></iframe></div>';

        var modal = $('<div class="poll-maker-welcome-modal"></div>');
        var modalContent = $('<div class="poll-maker-welcome-modal-content"></div>');
        var closeIcon = $('<span class="poll-maker-welcome-modal-close">&times;</span>');

        modal.append(modalContent);
        modalContent.append(closeIcon);
        modalContent.append(video);

        $('body').append(modal);

        closeIcon.on('click', function () {
            modal.remove();
        });

        $(document).on('keyup', function (event) {
            if (event.key === 'Escape') {
                modal.remove();
            }
        });

        modal.on('click', function (event) {
            if (!$(event.target).closest('.poll-maker-welcome-modal-content').length) {
                modal.remove();
            }
        });

    } );
})(jQuery);
