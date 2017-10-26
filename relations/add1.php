<?php


include('../init.php'); include('../login/login.php');


$sql = 'SELECT * FROM person WHERE id_person != :id';
$personSql = 'SELECT * FROM person WHERE id_person = :id';

$person;

if (!empty($_GET['person_id'])) {
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $_GET['person_id']);
        $stmt->execute();
        $tplVars['people'] = $stmt->fetchAll();
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
    try {
        $stmt = $db->prepare($personSql);
        $stmt->bindValue(':id', $_GET['person_id']);
        $stmt->execute();
        $person = $stmt->fetch();
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
    $tplVars['title'] = $person['first_name'] . ' ' . $person['last_name'] . ' add relation';
}
else {
    $tplVars['message'] = 'Someone want to mess around';
    $tplVars['title'] = "Don't do this, this isn't cool, just follow clickable buttons.";
    $tplVars['people'] = [];
}

  
  
$latte->render('addfirst.latte', $tplVars);
