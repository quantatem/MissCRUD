<?php

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : false;

if ($mode === 'auth') {
    require 'core/auth.html';
} elseif ($mode === 'table' && isset($_SESSION['authorized']) && $_SESSION["authorized"]) { // крайне уязвимо ?
    require 'core/table.html';
} elseif ($mode === 'logout') {
    session_destroy();
    require 'index.html';
    header('Location: /');
}
elseif ($mode === 'contact') {
    require 'core/contact.html';
}
else {
    require 'index.html';
}