<?php
ob_start();

class Pma_Results_List_Table extends WP_List_Table {
	private $plugin_name;
	private $results_obj;
	private $title_length;

	/** Class constructor */
	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
		$this->title_length = Poll_Maker_Ays_Admin::get_listtables_title_length('results');
		parent::__construct(array(
			'singular' => __('Result', "poll-maker"), //singular name of the listed records
			'plural'   => __('Results', "poll-maker"), //plural name of the listed records
			'ajax'     => false, //does this table support ajax?
		));
		add_action('admin_notices', array($this, 'results_notices'));

	}

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_reports( $per_page = 50, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT
        {$wpdb->prefix}ayspoll_polls.id,
		SUM({$wpdb->prefix}ayspoll_answers.votes) AS voted,
		{$wpdb->prefix}ayspoll_answers.poll_id,
        {$wpdb->prefix}ayspoll_polls.categories
        FROM
        {$wpdb->prefix}ayspoll_answers 
        INNER JOIN 
		{$wpdb->prefix}ayspoll_polls
        ON {$wpdb->prefix}ayspoll_answers.poll_id = {$wpdb->prefix}ayspoll_polls.id ";

		if (isset($_REQUEST['orderbypoll']) && $_REQUEST['orderbypoll'] > 0) {
			$poll_id = absint(sanitize_text_field( $_REQUEST['orderbypoll'] ));

			$sql .= " AND {$wpdb->prefix}ayspoll_reports.answer_id IN (SELECT {$wpdb->prefix}ayspoll_answers.id FROM {$wpdb->prefix}ayspoll_answers WHERE {$wpdb->prefix}ayspoll_answers.poll_id='$poll_id')";
		}

		if (isset($_REQUEST['orderbycat']) && $_REQUEST['orderbycat'] > 0) {
			$cat_id 		= absint(sanitize_text_field( $_REQUEST['orderbycat'] ));
			$sql .= " AND {$wpdb->prefix}ayspoll_polls.categories LIKE('%,{$cat_id},%')";
		}

		$sql .= "GROUP BY {$wpdb->prefix}ayspoll_answers.poll_id";
		if (!empty($_REQUEST['orderby'])) {
			$order_by = ( isset( $_REQUEST['orderby'] ) && sanitize_text_field( $_REQUEST['orderby'] ) != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'id';
			$order_by .= ( ! empty( $_REQUEST['order'] ) && strtolower( $_REQUEST['order'] ) == 'asc' ) ? ' ASC' : ' DESC';

			$sql_orderby = sanitize_sql_orderby($order_by);

            if ( $sql_orderby ) {
                $sql .= ' ORDER BY ' . $sql_orderby;
            } else {
                $sql .= ' ORDER BY id DESC';
            }
		} else {
			$sql .= ' ORDER BY id DESC';
		}

		$sql .= " LIMIT %d";
		$args[] = $per_page;
		$offset = ($page_number - 1) * $per_page;
		$sql .= " OFFSET %d";
		$args[] = $offset;

		$result = $wpdb->get_results(
		  	$wpdb->prepare( $sql, $args),
		  	'ARRAY_A'
		);

		return $result;
	}

	public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            
            <div class="alignleft actions">
                <?php  $this->bulk_actions( $which ); ?>
            </div>
            <?php
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
	}

	public function extra_tablenav($which) {
		$this->ays_category_filter_content();
	}

	public function get_report_by_id( $id ) {
		global $wpdb;
		$report_id = absint(sanitize_text_field($id));
		$report_table = esc_sql($wpdb->prefix."ayspoll_reports");
		$sql  = "SELECT * FROM ".$report_table." WHERE id=%d";
		$result = $wpdb->get_row(
		  	$wpdb->prepare( $sql, $report_id),
		  	'ARRAY_A'
		);

		return $result;
	}

	public static function get_answer_by_id( $id ) {
		global $wpdb;

		$answ_id 	= absint(sanitize_text_field($id));
		$answ_table = esc_sql($wpdb->prefix."ayspoll_answers");

		$sql = "SELECT * FROM ".$answ_table." WHERE id=%d";

		$result = $wpdb->get_row(
		  	$wpdb->prepare( $sql, $answ_id),
		  	'ARRAY_A'
		);

		return $result;
	}

	public function get_polls() {
		global $wpdb;

		$poll_table = esc_sql($wpdb->prefix."ayspoll_polls");
		$sql = "SELECT * FROM ".$poll_table;

		$result = $wpdb->get_results($sql, 'ARRAY_A');

		return $result;
	}

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_reports( $id ) {

		global $wpdb;

		$wpdb->update(
			"{$wpdb->prefix}ayspoll_answers",
			array(
				"votes" => 0
			),
			array(
				'poll_id' => $id
			)
		);
	
		$sql = "DELETE r FROM {$wpdb->prefix}ayspoll_reports as r
            JOIN {$wpdb->prefix}ayspoll_answers ON {$wpdb->prefix}ayspoll_answers.id = r.answer_id
            WHERE {$wpdb->prefix}ayspoll_answers.poll_id = $id";
		$res = $wpdb->query($sql);

		return $res > 0;
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}ayspoll_polls";
		return $wpdb->get_var($sql);
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e('There are no results yet.', "poll-maker");
	}

	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'poll_title':
			case 'voted':
			case 'unread':
			case 'id':
				return $item[$column_name];
				break;
			default:
				return print_r($item, true); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-action[]" value="%s">', $item['id']
		);
	}

	function column_poll_title( $item ) {
		global $wpdb;
		$delete_nonce = wp_create_nonce($this->plugin_name . '-delete-result');

		$res = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_polls WHERE id={$item['id']}", "ARRAY_A");

		$restitle = Poll_Maker_Ays_Admin::ays_restriction_string("word",stripcslashes($res['title']), $this->title_length);
		$title   = sprintf('<a href="?page=%s-each&poll=%d&title=%s">' . $restitle . '</a>', esc_attr($_REQUEST['page']), absint($item['id']), stripslashes($res['title']));
		$actions = [
			'delete' => sprintf('<a href="?page=%s&action=%s&result=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['id']), $delete_nonce),
		];

		return $title . $this->row_actions($actions);
	}

	function get_poll_title( $item ) {
		global $wpdb;
		$delete_nonce = wp_create_nonce($this->plugin_name . '-delete-result');

		$result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_answers WHERE id={$item['answer_id']}", "ARRAY_A");

		$res = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_polls WHERE id={$result['poll_id']}", "ARRAY_A");

		return stripslashes($res['title']);
	}

	function column_answer( $item ) {
		global $wpdb;
		$delete_nonce = wp_create_nonce($this->plugin_name . '-delete-result');

		$result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_answers WHERE id={$item['answer_id']}", "ARRAY_A");		

		$poll_type = $this->get_poll_type($result['poll_id']);

		$result['answer'] = $poll_type['type'] == 'voting' && $result['answer'] == '1' ? 'Like' :  $result['answer'];
		$result['answer'] = $poll_type['type'] == 'voting' && $result['answer'] == '-1' ? 'Dislike' :  $result['answer'];
		
		return stripslashes($result['answer']);
	}

	function column_voted( $item ) {
		global $wpdb;
		$sql = "SELECT
            COUNT({$wpdb->prefix}ayspoll_reports.id)
            FROM
            {$wpdb->prefix}ayspoll_reports
            JOIN {$wpdb->prefix}ayspoll_answers ON {$wpdb->prefix}ayspoll_answers.id = {$wpdb->prefix}ayspoll_reports.answer_id
            WHERE {$wpdb->prefix}ayspoll_answers.poll_id = {$item['id']}
            GROUP BY {$wpdb->prefix}ayspoll_answers.poll_id";
		$res = $wpdb->get_var($sql);

		return $res ? $res : 0;
	}

	function column_unread( $item ) {
		global $wpdb;
		$sql = "SELECT
            COUNT({$wpdb->prefix}ayspoll_reports.unread)
            FROM
            {$wpdb->prefix}ayspoll_reports
            JOIN {$wpdb->prefix}ayspoll_answers ON {$wpdb->prefix}ayspoll_answers.id = {$wpdb->prefix}ayspoll_reports.answer_id
            WHERE {$wpdb->prefix}ayspoll_answers.poll_id = {$item['id']} AND {$wpdb->prefix}ayspoll_reports.unread = 1
            GROUP BY {$wpdb->prefix}ayspoll_answers.poll_id";
		$res = $wpdb->get_var($sql);
		$result = $res ? $res : 0;
		$unread = $res ? 'ays_poll_unread' : '';
		$count   = sprintf('<span class="%s">' . $result . '</span>', $unread);
		return $count;
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = array(
			'cb'         => '<input type="checkbox" />',
			'id'         => __('ID',"poll-maker"),
			'poll_title' => __('Poll',"poll-maker"),
			'voted'      => __('Voters count',"poll-maker"),
			'unread'     => __('New results count',"poll-maker")
		);

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'vote_date' => array('vote_date', true),
			'id'        => array('id', true),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-read'   => __('Mark as read', "poll-maker"),
			'bulk-delete' => __('Delete', "poll-maker"),
		);

		return $actions;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page = $this->get_items_per_page('poll_results_per_page', 50);

		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args(array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page, //WE have to determine how many items to show on a page
		));

		$this->items = self::get_reports($per_page, $current_page);
	}

	public function process_bulk_action() {	
		//Detect when a bulk action is being triggered...
		
		if ('delete' === $this->current_action()) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr($_REQUEST['_wpnonce']);

			if (!wp_verify_nonce($nonce, $this->plugin_name . '-delete-result')) {
				die('Go get a life script kiddies');
			} else {
				self::delete_reports(absint($_GET['result']));

				// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
				// add_query_arg() return the current url
				$message = 'deleted';
				$url     = esc_url_raw(remove_query_arg([
						'action',
						'result',
						'_wpnonce'
					])) . '&status=' . $message;
				wp_redirect($url);
			}

		}

		// If the delete bulk action is triggered
		if ((isset($_POST['action']) && 'bulk-delete' == $_POST['action'])
		    || (isset($_POST['action2']) && 'bulk-delete' == $_POST['action2'])
		) {
			$delete_ids = esc_sql($_POST['bulk-action']);

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_reports($id);
			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
			// add_query_arg() return the current url

			$message = 'deleted';
			$url     = esc_url_raw(remove_query_arg(['action', 'result', '_wpnonce'])) . '&status=' . $message;
			wp_redirect($url);
		} elseif ((isset($_POST['action']) && 'bulk-read' == $_POST['action'])
		          || (isset($_POST['action2']) && 'bulk-read' == $_POST['action2'])
		) {

			$read_ids = esc_sql($_POST['bulk-action']);

			// loop over the array of record IDs and mark as readed them
			foreach ( $read_ids as $id ) {
				echo $id . "<br>";
				self::mark_as_read_reports($id);
			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
			// add_query_arg() return the current url

			$message = 'read';
			$url     = esc_url_raw(remove_query_arg(['action', 'result', '_wpnonce'])) . '&status=' . $message;
			wp_redirect($url);
		}
	}

	/**
	 * Mark as read a result record.
	 *
	 * @param int $id result ID
	 */
	public static function mark_as_read_reports( $id ) {
		global $wpdb;
		$sql = "UPDATE {$wpdb->prefix}ayspoll_reports
            JOIN {$wpdb->prefix}ayspoll_answers ON {$wpdb->prefix}ayspoll_answers.id = {$wpdb->prefix}ayspoll_reports.answer_id
            SET {$wpdb->prefix}ayspoll_reports.unread = 0
            WHERE {$wpdb->prefix}ayspoll_answers.poll_id = $id";
		$res = $wpdb->query($sql);

		return $res > 0 ? true : false;
	}

	public function results_notices() {
		$status = (isset($_REQUEST['status'])) ? sanitize_text_field($_REQUEST['status']) : '';

		if (empty($status)) {
			return;
		}

		if ('deleted' == $status) {
			$updated_message = esc_html(__('Result deleted.', "poll-maker"));
		}

		if (empty($updated_message)) {
			return;
		}

		?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
		<?php
	}

	public function get_poll_type($item) {
		global $wpdb;

		$id = absint(sanitize_text_field($item));

		$poll_table = esc_sql($wpdb->prefix."ayspoll_polls");
		$sql = "SELECT type FROM ".$poll_table." WHERE id=%d";

		$result = $wpdb->get_row(
		  	$wpdb->prepare( $sql, $id),
		  	'ARRAY_A'
		);

		return $result;
	}

	public function ays_category_filter_content(){
		$poll_cats = $this->get_categories();
		$content = "";		
		if(isset($poll_cats)){
			$content = '<label for="bulk-action-selector-top-cat" class="screen-reader-text">Select Filter Type</label>
						<select name="orderbycat" id="bulk-action-selector-top-cat">
						<option value="0" selected>' .__("Select Category", "poll-maker"). '</option>';
			$selected = "";
			$this_cat_id = 0;
			foreach ($poll_cats as $cat_key => $cat_value) {
				$selected  = (isset($_REQUEST['orderbycat']) && $_REQUEST['orderbycat'] == $cat_value['id'] ) ? 'selected' : '';
				$this_cat_id = isset($_REQUEST['orderbycat']) ? $_REQUEST['orderbycat'] : $this_cat_id;
				$cat_id    = isset($cat_value['id']) && $cat_value['id'] != "" ? esc_attr($cat_value['id']) : "";
				$cat_value = isset($cat_value['title']) && $cat_value['title'] != "" ? esc_attr($cat_value['title']) : "";
				$content .= '<option value="'.$cat_id.'" '.$selected.'>'.$cat_value.'</option>';
			}
			$content .= '</select>';
			$content .= '<input type="submit" id="doactioncat" name="filter_by_cat" class="button action" value="'.__("Filter", "poll-maker").'" style="width: 3.7rem;margin-left: 5px;">';
			if(isset($_REQUEST['filter_by_cat'])){
				$new_url = remove_query_arg("orderbycat")."&orderbycat=".$this_cat_id;
				wp_redirect($new_url);
			}
		}
		echo $content;
	}

	public function get_categories() {
		global $wpdb;
		$category_table = $wpdb->prefix . 'ayspoll_categories';

        $sql = "SELECT id,title FROM ".$category_table." ORDER BY title";
        $results = $wpdb->get_results($sql , "ARRAY_A");

		if(isset($results) && !empty($results)){
			return $results;
		}
		else{
			return array();
		}
	}

	// Get answers modified
	public function get_poll_answers($poll_id){
		global $wpdb;

		$sql = "SELECT * FROM `{$wpdb->prefix}ayspoll_reports` WHERE poll_id=$poll_id";
		$result = $wpdb->get_results($sql, "ARRAY_A");

		$multivote_answers_ids = array();
		$multivote_answers = array();

		foreach ($result as $r_key => $r_value) {

			$r_value['multi_answer_id'] = json_decode($r_value['multi_answer_ids'], true);

			$multivote_res = false;
			if (isset($r_value['multi_answer_id']) && count($r_value['multi_answer_id']) > 0) {
				$multivote_res = true;
			}

			if ($multivote_res) {
				foreach ($r_value['multi_answer_id'] as $m_key => $m_val) {
					$multivote_answers_ids[] = $m_val;
					$multi_answer = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_answers WHERE id=".$m_val, "ARRAY_A");
					$multivote_answers[ $m_val ] = $multi_answer['answer'];
				}
				$answ_poll_id = $multi_answer['poll_id'];
			} else {
				$answer = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ayspoll_answers WHERE id={$r_value['answer_id']}", "ARRAY_A");
				$multivote_answers_ids[] = $r_value['answer_id'];
				$multivote_answers[ $r_value['answer_id'] ] = $answer['answer'];
				$answ_poll_id = $answer['poll_id'];
			}
		}

		$multivote_answers_count_arr = array_count_values( $multivote_answers_ids );
		
		$res = array();
		foreach ($multivote_answers_count_arr as $key => $value) {
			$res[] = array(
				"votes" => $value,
				"answer" => $multivote_answers[ $key ],
			);
		}

		if ( is_null( $res ) || empty( $res ) ) {
			$res = array();
		}

		return $res;
	}

}
