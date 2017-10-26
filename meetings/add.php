<?

include('../init.php'); include('../login/login.php');


$sql = 'SELECT * FROM person 
        LEFT JOIN location USING (id_location) 
        WHERE id_person 
        NOT IN (
            SELECT id_person FROM person_meeting WHERE id_meeting = :meeting_id
        )
';

$insertSql = 'INSERT INTO person_meeting (id_person, id_meeting) VALUES (:person_id, :meeting_id)';
$insertMultiple = 'INSERT INTO person_meeting (id_person, id_meeting) VALUES ';


if (!empty($_GET['to_add'])) {
    $stmt = $db->prepare($insertSql);
    $stmt->bindValue(':meeting_id', $_GET['meeting_id']);
    $stmt->bindValue(':person_id', $_GET['to_add']);    
    $stmt->execute();
    header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meetings'); 
} else if (!empty($_POST['submit'])) {
    $values = '(' . implode(','.$_GET['meeting_id'].'),(', $_POST['people']) . ','.$_GET['meeting_id'].')';
    $insertMultiple .= $values;
    $stmt = $db->prepare($insertMultiple);
    $stmt->execute();
    header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meetings'); 

}
try {
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':meeting_id', $_GET['meeting_id']);
    $stmt->execute();
    $tplVars['people'] = $stmt->fetchAll();
} catch (Exception $e) {
    $tplVars['error'] = $e->getMessage();
}


  $tplVars['title'] = 'Add people';
  $latte->render('add.latte', $tplVars);
