<?php

include('../init.php'); include('../login/login.php');
  
$appendix = '';
if (!empty($_GET['id']) && !empty($_GET['person_id'])) {
    try {
        $stmt = $db->prepare('DELETE FROM relation WHERE id_relation = :id');
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        $appendix = '?message=Remove successful';
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
}


header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/relations' . $appendix);
