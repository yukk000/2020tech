
<?php
if (empty($_POST['login_id']) || empty($_POST['password'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./login.php");
  return;
}

$dbh = new PDO('mysql:host=mysql;dbname=2020techc_db', '2020techc_username', '2020techc_password');

$select_sth = $dbh->prepare('SELECT login_id, password FROM users WHERE login_id = :login_id LIMIT 1');
$select_sth->execute([
    ':login_id' => $_POST['login_id'],
]);
$row = $select_sth->fetch();

if (!$row) {
    print('ログインIDがみつかりませんでした。<a href="./login.php">戻る</a>');
    return;
}

if (!password_verify($_POST['password'], $row['password'])) {
    print('パスワードが間違っています。<a href="./login.php">戻る</a>');
    return;
}

setcookie('login_id', $row['login_id'], 0, '/');

header("HTTP/1.1 302 Found");
header("Location: ./login_finish.php");
return;
?>
