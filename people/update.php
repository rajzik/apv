<?php

include('../init.php'); include('../login/login.php');

$updateSql = 'UPDATE person SET 
                id_location = :location_id
                WHERE id_person = :id';

$locationSql = 'SELECT * FROM location';


if(!empty($_POST['id'])) {
    $realId = $_POST['id'] == 'tottalyRandomStringWhichCannotBeAsIDsoItShouldBeOk123' ? NULL : $_POST['id'];
    try {
        $stmt = $db->prepare($updateSql);
        $stmt->bindValue(':location_id', $realId);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        
        header('Location: ' . $root . 'people');
    } catch(Exception $e) {
        $tplVars['message'] = $e->getMessage();    
    }
}


try {
    $stmt = $db->prepare($locationSql);
    $stmt->execute();
    $tplVars['locations'] = $stmt->fetchAll();
} catch (Exception $e) {
    $tplVars['message'] = $e->getMessage();
}

  $tplVars['title'] = 'Update address';
  $latte->render('update.latte', $tplVars);
