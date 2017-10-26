<?

include('../init.php'); include('../login/login.php');



  $tplVars['form'] = array('first_name' => '', 'last_name' => '', 'nick' => '', 'birth_date' => '', 'height' => '');

  function checkSQLDate($s) {
    $reg = '/^\d{4}-\d{2}-\d{2}$/';
    return preg_match($reg, $s);
  }
  if(!empty($_POST['submit'])){
    if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['nick']) || (!empty($_POST['birth_date']) && !checkSQLDate($_POST['birth_date']))){
      $tplVars['message'] = 'Please enter all required inputs';
      $tplVars['form'] = $_POST;
    }
    else{
      try{
        $stmt = $db->prepare('INSERT INTO person (nickname, first_name, last_name, birth_day, height) VALUES (:nick, :first_name, :last_name, :birth_date, :height)');

        $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
        $height = !empty($_POST['height']) ? $_POST['height'] : null;
        
        $stmt->bindValue(':nick', $_POST['nick']);
        $stmt->bindValue(':first_name', $_POST['first_name']);
        $stmt->bindValue(':last_name', $_POST['last_name']);
        $stmt->bindValue(':birth_date', $birth_date);
        $stmt->bindValue(':height', $height);
        
        $stmt->execute();

        header('Location: '. $root .'people'); 

      } catch(Exception $e){
        $tplVars['error'] = $e->getMessage();
      }
    }
  }
  $tplVars['title'] = 'Add new person';
  $latte->render('add.latte', $tplVars);
