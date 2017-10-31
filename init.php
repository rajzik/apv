<?php

require 'latte.php';
$latte = new Latte\Engine;
$db;
try {
    // substitute your user name and password for your DB
    $db = new PDO('pgsql:host=localhost;dbname=username', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('I cannot connect to database: ' . $e->getMessage());
}
$tplVars = array();

$root = '/~xsilhan2/';

