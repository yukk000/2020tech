<?php

$dbh = new PDO('mysql:host=mysql;dbname=2020techc_db', '2020techc_username', '2020techc_password');

$select_sth = $dbh->prepare('SELECT name, body, created_at FROM bbs_entries ORDER BY id DESC');
$select_sth->execute();
$rows = $select_sth->fetchAll();
?>
<!DOCTYPE html>
<head>
  <title>掲示板</title>
</head>
<body>
  <h1>掲示板</h1>
 
  <?php if(!empty($_COOKIE["login_id"])): ?>
  <form method="POST" action="./write.php" style="margin: 2em;">
    <div>
      名前(ログインid): <?= htmlspecialchars($_COOKIE["login_id"]) ?>
    </div>
    <div>
      <textarea name="body" rows="5" cols="100" required></textarea>
    </div>
    <button type="submit">投稿</button>
  </form>
  <?php else: ?>
  投稿するには<a href="/users/login.php">ログイン</a>してください。
  <?php endif; ?>

  <hr>

  <?php foreach ($rows as $row) : ?>
  <div style="margin: 2em;">
    <span><?= htmlspecialchars($row['name']) ?>さんの投稿</span>
    <span>(投稿日: <?= $row['created_at'] ?>)</span>
    <div style="margin-top: 0.5em;"><?= nl2br(htmlspecialchars($row['body'])) ?></div>
  </div>
  <hr>
  <?php endforeach; ?>

</body>
