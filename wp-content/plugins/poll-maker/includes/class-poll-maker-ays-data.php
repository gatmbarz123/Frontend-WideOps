<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Poll_Maker
 * @subpackage Poll_Maker/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Poll_Maker
 * @subpackage Poll_Maker/includes
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Poll_Maker_Data {

    // Retrieves the attachment ID from the file URL
    public static function ays_poll_get_image_id_by_url( $image_url ) {
        global $wpdb;

        $image_alt_text = "";
        if ( !empty( $image_url ) ) {

            $re = '/-\d+[Xx]\d+\./';
            $subst = '.';

            $image_url = preg_replace($re, $subst, $image_url, 1);

            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
            if ( !is_null( $attachment ) && !empty( $attachment ) ) {

                $image_id = (isset( $attachment[0] ) && $attachment[0] != "") ? absint(  $attachment[0] ) : "";
                if ( $image_id != "" ) {
                    $image_alt_text = self::ays_poll_get_image_alt_text_by_id( $image_id );
                }
            }
        }

        return $image_alt_text; 
    }

    public static function ays_poll_get_image_alt_text_by_id( $image_id ) {

        $image_data = "";
        if ( $image_id != "" ) {

            $result = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
            if ( $result && $result != "" ) {
                $image_data = esc_attr( $result );
            }
        }

        return $image_data; 
    }

    public static function ays_poll_autoembed( $content ) {
        global $wp_embed;
        $content = stripslashes( wpautop( $content ) );
        $content = $wp_embed->autoembed( $content );
        if ( strpos( $content, '[embed]' ) !== false ) {
            $content = $wp_embed->run_shortcode( $content );
        }
        $content = do_shortcode( $content );
        return $content;
    }

    public static function ays_poll_is_elementor(){
        if( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ){
            $is_elementor = true;
        }elseif( isset( $_REQUEST['elementor-preview'] ) && $_REQUEST['elementor-preview'] != '' ){
            $is_elementor = true;
        }else{
            $is_elementor = false;
        }

        if ( ! $is_elementor ) {
            $is_elementor = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'elementor_ajax' ) ? true : false;
        }

        return $is_elementor;
    }

    public static function ays_poll_is_editor(){
        $is_editor = false;
        if( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ){
            if ( isset( $_GET['post'] ) && absint( $_GET['post'] ) > 0 ) {
                $is_editor = true;
            }
        } elseif ( isset( $_GET['context'] ) && ( $_GET['context'] == 'add' || $_GET['context'] == 'edit' ) ) {
            if( isset( $_GET['post_id'] ) & absint( $_GET['post_id'] ) > 0 ){
                $is_editor = true;
            }
        }

        return $is_editor;
    }

    public static function get_user_passed_polls_count( $user_id ){
        global $wpdb;

        if (is_null($user_id) || $user_id == 0 ) {
            return null;
        }

        $user_id = absint( $user_id );

        $reports_table = esc_sql($wpdb->prefix."ayspoll_reports");
        $answ_table    = esc_sql($wpdb->prefix."ayspoll_answers");
        $polls_table   = esc_sql($wpdb->prefix."ayspoll_polls");

        $sql = "SELECT COUNT(*) FROM {$reports_table} AS r
                JOIN {$answ_table} AS a 
                ON a.id = r.answer_id 
                JOIN {$polls_table} AS p 
                ON a.poll_id = p.id
                WHERE r.user_id = ".$user_id;

        $results = $wpdb->get_var($sql);

        if ( ! empty( $results ) ) {
            $results = absint( $results );
        } else {
            $results = 0;
        }

        return $results;
    }

    public static function get_all_polls(){
        global $wpdb;
        $polls_table = $wpdb->prefix."ayspoll_polls";
        $sql = "SELECT id,title FROM ".$polls_table;
        $result = $wpdb->get_results($sql , "ARRAY_A");
        return $result;
    }

    public static function check_user_capability(){
        return current_user_can( 'manage_options' ) && is_user_logged_in();
    }
}