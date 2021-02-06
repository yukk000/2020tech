<?php

if (empty($_POST['login_id']) || empty($_POST['password'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./login.php");
  return;
}

$dbh = new PDO('mysql:host=mysql;dbname=2020techc_db', '2020techc_username', '2020techc_password');


$select_sth = $dbh->prepare('SELECT id, login_id, password FROM users WHERE login_id = :login_id LIMIT 1');
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

$redis = new Redis();
$redis->connect("redis", 6379);
$session_id_cookie_key = "session_id";
$session_id = isset($_COOKIE[$session_id_cookie_key]) ? ($_COOKIE[$session_id_cookie_key]) : null;
if ($session_id === null) {
    $session_id = bin2hex(random_bytes(25));
    setcookie($session_id_cookie_key, $session_id, 0, '/');
}
$redis_session_key = "session-" . $session_id;
$session_values = $redis->exists($redis_session_key)
    ? json_decode($redis->get($redis_session_key), true)
    : [];
$session_values["login_user_id"] = $row['id'];
$redis->set($redis_session_key, json_encode($session_values));

header("HTTP/1.1 302 Found");
header("Location: ./login_finish.php");
return;
?>
