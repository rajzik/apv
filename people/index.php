<?

include('../init.php'); include('../login/login.php');

$sql = 'SELECT * FROM person 
LEFT JOIN location USING (id_location) 
LEFT JOIN (SELECT id_person, COUNT(*) AS contact_count FROM contact GROUP BY id_person) 
AS pk USING (id_person) 
LEFT JOIN (SELECT id_person1 AS id_person, COUNT(*) AS relations_count FROM relation GROUP BY id_person)
AS pk2 USING (id_person)
LEFT JOIN (SELECT id_person, COUNT(*) AS meeting_count FROM person_meeting GROUP BY id_person)
AS pk3 USING (id_person)
';

$tplVars['form'] = array('first_name' => '', 'last_name' => '', 'nick' => '', 'city' => '');

try {
    if (!empty($_GET['last_name']) || !empty($_GET['first_name']) || !empty($_GET['city']) || !empty($_GET['nick'])) {
        // Fixed searching where was problem with city.
        $finalSql = $sql . 'WHERE last_name ILIKE :last_name AND first_name ILIKE :first_name AND nickname ILIKE :nick';
        // Look for city when it's entered
        if (!empty($_GET['city'])){
            $finalSql .= ' AND city ILIKE :city';
        }
        $finalSql .= ' ORDER BY last_name';

        $stmt = $db->prepare($finalSql);

        $stmt->bindValue(':last_name', $_GET['last_name'] . '%');
        $stmt->bindValue(':nick', $_GET['nick'] . '%');
        $stmt->bindValue(':first_name', $_GET['first_name'] . '%');
        if (!empty($_GET['city'])) {
            $stmt->bindValue(':city', $_GET['city'] . '%');
        }
        $stmt->execute();
        $tplVars['form'] = $_GET;
    } else {
        $stmt = $db->query($sql . 'ORDER BY last_name');
    }
    $tplVars['people'] = $stmt->fetchAll();
} catch (Exception $e) {
    $tplVars['error'] = $e->getMessage();
}

  $tplVars['title'] = 'People overview';
  $latte->render('index.latte', $tplVars);
