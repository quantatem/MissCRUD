<?php

require 'hotload.php';

$tableSelective = $app['database']->selectAll($app['config']['database']['tablename']);
$tableMessages = $app['database']->selectAll($app['config']['database']['tablename'] . '2'); //facepalm


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $_REQUEST = array_map('trim', $_REQUEST);
    
    if ($app['database']->CheckUserRequirements($_REQUEST)) {
        $_REQUEST['password'] = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
        $app['database']->AddUser($_REQUEST);
        $answer = '<div class="alert alert-success text-center" role="alert">
                    User ' . $_REQUEST['firstname'] . ' ' . $_REQUEST['lastname'] . ' successfully registered!
                   </div>';
    } elseif ($app['database']->userExists($_REQUEST)) {
        $_SESSION["authorized"] = true;
        $_SESSION['user'] = $_REQUEST;
        //$mode = $_REQUEST['mode'] = 'table';
    } else {
        //$_SESSION["authorized"] = false;
        $answer = '<div class="alert alert-danger text-center" role="alert">
                    Ошибка: проверьте правильность заполнения формы!
                   </div>';
    } 
}

require 'core/routes.php';

exit;