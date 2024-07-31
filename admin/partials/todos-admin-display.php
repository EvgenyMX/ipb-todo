<?php

$dateSync = get_option('todo-sync-date');

if ( !$dateSync ) {
    $dateSync = '-';
}
?>

<style>
    .lds-ring {
    /* change color here */
    color: #1c4c5b
    }
    .lds-ring,
    .lds-ring div {
    box-sizing: border-box;
    }
    .lds-ring {
    display: inline-block;
    position: relative;
    width: 25px;
    height: 25px;
    }
    .lds-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 24px;
    height: 24px;
    margin: 8px;
    border: 8px solid currentColor;
    border-radius: 50%;
    animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: currentColor transparent transparent transparent;
    }
    .lds-ring div:nth-child(1) {
    animation-delay: -0.45s;
    }
    .lds-ring div:nth-child(2) {
    animation-delay: -0.3s;
    }
    .lds-ring div:nth-child(3) {
    animation-delay: -0.15s;
    }
    @keyframes lds-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    }
</style>
<br>

<h1>Список задач</h1>
<button class="button sync-todo-list">Синхронизовать список</button>
<p>Последний синк: <?php echo $dateSync; ?> </p>


<?php
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
