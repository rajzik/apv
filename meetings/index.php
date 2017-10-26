<?php
include('../init.php'); include('../login/login.php');


$mainSql = 'SELECT * FROM person_meeting
NATURAL JOIN meeting
FULL JOIN person on person_meeting.id_person = person.id_person
WHERE id_meeting = :id_meeting
ORDER BY id_meeting, last_name
';

$meetingsSql = 'SELECT * FROM meeting
NATURAL JOIN person_meeting
NATURAL JOIN location
WHERE person_meeting.id_person = :id';

$nameSql = 'SELECT * FROM person
    WHERE id_person = :id
';

if (isset($_GET['message'])) {
    $tplVars['message'] = $_GET['message'];
}

if (!empty($_GET['person_id'])) {
    try {
        $stmt = $db->prepare($nameSql);
        $stmt->bindValue(':id', $_GET['person_id']);
        $stmt->execute();
        $tplVars['person'] = $stmt->fetch();
    } catch (Exception $e) {
        $tplVars['Message'] = $e->getMessage();        
    }
    try {
        $stmt = $db->prepare($meetingsSql);
        $stmt->bindValue(':id', $_GET['person_id']);
        $stmt->execute();
        $tplVars['meetings'] = $stmt->fetchAll();
        mapParticipants($tplVars['meetings']);
    } catch(Exception $e) {
        $tplVars['Message'] = $e->getMessage();
    }
}

function mapParticipants(&$arr) {
    global $db, $mainSql;
    foreach($arr as &$item) {
        try {
            $stmt = $db->prepare($mainSql);
            $stmt->bindValue(':id_meeting', $item['id_meeting']);
            $stmt->execute();
            $item['participants'] = $stmt->fetchAll();
        } catch (Exception $e) {
            $tplVars['message'] = $e->getMessage();
        }
    }
}


$tplVars['title'] = 'Meetings overview';
$latte->render('index.latte', $tplVars);

