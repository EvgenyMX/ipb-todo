<?php

class Todos_Deactivator {

	public static function deactivate() {

		global $wpdb;
		$table_name = $wpdb->prefix . 'togos';
		$wpdb->query("DROP TABLE IF EXISTS `$table_name`");
		delete_option('todos_db_version');

	}

}
