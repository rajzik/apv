<?
include('../init.php'); include('../login/login.php');




$tplVars['form'] = array('id' => '', 'id_contact_type' => '', 'value' => '');
try {
    $stmt = $db->prepare('SELECT * FROM contact_type');
    $stmt->execute();
    $tplVars['contact_type'] = $stmt->fetchAll();
} catch (Exception $e) {
    $tplVars['error'] = $e->getMessage();
}
  
if (!empty($_POST['submit'])) {
    if (empty($_POST['id']) || empty($_POST['type']) || empty($_POST['value'])) {
        $tplVars['message'] = 'Please enter all required inputs';
        $tplVars['form'] = $_POST;
    } else {
        try {
            $stmt = $db->prepare('UPDATE contact SET id_contact_type = :id_contact_type, contact = :value WHERE id_contact = :id');
        
            $stmt->bindValue(':id', $_POST['id']);
            $stmt->bindValue(':id_contact_type', $_POST['type']);
            $stmt->bindValue(':value', $_POST['value']);
        
            $stmt->execute();
            header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/contact');
        } catch (Exception $e) {
            $tplVars['error'] = $e->getMessage();
        }
    }
} elseif (!empty($_GET['id'])) {
    try {
        $stmt = $db->prepare('SELECT * FROM contact WHERE id_contact = :id');
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();

        $contact = $stmt->fetch();
        if ($contact) {
            $tplVars['form'] = array(
            'id' => $contact['id_contact'],
            'id_contact_type' => $contact['id_contact_type'],
            'value' => $contact['contact']
            );
        } else {
            $tplVars['message'] = 'Contact not found';
        }
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
}
  
  $tplVars['title'] = 'Change contact';
  $latte->render('edit.latte', $tplVars);
