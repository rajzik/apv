<?php


include('../init.php'); include('../login/login.php');







$tplVars['form'] = array('id' => '', 'id_relation_type' => '', 'description' => '');
try {
    $stmt = $db->prepare('SELECT * FROM relation_type');
    $stmt->execute();
    $tplVars['types'] = $stmt->fetchAll();
} catch (Exception $e) {
    $tplVars['error'] = $e->getMessage();
}
  
if (!empty($_POST['add'])) {
    if (empty($_POST['id']) || empty($_POST['type'])) {
        $tplVars['message'] = 'Please enter all required inputs';
        $tplVars['form'] = $_POST;
    } else {
        try {
            $stmt = $db->prepare('UPDATE relation SET id_relation_type = :id_relation_type, description = :description WHERE id_relation = :id');
        
            $stmt->bindValue(':id', $_POST['id']);
            $stmt->bindValue(':id_relation_type', $_POST['type']);
            $stmt->bindValue(':description', $_POST['description']);
        
            $stmt->execute();
            header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/relations');
        } catch (Exception $e) {
            $tplVars['error'] = $e->getMessage();
        }
    }
} elseif (!empty($_GET['id'])) {
    try {
        $stmt = $db->prepare('SELECT * FROM relation WHERE id_relation = :id');
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();

        $relation = $stmt->fetch();
        if ($relation) {
            $tplVars['form'] = array(
            'id' => $relation['id_relation'],
            'id_relation_type' => $relation['id_relation_type'],
            'description' => $relation['description']
            );
        } else {
            $tplVars['message'] = 'Contact not found';
        }
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
}
  
  $tplVars['title'] = 'Change relation';
  $latte->render('edit.latte', $tplVars);

