<?php


include('../init.php'); include('../login/login.php');


$sql = 'SELECT * FROM relation 
         NATURAL JOIN relation_type
         JOIN person ON person.id_person = relation.id_person2
WHERE id_person1 = :id';
$personSql = 'SELECT * FROM person WHERE id_person = :id';

$person;
if (isset($_GET['message'])) {
    $tplVars['message'] =$_GET['message'];
}


if (!empty($_GET['id'])) {
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        $tplVars['relations'] = $stmt->fetchAll();
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
    try {
        $stmt = $db->prepare($personSql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        $tplVars['person'] = $stmt->fetch();
        $person = $tplVars['person'];
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
}


$tplVars['title'] = $person['first_name'] . ' ' . $person['last_name'] . ' relations';
  
  
$latte->render('index.latte', $tplVars);
