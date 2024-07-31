<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://me.ru
 * @since      1.0.0
 *
 * @package    Todos
 * @subpackage Todos/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Todos
 * @subpackage Todos/admin
 * @author     Mikhalev Evgeniy <mixalev10a@gmail.com>
 */
class Todos_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/todos-admin.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/todos-admin.js', array( 'jquery' ), $this->version, false );
	}


	private function sendCurl( $method = 'GET', $url = '', $data = [], $headers = [] ) {

		$real_header = array_merge([
			'Accept: application/json',
			'Content-Type: application/json'
		], $headers);

		$curl = curl_init();

		curl_setopt_array($curl, array(
        	CURLOPT_URL => $url,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_SSL_VERIFYHOST => false,
        	CURLOPT_SSL_VERIFYPEER => false,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => $method,
        	CURLOPT_POSTFIELDS => $data,
        	CURLOPT_HTTPHEADER => $real_header
		));


		$resp = curl_exec($curl);
		$response = json_decode($resp, true);

		$error_msg = null;
		if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

		$code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

		return [
			'data' => $response,
			'error' => $error_msg,
			'code' => $code,
			'resp' => $resp,
			"url" => $url,
			'headers' => $real_header
		];

	}


	public function requst_get_todos() {
	 	$url = 'https://jsonplaceholder.typicode.com/todos';

		$request = $this->sendCurl( 'GET', $url );

		if ( $request['code'] != 200 ) {
			return false;
		}

		return $request;


	}


	public function sync_todo() {

		$requsetList = $this->requst_get_todos();

		// $last = $this->get_last_todos( 5, false);
		// if ( $last ) {
		// 	$lastNumber = array_pop($requsetList['data']);
		// 	var_dump($lastNumber);
		// 	if ( $last['todo_id'] != $lastNumber['id'] ) {
		// 		$newItems = [];
		// 		foreach ($requsetList as $key => $item) {
		// 			if ( $item['id'] <= $last['todo_id'] ) continue;
		// 			$newItems[] = $item;
		// 		}
		// 		// $this->insert_dotos($newItems);
		// 		var_dump('q');
		// 	} else {
		// 		var_dump('w');
		// 	}

		// } else {
		// 	var_dump('e');

			// $this->insert_dotos($requsetList['data']);
		// }



		echo json_encode( $requsetList );
		wp_die();

	}

	public function insert_dotos( $list ) {
		global $wpdb;

		$table_name = $wpdb->prefix .'todos';


		foreach ($list as $item) {

			$completed = $item['completed'] == true ? 1 : 0;

			$wpdb->insert($table_name, array(
				'todo_user_id' => $item['userId'],
				'todo_title' => $item['title'],
				'todo_completed' => $completed,
			));

		}

	}

	public function get_last_todo() {
		global $wpdb;

		$table_name = $wpdb->prefix .'todos';
		$todo = $wpdb->get_row( "SELECT * FROM $table_name ORDER BY todo_id DESC LIMIT 1", ARRAY_A );

		return $todo;
	}

	public function get_random_todo( $count, $completed ){
		global $wpdb;
		$table_name = $wpdb->prefix .'todos';

		$_completed = $completed ? 1 : 0;
		$random = $wpdb->get_results( "SELECT * FROM
										( SELECT *  FROM $table_name
											WHERE todo_completed = $_completed
											ORDER BY RAND()
											LIMIT $count ) as rand_list ORDER BY todo_id");

		return $random;

	}

	public function get_last_todos( $count, $completed ){
		global $wpdb;
		$table_name = $wpdb->prefix .'todos';

		$_completed = $completed ? 1 : 0;
		$random = $wpdb->get_results( "SELECT * FROM
										( SELECT *  FROM $table_name
											WHERE todo_completed = $_completed
											ORDER BY todo_id DESC
											LIMIT $count ) as rand_list ORDER BY RAND()");


		return $random;

	}

	public function get_todos() {
		global $wpdb;

		$table_name = $wpdb->prefix .'todos';
		$todo = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
		return $todo;
	}


	public function add_todo_page() {

		$hook = add_menu_page(
			'To Do',     // page title
			'To Do',     // menu title
			'manage_options',   // capability
			'todo',     // menu slug
			[$this, 'view_todo_page' ]
		);

		add_action( "load-$hook", [$this, 'example_table_page_load'] );


	}


	public function search( $s, $key ) {
		global $wpdb;

		$table_name = $wpdb->prefix .'todos';
		$todo = $wpdb->get_results( "SELECT * FROM $table_name WHERE $key LIKE '%$s%'  ", ARRAY_A );

		return $todo;
	}


	public function example_table_page_load() {
		require_once __DIR__ . '\class-todos-table.php'; // тут находится класс Example_List_Table...

		$GLOBALS['ToDo_List_Table'] = new ToDo_List_Table();
	}

	public function view_todo_page() {


		$file = plugin_dir_path( __FILE__ ) . "partials/todos-admin-display.php";
		if ( file_exists( 'D:\ospanel\domains\ipb\wp-content\plugins\todos\admin\partials\todos-admin-display.php' ) )
        	require $file;


	}






}
