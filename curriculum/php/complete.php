<?php 
session_start();

// ここに来た時にセッション変数がなければコンタクトへ
if(!isset($_SESSION['form'])){
    header('Location: contact.php');
    exit();

}else {
    // セッション変数を受け取る
    $post = $_SESSION['form'];

    // セッション変数をDBへおくる。
    try {
        
        require_once('db.php');
        $PDO = new PDO($dsn, $user, $pass); //PDOでMySQLのデータベースに接続
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDOのエラーレポートを表示
       
        //input.phpの値を取得
        $name = $post['name'];
        $kana = $post['kana'];
        $tel = $post['tel'];
        $email = $post['email'];
        $body = $post['body'];
       
        $sql = "INSERT INTO contacts (name, kana, tel, email, body) VALUES (:name, :kana, :tel, :email, :body)"; // テーブルに登録するINSERT INTO文を変数に格納　VALUESはプレースフォルダーで空の値を入れとく
        $stmt = $PDO->prepare($sql); //値が空のままSQL文をセット
        $params = array(':name' => $name,':kana' =>$kana,'tel'=>$tel,':email' => $email,':body'=> $body); // 挿入する値を配列に格納
        $stmt->execute($params); //挿入する値が入った変数をexecuteにセットしてSQLを実行
       
        // 登録内容確認・メッセージ
        // echo "<p>名前: " . $name . "</p>";
        // echo "<p>メールアドレス: " . $email . "</p>";
        // echo "<p>メッセージ: " . $body . "</p>";
        // echo '<p>上記の内容をデータベースへ登録しました。</p>';
      } catch (PDOException $e) {
        exit('データベースに接続できませんでした。' . $e->getMessage());
      }

    // 最終的にはセッションは消す
    unset($_SESSION['form']);
}


?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="contact.css">
    <!-- <link rel="stylesheet" type="text/css" href="header.css"> -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!-- <link rel="stylesheet" href="animation.css"> -->
    <!-- <link rel="stylesheet" href="responsive.css"> -->
    <!-- <link rel="stylesheet" href="responsive-iphone.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/5f1eeb6c2d.js" crossorigin="anonymous"></script>
    <script src="index-1.js"></script>
    </head>
    <body>
        <?php require_once('header.php') ?>

        <section class="section">
        <div class="contact_box">
            <h2>お問い合わせ</h2>
            <div class="complete_msg">
                <p>お問い合わせ頂きありがとうございます。</p>
                <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
                <a href="index.php">トップへ戻る</a>
            </div>
        </div>
    </section>

        <?php require_once('footer.php') ?>
    </body>
</html>