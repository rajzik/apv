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

$sql = 'INSERT INTO location (city, street_name, street_number, zip, country, name, latitude, longitude) 
    VALUES (
        :city,
        :streetName,
        :streetNumber,
        :zip,
        :country,
        :name,
        :lat,
        :lon
    )';
if (!empty($_POST['submit'])) {
    try {

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':city', $_POST['city']?$_POST['city']:NULL);
        $stmt->bindValue(':streetName', $_POST['street_name']?$_POST['street_name']:NULL);
        $stmt->bindValue(':streetNumber', $_POST['street_number']?$_POST['street_number']:NULL);
        $stmt->bindValue(':zip', $_POST['zip']?$_POST['zip']:NULL);
        $stmt->bindValue(':country', $_POST['country']?$_POST['country']:NULL);
        $stmt->bindValue(':name', $_POST['name']?$_POST['name']:NULL);
        $stmt->bindValue(':lat', $_POST['latitude']?$_POST['latitude']:NULL);
        $stmt->bindValue(':lon', $_POST['longitude']?$_POST['longitude']:NULL);
        $stmt->execute();

    } catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
    header('location: ' . $root . 'addresses');
}


$tplVars['title'] = 'Add address';
$latte->render('add.latte', $tplVars);
