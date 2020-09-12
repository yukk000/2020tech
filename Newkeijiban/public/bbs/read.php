<?php
// データベースハンドラ作成 db名/ユーザー名/パスワードを独自のものに設定しているひとは書き換えてください。
$dbh = new PDO('mysql:host=mysql;dbname=2020techc_db', '2020techc_username', '2020techc_password');

// 全行取得 id降順(新しい投稿が上にくる)
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
  <!--
    投稿用フォーム
    読み込みページに投稿用フォームを用意してあげると利用者は便利です。
    ログインしている場合のみ表示します。
  -->
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
  <!-- 投稿用フォームここまで -->
  <hr>

  <!--
    投稿の表示
    foreachを用いたループで表現しています。
    利用者が任意の内容を投稿する部分(名前と本文)は htmlspecialchars() を用いエスケープします。
      XSS対策です。
  -->
  <?php foreach ($rows as $row) : ?>
  <div style="margin: 2em;">
    <span><?= htmlspecialchars($row['name']) ?>さんの投稿</span>
    <span>(投稿日: <?= $row['created_at'] ?>)</span>
    <div style="margin-top: 0.5em;"><?= nl2br(htmlspecialchars($row['body'])) ?></div>
  </div>
  <hr>
  <?php endforeach; ?>
  <!-- 投稿の表示ここまで -->

</body>
