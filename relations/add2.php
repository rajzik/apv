<?php


include('../init.php'); include('../login/login.php');


$sql = 'SELECT * FROM relation_type';
$personSql = 'SELECT * FROM person WHERE id_person = :id';
$insertSql = 'INSERT INTO relation (id_person1, id_person2, description, id_relation_type) VALUES (:id1, :id2, :desc, :idtype)';
$person;
$person2;
$tplVars['typos'] = '';



if(!empty($_POST['add'])) {
    if (empty($_POST['type'])) {
        $tplVars['message'] = 'You have to add type';
    }
    else {
        $stmt = $db->prepare($insertSql);
        $stmt->bindValue(':id1', $_POST['person_id1']);
        $stmt->bindValue(':id2', $_POST['person_id2']);
        $stmt->bindValue(':desc', $_POST['description']);
        $stmt->bindValue(':idtype', $_POST['type']);
        $stmt->execute();
        
        header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/relations');
    }
}
else if (!empty($_GET['person_id'])) {
    $tplVars['person_id1'] = $_GET['person_id'];
    $tplVars['person_id2'] = $_GET['person_id2'];
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $tplVars['types'] = $stmt->fetchAll();
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
    try {
        $stmt = $db->prepare($personSql);
        $stmt->bindValue(':id', $_GET['person_id']);
        $stmt->execute();
        $tplVars['person'] = $stmt->fetch();
        $person = $tplVars['person'];
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
    try {
        $stmt = $db->prepare($personSql);
        $stmt->bindValue(':id', $_GET['person_id2']);
        $stmt->execute();
        $tplVars['person2'] = $stmt->fetch();
        $person2 = $tplVars['person2'];
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }

}  

$tplVars['title'] = $person['first_name'] . ' ' . $person['last_name'] . ' and ' . $person2['first_name'] . ' ' . $person2['last_name'] . ' relation';
  
  
$latte->render('add2.latte', $tplVars);
