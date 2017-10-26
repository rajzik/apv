<?php 

include('../init.php'); include('../login/login.php');

  if(!empty($_GET['id'])){
    try{
      $stmt = $db->prepare('DELETE FROM location WHERE id_location = :id');
      $stmt->bindValue(':id', $_REQUEST['id']);
      $stmt->execute();
    } catch(Exception $e){
      echo $e->getMessage();
      exit();
    }
  }  
  
  header('Location: ' . $root . 'addresses');
  
