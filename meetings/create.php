<?php
include('../init.php'); include('../login/login.php');


$tplVars['form'] = array('start' => '', 'description' => '', 'duration' => '');

  
if (!empty($_POST['submit'])) {
    if (empty($_POST['start'])) {
        $tplVars['message'] = 'Please enter all required inputs';
        $tplVars['form'] = $_POST;
    } else {
        try {
            $appendix = 'start='.$_POST['start'];
            if ($_POST['duration']) {
                $appendix.= '&duration='.$_POST['duration'];
            }
            if ($_POST['description']) {
                $appendix.= '&description='.$_POST['description'];
            }
            
            header('Location: ' . $root . 'person/' . $_GET['person_id'] . '/meeting/create/location?'.$appendix);
        } catch (Exception $e) {
            $tplVars['error'] = $e->getMessage();
        }
    }
}
  
  $tplVars['title'] = 'Create meeting';
  $latte->render('create.latte', $tplVars);
