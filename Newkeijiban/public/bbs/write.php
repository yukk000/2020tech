
<?php
if (empty($_POST['body'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./read.php");
  return;
}

if (empty($_COOKIE["login_id"])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./read.php");
  return;
}

$dbh = new PDO('mysql:host=mysql;dbname=2020techc_db', '2020techc_username', '2020techc_password');

$insert_sth = $dbh->prepare("INSERT INTO bbs_entries (name, body) VALUES (:name, :body)");
$insert_sth->execute([
    ':name' => $_COOKIE["login_id"],
    ':body' => $_POST['body'],
]);

setcookie("name", $_POST['name']);

header("HTTP/1.1 302 Found");
header("Location: ./read.php");
return;
?>
