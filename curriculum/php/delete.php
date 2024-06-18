<?php 
session_start();

// ここにアクセスしたと気にidを持っていなければ
if(!isset($_GET['id'])){
    header('Location: contact.php');
    exit();

}else{
    // idを変数に格納
    $aaa=$_GET['id'];
    // idをもとにレコードを取得
    try {
        // 接続処理
        $host = 'mysql';
        $db = 'cafe';
        $charset = 'utf8';
        $dsn = "mysql:host=$host; dbname=$db; charset=$charset";
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);

        // SELECT文を発行
        $sql = "SELECT * FROM contact WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $aaa, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_OBJ); // 1件のレコードを取得
        // 接続切断
        $dbh = null;

    } catch (PDOException $e) {
        print $e->getMessage() . "<br/>";
        die();
    }

}




// もし移動先がPOSTだったら
if($_SERVER['REQUEST_METHOD']==='POST'){
    // contact.phpに戻す
    try {
        $id = intval($aaa);

        // 接続処理
        $host = 'mysql';
        $db = 'cafe';
        $charset = 'utf8';
        $dsn = "mysql:host=$host; dbname=$db; charset=$charset";
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);

        // SELECT文を発行
        $sql = "DELETE FROM contact WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // $record = $stmt->fetch(PDO::FETCH_OBJ); // 1件のレコードを取得
        // 接続切断
        $dbh = null;

    } catch (PDOException $e) {
        print $e->getMessage() . "<br/>";
        die();
    }
    header('Location:contact.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title></title>
  
  <!-- <link rel="stylesheet" type="text/css" href="header.css"> -->
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <!-- <link rel="stylesheet" href="animation.css"> -->
  <!-- <link rel="stylesheet" href="responsive.css"> -->
  <!-- <link rel="stylesheet" href="responsive-iphone.css"> -->
  <link rel="stylesheet" type="text/css" href="contact.css">
  <script src="https://kit.fontawesome.com/5f1eeb6c2d.js" crossorigin="anonymous"></script>
  <script src="index-1.js"></script>
</head>
<body>
    <?php require_once('header.php') ?>

    <section class="section">
        <div class="contact_box">
            <h2>削除しますか？</h2>
			<form action="" method="post">
                <p>下記の内容をご確認の上、削除ボタンを押してください</p>
                <p>削除をやめる場合は戻るを押してください。</p>
                <dl class="confirm">
                    <dt>氏名</dt>
                    <dd><?php echo $post->name; ?></dd>
                    <dt>フリガナ</dt>
                    <dd><?php echo $post->kana; ?></dd>
                    <dt>電話番号</dt>
                    <dd><?php echo $post->tel; ?></dd>
                    <dt>メールアドレス</dt>
                    <dd><?php echo $post->email; ?></dd>
                    <dt>お問い合わせ内容</dt>
                    <dd><?php echo $post->body; ?></dd>
                    <dd class="confirm_btn">
                        <button type="submit">削　除</button>
                        <a href="contact.php">戻　る</a>
                    </dd>
                    <!-- <a href="javascript:history.back();"></a> -->
                </dl>
                <!-- <a href="javascript:history.back();"></a> -->
            </form>
        </div>
        <!-- <a href="javascript:history.back();"></a> -->
    </section>
    <!-- <a href="javascript:history.back();"></a> -->



    <?php require_once('footer.php') ?>
</body>