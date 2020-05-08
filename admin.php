<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

$db = new PDO('mysql:host=localhost;dbname=u17334', 'u17334', '4897115', array(
    PDO::ATTR_PERSISTENT => true
));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $db->prepare('DELETE FROM web5 WHERE login = ?');
        $stmt->execute(array(
            $_POST['delete']
        ));

    } catch (PDOException $e) {
        echo 'Ошибка: ' . $e->getMessage();
        exit();
    }
    header('Location: ./admin.php');
}

try {
    $stmt = $db->query('SELECT * FROM web5');
    ?>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Задание 6</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
    <form action="" method="post">
        <table class="table table-striped table-dark table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Год</th>
                <th>Пол</th>
                <th>Кол-во конечностей</th>
                <th>Способности</th>
                <th>Биография</th>
                <th>Удалить</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print('<tr>');
                foreach ($row as $cell) {
                    print('<td>' . $cell . '</td>');
                }
                print('<td><button class="btn btn-danger btn-sm" name="delete" type="submit" value="' . $row['login'] . '">x</button></td>');
                print('</tr>');
            }
            ?>
            </tbody>
        </table>
    </form>
    </body>
    <?php
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
}
