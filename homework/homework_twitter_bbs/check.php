<?php
    session_start();

    require('dbconnect.php');
    // htmlspecialchars のショートカット
    function h($value){
      return htmlspecialchars($value, ENT_QUOTES,'UTF-8');
    }


    if (!isset($_SESSION["join"])) {
      header('Location: index.php');
      exit();
    }

    if (!empty($_POST)) {
      // 登録処理をする
      // ToDoここの書き方がくっそいけてない
      if ( $_SESSION['join']['modified_password'] == '' && $_SESSION['join']['image'] == '') {
        $sql = sprintf('UPDATE members SET name="%s", email="%s", modified=NOW() WHERE id=%d',
                    mysqli_real_escape_string($db, $_SESSION['join']['name']),
                    mysqli_real_escape_string($db, $_SESSION['join']['email']),
                    mysqli_real_escape_string($db, $_SESSION['id'])
                    );
      } elseif ($_SESSION['join']['image'] == '') {
        $sql = sprintf('UPDATE members SET name="%s", email="%s", password="%s", modified=NOW() WHERE id=%d',
            mysqli_real_escape_string($db, $_SESSION['join']['name']),
            mysqli_real_escape_string($db, $_SESSION['join']['email']),
            mysqli_real_escape_string($db, sha1($_SESSION['join']['modified_password'])),
            mysqli_real_escape_string($db, $_SESSION['id'])
            );
      } elseif ($_SESSION['join']['modified_password'] == '') {
        $sql = sprintf('UPDATE members SET name="%s", email="%s", picture="%s", modified=NOW() WHERE id=%d',
              mysqli_real_escape_string($db, $_SESSION['join']['name']),
              mysqli_real_escape_string($db, $_SESSION['join']['email']),
              mysqli_real_escape_string($db, $_SESSION['join']['image']),
              mysqli_real_escape_string($db, $_SESSION['id'])
              );
      } else {
      $sql = sprintf('UPDATE members SET name="%s", email="%s", password="%s", picture="%s", modified=NOW() WHERE id=%d',
            mysqli_real_escape_string($db, $_SESSION['join']['name']),
            mysqli_real_escape_string($db, $_SESSION['join']['email']),
            mysqli_real_escape_string($db, sha1($_SESSION['join']['modified_password'])),
            mysqli_real_escape_string($db, $_SESSION['join']['image']),
            mysqli_real_escape_string($db, $_SESSION['id'])
            );
      }
      mysqli_query($db, $sql) or die(mysqli_error($db));
      unset($_SESSION['join']);

      header('Location: index.php');
      exit();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>確認画面</title>
</head>
<body>
  <form action="" method="post">
    <!-- ↓ToDo これはなんだ？ -->
    <input type="hidden" name="action" value="submit">
    <!-- ⇒submitボタンを押した時に$_POSTを空にしないために必要だった -->
    <!-- submitボタンだけでは、$_POSTの中には何も送信されない -->
    <dl>
      <dt>ニックネーム</dt>
      <dd>
        <?php echo h($_SESSION['join']['name']); ?>
      </dd>
      <dt>メールアドレス</dt>
      <dd>
        <?php echo h($_SESSION['join']['email']); ?>
      </dd>
      <dt>パスワード</dt>
      <dd>
        【表示されません】
      </dd>
      <?php if (isset($_SESSION['join']['image'])): ?>
        <?php $ext = substr($_SESSION['join']['image'], -3) ; ?>
        <?php if($ext == 'jpg' || $ext == 'gif'): ?>
          <dt>写真など</dt>
          <dd>
            <img src="member_picture/<?php echo h($_SESSION['join']['image']); ?>" width="100" height="100" alt="">
          </dd>
        <?php endif ; ?>
      <?php endif ?>
    </dl>
    <div><a href="change.php?action=rewrite">&laquo;&nbsp;書き直す</a>｜<input type="submit" value="登録する"></div>
  </form>
</body>
</html>
