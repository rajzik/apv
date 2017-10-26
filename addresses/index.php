<?php

include('../init.php'); include('../login/login.php');


$locationSql = 'SELECT * FROM location ORDER BY city';


try {
        $stmt = $db->prepare($locationSql);
        $stmt->execute();
        $tplVars['locations'] = $stmt->fetchAll();
} catch (Exception $e) {
        $tplVars['message'] = $e->getMessage();
}

  $tplVars['title'] = 'Addresses';
  $latte->render('index.latte', $tplVars);
