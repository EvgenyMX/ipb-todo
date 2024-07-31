<?php

// расширять класс нужно после или во время admin_init
// класс удобнее поместить в отдельный файл.

class ToDo_List_Table extends WP_List_Table {

	function __construct(){
		parent::__construct(array(
			'singular' => 'log',
			'plural'   => 'logs',
			'ajax'     => false,
		));

		$this->bulk_action_handler();

		// screen option
		add_screen_option( 'per_page', array(
			'label'   => 'Показывать на странице',
			'default' => 20,
			'option'  => 'logs_per_page',
		) );

		$this->prepare_items();

		add_action( 'wp_print_scripts', [ __CLASS__, '_list_table_css' ] );
	}

	// создает элементы таблицы
	function prepare_items(){
		global $wpdb;

		// пагинация
		$per_page = get_user_meta( get_current_user_id(), get_current_screen()->get_option( 'per_page', 'option' ), true ) ?: 20;

		// $this->set_pagination_args( array(
		// 	'total_items' => 3,
		// 	'per_page'    => $per_page,
		// ) );
		$cur_page = (int) $this->get_pagenum(); // желательно после set_pagination_args()

		// элементы таблицы
		// обычно элементы получаются из БД запросом
		// $this->items = get_posts();

        $plugin = new Todos_Admin('','');



        if ( isset($_REQUEST['s']) && isset($_REQUEST['page']) && $_REQUEST['page'] == 'todo' ) {
            $text = $_REQUEST['s'];
            $list = $plugin->search($text, 'todo_title');

        } else {

            $list = $plugin->get_todos();
        }


        $this->items = [];


        foreach ($list as $key => $item) {
            $this->items[] = (object) [
                'todo_id'   => $item['todo_id'],
				'todo_user_id'  => $item['todo_user_id'],
				'todo_title' => $item['todo_title'],
				'todo_completed' => $item['todo_completed'],
            ];
        }

	}

	// колонки таблицы
	function get_columns(){
		return array(
			// 'cb'            => '<input type="checkbox" />',
			'todo_id'      => 'ID',
			'todo_user_id' => 'User ID',
			'todo_title' => 'Title',
            'todo_completed' => 'Completed'
			// 'license_key'   => 'License Key',
		);
	}

	// сортируемые колонки
	function get_sortable_columns(){
		return array(
			// 'todo_title' => array( 'todo_title', 'desc' ),
			// 'todo_completed' => array( 'todo_completed', 'desc' ),
		);
	}

	protected function get_bulk_actions() {
		return array(
			// 'delete' => 'Delete',
		);
	}

	// Элементы управления таблицей. Расположены между групповыми действиями и панагией.
	function extra_tablenav( $which ){

		$btn = get_submit_button( "Поиск", "", "", false, array( "id" => "search-submit" ) );
		$s_inp =  '<p class="search-box">
			<input type="hidden" name="page" value="todo">
			<input type="search" id="todo-seach" name="s" value="'._admin_search_query().'" placeholder="Поиск по Title">
			'.$btn.'
			</p>';

		echo $s_inp;

		// echo '<div class="alignleft actions">HTML код полей формы (select). Внутри тега form...</div>';
	}

	// вывод каждой ячейки таблицы -------------

	static function _list_table_css(){
		?>
		<style>
			table.logs .column-id{ width:2em; }
			table.logs .column-license_key{ width:8em; }
			table.logs .column-customer_name{ width:15%; }
		</style>
		<?php
	}

	// вывод каждой ячейки таблицы...
	function column_default( $item, $colname ){

		if( $colname === 'todo_title' ){
			// ссылки действия над элементом
			$actions = array();
			$actions['edit'] = sprintf( '<a href="%s">%s</a>', '#', __('edit','hb-users') );

			return esc_html( $item->todo_title ) . $this->row_actions( $actions );
		}
		else {
			return isset($item->$colname) ? $item->$colname : print_r($item, 1);
		}

	}

	// заполнение колонки cb
	function column_cb( $item ){
		echo '<input type="checkbox" name="licids[]" id="cb-select-'. $item->todo_id .'" value="'. $item->todo_id .'" />';
	}

	// остальные методы, в частности вывод каждой ячейки таблицы...


    public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if ( is_array( $_REQUEST['orderby'] ) ) {
				foreach ( $_REQUEST['orderby'] as $key => $value ) {
					echo '<input type="hidden" name="orderby[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '" />';
				}
			} else {
				echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
			}
		}
		if ( ! empty( $_REQUEST['order'] ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['detached'] ) ) {
			echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
		}
		?>

            <p class="search-box">
                <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
                <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
                    <?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
            </p>
		<?php
    }

	// helpers -------------

	private function bulk_action_handler(){
		if( empty($_POST['licids']) || empty($_POST['_wpnonce']) ) return;

		if ( ! $action = $this->current_action() ) return;

		if( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) )
			wp_die('nonce error');

		// делает что-то...
		die( $action ); // delete
		die( print_r($_POST['licids']) );

	}

	/*
	// Пример создания действий - ссылок в основной ячейки таблицы при наведении на ряд.
	// Однако гораздо удобнее указать их напрямую при выводе ячейки - см ячейку customer_name...

	// основная колонка в которой будут показываться действия с элементом
	protected function get_default_primary_column_name() {
		return 'disp_name';
	}

	// действия над элементом для основной колонки (ссылки)
	protected function handle_row_actions( $post, $column_name, $primary ) {
		if ( $primary !== $column_name ) return ''; // только для одной ячейки

		$actions = array();

		$actions['edit'] = sprintf( '<a href="%s">%s</a>', '#', __('edit','hb-users') );

		return $this->row_actions( $actions );
	}
	*/

}