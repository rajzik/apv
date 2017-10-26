<?php

require 'latte.php';
$latte = new Latte\Engine;
$db;
try {
    $db = new PDO('pgsql:host=localhost;dbname=xsilhan2', 'xsilhan2', '$engr%');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('I cannot connect to database: ' . $e->getMessage());
}
$tplVars = array();

$root = '/~xsilhan2/';

