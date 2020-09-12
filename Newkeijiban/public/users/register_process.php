
<?php

if (empty($_POST['login_id']) || empty($_POST['password'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./register.php");
  return;
}

$dbh = new PDO('mysql:host=mysql;dbname=2020techc_db', '2020techc_username', '2020techc_password');

$select_sth = $dbh->prepare('SELECT login_id, password FROM users WHERE login_id = :login_id LIMIT 1');
$select_sth->execute([
    ':login_id' => $_POST['login_id'],
]);
if (!empty($select_sth->fetch())) {
    print('そのログインIDは既に登録されています。<a href="./register.php">戻る</a>');
    return;
}

$insert_sth = $dbh->prepare("INSERT INTO users (login_id, password) VALUES (:login_id, :password)");
$insert_sth->execute([
    ':login_id' => $_POST['login_id'],
    ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
]);
header("HTTP/1.1 302 Found");
header("Location: ./register_finish.php");
return;
?>
