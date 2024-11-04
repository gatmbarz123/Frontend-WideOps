(function ($) {
    // String.prototype.stripSlashes = function () {
    //     return this.replace(/\\(.)/mg, "$1");
    // }
    function stripSlashes(str) {
        return str.replace(/\\(.)/mg, "$1");
    }
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

    $.fn.goToPoll = function(enabelAnimation, scroll) {
        var pollAnimationTop = (scroll && scroll != 0) ? parseInt(scroll) : 100;
        
        if( enabelAnimation ){
            $('html, body').animate({
                scrollTop: $(this).offset().top - scroll + 'px'
            }, 'slow');
        }
        return this; // for chaining...
    }

    $.fn.aysModal = function(action){
        var $this = $(this);
        switch(action){
            case 'hide':
                $(this).find('.ays-poll-avatars-modal-content').css('animation-name', 'zoomOut');
                setTimeout(function(){
                    $(document.body).removeClass('modal-open');
                    $(document).find('.ays-modal-backdrop').remove();
                    $this.hide();
                }, 250);
                break;
            case 'show':
            default:
                $this.show();
                $(this).find('.ays-poll-avatars-modal-content').css('animation-name', 'zoomIn');
                $(document).find('.modal-backdrop').remove();
                $(document.body).append('<div class="ays-modal-backdrop"></div>');
                $(document.body).addClass('modal-open');
                break;
        }
    }

    $(document).find(".ays-poll-main .apm-info-form").each(function(e){
        var infoForm = $(this);
        var mainContainer = infoForm.parents('.ays-poll-main');
        var id = mainContainer.attr('id').match(/\d+$/)[0];
        var pollBox = mainContainer.find('div[data-id="' + id + '"]');
        var uniqueId = pollBox.attr('id');
        var pollOptions = JSON.parse(window.atob(window.aysPollOptions[uniqueId]));
    
        if (pollOptions.autofill_user_data && pollOptions.autofill_user_data == "on") {
            var userData = {};
            userData.action = 'ays_poll_get_user_information';
            $.ajax({
                url: poll_maker_ajax_public.ajax_url,
                method: 'post',
                dataType: 'json',
                data: userData,
                success: function (response) {
                    if(response !== null){
                        infoForm.find("input[name='apm_name']").val(response.data.display_name);
                        infoForm.find("input[name='apm_email']").val(response.data.user_email);
                    }
                }
            });
        }
    })

    function socialBtnAdd(formId, buttons) {
        var socialDiv = $("<div class='apm-social-btn'></div>");
        if(buttons.heading != ""){
            socialDiv.append("<div class='ays-survey-social-shares-heading'>"+
                                    buttons.heading
                                +"</div>");
        }
        if(buttons.faceBook){
            socialDiv.append("<a class='fb-share-button ays-share-btn ays-share-btn-branded ays-share-btn-facebook'"+
                                        "title='Share on Facebook'>"+
                                        "<span class='ays-share-btn-text'>Facebook</span>"+
                                    "</a>");
        }
        if(buttons.twitter){
            socialDiv.append("<a class='twt-share-button ays-share-btn ays-share-btn-branded ays-share-btn-twitter'"+
                                    "title='Share on Twitter'>"+
                                    "<span class='ays-share-btn-text'>Twitter</span>"+
                                "</a>");
        }
        if(buttons.linkedIn){
            socialDiv.append("<a class='linkedin-share-button ays-share-btn ays-share-btn-branded ays-share-btn-linkedin'"+
                                    "title='Share on LinkedIn'>"+
                                    "<span class='ays-share-btn-text'>LinkedIn</span>"+
                                "</a>");
        }
        if(buttons.vkontakte){
            socialDiv.append("<a class='vkontakte-share-button ays-share-btn ays-share-btn-branded ays-share-btn-vkontakte'"+
                                    "title='Share on VKontakte'>"+
                                    "<span class='ays-survey-share-btn-icon'></span>"+
                                    "<span class='ays-share-btn-text'>VKontakte</span>"+
                                "</a>");
        }
        $("#"+formId).append(socialDiv);
        $(document).on('click', '.fb-share-button', function (e) {
            window.open('https://www.facebook.com/sharer/sharer.php?u=' + window.location.href,
                'facebook-share-dialog',
                'width=650,height=450'
            );
            return false;
        })
        $(document).on('click', '.twt-share-button', function (e) {
            window.open('https://twitter.com/intent/tweet?url=' + window.location.href,
                'twitter-share-dialog',
                'width=650,height=450'
            );
            return false;
        })
        $(document).on('click', '.linkedin-share-button', function (e) {
            window.open('https://www.linkedin.com/shareArticle?mini=true&url=' + window.location.href,
                'linkedin-share-dialog',
                'width=650,height=450'
            );
            return false;
        })
        $(document).on('click', '.vkontakte-share-button', function (e) {
            window.open('https://vk.com/share.php?url=' + window.location.href,
                'vkontakte-share-dialog',
                'width=650,height=450'
            );
            return false;
        })
        setTimeout(function() {
            $("#"+formId+" .apm-social-btn").css('opacity', '1');
        }, 1000);
    }

    function socialLinksAdd(formId, buttons) {
        var socialLinksDiv = $("<div class='apm-social-btn'></div>");
        if(buttons.heading != ""){
            socialLinksDiv.append("<div class='ays-survey-social-shares-heading'>"+
                                    buttons.heading
                                +"</div>");
        }
        if(buttons.faceBookLink != ''){
            socialLinksDiv.append("<a class='ays-share-btn ays-share-btn-branded ays-share-btn-facebook'"+
                                        "title='Facebook Link'  target='_blank' href=" + buttons.faceBookLink + ">"+
                                        "<div class='ays-poll-link-icon'><?xml version='1.0'?><svg fill='#fff' xmlns='http://www.w3.org/2000/svg'  viewBox='0 0 24 24' width='48px' height='48px'><path d='M19,3H5C3.895,3,3,3.895,3,5v14c0,1.105,0.895,2,2,2h7.621v-6.961h-2.343v-2.725h2.343V9.309 c0-2.324,1.421-3.591,3.495-3.591c0.699-0.002,1.397,0.034,2.092,0.105v2.43h-1.428c-1.13,0-1.35,0.534-1.35,1.322v1.735h2.7 l-0.351,2.725h-2.365V21H19c1.105,0,2-0.895,2-2V5C21,3.895,20.105,3,19,3z'/></svg></div>"+
                                    "</a>");
        }
        if(buttons.twitterLink != ''){
            socialLinksDiv.append("<a class='ays-share-btn ays-share-btn-branded ays-share-btn-twitter'"+
                                    "title='Twitter Link'  target='_blank' href=" + buttons.twitterLink + ">"+
                                    "<div class='ays-poll-link-icon'><svg fill='#fff' xmlns='http://www.w3.org/2000/svg'  viewBox='0 0 48 48' width='16px' height='16px'><path d='M44.719,10.305C44.424,10,43.97,9.913,43.583,10.091l-0.164,0.075c-0.139,0.064-0.278,0.128-0.418,0.191  c0.407-0.649,0.73-1.343,0.953-2.061c0.124-0.396-0.011-0.829-0.339-1.085c-0.328-0.256-0.78-0.283-1.135-0.066 c-1.141,0.693-2.237,1.192-3.37,1.54C37.4,7.026,35.071,6,32.5,6c-5.247,0-9.5,4.253-9.5,9.5c0,0.005,0,0.203,0,0.5l-0.999-0.08 c-9.723-1.15-12.491-7.69-12.606-7.972c-0.186-0.47-0.596-0.813-1.091-0.916C7.81,6.927,7.297,7.082,6.939,7.439  C6.741,7.638,5,9.48,5,13c0,2.508,1.118,4.542,2.565,6.124c-0.674-0.411-1.067-0.744-1.077-0.753 c-0.461-0.402-1.121-0.486-1.669-0.208c-0.546,0.279-0.868,0.862-0.813,1.473c0.019,0.211,0.445,4.213,5.068,7.235l-0.843,0.153 c-0.511,0.093-0.938,0.444-1.128,0.928C6.914,28.437,6.988,28.984,7.3,29.4c0.105,0.141,2.058,2.68,6.299,4.14  C11.334,34.295,8.222,35,4.5,35c-0.588,0-1.123,0.344-1.366,0.88c-0.244,0.536-0.151,1.165,0.237,1.607 C3.532,37.672,7.435,42,17.5,42C34.213,42,42,26.485,42,16v-0.5c0-0.148-0.016-0.293-0.022-0.439 c2.092-2.022,2.879-3.539,2.917-3.614C45.084,11.067,45.014,10.609,44.719,10.305z'/></svg></div>"+
                                "</a>");
        }
        if(buttons.linkedInLink != ''){
            socialLinksDiv.append("<a class='ays-share-btn ays-share-btn-branded ays-share-btn-linkedin'"+
                                    "title='LinkedIn Link' target='_blank' href=" + buttons.linkedInLink + ">"+
                                    "<div class='ays-poll-link-icon'><?xml version='1.0'?><svg fill='#fff' xmlns='http://www.w3.org/2000/svg'  viewBox='0 0 24 24' width='48px' height='48px'>    <path d='M21,3H3v18h18V3z M9,17H6.477v-7H9V17z M7.694,8.717c-0.771,0-1.286-0.514-1.286-1.2s0.514-1.2,1.371-1.2 c0.771,0,1.286,0.514,1.286,1.2S8.551,8.717,7.694,8.717z M18,17h-2.442v-3.826c0-1.058-0.651-1.302-0.895-1.302 s-1.058,0.163-1.058,1.302c0,0.163,0,3.826,0,3.826h-2.523v-7h2.523v0.977C13.93,10.407,14.581,10,15.802,10 C17.023,10,18,10.977,18,13.174V17z'/></svg></div>"+
                                "</a>");
        }
        if(buttons.vkontakteLink != ''){
            socialLinksDiv.append("<a class='ays-share-btn ays-share-btn-branded ays-share-btn-vkontakte'"+
                                    "title='VKontakte Link'  target='_blank' href=" + buttons.vkontakteLink + ">"+
                                    "<div class='ays-poll-link-icon'><svg fill='#fff' xmlns='http://www.w3.org/2000/svg'  viewBox='0 0 48 48' width='48px' height='48px'><path d='M45.763,35.202c-1.797-3.234-6.426-7.12-8.337-8.811c-0.523-0.463-0.579-1.264-0.103-1.776 c3.647-3.919,6.564-8.422,7.568-11.143C45.334,12.27,44.417,11,43.125,11l-3.753,0c-1.237,0-1.961,0.444-2.306,1.151 c-3.031,6.211-5.631,8.899-7.451,10.47c-1.019,0.88-2.608,0.151-2.608-1.188c0-2.58,0-5.915,0-8.28 c0-1.147-0.938-2.075-2.095-2.075L18.056,11c-0.863,0-1.356,0.977-0.838,1.662l1.132,1.625c0.426,0.563,0.656,1.248,0.656,1.951 L19,23.556c0,1.273-1.543,1.895-2.459,1.003c-3.099-3.018-5.788-9.181-6.756-12.128C9.505,11.578,8.706,11.002,7.8,11l-3.697-0.009 c-1.387,0-2.401,1.315-2.024,2.639c3.378,11.857,10.309,23.137,22.661,24.36c1.217,0.12,2.267-0.86,2.267-2.073l0-3.846 c0-1.103,0.865-2.051,1.977-2.079c0.039-0.001,0.078-0.001,0.117-0.001c3.267,0,6.926,4.755,8.206,6.979 c0.368,0.64,1.056,1.03,1.8,1.03l4.973,0C45.531,38,46.462,36.461,45.763,35.202z'/></svg></div>"+
                                "</a>");
        }
        if(buttons.youtubeLink != ''){
            socialLinksDiv.append("<a class='ays-share-btn ays-share-btn-branded ays-poll-share-btn-youtube'"+
                                    "title='Youtube Link'  target='_blank' href=" + buttons.youtubeLink + ">"+
                                    "<div class='ays-poll-link-icon'><svg xmlns='http://www.w3.org/2000/svg' fill='#FF0000' viewBox='0 0 28 28' width='48px' height='48px'>    <path d='M 15 4 C 10.814 4 5.3808594 5.0488281 5.3808594 5.0488281 L 5.3671875 5.0644531 C 3.4606632 5.3693645 2 7.0076245 2 9 L 2 15 L 2 15.001953 L 2 21 L 2 21.001953 A 4 4 0 0 0 5.3769531 24.945312 L 5.3808594 24.951172 C 5.3808594 24.951172 10.814 26.001953 15 26.001953 C 19.186 26.001953 24.619141 24.951172 24.619141 24.951172 L 24.621094 24.949219 A 4 4 0 0 0 28 21.001953 L 28 21 L 28 15.001953 L 28 15 L 28 9 A 4 4 0 0 0 24.623047 5.0546875 L 24.619141 5.0488281 C 24.619141 5.0488281 19.186 4 15 4 z M 12 10.398438 L 20 15 L 12 19.601562 L 12 10.398438 z'/></svg></div>"+
                                "</a>");
        }
        $("#"+formId).append(socialLinksDiv);

        setTimeout(function() {
            $("#"+formId+" .apm-social-btn").css('opacity', '1');
        }, 1000);
    }

    function loadEffect(formId, onOff , fontSize,message) {
        var loadFontSize = fontSize.length > 0 ? fontSize+"px" : '100%';
        var form = $("#"+formId),
            effect = form.attr('data-loading');
        switch (effect) {
            case 'blur':
                form.css({
                    WebkitFilter: onOff ? 'blur(5px)' : 'unset',
                    filter: onOff ? 'blur(5px)' : 'unset',
                })
                break;
            case 'load_gif':
                if (onOff) {
                    var loadSvg = '';
                    switch (form.attr('data-load-gif')) {
                        case 'plg_1':
                            loadSvg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width='+loadFontSize+' height='+loadFontSize+' viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">'+
                            '<rect x="0" y="0" width="4" height="10" fill="#333">'+
                              '<animateTransform attributeType="xml"'+
                                'attributeName="transform" type="translate"'+
                                'values="0 0; 0 20; 0 0"'+
                                'begin="0" dur="0.8s" repeatCount="indefinite" />'+
                            '</rect>'+
                            '<rect x="10" y="0" width="4" height="10" fill="#333">'+
                              '<animateTransform attributeType="xml"'+
                                'attributeName="transform" type="translate"'+
                                'values="0 0; 0 20; 0 0"'+
                                'begin="0.2s" dur="0.8s" repeatCount="indefinite" />'+
                            '</rect>'+
                            '<rect x="20" y="0" width="4" height="10" fill="#333">'+
                              '<animateTransform attributeType="xml"'+
                                'attributeName="transform" type="translate"'+
                                'values="0 0; 0 20; 0 0"'+
                                'begin="0.4s" dur="0.8s" repeatCount="indefinite" />'+
                            '</rect>'+
                        '</svg>';
                            break;
                        case 'plg_2':
                            loadSvg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"  width='+loadFontSize+' height='+loadFontSize+' viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">'+
                            '<rect x="0" y="10" width="4" height="10" fill="#333" opacity="0.2">'+
                                '<animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.7s" repeatCount="indefinite" />'+
                                '<animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.7s" repeatCount="indefinite" />'+
                                '<animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.7s" repeatCount="indefinite" />'+
                            '</rect>'+
                            '<rect x="8" y="10" width="4" height="10" fill="#333"  opacity="0.2">'+
                                '<animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2"    begin="0.15s" dur="0.7s" repeatCount="indefinite" />'+
                                '<animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.7s" repeatCount="indefinite" />'+
                                '<animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s"   dur="0.7s" repeatCount="indefinite" />'+
                            '</rect>'+
                            '<rect x="16" y="10" width="4" height="10" fill="#333"  opacity="0.2">'+
                                '<animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.7s" repeatCount="indefinite" />'+
                                '<animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.7s" repeatCount="indefinite" />'+
                                '<animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.7s" repeatCount="indefinite" />'+
                            '</rect>'+
                        '</svg>';
                            break;
                        case 'plg_3':
                            loadSvg = '<svg width='+loadFontSize+' height='+loadFontSize+' viewBox="0 0 105 105" xmlns="http://www.w3.org/2000/svg" fill="#000">'+
                            '<circle cx="12.5" cy="12.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="0s" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="12.5" cy="52.5" r="12.5" fill-opacity=".5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="100ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="52.5" cy="12.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="300ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="52.5" cy="52.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="600ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="92.5" cy="12.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="800ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="92.5" cy="52.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="400ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="12.5" cy="92.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="700ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="52.5" cy="92.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="500ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                            '<circle cx="92.5" cy="92.5" r="12.5">'+
                                '<animate attributeName="fill-opacity"'+
                                 'begin="200ms" dur="0.9s"'+
                                 'values="1;.2;1" calcMode="linear"'+
                                 'repeatCount="indefinite" />'+
                            '</circle>'+
                        '</svg>';
                            break;
                        case 'plg_4':
                            loadSvg = '<svg width='+loadFontSize+' height='+loadFontSize+' viewBox="0 0 57 57" xmlns="http://www.w3.org/2000/svg"  stroke="#000">'+
                            '<g fill="none" fill-rule="evenodd">'+
                                '<g transform="translate(1 1)" stroke-width="2">'+
                                    '<circle cx="5" cy="50" r="5">'+
                                        '<animate attributeName="cy"'+
                                             'begin="0s" dur="2.2s"'+
                                             'values="50;5;50;50"'+
                                             'calcMode="linear"'+
                                             'repeatCount="indefinite" />'+
                                        '<animate attributeName="cx"'+
                                             'begin="0s" dur="2.2s"'+
                                             'values="5;27;49;5"'+
                                             'calcMode="linear"'+
                                             'repeatCount="indefinite" />'+
                                    '</circle>'+
                                    '<circle cx="27" cy="5" r="5">'+
                                        '<animate attributeName="cy"'+
                                             'begin="0s" dur="2.2s"'+
                                             'from="5" to="5"'+
                                             'values="5;50;50;5"'+
                                             'calcMode="linear"'+
                                             'repeatCount="indefinite" />'+
                                        '<animate attributeName="cx"'+
                                             'begin="0s" dur="2.2s"'+
                                             'from="27" to="27"'+
                                             'values="27;49;5;27"'+
                                             'calcMode="linear"'+
                                             'repeatCount="indefinite" />'+
                                    '</circle>'+
                                    '<circle cx="49" cy="50" r="5">'+
                                        '<animate attributeName="cy"'+
                                             'begin="0s" dur="2.2s"'+
                                             'values="50;50;5;50"'+
                                             'calcMode="linear"'+
                                             'repeatCount="indefinite" />'+
                                        '<animate attributeName="cx"'+
                                             'from="49" to="49"'+
                                             'begin="0s" dur="2.2s"'+
                                             'values="49;5;27;49"'+
                                             'calcMode="linear"'+
                                             'repeatCount="indefinite" />'+
                                    '</circle>'+
                                '</g>'+
                            '</g>'+
                        '</svg>';
                            break;
                        case 'plg_5':
                            loadSvg = '<svg width='+loadFontSize+' height='+loadFontSize+' viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg"  stroke="#000">'+
                            '<rect y="10" width="15" height="120" rx="6">'+
                                '<animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" />'+
                                '<animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" />'+
                            '</rect>'+
                            '<rect x="30" y="10" width="15" height="120" rx="6">'+
                                '<animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/>'+
                                '<animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/>'+
                            '</rect>'+
                           ' <rect x="60" width="15" height="140" rx="6">'+
                                '<animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/>'+
                                '<animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/>'+
                            '</rect>'+
                            '<rect x="90" y="10" width="15" height="120" rx="6">'+
                                '<animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/>'+
                                '<animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/>'+
                            '</rect>'+
                            '<rect x="120" y="10" width="15" height="120" rx="6">'+
                                '<animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/>'+
                                '<animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/>'+
                            '</rect>'+
                        '</svg>';
                            break;
                        default:
                            loadSvg = '<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"'+
                            'width='+loadFontSize+' height='+loadFontSize+' viewBox="0 0 50 50" style="enable-background:new 0 0 50  50;" xml:space="preserve">'+
                                '<path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318, 0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0, 14.615, 6.543,14.615,14.615H43.935z">'+
                                    '<animateTransform attributeType="xml"'+
                                        'attributeName="transform"'+
                                        'type="rotate"'+
                                        'from="0 25 25"'+
                                        'to="360 25 25"'+
                                        'dur="0.6s"'+
                                        'repeatCount="indefinite"/>'+
                                '</path>'+
                            '</svg>';
                    }
                    var layer = $('<div class="apm-opacity-layer-light" style="margin-top: 0">'+
                        '<div class="apm-loading-gif">'+
                            '<div class="apm-loader loader--style3">'+
                                loadSvg+
                            '</div>'+
                        '</div>'+
                    '</div>');
                    form.css({
                        position: 'relative'
                    });
                    form.append(layer);
                    layer.css('opacity', 1);
                } else {
                    $('.apm-opacity-layer-light').css('opacity', 0).empty();
                    setTimeout(function() {
                        $('.apm-opacity-layer-light').remove();
                    }, 500);
                }
                break;
            case 'message':
                if (onOff) {
                    var layer = $('<div class="apm-opacity-layer-light apm-load-message-container"><span>'+message+'</span></div>');
                    form.css({
                        position: 'relative'
                    });
                    form.append(layer);
                    layer.css('opacity', 1);
                    setTimeout(function() {
                        $('.apm-load-message-container').remove();
                    }, 500);
                }
                else{
                     $('.apm-opacity-layer-light').css('opacity', 0).empty();
                    setTimeout(function() {
                        $('.apm-opacity-layer-dark').remove();
                    }, 500);
                }
                break;    
            default:
                if (onOff) {
                    var layer = $('<div class="apm-opacity-layer-dark"></div>');
                    form.css({
                        position: 'relative'
                    });
                    form.append(layer);
                    layer.css('opacity', 1);
                } else {
                    $('.apm-opacity-layer-dark').css('opacity', 0);
                    setTimeout(function() {
                        $('.apm-opacity-layer-dark').remove();
                    }, 500);
                }
                break;
        }
    }

    function sortDate(rateCount, votesSum, answers, formId) {
        var form = $("#"+formId),
            sortable = form.attr('data-res-sort'),
            widths = [];
        for (var i = 0; i < rateCount; i++) {
            var answer = answers[i];
            widths[i] = {};
            var width = (answer.votes * 100 / votesSum).toFixed(0);
            widths[i].width = width;
            widths[i].index = i;
        }
        if (sortable === "ASC") {
            for (var i = 0; i < rateCount; i++) {
                for (var j = (i + 1); j < rateCount; j++) {
                    if (Number(widths[i].width) > Number(widths[j].width)) {
                        var temp = widths[i].width;
                        widths[i].width = widths[j].width;
                        widths[j].width = temp;
                        temp = widths[i].index;
                        widths[i].index = widths[j].index;
                        widths[j].index = temp;
                    }
                }
            }
        } else if (sortable === "DESC") {
            for (var i = 0; i < rateCount; i++) {
                for (var j = (i + 1); j < rateCount; j++) {
                    if (Number(widths[i].width) < Number(widths[j].width)) {
                        var temp = widths[i].width;
                        widths[i].width = widths[j].width;
                        widths[j].width = temp;
                        temp = widths[i].index;
                        widths[i].index = widths[j].index;
                        widths[j].index = temp;
                    }
                }
            }
        }
        return widths;
    }

    var apmIcons = {
        star: "<i class='ays_poll_far ays_poll_fa-star'></i>",
        star1: "<i class='ays_poll_fas ays_poll_fa-star'></i>",
        emoji: [
            "<i class='ays_poll_far ays_poll_fa-dizzy'></i>",
            "<i class='ays_poll_far ays_poll_fa-smile'></i>",
            "<i class='ays_poll_far ays_poll_fa-meh'></i>",
            "<i class='ays_poll_far ays_poll_fa-frown'></i>",
            "<i class='ays_poll_far ays_poll_fa-tired'></i>",
        ],
        emoji1: [
            "<i class='ays_poll_fas ays_poll_fa-dizzy'></i>",
            "<i class='ays_poll_fas ays_poll_fa-smile'></i>",
            "<i class='ays_poll_fas ays_poll_fa-meh'></i>",
            "<i class='ays_poll_fas ays_poll_fa-frown'></i>",
            "<i class='ays_poll_fas ays_poll_fa-tired'></i>",
        ],
        hand: [
            "<i class='ays_poll_far ays_poll_fa-thumbs-up'></i>",
            "<i class='ays_poll_far ays_poll_fa-thumbs-down'></i>"
        ],
        hand1: [
            "<i class='ays_poll_fas ays_poll_fa-thumbs-up'></i>",
            "<i class='ays_poll_fas ays_poll_fa-thumbs-down'></i>"
        ],
    };

    function showInfoForm($form) {
        $form.find('.ays_question, .apm-answers').fadeOut(0);
        $infoForm = $form.find('.apm-info-form');
        $infoForm.fadeIn();
        $form.find('.ays_finish_poll').val($infoForm.attr('data-text'));
        $form.find('.ays_finish_poll').attr('style', 'display:initial !important');
        $form.find('.ays-see-res-button-show').attr('style', 'display:none');
        $form.attr('data-info-form', '');
    }

    var emailValivatePattern = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.\w{2,}$/;

    function voting(btnId , type) {
        if (typeof btnId == "undefined"){
            btnId = 0;
        }
        var btn;
        if( btnId === 0 ){
            btn = $(this);
        }else{
            btn = btnId;
        }
        var seeRes = btn.attr('data-seeRes'),
            isLimitation = btn.attr('data-limitation') == 'true';
            formId = btn.attr('data-form'),
            form = $("#"+formId),
            pollId = form.attr('data-id'),
            isRestart = form.attr('data-restart'),
            voteURLRedirection = form.attr('data-redirect-check'),
            voteRedirection = form.attr('data-redirection'),
            infoForm = form.attr('data-info-form'),
            resultColorsRgba = form.attr('data-res-rgba'),
            hideBgImage = form.attr('data-hide-bg-image'),
            hideBgImageDefColor = form.data('hide-bg-image-def-color'),
            backgroundGradientCheck = form.data('gradient-check'),
            backgroundGradientC1 = form.data('gradient-c1'),
            backgroundGradientC2 = form.data('gradient-c2'),
            backgroundGradientDir = form.data('gradient-dir'),
            loadEffectFontSize = form.attr('data-load-gif-font-size');
            enableTopAnimation = form.attr('data-enable-top-animation');
            topAnimationScroll = form.attr('data-top-animation-scroll');
            loadEffectMessage  = typeof form.data('loadMessage') != "undefined" ? form.data('loadMessage') : "";

        var pollOptions = JSON.parse(window.atob(window.aysPollOptions[formId]));
        var pollEnableLn = typeof pollOptions.poll_show_social_ln != "undefined" && pollOptions.poll_show_social_ln.length > 0 && pollOptions.poll_show_social_ln == "on" ? true : false;
        var pollEnableFb = typeof pollOptions.poll_show_social_fb != "undefined" && pollOptions.poll_show_social_fb.length > 0 && pollOptions.poll_show_social_fb == "on" ? true : false;
        var pollEnableTr = typeof pollOptions.poll_show_social_tr != "undefined" && pollOptions.poll_show_social_tr.length > 0 && pollOptions.poll_show_social_tr == "on" ? true : false;
        var pollEnableVk = typeof pollOptions.poll_show_social_vk != "undefined" && pollOptions.poll_show_social_vk.length > 0 && pollOptions.poll_show_social_vk == "on" ? true : false;
        var pollSocialButtons = {
            linkedIn  : pollEnableLn,
            faceBook  : pollEnableFb,
            twitter   : pollEnableTr,
            vkontakte : pollEnableVk,
        };

        if( typeof pollOptions.social_links != "undefined" ) {
            var pollLinkedInLink = pollOptions.social_links.linkedin_link != "" ? pollOptions.social_links.linkedin_link : "";
            var pollFacebookInLink = pollOptions.social_links.facebook_link != "" ? pollOptions.social_links.facebook_link : "";
            var pollTwitterLink = pollOptions.social_links.twitter_link != "" ? pollOptions.social_links.twitter_link : "";
            var pollVkontakteLink = pollOptions.social_links.vkontakte_link != "" ? pollOptions.social_links.vkontakte_link : "";
            var pollYoutubeLink = pollOptions.social_links.youtube_link != "" ? pollOptions.social_links.youtube_link : "";
        }
        var pollSocialLinks = {
            linkedInLink  : pollLinkedInLink,
            faceBookLink  : pollFacebookInLink,
            twitterLink   : pollTwitterLink,
            vkontakteLink : pollVkontakteLink,
            youtubeLink   : pollYoutubeLink,
        }

        var data = form.parent().serializeFormJSON();
        
        if(form.hasClass('choosing-poll') && !seeRes){
            var allowMultivoteCheck = $('#'+formId).find('input#ays_poll_multivote_min_count').data("allow");
            if(allowMultivoteCheck){
                var numberCheckedAnswers  = $('#'+formId).find('input:checkbox:checked').length;
                var numberAllAnswers  = $('#'+formId).find('input:checkbox').length;
                var minimumVotesCount = $('#'+formId).find('input#ays_poll_multivote_min_count').val();
                var otherAnswer = $('#'+formId).find('input.ays-poll-new-answer-apply-text');
                var otherAnswerVal = otherAnswer.val();
                if(otherAnswer.length > 0 && otherAnswerVal != ""){
                    numberCheckedAnswers++;
                }
                if( numberAllAnswers < minimumVotesCount){
                    minimumVotesCount = numberAllAnswers;
                    form.find('.ays-poll-multivote-message').html("Minimum votes count shoulde be "+minimumVotesCount);
                }
                if(minimumVotesCount > numberCheckedAnswers){
                    form.find('.ays-poll-multivote-message').show();
                    return false;
                }
            }
        }

        if (infoForm) {
            if (type == "text" && data.answer == ""){
                return;
            }

            if ('answer' in data && !seeRes || (('ays_poll_new_answer' in data) && data.ays_poll_new_answer != '')) {
                return showInfoForm(form);
            } else if(!seeRes) {
                return false;
            }
        }

        var valid = true;
        form.find('.apm-info-form input[name]').each(function () {
            $(this).removeClass('ays_poll_shake');
            if ($(this).attr('data-required') == 'true' && $(this).val() == "" && !seeRes) {
                $(this).addClass('apm-invalid');
                $(this).addClass('ays_red_border');
                $(this).addClass('ays_poll_shake');
                valid = false;
            }
        });
        
        var email_val = $('[check_id="'+formId+'"]');
        if (email_val.attr('type') !== 'hidden' && email_val.attr('check_id') == formId) {
            if(email_val.val() != ''){
                if (!(emailValivatePattern.test(email_val.val())) && !seeRes) {
                    email_val.addClass('ays_red_border');
                    email_val.addClass('ays_poll_shake');
                    valid = false;
                }else{
                    email_val.addClass('ays_green_border');
                }
            }
        }

        var phoneInput = $(document).find("#"+formId).find('input[name="apm_phone"]');
        var phoneInputVal = phoneInput.val();
        if(phoneInputVal != '' && typeof phoneInputVal !== 'undefined'){
            phoneInput.removeClass('ays_red_border');
            phoneInput.removeClass('ays_green_border');
            if (!validatePhoneNumber(phoneInput.get(0))) {
                if (phoneInput.attr('type') !== 'hidden') {
                    phoneInput.addClass('ays_red_border');
                    phoneInput.addClass('ays_poll_shake');
                    valid = false;
                }
            }else{
                phoneInput.addClass('ays_green_border');
            }
        }
        
        if (!valid && !seeRes) {
            return false;
        }

        if ((!('answer' in data) && !seeRes) && (!('ays_poll_new_answer' in data) || (('ays_poll_new_answer' in data) && data.ays_poll_new_answer == ''))) return;
        var userAnswerValue;
        if (data.answer) {
            var userAnswerId = data.answer;

            if ($.isArray(userAnswerId)) {
                var userEachAnswerValue = [];

                for (var i = 0; i < userAnswerId.length; i++) {
                    var userEachAnswer = form.find('input[name="answer"][value="'+userAnswerId[i]+'"]');
                    var userEachAnswerBox = userEachAnswer.parent();
                    userEachAnswerValue.push(userEachAnswerBox.find('span.ays-poll-each-answer-grid').text());
                }

                userAnswerValue = userEachAnswerValue;

                if (data.ays_poll_new_answer) {
                    userAnswerValue.push(data.ays_poll_new_answer);
                }
            } else {
                var userAnsweInput = form.find('input[name="answer"][value="'+userAnswerId+'"]');
                var userAnswerBox = userAnsweInput.parent();
                userAnswerValue = userAnswerBox.find('span.ays-poll-each-answer-grid').text();
            }
        } else if(data.ays_poll_new_answer) {
            userAnswerValue = data.ays_poll_new_answer;
        }
        if (seeRes && ('answer' in data)) delete data['answer'];
        loadEffect(formId, true , loadEffectFontSize,loadEffectMessage);
        btn.off();
        data.action = 'ays_finish_poll';
        data.poll_id = pollId;

        var endDate = GetFullDateTime();
        data.end_date = endDate;

        // Mute answer sound button
        var $this = $(document).find('.ays_finish_poll').data("form");
        var currentContainer = $(document).find("#"+$this);
        var soundEls = currentContainer.find('.ays_music_sound');
        if(soundEls.hasClass("ays_music_sound")){
            soundEls.removeClass("ays_sound_active");
            soundEls.addClass("ays_poll_display_none");
        }
        if($(document).scrollTop() >= form.offset().top){
            form.goToPoll(enableTopAnimation,topAnimationScroll);
        }

        $.ajax({
            url: poll_maker_ajax_public.ajax_url,
            dataType: 'json',
            method:'post',
            data: data,
            success: function(res) {
                var answers_sounds = $("#"+formId).parent().find('.ays_poll_ans_sound').get(0);
                if(answers_sounds){
                    setTimeout(function() {
                        resetPlaying(answers_sounds);
                    }, 1000);
                }
                if(hideBgImage == 'true'){
                    if(!backgroundGradientCheck){
                        $(document).find("#"+formId).css("background-image", "none");
                        $(document).find("#"+formId).css("background-color", hideBgImageDefColor);
                    }
                    else{
                        $(document).find("#"+formId).css("background-image", "linear-gradient("+backgroundGradientDir+", "+backgroundGradientC1+", "+backgroundGradientC2+")");
                    }
                }
                $("#"+formId+" .ays_poll_cb_and_a").hide();
                $("#"+formId+" .ays_poll_show_timer").hide();
                var delay = $('.ays-poll-main').find('div.box-apm[data-delay]').attr('data-delay');
                delayCountDown(delay);
                loadEffect(formId, false , loadEffectFontSize,loadEffectMessage);
                form.parent().next().prop('disabled', false);
                $('.answer-' + formId).parent().remove(); //for removing apm-answer
                form.find('.ays_poll_passed_count').remove();
                form.find('.apm-info-form').remove();
                var redirectMessage = voteRedirection ? form.find('.redirectionAfterVote').clone(true) : '';
                $("#"+formId+" .apm-button-box").remove();
                var hideRes = form.attr('data-res');
                var resultContainer = $("#"+formId).parent().find('.box-apm');

                var hideResOption = false;
                var pollSocialLinksHeading = "";
                var pollSocialButtonsHeading = "";
                if(typeof res.styles != "undefined"){

                    if(typeof res.styles['hide_results'] != "undefined"){
                        hideResOption = res.styles['hide_results'].length > 0 && res.styles['hide_results'] != 1 ? true : false;
                    }

                    if(typeof res.styles['poll_social_links_heading'] != "undefined"){
                        pollSocialLinksHeading = typeof res.styles['poll_social_links_heading'] != "undefined" && res.styles['poll_social_links_heading'].length > 0 && res.styles['poll_social_links_heading'] != "" ? res.styles['poll_social_links_heading'] : "";
                    }

                    if(typeof res.styles['poll_social_buttons_heading'] != "undefined"){
                        pollSocialButtonsHeading = typeof res.styles['poll_social_buttons_heading'] != "undefined" && res.styles['poll_social_buttons_heading'].length > 0 && res.styles['poll_social_buttons_heading'] != "" ? res.styles['poll_social_buttons_heading'] : "";
                    }

                    var result_message = typeof res.styles['result_message'] != "undefined" && res.styles['result_message'] != "" ? res.styles['result_message'] : "";

                    if (!isLimitation) {
                        form.find('.apm-title-box.ays_res_mess').html(result_message);
                    }

                    var hide_results_text = typeof res.styles['hide_results_text'] != "undefined" && res.styles['hide_results_text'] != "" ? res.styles['hide_results_text'] : "";
                    form.find('.ays-poll-hide-result-box').html(hide_results_text);

                }
                pollSocialLinks.heading = pollSocialLinksHeading;
                pollSocialButtons.heading = pollSocialButtonsHeading;
                    
                if( !res.voted_status && !seeRes && hideResOption){
                    var content = '';
                    var limitation_message = (res.styles['limitation_message'] && res.styles['limitation_message'] != '') ? res.styles['limitation_message'] : poll_maker_ajax_public.alreadyVoted;
                    limitation_message = limitation_message.replace(/\\/g, '');

                    content += '<div class="ays-poll-vote-message">';
                        content += '<p>'+ limitation_message +'</p>';
                    content += '</div>';

                    resultContainer.append(content);
                }

                if (hideRes != 0) {
                    $("#"+formId+" .ays_question").remove();
                    $("#"+formId+" .hideResults").css("display", "block");
                }
                else if ( type == "text" ) {
                    $("#"+formId+" .ays_question").remove();
                    $("#"+formId+" .hideResults").css("display", "block");
                    $("#"+formId+" .ays_res_mess").fadeIn();
                }
                else if ( !res.voted_status ) {
                    $("#"+formId+" .hideResults").css("display", "block");
                }
                else {
                    form.append('<div class="results-apm" id="pollResultId' +data.poll_id+ '">' + '</div>');
                    var votesSum = 0;
                    var votesMax = 0;
                    var answer;
                    for ( answer in res.answers) {
                        votesSum = Math.abs(res.answers[answer].votes) + votesSum;
                        if (+res.answers[answer].votes > votesMax) {
                            votesMax = +res.answers[answer].votes;
                        }
                    }
                    var answer2 = res.answers;

                    // Answer Numbering
                    
                    var widths = sortDate(res.answers.length, votesSum, answer2, formId );
                    //show votes count 
                    var showvotescounts = true;
                    if (res.styles.show_votes_count == 0) {
                        showvotescounts = false;
                    }

                    //show result percent 
                    var showrespercent = true;
                    if (res.styles.show_res_percent == 0) {
                        showrespercent = false;
                    }

                    var barChartType = typeof res.styles['show_chart_type'] != "undefined" && res.styles['show_chart_type'].length > 0 && res.styles['show_chart_type'] != "" ? res.styles['show_chart_type'] : "default_bar_chart";

                    if(barChartType == "google_bar_chart") {
                        var chartHeight = typeof res.styles['show_chart_type_google_height'] != "undefined" && res.styles['show_chart_type_google_height'] != "" ? res.styles['show_chart_type_google_height'] : 400;
                        var aysBarChartData = new Array(['', '']);
                        $("#"+formId+" .ays_res_mess").fadeIn();
                        for (var tox in widths) {
                            var chartRealVotes = +answer2[widths[tox].index].votes;
                            var answerTextVal   = answer2[widths[tox].index].answer;
                            //var finalAnswerText = ays_poll_restriction_string( 'words', answerTextVal, 2 );
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

                            var chart = new google.visualization.BarChart(document.getElementById('pollResultId' + pollId));
                            google.visualization.events.addListener(chart, 'ready', function () {
                                $.each($('text[text-anchor="end"]'), function (index, label) {
                                    var labelText = $(label).text();
                                    if ($.isArray(userAnswerValue)) {
                                        for (var i = 0; i < userAnswerValue.length; i++) {
                                            if (labelText == userAnswerValue[i]) {
                                                $(this).css('font-weight', '600');
                                            }
                                        }
                                    } else {
                                      if (labelText == userAnswerValue) {
                                        $(this).css('font-weight', '600');
                                      }
                                    }
                                });
                            });
                            chart.draw(view,options);
                        }
                    } else if (barChartType == "default_bar_chart") {
                        if(typeof data.answer == "string") {
                            var dataAnswerArr = data.answer.split(",")
                        }
                        else {
                            dataAnswerArr = data.answer;
                        }

                        for (var i = 0; i < res.answers.length; i++) {
                            var rightAnswerCheck = (jQuery.inArray(res.answers[widths[i].index].id, dataAnswerArr) !== -1) ? 'ays_check' : '';
                            var starAnswerCheck = (data.answer == res.answers[widths[i].index].id) ? apmIcons.star1 : apmIcons.star;
                            var emojiAnswerCheck = (data.answer == res.answers[widths[i].index].id) ? apmIcons.emoji1 : apmIcons.emoji;
                            var handAnswerCheck = (data.answer == res.answers[widths[i].index].id) ? apmIcons.hand1 : apmIcons.hand;
                            var answer = res.answers;
                            var percentColor = form.attr('data-percent-color');
                            
                            var answerDiv = $('<div class="answer-title flex-apm"></div>'),
                            answerBar = $('<div class="answer-percent" data-percent="'+widths[i].width+'"></div>');
                            var userMoreImage;
                            if(res.check_user_pic && res.answers[i].avatar){
                                var userpicsMore = res.answers[widths[i].index].avatar;
                                var userPicsCount = res.check_user_pic_count;
                                var addedMoreImage = "<div class='ays-users-profile-pics'><img src="+res.check_user_pic_url+" width='24' height='24' class='ays-user-image-more' data-answer-id="+res.answers[widths[i].index].id+"></div>";                                
                                if(userpicsMore.length != 0){
                                    userpicsMore = userpicsMore.splice(0 , userPicsCount);
                                    userpicsMore.push(addedMoreImage);
                                }
                                userMoreImage = $('<div class="ays-user-count">'+userpicsMore.join(' ')+'</div>');
                            }

                            if (resultColorsRgba) {
                                answerBar.attr('style', 'background-color:'+hexToRgba(percentColor, widths[i].width/100)+'  !important; border: 1px solid ' + percentColor +' !important;');
                            }
                            else{
                                answerBar.attr('style', 'background-color:'+percentColor);
                            }

                            answerBar.css({
                                width: '1%'
                            });

                            var answerText = '';
                            var pollShowAnswerImage = false;
                            switch (type) {
                                case 'choose':
                                    pollShowAnswerImage = (res.styles.poll_enable_answer_image_after_voting == "on") ? true : false;
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

                                    answerText = $('<span class="answer-text '+rightAnswerCheck+'"></span>');
                                    var htmlstr = stripSlashes(answer[widths[i].index].answer);

                                    answerText.html(htmlstr);
                                    break;
                                case 'rate':
                                    switch (res.view_type) {
                                        case 'emoji':
                                            answerText = emojiAnswerCheck[res.answers.length / 2 + 1.5 - widths[i].index];
                                            break;

                                        case 'star':
                                            for (var j = 0; j <= widths[i].index; j++) {
                                                answerText += starAnswerCheck;
                                            }
                                            break;
                                    }
                                    answerText = $('<span class="answer-text">'+answerText+'</span>');
                                    break;
                                case 'vote':
                                    switch (res.view_type) {
                                        case 'hand':
                                            answerText = handAnswerCheck[widths[i].index];
                                            break;

                                        case 'emoji':
                                            answerText = emojiAnswerCheck[2 * widths[i].index + 1];
                                            break;
                                    }
                                    answerText = $('<span class="answer-text">'+answerText+'</span>');
                                    break;
                            }
                            
                            var answerVotes = $('<span class="answer-votes"></span>');
                            if(showvotescounts){
                            answerVotes.text(answer[widths[i].index].votes);
                            }
                            if(res.check_admin_approval){
                                if(type == 'choose'){
                                    answerDiv.append("<span class='ays_grid_answer_span' >"+poll_maker_ajax_public.thank_message+"</span>").appendTo("#"+formId+" .results-apm");
                                    break;
                                }
                            }

                            if(!pollShowAnswerImage){
                                answerDiv.append(answerText).append(answerVotes).appendTo("#"+formId+" .results-apm");
                                $("#"+formId+" .results-apm").append(userMoreImage).append(answerBar);
                            }
                            else{
                                answerMainDiv.appendTo("#"+formId+" .results-apm");
                                answerImageBox.appendTo(answerMainDiv);
                                answerTextAndPercent.appendTo(answerMainDiv);
                                answerDiv.append(answerText).append(answerVotes).appendTo(answerTextAndPercent);

                                if(typeof userMoreImage != "undefined"){
                                    answerTextAndPercent.append(userMoreImage);
                                }
                                
                                answerBar.appendTo(answerTextAndPercent); 
                            }

                            $("#"+formId+" .ays_res_mess").fadeIn();
                            $('.redirectionAfterVote').show();

                        }

                        setTimeout(function() {
                            form.find('.answer-percent').each(function () {
                                var percent = $(this).attr('data-percent');

                                $(this).css({
                                    width: (percent || 1) + '%'
                                });

                                if (showrespercent) {
                                    var aaa = $(this);
                                    setTimeout(function() {
                                        aaa.text(percent > 5 ? percent + '%' : '');
                                    }, 200);
                                }
                            });
                        }, 100);
                    }
                }

                // Remove disable from next button for category polls start
                form.parents('.ays_poll_category-container').find('.ays-poll-next-btn').prop('disabled', false);
                var vvv = form.parents('.ays_poll_category-container').attr("data-var");
                window['showNext' + vvv] = true;
                if (typeof(window['catIndex' + vvv]) != 'undefined') {
                    if (typeof(window['pollsGlobalPool'+vvv]) != 'undefined') {
                        if (window['catIndex' + vvv] == window['pollsGlobalPool' + vvv].length - 1) {
                            form.parents('.ays_poll_category-container').find('.ays-poll-next-btn').prop('disabled', true);
                        }
                    }

                    if (window['catIndex' + vvv] == 0 && form.parents('.ays_poll_category-container').find('.results-apm').length > 0) {
                        form.parents('.ays_poll_category-container').find('.ays-poll-previous-btn').prop('disabled', true);
                    }
                }
                // Remove disable from next button for category polls end

                if (form.attr('data-show-social') == 1) {
                    socialBtnAdd(formId, pollSocialButtons);
                }
                if (form.attr('data-enable-social-links') == 1) {
                    socialLinksAdd(formId, pollSocialLinks);
                }
                if (voteURLRedirection == 1) {
                    var url = form.attr('data-url-href');
                    var answerRedirectDelay = +form.attr('data-delay');
                    form.append(redirectMessage);
                    if (url !== '') {
                        setTimeout(function() {
                            location.href = url;
                        } , answerRedirectDelay * 1000);
                    }else{
                        $('.redirectionAfterVote').hide();
                    }                    
                }else{
                    voteURLRedirection = false;
                }
                if (voteRedirection == 1 && voteURLRedirection == false) {
                    var url = form.attr('data-href');
                    var delay = +form.attr('data-delay');
                    form.append(redirectMessage);
                    setTimeout(function() {
                        location.href = url;
                    }, delay * 1000);
                }
                if (isRestart == 'true') {
                    showRestart(formId);
                }

                if(res.check_user_pic){
                    var checkModal = $(document).find(".ays-poll-avatars-modal-main");
                    if(checkModal.length < 1){
                    var avatarsModal = "<div class='ays-poll-avatars-modal-main'>" +
                                            "<div class='ays-poll-avatars-modal-content'>" +
                                                "<div class='ays-poll-avatars-preloader'>" +
                                                    "<img class='ays-poll-avatar-pic-loader' src="+res.check_user_pic_loader+">" +
                                                "</div>" +
                                                "<div class='ays-poll-avatars-modal-header'>" +
                                                    "<span class='ays-close' id='ays-poll-close-avatars-modal'>&times;</span>" +
                                                    "<span style='font-weight: bold;'></span>" +
                                                "</div>" +
                                                "<div class='ays-poll-modal-body' id='ays-poll-avatars-body'></div>" +
                                            "</div>" +
                                        "</div>";
                    $(document.body).append(avatarsModal);
                    }
                }
            },
            error: function () {
                loadEffect(formId, false , loadEffectFontSize,loadEffectMessage);
                $(".user-form-"+formId).fadeOut();
                form.parent().next().prop('disabled', false);
                $('.answer-' + formId).parent().parent().find('.apm-button-box').remove();
                $('.answer-' + formId).remove();
                btn.remove();
                $("#"+formId+" .ays_question").text("Something went wrong. Please reload page.");
            }
        });

    }

    function showRestart(formId) {
        var restartBtn = $('<div class="apm-button-box"><input type="button" class="btn ays-poll-btn btn-restart" onclick="location.reload()" value="'  + poll_maker_ajax_public.restart + '"></div>');
        $("#"+formId).append(restartBtn);
    }

    if ($(document).find('#ays_res_without_see').length > 0) {
        
        let btn = $(document).find('#ays_res_without_see'),
            type = $(document).find('#ays_res_without_see').attr('data-polltype');
            
        voting( btn, type );
    }  

    $(document).on('click', '.ays-poll-btn.choosing-btn', function () {
        voting( $(this), 'choose' );
    });
    $(document).on('click', '.ays-poll-btn.rating-btn', function () {
        voting( $(this), 'rate' );
    });
    $(document).on('click', '.ays-poll-btn.voting-btn', function () {
        voting( $(this), 'vote' );
    });
    $(document).on('click', '.ays-poll-btn.text-btn', function () {
        voting( $(this), 'text' );
    });

    $(document).on('change', '.apm-answers-without-submit input', function () {
        if ($(this).parent().hasClass('apm-rating')) {
            voting($(this).parents('.box-apm').find('.apm-button-box input.ays_finish_poll'), 'rate');
        } else if ($(this).parent().hasClass('apm-voting')) {
            voting($(this).parents('.box-apm').find('.apm-button-box input.ays_finish_poll'), 'vote');
        } else if ($(this).parent().hasClass('apm-choosing')) {
            voting($(this).parents('.box-apm').find('.apm-button-box input.ays_finish_poll'), 'choose');
        }
    })

    function delayCountDown(sec) {
        delaySec = parseInt(sec);
        var intervalSec = setInterval(function() {
            if (delaySec > 0) {
                delaySec--;
                $('.ays-poll-main').find('p.redirectionAfterVote span').text(delaySec);
            } else {
                clearInterval(intervalSec);
            }
        }, 1000);
    }

    function resetPlaying(audelems) {
        audelems.pause();
        audelems.currentTime = 0;
    }

    function validatePhoneNumber(input) {
        var phoneno = /^[+ 0-9-]+$/;
        if (typeof input !== 'undefined') {
            if (input.value.match(phoneno)) {
                return true;
            } else {
                return false;
            }

        }
    }

    /**
     * @return {string}
     */
    function GetFullDateTime(){
        var now = new Date();
        return [[now.getFullYear(), AddZero(now.getMonth() + 1), AddZero(now.getDate())].join("-"), [AddZero(now.getHours()), AddZero(now.getMinutes()), AddZero(now.getSeconds())].join(":")].join(" ");
    }

    /**
     * @return {string}
     */
    function AddZero(num) {
        return (num >= 0 && num < 10) ? "0" + num : num + "";
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

        function ays_poll_restriction_string( type, x, length ){
        var tval = '';
        if( x.length > 0 && x != null){
            tval = x.trim();
        }
        switch ( type ) {
            case 'characters':
                break;
            case 'words':
                if(tval.length > 0 && tval != null && tval.length != ''){
                    var wordsLength = tval.match(/\S+/g).length;
                    if (wordsLength > length) {
                        var trimmed = tval.split(/\s+/, length).join(" ");
                        x = trimmed + '...';
                    }
                }
                break;
            default:
                break;
        }
        return x;
    }

    // Avatars modal start

    // Open users avatars modal
    $(document).on('click', '.ays-user-image-more', function(e){
        $(document).find('div.ays-poll-avatars-preloader').css('display', 'flex');
        $(document).find('.ays-poll-avatars-modal-main').aysModal('show');
        var $this = $(this);
        var answer_id = $(this).data('answerId');
        var action = 'ays_poll_get_current_answer_users_pics';
        data = {};
        data.action = action;
        data.answer_id = answer_id;
        $.ajax({
            url: poll_maker_ajax_public.ajax_url,
            dataType: 'json',
            method:'post',
            data: data,
            success: function(response){
                    for(var avatars of response){
                        $('div#ays-poll-avatars-body').append(avatars);

                    }
                    var answerTitle = $this.parents(".ays-user-count").prev().find(".answer-text").html();
                    $(document).find('div.ays-poll-avatars-preloader').css('display', 'none');
                    $(document).find('div.ays-poll-avatars-modal-header span:nth-child(2)').append(answerTitle);
            }
        });
    });

    // Close users avatars modal
    $(document).on('click', '.ays-close', function () {
        $(document).find('.ays-poll-avatars-modal-main').aysModal('hide');
        setTimeout(function(){
            $(document).find('div#ays-poll-avatars-body').html('');
            $(document).find('div.ays-poll-avatars-modal-header span:nth-child(2)').html('');
        }, 250);
    });

    // Cldoe users avatars modal with ESC button
    $(document).on("keydown", function(e){
        if(e.keyCode === 27){
            $(document).find('.ays-close').trigger('click');
            return false;
        }
    });

    if(typeof idChecker !== 'undefined'){
        var checkResShow = $(document).find("#"+idChecker);
        if(checkResShow.data("loadMethod")){
            var checkModal = $(document).find(".ays-poll-avatars-modal-main");
            if(checkModal.length < 1){
                var avatarsModal = "<div class='ays-poll-avatars-modal-main'>" +
                                        "<div class='ays-poll-avatars-modal-content'>" +
                                            "<div class='ays-poll-avatars-preloader'>" +
                                            "<img class='ays-poll-avatar-pic-loader' src="+resLoader+">" +
                                            "</div>" +
                                            "<div class='ays-poll-avatars-modal-header'>" +
                                                "<span class='ays-close' id='ays-poll-close-avatars-modal'>&times;</span>" +
                                                "<span style='font-weight: bold;'></span>" +
                                            "</div>" +
                                            "<div class='ays-poll-modal-body' id='ays-poll-avatars-body'></div>" +
                                        "</div>" +
                                    "</div>";
                $(document.body).append(avatarsModal);
            }
        }
    }
    // Avatars modal end

})(jQuery);