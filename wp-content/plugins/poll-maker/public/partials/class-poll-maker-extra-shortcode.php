<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Poll_Maker_Ays
 * @subpackage Poll_Maker_Ays/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Poll_Maker_Ays
 * @subpackage Poll_Maker_Ays/public
 * @author     Poll Maker Team <info@ays-pro.com>
 */
class Ays_Poll_Maker_Extra_Shortcodes_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $html_class_prefix = 'ays-poll-extra-shortcodes-';
    private $html_name_prefix = 'ays-poll-';
    private $name_prefix = 'ays_poll_';
    private $unique_id;
    private $unique_id_in_class;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode('ays_poll_passed_users_count', array($this, 'ays_generate_passed_users_count_method'));
        add_shortcode('ays_poll_user_first_name', array($this, 'ays_generate_user_first_name_method'));
        add_shortcode('ays_poll_user_last_name', array($this, 'ays_generate_user_last_name_method'));
        add_shortcode('ays_poll_user_display_name', array($this, 'ays_generate_user_display_name_method'));
        add_shortcode('ays_poll_creation_date', array($this, 'ays_generate_creation_date_method'));
        add_shortcode('ays_poll_user_email', array($this, 'ays_generate_user_email_method'));
        add_shortcode('ays_poll_user_passed_polls_count', array($this, 'ays_generate_user_passed_polls_count_method'));
        add_shortcode('ays_poll_user_all_passed_polls_count', array($this, 'ays_generate_user_all_passed_polls_count_method'));
        add_shortcode('ays_poll_categories_descriptions', array($this, 'ays_generate_category_description_method'));
        add_shortcode('ays_poll_categories_titles', array($this, 'ays_generate_category_title_method'));
        add_shortcode('ays_poll_current_author', array($this, 'ays_generate_current_poll_author_method'));
        add_shortcode('ays_poll_answers_count', array($this, 'ays_generate_poll_answers_count_method'));
    }

    /*
    ==========================================
        Passed users count | Start
    ==========================================
    */

    public function ays_generate_passed_users_count_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $passed_users_count_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', "poll-maker") . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $passed_users_count_html = $this->ays_poll_passed_users_count_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
    }

    public function ays_poll_passed_users_count_html( $id ){

        $results = array();
        if ( class_exists( 'Poll_Maker_Ays_Public' ) ) {
            $results = Poll_Maker_Ays_Public::get_poll_results_count_by_id( $id );
        }

        $content_html = array();
        if( is_null( $results ) ){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", "poll-maker" ) . "</p>";
            return $content_html;
        }

        $passed_users_count = (isset( $results['res_count'] ) && $results['res_count'] != '') ? sanitize_text_field( $results['res_count'] ) : 0;

        if ( $passed_users_count == 0 ) {
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", "poll-maker" ) . "</p>";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."passed-users-count-box' id='". $this->html_name_prefix ."passed-users-count-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $passed_users_count;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Passed users count | End
    ==========================================
    */

    /*
    ==========================================
        Show users firstname | Start
    ==========================================
    */
    public function ays_generate_user_first_name_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_first_name_html = "";
        if(is_user_logged_in()){
            $user_first_name_html = $this->ays_generate_users_html('first');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_first_name_html);
    }
    /*
    ==========================================
        Show users firstname | End
    ==========================================
    */

    /*
    ==========================================
        Show users lastname | Start
    ==========================================
    */
    public function ays_generate_user_last_name_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_last_name_html = "";
        if(is_user_logged_in()){
            $user_last_name_html = $this->ays_generate_users_html('last');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_last_name_html);
    }
        /*
    ==========================================
        Show users lastname | Start
    ==========================================
    */

    /*
    ==========================================
        Show User Display name | Start
    ==========================================
    */

    public function ays_generate_user_display_name_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_display_name_html = "";
        if(is_user_logged_in()){
            $user_display_name_html = $this->ays_generate_users_html('display');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_display_name_html);
    }

    /*
    ==========================================
        Show User Display name | End
    ==========================================
    */

    /*
    ==========================================
        Poll show creation date | Start
    ==========================================
    */
    public function ays_generate_creation_date_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $poll_creation_date_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', "poll-maker") . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_creation_date_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $poll_creation_date_html = $this->ays_poll_creation_date_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_creation_date_html);
    }
    
    public function ays_poll_creation_date_html( $id ){

        $results = $this->get_curent_poll_creation_date($id);

        $content_html = array();

        if($results === null){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", "poll-maker" ) . "</p>";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."creation-date-box' id='". $this->html_name_prefix ."creation-date-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }
    /*
    ==========================================
        Poll show creation date | End
    ==========================================
    */
    
    /*
    ==========================================
        Show User Display name | Start
    ==========================================
    */

    public function ays_generate_user_email_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_email_html = "";
        if(is_user_logged_in()){
            $user_email_html = $this->ays_generate_users_html('email');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_email_html);
    }

    /*
    ==========================================
        Show User Display name | End
    ==========================================
    */

    public function ays_generate_users_html($arg){

        $results = $this->get_user_profile_data();

        $content_html = array();
        
        if( is_null( $results ) || $results == 0 ){
            $content_html = "";
            return $content_html;
        }

        $user_info = (isset( $results['user_'.$arg.'_name'] ) && $results['user_'.$arg.'_name']  != "") ? sanitize_text_field( $results['user_'.$arg.'_name'] ) : '';

        $content_html[] = "<span class='ays-poll-user-".$arg."-name' id='ays-poll-user-".$arg."-name-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $user_info;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

	public function get_user_profile_data(){

        $user_first_name = '';
        $user_last_name  = '';
        $user_nickname   = '';

        $user_id = get_current_user_id();
        if($user_id != 0){
            $usermeta = get_user_meta( $user_id );
            if($usermeta !== null){
                $user_first_name = (isset($usermeta['first_name'][0]) && sanitize_text_field( $usermeta['first_name'][0] != '') ) ? sanitize_text_field( $usermeta['first_name'][0] ) : '';
                $user_last_name  = (isset($usermeta['last_name'][0]) && sanitize_text_field( $usermeta['last_name'][0] != '') ) ? sanitize_text_field( $usermeta['last_name'][0] ) : '';
                $user_nickname   = (isset($usermeta['nickname'][0]) && sanitize_text_field( $usermeta['nickname'][0] != '') ) ? sanitize_text_field( $usermeta['nickname'][0] ) : '';
            }

            $current_user_data = get_userdata( $user_id );
            if ( ! is_null( $current_user_data ) && $current_user_data ) {
                $user_display_name = ( isset( $current_user_data->data->display_name ) && $current_user_data->data->display_name != '' ) ? sanitize_text_field( $current_user_data->data->display_name ) : "";
                $user_email = ( isset( $current_user_data->data->user_email ) && $current_user_data->data->user_email != '' ) ? sanitize_text_field( $current_user_data->data->user_email ) : "";
            }
        }

        $message_data = array(
            'user_first_name'   => $user_first_name,
            'user_last_name'    => $user_last_name,
            'user_nickname'     => $user_nickname,
            'user_display_name' => $user_display_name,
            'user_email_name'        => $user_email,
        );
		
        return $message_data;
    }

    public function get_curent_poll_creation_date( $id ){
        global $wpdb;

        $polls_table = esc_sql( $wpdb->prefix . "ayspoll_polls" );

        if (is_null($id) || $id == 0 ) {
            return null;
        }

        $id = absint( $id );

        $sql = "SELECT `styles` FROM `{$polls_table}` WHERE `id` = {$id}";
        $results = $wpdb->get_var($sql);

        $styles = ( isset( $results ) &&  $results != '' ) ? json_decode( $results, true ) : '';

        if ( is_null( $results ) || $results == "" ) {
            $results = null;
        }

        $creation_date = (isset( $styles['create_date'] ) && $styles['create_date'] != '') ? date_i18n( get_option( 'date_format' ), strtotime( $styles['create_date'] ) ) : '';
        
        return sanitize_text_field( $creation_date );
    }

        /*
    ==========================================
        Passed polls count per user | Start
    ==========================================
    */
    public function get_user_passed_polls_count( $user_id ){
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

    public function ays_generate_user_passed_polls_count_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $passed_polls_count_html = "";
        if(is_user_logged_in()){
            $passed_polls_count_html = $this->ays_generate_user_passed_polls_count_html();
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_polls_count_html);
    }

    public function ays_generate_user_passed_polls_count_html(){
        $user_id = get_current_user_id();

        $results = $this->get_user_passed_polls_count( $user_id );

        $content_html = array();

        if( is_null( $results ) || $results == 0 ){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."passed-polls-count-per-user' id='". $this->html_name_prefix ."passed-polls-count-per-user-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Passed polls count per user | End
    ==========================================
    */

        /*
    ==========================================
        All passed polls count per user | Start
    ==========================================
    */
    public function get_user_all_passed_polls_count( $user_id ){
        global $wpdb;

        $reports_table = esc_sql( $wpdb->prefix . "ayspoll_reports" );
        $answ_table    = esc_sql($wpdb->prefix  . "ayspoll_answers");
		$polls_table   = esc_sql($wpdb->prefix  . "ayspoll_polls");
        
        if (is_null($user_id) || $user_id == 0 ) {
            return null;
        }

        $user_id = absint( $user_id );

        $sql = "SELECT SUM(a.count) FROM ( SELECT COUNT(*) AS count FROM {$reports_table} AS r
        JOIN {$answ_table} AS a 
        ON a.id = r.answer_id 
        JOIN {$polls_table} AS p 
        ON a.poll_id = p.id
        WHERE r.user_id = {$user_id} ) AS a";

        $results = $wpdb->get_var($sql);

        if ( ! empty( $results ) ) {
            $results = absint( $results );
        } else {
            $results = 0;
        }

        return $results;
    }

    public function ays_generate_user_all_passed_polls_count_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $all_passed_polls_count_html = "";
        if(is_user_logged_in()){
            $all_passed_polls_count_html = $this->ays_generate_user_all_passed_polls_count_html();
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $all_passed_polls_count_html);
    }

    public function ays_generate_user_all_passed_polls_count_html(){
        $user_id = get_current_user_id();

        $results = $this->get_user_all_passed_polls_count( $user_id );

        $content_html = array();
        
        if( is_null( $results ) || $results == 0 ){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."all-passed-polls-count-per-user' id='". $this->html_name_prefix ."all-passed-polls-count-per-user-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        All passed polls count per user | End
    ==========================================
    */

    public static function get_poll_by_id($id){
        global $wpdb;

        $sql = "SELECT *
                FROM {$wpdb->prefix}ayspoll_polls
                WHERE id=" . absint($id);

        $poll = $wpdb->get_row($sql, 'ARRAY_A');

        return $poll;
    }

        /*
    ==========================================
        Show poll category description | Start
    ==========================================
    */

    public function ays_generate_category_description_method( $attr ) {
        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $poll_category_description = "";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_category_description);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $poll_category_description = $this->ays_generate_category_description_html( $id );

        return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_category_description);
    }

    public function get_poll_category_by_id( $id ) {
		global $wpdb;
		$cat_table = esc_sql($wpdb->prefix."ayspoll_categories");
		$sql = "SELECT * FROM ".$cat_table." WHERE id IN(".implode( ',', $id).")";
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

    public function ays_generate_category_description_html( $id ) {
        $poll_data = self::get_poll_by_id($id);
        
        if( is_null( $poll_data ) || empty( $poll_data ) ){
            $content_html = "";
            return $content_html;
        }
        
        $poll_category_id = (isset($poll_data['categories']) && $poll_data['categories'] != '') ? explode( ',', $poll_data['categories'])  : "";

        if ( $poll_category_id == "") {
            $content_html = "";
            return $content_html;
        }
        
        $results = self::get_poll_category_by_id(array_filter( $poll_category_id ));
        $content_html = array();
        
        if( is_null( $results ) || empty( $results ) ){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<div class='". $this->html_name_prefix ."category-description' id='". $this->html_name_prefix ."category-description-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
        foreach ($results as $key => $result) {
            $category_description = (isset($result['description']) && $result['description'] != '') ? Poll_Maker_Data::ays_poll_autoembed($result['description']) : "";

            if ( $category_description == "" ) {
                $content_html = "";
                return $content_html;
            }

                $content_html[] = $category_description;
        }
        $content_html[] = "</div>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Show poll category description | End
    ==========================================
    */
        /*
    ==========================================
        Show poll category title | Start
    ==========================================
    */

    public function ays_generate_category_title_method( $attr ) {
        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $poll_category_title = "";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_category_title);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $poll_category_title = $this->ays_generate_category_title_html( $id );

        return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_category_title);
    }

    public function ays_generate_category_title_html( $id ) {
        $poll_data = self::get_poll_by_id($id);
        
        if( is_null( $poll_data ) || empty( $poll_data ) ){
            $content_html = "";
            return $content_html;
        }
        
        $poll_category_id = (isset($poll_data['categories']) && $poll_data['categories'] != '') ? explode( ',', $poll_data['categories'])  : "";

        if ( $poll_category_id == "") {
            $content_html = "";
            return $content_html;
        }
        
        $results = self::get_poll_category_by_id(array_filter( $poll_category_id ));
        $content_html = array();
        
        if( is_null( $results ) || empty( $results ) ){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<div class='". $this->html_name_prefix ."category-title' id='". $this->html_name_prefix ."category-title-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
        foreach ($results as $key => $result) {
            $category_title = (isset($result['title']) && $result['title'] != '') ? Poll_Maker_Data::ays_poll_autoembed($result['title']) : "";

            if ( $category_title == "" ) {
                $content_html = "";
                return $content_html;
            }

                $content_html[] = $category_title;
        }
        $content_html[] = "</div>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Show poll category title | End
    ==========================================
    */

    /*
    ==========================================
        Show current poll author | Start
    ==========================================
    */

    public function ays_generate_current_poll_author_method( $attr ) {

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $poll_author = "";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_author);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $poll_author = $this->ays_generate_current_poll_author_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_author);
    }

    public function ays_generate_current_poll_author_html( $id ) {

        global $wpdb;

        $polls_table = esc_sql( $wpdb->prefix . "ayspoll_polls" );

        if (is_null($id) || $id == 0 ) {
            return null;
        }

        $id = absint( $id );

        $sql = "SELECT `styles` FROM `{$polls_table}` WHERE `id` = {$id}";
        $results = $wpdb->get_var($sql);

        $content_html = array();
        
        if( is_null( $results ) || empty( $results ) ){
            $content_html = "";
            return $content_html;
        }

        $options = ( json_decode($results, true) != null ) ? json_decode($results, true) : array();

        if(isset($options['author'])){
            if(is_array($options['author'])){
                $author = $options['author'];
            }else{
                $author = json_decode($options['author'], true);
            }
        }else{
            $author = array("name"=>"Unknown");
        }

        if(isset($author['name']) && $author['name'] == "Unknown"){
            $author['name'] = __( "Unknown", "poll-maker" );
        }

        $poll_author = (isset($author['name']) && $author['name'] != '') ? sanitize_text_field( $author['name'] ) : "";

        $content_html[] = "<span class='". $this->html_name_prefix ."current-poll-author' id='". $this->html_name_prefix ."current-poll-author-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $poll_author;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Show current poll author | End
    ==========================================
    */

    /*
    ==========================================
        Show poll answers count | Start
    ==========================================
    */

    public function ays_generate_poll_answers_count_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $poll_answers_count = "";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_answers_count);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $poll_answers_count = $this->ays_generate_poll_answers_count_html( $id );

        return str_replace(array("\r\n", "\n", "\r"), "\n", $poll_answers_count);
    }

    public function ays_generate_poll_answers_count_html($id) {
        global $wpdb;
        $answers_table = esc_sql($wpdb->prefix."ayspoll_answers");
        $sql = "SELECT COUNT($id)
                FROM {$answers_table}
                WHERE poll_id=" . esc_sql( absint( $id ) );

        $answers_str = $wpdb->get_var( $sql );
        $count = intval( $answers_str );

        $content_html = array();
        
        if( is_null( $count ) || $count == 0 ){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."poll-answers-count' id='". $this->html_name_prefix ."poll-answers-count". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $count;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

     /*
    ==========================================
        Show poll answers count | End
    ==========================================
    */
}
