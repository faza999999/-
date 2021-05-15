<?php

require_once 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$token = "5e79081d30af4e1c6d8d7aaa1b290d1fc7604ed5";
$ch = curl_init('https://api.freelancehunt.com/v2/skills');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
    'Accept-Language: ru'
));
$data = curl_exec($ch);
$result = json_decode($data, 1);
foreach ($result['data'] as $skill) {
    $name = $conn->real_escape_string($skill['name']);
    $sql = "INSERT INTO skills (skill_id, name) VALUES (" . $skill['id'] . ", '" . $name . "')";
    if (!$conn->query($sql)) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die;
    }
}

$link = 'https://api.freelancehunt.com/v2/projects';
while ($result['links']['last'] != $link) {
    $ch = curl_init($link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ));
    $data = curl_exec($ch);
    $result = json_decode($data, 1);

    foreach ($result['data'] as $project) {
        $name = $conn->real_escape_string($project['attributes']['name']);
        $login = $conn->real_escape_string($project['attributes']['employer']['login']);
        $first_name = $conn->real_escape_string($project['attributes']['employer']['first_name']);
        $budget_amount = $project['attributes']['budget']['amount'] ?: 'NULL';
        $sql = "INSERT INTO projects (project_id, name, link, login, first_name, budget_amount)
	         VALUES (" . $project['id'] . ", '" . $name . "', '" . $project['links']['self']['web'] . "', '" . $login . "', '" . $first_name . "', $budget_amount)";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
            die;
        }
        foreach ($project['attributes']['skills'] as $skill) {
            $sql = "INSERT INTO project_skill (project_id, skill_id)
			 VALUES (" . $project['id'] . ", " . $skill['id'] . ")";
            if (!$conn->query($sql)) {
                echo "Error: " . $sql . "<br>" . $conn->error;
                die;
            }
        }
    }
    $link = $result['links']['next'];
    curl_close($ch);
}

?>

