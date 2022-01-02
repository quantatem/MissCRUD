<?php

require '../hotload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_SESSION['authorized'])) {
        $userFound = $app['database']->userFind($_SESSION['user']['email']);
    }

    //file upload
    $target_dir = dirname(__DIR__, 1) . "/uploads/"; // пример из W3School
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // if ($FileType != "pdf" || $FileType != "png") {
    //     $uploadOk = 0;
    // }

    if ($uploadOk == 1) {

        if (file_exists($target_file)) {
            $fileurl = '/uploads/' . basename($_FILES["file"]["name"]);
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $fileurl = '/uploads/' . basename($_FILES["file"]["name"]);
            }
        }

    }

    if (isset($userFound)) {
        $post = $userFound;
    } else {
        $post = $_POST;
    }

    $data = [
        'fullname' => $post['fullname'],
        'email' => $post['email'],
        'gender' => $post['gender'],
        'thread' => $_POST['thread'],
        'maintext' => $_POST['maintext'],
        'fileurl' => $fileurl
    ];

    if (!$app['database']->messageExists($data['maintext'])) {
        $app['database']->postMessage($data);
    }

    // var_dump($app['database']->userFind($_SESSION['user']['email']));

    // echo 'request:<br/><br/>';
    // var_dump($_REQUEST);
    // echo '<br>';
    // echo 'post:<br><br>';
    // var_dump($_POST);
    // echo '<br>';
    // echo 'session:<br><br>';
    // var_dump($_SESSION);


    header('Location: /?mode=contact');

}
