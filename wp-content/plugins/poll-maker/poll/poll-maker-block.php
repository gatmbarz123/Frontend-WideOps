<?php
    /**
     * Enqueue front end and editor JavaScript
     */
    function ays_poll_gutenberg_scripts() {
        global $current_screen;
        global $wp_version;    
        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.3.12';
        $versionCompare = aysPollMakerVersionCompare($version1, $operator, $version2);

        if( ! $current_screen ){
            return null;
        }

        if( ! $current_screen->is_block_editor ){
            return null;
        }
        
        wp_enqueue_script("jquery-effects-core");
        wp_enqueue_script('ays_block_select2js', POLL_MAKER_AYS_ADMIN_URL . '/js/select2.min.js', array('jquery'), '4.0.6', true);
        wp_enqueue_script(POLL_MAKER_AYS_NAME . '-autosize', POLL_MAKER_AYS_PUBLIC_URL . '/js/poll-maker-autosize.js', array( 'jquery' ), POLL_MAKER_AYS_VERSION, false );
        wp_enqueue_script(POLL_MAKER_AYS_NAME, POLL_MAKER_AYS_PUBLIC_URL . '/js/poll-maker-ays-public.js', array('jquery'), POLL_MAKER_AYS_VERSION, false);
        wp_localize_script(POLL_MAKER_AYS_NAME . '-ajax-public', 'poll_maker_ajax_public', array('ajax_url' => admin_url('admin-ajax.php')));

        // Enqueue the bundled block JS file
        if( $versionCompare ){
            wp_enqueue_script(
                'poll-maker-block-js',
                POLL_MAKER_AYS_BASE_URL ."/poll/poll-maker-block-new.js",
                array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
                POLL_MAKER_AYS_VERSION, true
            );
        }
        else{
            wp_enqueue_script(
                'poll-maker-block-js',
                POLL_MAKER_AYS_BASE_URL ."/poll/poll-maker-block.js",
                array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
                POLL_MAKER_AYS_VERSION, true
            );
        }
        wp_localize_script('ays-poll-gutenberg-block-js', 'ays_poll_block_ajax', array('aysDoShortCode' => admin_url('admin-ajax.php')));

        wp_enqueue_style( POLL_MAKER_AYS_NAME . '-font-awesome', POLL_MAKER_AYS_ADMIN_URL . '/css/poll-maker-font-awesome-all.css', array(), POLL_MAKER_AYS_VERSION, 'all');
        wp_enqueue_style('ays-block-animate', POLL_MAKER_AYS_ADMIN_URL . '/css/animate.min.css', array(), '2.0.6', 'all');
        wp_enqueue_style('ays-block-select2', POLL_MAKER_AYS_ADMIN_URL . '/css/select2.min.css', array(), '4.0.6', 'all');
        wp_enqueue_style(POLL_MAKER_AYS_NAME, POLL_MAKER_AYS_PUBLIC_URL . '/css/poll-maker-ays-public.css', array(), POLL_MAKER_AYS_VERSION, 'all');

        // Enqueue the bundled block CSS file
         if( $versionCompare ){            
            wp_enqueue_style(
                'poll-maker-block-css',
                POLL_MAKER_AYS_BASE_URL ."/poll/poll-maker-block-new.css",
                array(),
                POLL_MAKER_AYS_VERSION, 'all'
            );
        }
        else{            
            wp_enqueue_style(
                'poll-maker-block-css',
                POLL_MAKER_AYS_BASE_URL ."/poll/poll-maker-block.css",
                array(),
                POLL_MAKER_AYS_VERSION, 'all'
            );
        }
    }

    function ays_poll_gutenberg_block_register() {

        global $wpdb;
        $block_name = 'poll';
        $block_namespace = 'poll-maker/' . $block_name;   

        $sql = "SELECT id, title FROM ". $wpdb->prefix . "ayspoll_polls ORDER BY id DESC";
        $results = $wpdb->get_results($sql, "ARRAY_A");

        register_block_type(
            $block_namespace,
            array(
                'render_callback' => 'pollmaker_render_callback',
                'editor_script' => 'poll-maker-block-js', // The block script slug
                'style' => 'poll-maker-block-css',
                'attributes' => array(
                    'idner' => $results,
                    'metaFieldValue' => array(
                        'type' => 'integer',
                    ),
                    'shortcode' => array(
                        'type' => 'string',
                    ),
                    'className' => array(
                        'type'  => 'string',                
                    ),
                    'openPopupId' => array(
                        'type'  => 'string',
                    ),
                ),
            )
        );
    }

    function pollmaker_render_callback($attributes) {
        $ays_html = "<div class='ays-poll-render-callback-box'></div>";
        if(isset($attributes["metaFieldValue"]) && $attributes["metaFieldValue"] === 0) {
            return $ays_html;
        }

        if(isset($attributes["shortcode"]) && $attributes["shortcode"] != '') {
            $ays_html = do_shortcode( $attributes["shortcode"] );
        }
        return $ays_html;
    }

    function aysPollMakerVersionCompare($version1, $operator, $version2) {
        
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
        
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
        
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
        
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }

    if (function_exists("register_block_type")) {
        global $wp_version;

        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.2';
        $versionCompare = aysPollMakerVersionCompare($version1, $operator, $version2);

        if ( $versionCompare ) {
            // Hook scripts function into block editor hook
            add_action('enqueue_block_editor_assets', 'ays_poll_gutenberg_scripts');
            add_action('init', 'ays_poll_gutenberg_block_register');        
        }
    }