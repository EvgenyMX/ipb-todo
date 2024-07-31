<?php

class Todos_Activator {


	public static $db_name = 'todos';
	public static $db_vers = '0.0.1';

	public static function activate() {
		self::create_db();
	}

	private static function create_db() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    	global $wpdb;

		$table_name = $wpdb->prefix . self::$db_name;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			todo_id bigint(20) NOT NULL AUTO_INCREMENT,
			todo_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			todo_user_id bigint(20),
			todo_title varchar(255),
			todo_completed varchar(255) DEFAULT '0',
			PRIMARY KEY (todo_id)
		) $charset_collate;";
		dbDelta( $sql );

		update_option('todos_db_version', self::$db_vers );

	}



}
