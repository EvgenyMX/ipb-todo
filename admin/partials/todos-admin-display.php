<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://me.ru
 * @since      1.0.0
 *
 * @package    Todos
 * @subpackage Todos/admin/partials
 */


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<button class="sync-todo-list">Синхронизовать список</button>

<?php


$todo = new Todos_Admin('', '');


echo '<form action="/wp-admin/admin.php?page=todo" method="GET">';
?>

<p class="search-box">
    <input type="hidden" name="page" value="todo">
	<label class="screen-reader-text" for="todo-seach">Поиск:</label>
	<input type="search" id="todo-seach" name="s" value="<?php _admin_search_query(); ?>" />
    <?php submit_button( 'Поиск', '', '', false, array( 'id' => 'search-submit' ) ); ?>
</p>
<?php


$GLOBALS['ToDo_List_Table']->display();
echo '</form>';
