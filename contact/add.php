<?php

include('../init.php'); include('../login/login.php');

$message = 'Error';
$appendix = '';
if (!empty($_POST['add-contact'])) {
    if (empty($_POST['type']) || empty($_POST['value'])) {
        $message = 'All inputs required';
        $appendix .= '&type=' . $_POST['type'] . '&value=' . $_POST['value'];
    } else {
        try {
            $stmt = $db->prepare('INSERT INTO contact (id_person, id_contact_type, contact) VALUES (:id, :type, :value)');

            $stmt->bindValue(':id', $_GET['id']);
            $stmt->bindValue(':type', $_POST['type']);
            $stmt->bindValue(':value', $_POST['value']);
            $stmt->execute();
            $message = 'Contact added!';
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
}

header('Location: ' . $root . 'person/' . $_GET['id'] . '/contact?message='.$message.$appendix);
