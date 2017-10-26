<?php
include('../init.php'); include('../login/login.php');



$tplVars['form'] = array('start' => '', 'description' => '', 'duration' => '');

  
if (!empty($_POST['submit'])) {
    if (empty($_POST['start'])) {
        $tplVars['message'] = 'Please enter all required inputs';
        $tplVars['form'] = $_POST;
    } else {
        try {
            $stmt = $db->prepare('UPDATE meeting SET 
                start = :start, 
                description = :description, 
                duration = :duration 
                WHERE id_meeting = :id');
        
            $stmt->bindValue(':id', $_POST['id']);
            $stmt->bindValue(':duration', $_POST['duration']?$_POST['duration']:NULL);
            $stmt->bindValue(':description', $_POST['description']);
            $stmt->bindValue(':start', $_POST['start']);
            $stmt->execute();
            header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meetings');
        } catch (Exception $e) {
            $tplVars['error'] = $e->getMessage();
        }
    }
} elseif (!empty($_GET['meeting_id'])) {
    try {
        $stmt = $db->prepare('SELECT * FROM meeting WHERE id_meeting = :id');
        $stmt->bindValue(':id', $_GET['meeting_id']);
        $stmt->execute();
        $meeting = $stmt->fetch();
        if ($meeting) {
            $tplVars['form'] = $meeting;
        } else {
            $tplVars['message'] = 'Contact not found';
        }
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
}
  
  $tplVars['title'] = 'Change meeting';
  $latte->render('edit.latte', $tplVars);
