<?php
require_once 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if($_POST['skills']) {
    $andWhere = " AND project_id IN (SELECT project_id FROM project_skill WHERE skill_id IN (".implode(',', $_POST['skills'])."))";
}
$cnt_500 = $conn->query("SELECT COUNT(*) as cnt_500 FROM projects WHERE budget_amount IS NOT NULL AND budget_amount <= 500 $andWhere")->fetch_object()->cnt_500;
$cnt_500_1000 = $conn->query("SELECT COUNT(*) as cnt_500_1000 FROM projects WHERE budget_amount IS NOT NULL AND budget_amount > 500 AND budget_amount <= 1000 $andWhere")->fetch_object()->cnt_500_1000;
$cnt_1000_5000 = $conn->query("SELECT COUNT(*) as cnt_1000_5000 FROM projects WHERE budget_amount IS NOT NULL AND budget_amount > 1000 AND budget_amount <= 5000 $andWhere")->fetch_object()->cnt_1000_5000;
$cnt_5000 = $conn->query("SELECT COUNT(*) as cnt_5000 FROM projects WHERE budget_amount IS NOT NULL AND budget_amount > 5000 $andWhere")->fetch_object()->cnt_5000;
$skills = $conn->query("SELECT * FROM skills");
if($_POST['skills']) {
    $sql = "SELECT * FROM projects WHERE project_id IN (SELECT project_id FROM project_skill WHERE skill_id IN (".implode(',', $_POST['skills'])."))";
} else {
    $sql = "SELECT * FROM projects";
}
$projects = $conn->query($sql);
require_once 'view.php';
?>
