<?php

include('../init.php'); include('../login/login.php');


$insertSql = 'INSERT INTO meeting (start, description, duration, id_location) 
                VALUES  
                (
                :start, 
                :description, 
                :duration,
                :id)';
$insertPersonSql = 'INSERT INTO person_meeting (id_meeting, id_person)
    VALUES (
        :id_meeting,
        :id_person
    )';
$updateSql = 'UPDATE meeting SET 
                id_location = :location_id
                WHERE id_meeting = :id';

$locationSql = 'SELECT * FROM location';
$locationSqlWithConstraint = $locationSql . ' WHERE id_location NOT IN (SELECT id_location FROM meeting WHERE id_meeting = :id_meeting)';
$tplVars['form'] = $_GET;

if(!empty($_POST['id'])) {
    if (strcmp($_GET['type'], 'create') == 0) {
        try {
            $stmt = $db->prepare($insertSql);
            $stmt->bindValue(':start', $_POST['start']);
            $stmt->bindValue(':description', $_POST['description']);
            $stmt->bindValue(':duration', $_POST['duration']);
            $stmt->bindValue(':id', $_POST['id']);
            $stmt->execute();
            
            $meeting_id = $db->lastInsertId('meeting_id_meeting_seq');

            $stmt = $db->prepare($insertPersonSql);
            $stmt->bindValue(':id_meeting', $meeting_id);
            $stmt->bindValue(':id_person', $_GET['person_id']);
            $stmt->execute();

            header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meetings');
        
        } catch (Exception $e) {
            $tplVars['message'] = $e->getMessage();    
        }
    }
    if (strcmp($_GET['type'], 'modify') == 0 ){
        try {
            $stmt = $db->prepare($updateSql);
            $stmt->bindValue(':location_id', $_POST['id']);
            $stmt->bindValue(':id', $_GET['meeting_id']);
            $stmt->execute();
            
            header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meetings');
        } catch(Exception $e) {
            $tplVars['message'] = $e->getMessage();    

        }
    }
}

if(!empty($_GET['meeting_id'])){
    try {
        $stmt = $db->prepare($locationSqlWithConstraint);
        $stmt->bindValue(':id_meeting', $_GET['meeting_id']);
        $stmt->execute();
        $tplVars['locations'] = $stmt->fetchAll();
    } catch (Exception $e) {
        $tplVars['message'] = $e->getMessage();
    }
}
else {
    try {
        $stmt = $db->prepare($locationSql);
        $stmt->execute();
        $tplVars['locations'] = $stmt->fetchAll();
    } catch (Exception $e) {
        $tplVars['message'] = $e->getMessage();
    }
}

  $tplVars['title'] = 'Add location';
  $latte->render('changeLocation.latte', $tplVars);
