<?php

  include('../init.php'); include('../login/login.php');
  
  if(!empty($_REQUEST['id'])){
    try{
      $stmt = $db->prepare('DELETE FROM person WHERE id_person = :id');
      $stmt->bindValue(':id', $_REQUEST['id']);
      $stmt->execute();
    } catch(Exception $e){
      echo $e->getMessage();
      exit();
    }
  }  
  
  header('Location: ' . $root . 'people');
  
