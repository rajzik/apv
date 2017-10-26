<?php

include('../init.php'); include('../login/login.php');

$tplVars['form'] = array(
    'city' => '', 
    'street_name' => '', 
    'street_number' => '', 
    'zip' => '', 
    'country' => '', 
    'name' => '', 
    'latitude' => '', 
    'longitude' => ''
);
$selectSql = 'SELECT * FROM location WHERE id_location = :id';

$sql = 'UPDATE location SET 
    city = :city, 
    street_name = :streetName, 
    street_number = :streetNumber, 
    zip = :zip, 
    country = :country, 
    name = :name, 
    latitude = :lat, 
    longitude = :lon 
WHERE id_location = :id';
if (!empty($_POST['submit'])) {
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':city', $_POST['city']?$_POST['city']:NULL);
        $stmt->bindValue(':streetName', $_POST['street_name']? $_POST['street_name']:NULL);
        $stmt->bindValue(':streetNumber', $_POST['street_number']?intval($_POST['street_number']):NULL);
        $stmt->bindValue(':zip', $_POST['zip']?$_POST['zip']:NULL);
        $stmt->bindValue(':country', $_POST['country']?$_POST['country']:NULL);
        $stmt->bindValue(':name', $_POST['name']?$_POST['name']:NULL);
        $stmt->bindValue(':lat', $_POST['latitude']?$_POST['latitude']:NULL);
        $stmt->bindValue(':lon', $_POST['longitude']?$_POST['longitude']:NULL);
        $stmt->bindValue(':id', $_POST['id_location']);
        $stmt->execute();
    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
    header('location: ' . $root . 'addresses');
} else {

    try {

        $stmt = $db->prepare($selectSql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        $tplVars['form'] = $stmt->fetch();

    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
}


$tplVars['title'] = 'Edit address';
$latte->render('edit.latte', $tplVars);
