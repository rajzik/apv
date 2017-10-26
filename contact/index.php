<?

include('../init.php'); include('../login/login.php');

$nameSql = 'SELECT * FROM person WHERE id_person = :id';
$sql = 'SELECT * FROM person NATURAL JOIN contact NATURAL JOIN contact_type WHERE id_person = :id';
$contactTypeSql = 'SELECT * FROM contact_type';
$tplVars['typos'] = '';

$tplVars['person_id'] = $_GET['id'];

if (isset($_GET['message'])) {
    $tplVars['message'] = $_GET['message'];
}

if (!empty($_GET['type'])){
    $tplVars['typos'] = $_GET['type'];
}

if (!empty($_GET['value'])){
    $tplVars['value'] = $_GET['value'];
}

 if(!empty($_GET['id'])){
    try {
        $stmt = $db->prepare($nameSql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        $person = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch(Exception $e) {
        $tplVars['error'] = $e->getMessage();        
    }
    try{
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        $tplVars['contacts'] = $stmt->fetchAll();
    } catch(Exception $e){
        $tplVars['error'] = $e->getMessage();
    }
    try {
        $stmt = $db->prepare($contactTypeSql);
        $stmt->execute();
        $tplVars['contact_type'] = $stmt->fetchAll();
    }
    catch (Exception $e) {
        $tplVars['error'] = $e->getMessage();
    }
  }  


  $tplVars['title'] = $person['first_name'].' '.$person['last_name'].' contacts';
  
  
  $latte->render('index.latte', $tplVars);