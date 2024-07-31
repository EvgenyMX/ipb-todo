<?php

if ( $args ) {
    ?>


<style>
    ul.todo-5 {
        display: flex;
        gap: 5px;
        flex-direction: column;
    }

    ul.todo-5 li {
        display: flex;
        gap: 5px;
        flex-direction: row;
    }

    ul.todo-5 li p {
        margin:  0;
    }
</style>
        <ul class="todo-5">
            <?php
                foreach ($args as $key => $item) {
                    ?>

                    <li>
                        <p>ID: <?php echo $item['todo_id'] ?></p>
                        <p>User ID: <?php echo $item['todo_user_id'] ?></p>
                        <p>Title: <?php echo $item['todo_title'] ?></p>
                        <p>Completed: <?php echo $item['todo_completed'] ? 'True' : 'False' ?></p>

                    </li>

                    <?php
                }
            ?>
        </ul>
    <?php
} else {
    echo "Задач нет";
}