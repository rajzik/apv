<?

include('../init.php'); include('../login/login.php');



  // $tplVars['form'] = array('first_name' => '', 'last_name' => '', 'nick' => '', 'birth_date' => '', 'height' => '');

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
        $stmt = $db->prepare('UPDATE person SET first_name = :first_name, last_name = :last_name, nickname = :nick, birth_day = :birth_date, height = :height WHERE id_person = :id');

        $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
        $height = !empty($_POST['height']) ? $_POST['height'] : null;
        
        $stmt->bindValue(':id', $_POST['id']);
        $stmt->bindValue(':nick', $_POST['nick']);
        $stmt->bindValue(':first_name', $_POST['first_name']);
        $stmt->bindValue(':last_name', $_POST['last_name']);
        $stmt->bindValue(':birth_date', $birth_date);
        $stmt->bindValue(':height', $height);
        
        $stmt->execute();
        header('Location: ' . $root . 'people');
      } catch(Exception $e){
        $tplVars['error'] = $e->getMessage();
      }
    }
  }
  elseif(!empty($_GET['id'])){
    try{
      $stmt = $db->prepare('SELECT * FROM person WHERE id_person = :id');
      $stmt->bindValue(':id', $_GET['id']);
      $stmt->execute();

      $person = $stmt->fetch();
      if($person){
        $tplVars['form'] = array(
          'id' => $person['id_person'],
          'first_name' => $person['first_name'],
          'last_name' => $person['last_name'],
          'nick' => $person['nickname'],
          'birth_date' => $person['birth_day'],
          'height' => $person['height']
        );
      }
      else{
        $tplVars['message'] = 'Person not found';
      }
    } catch(Exception $e){
      $tplVars['error'] = $e->getMessage();
    }
  }
  else{
    $tplVars['error'] = 'No ID passed';
  }
  
  $tplVars['title'] = 'Change person';
  $latte->render('edit.latte', $tplVars);
