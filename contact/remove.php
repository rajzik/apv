<?php

  include('../init.php'); include('../login/login.php');
  
  $appendix = '';
  if(!empty($_GET['id']) && !empty($_GET['person_id'])){
    try{
      $stmt = $db->prepare('DELETE FROM contact WHERE id_contact = :id');
      $stmt->bindValue(':id', $_GET['id']);
      $stmt->execute();
      $appendix = '?message=Remove successful'; 
    } catch(Exception $e){
      echo $e->getMessage();
      exit();
    }
  }  
  header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/contact' . $appendix);


  
