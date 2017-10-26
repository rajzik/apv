<?php
include('../init.php');
session_start();

if (!empty($_POST['submit'])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE login = :login");
            $stmt->bindValue(":login", $_POST["username"]);
            $stmt->execute();
            $user = $stmt->fetch();
            if ($user && sha1($_POST["password"]) == $user["password"]) {
                $_SESSION["logged"] = true;
                $_SESSION["id"] = $user["id_users"];
                $_SESSION["username"] = $user["login"];
            } else {
                $tplVars['message'] = "We cannot log you in!";
            }
        } catch (Exception $e) {
            $tplVars['message'] = $e->getMessage();
        }
    } else {
        $tplVars['message'] = 'Please all inputs are required';
    }
} else if (!empty($_POST['sbmt'])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        try {
            $stmt = $db->prepare("INSERT INTO users (login, password) VALUES (:username, :passwd)");
            $stmt->bindValue(":username", $_POST["username"]);
            $stmt->bindValue(":passwd", sha1($_POST["password"]));
            $stmt->execute();
            
            $user_id = $db->lastInsertId('users_id_users_seq');
            
            $_SESSION["logged"] = true;
            $_SESSION["id"] = $user_id;
            $_SESSION["username"] = $_POST["username"];
        } catch (Exception $e) {
            $tplVars['message'] = $e->getMessage();
        }
    } else {
        $tplVars['message'] = 'Please all inputs are required';
    }
}


if (empty($_SESSION["logged"])) {
    $tplVars["title"] = "login";
    $latte->render("../login/login.latte", $tplVars);
    exit();
}
