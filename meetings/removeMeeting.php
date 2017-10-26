<?php

  include('../init.php'); include('../login/login.php');
  
  $appendix = '';
  if(!empty($_GET['meeting_id'])){
    try{
      $stmt = $db->prepare('DELETE FROM meeting WHERE id_meeting = :id');
      $stmt->bindValue(':id', $_GET['meeting_id']);
      $stmt->execute();
      $appendix = '?message=Remove successful'; 
    } catch(Exception $e){
      echo $e->getMessage();
      exit();
    }
  }  
  header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meetings' . $appendix);


  
