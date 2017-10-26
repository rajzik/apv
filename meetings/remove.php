<?php

  include('../init.php'); include('../login/login.php');
  
  $appendix = '';
  if(!empty($_GET['meeting_id'])){
    try{
      $stmt = $db->prepare('DELETE FROM person_meeting WHERE id_meeting = :meeting_id AND id_person = :person_id');
      $stmt->bindValue(':meeting_id', $_GET['meeting_id']);
      $stmt->bindValue(':person_id', $_GET['participant']);
      $stmt->execute();
      $appendix = '?message=Remove successful'; 
    } catch(Exception $e){
      echo $e->getMessage();
      exit();
    }
  }  
  header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meetings' . $appendix);


  
