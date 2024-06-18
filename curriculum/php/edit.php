<?php 

// ゲットでアクセスされてidをもっていれば
if (isset($_GET['id'])) {

    // 編集したいidを更新時消えないように取得
    $aaa=$_GET['id'];

    try {

        // 接続処理
        require_once('db.php');

        $dbh = new PDO($dsn, $user, $pass);

        // SELECT文を発行
        $sql = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_OBJ); // 1件のレコードを取得
        // 接続切断
        $dbh = null;

    } catch (PDOException $e) {
        print $e->getMessage() . "<br/>";
        die();
    }

}


session_start();
// unset($_SESSION['form']);
$error=[];
$error['name']='';
$error['kana']='';
$error['tel']='';
$error['email']='';
$error['tel']='';
$error['body']='';

// アクセスがPOSTだったら
if($_SERVER['REQUEST_METHOD']==='POST'){
    // 悪徳なメールを無力化
    $post = filter_input_array(INPUT_POST,  FILTER_SANITIZE_ADD_SLASHES);
    // FILTER_SANITIZE_STRING
    // FILTER_FLAG_NO_ENCODE_QUOTES

    // フォームの送信時に空かどうかなどチェック
    if($post['name']===''){
        $error['name']='blank';
    }else{
        $error['name']='';
    }

    if($post['kana']===''){
        $error['kana']='blank';
    }else{
        $error['kana']='';
    }

    if($post['tel']===''){
        $error['tel']='blank';
    }else if(preg_match('/^0[0-9]{9,10}$/',$post['tel'])){
        $error['tel']='';
    }else{
        $error['tel']='blank';
    }

    if($post['email']===''){
        $error['email']='blank';
    }elseif(!filter_var($post['email'],FILTER_VALIDATE_EMAIL)){  //メールの書式になっているか
        $error['email']='email';
    }else{
        $error['email']='';
    }

    if($post['body']===''){
        $error['body']='blank';
    }else{
        $error['body']='';
    }

    // すべてのキーにエラーがなければ
    if($error['name']=="" && $error['kana']=="" && $error['email']=="" &&$error['tel']=="" && $error['body']=="" ){
        
        // アップデート処理
        try {
            // 接続処理
            require_once('db.php');

            $dbh = new PDO($dsn, $user, $pass);
 
            // UPDATE文を発行
            $id = intval($aaa); // UPDATEするレコードのID
 
            $name = isset($post['name']) ? $post['name'] : '';
            $kana = isset($post['kana']) ? $post['kana'] : 0;
            $tel = isset($post['tel']) ? $post['tel'] : '';
            $email = isset($post['email']) ? $post['email'] : '';
            $body = isset($post['body']) ? $post['body'] : 0;
            // $created_add =  isset($post['']) ? $post['body'] : 0;
            
 
            $sql = "UPDATE contacts SET name = :name, kana = :kana, tel = :tel, email = :email, body = :body  WHERE id = :id";
            $stmt = $dbh->prepare($sql);
 
            // 連想配列でバインド
            $stmt->execute([":name" => $name, ":kana" => $kana, ":tel" => $tel, ":email" => $email, ":body" => $body, ":id" => $id ]); 
 
            // print("レコードを更新しました<br>");
            // print('<a href="list.php">一覧表示へ</a>');
 
            // 接続切断
            $dbh = null;
 
 
        } catch (PDOException $e) {
            print $e->getMessage() . "<br/>";
            die();
        }

        header('Location: contact.php');
        exit();
    }
    
}else{

    // getで入ってきた際にセッションに値が入っていたら[戻るボタン]
    if(isset($_SESSION['form'])){
        $post = $_SESSION['form'];
    
        $error['name']='';
        $error['kana']='';
        $error['tel']='';
        $error['email']='';
        $error['tel']='';
        $error['body']='';

    }else{
        // getで入ってきてなおかつセッションに情報がなければ
        $post['name']='';
        $post['kana']='';
        $post['tel']='';
        $post['email']='';
        $post['body']='';
    }
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
  <!-- <script src="index.js"></script> -->
  <script src="https://kit.fontawesome.com/5f1eeb6c2d.js" crossorigin="anonymous"></script>
  <script src="index-1.js"></script>
</head>
<body>
    <?php require_once('header.php') ?>

    <section class="section">
        <div class="contact_box">
            <h2>編集画面</h2>
			<form action="" method="POST" onsubmit="return subForm()">
                <h3>下記の項目をご記入の上送信ボタンを押してください</h3>
                <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
                <p><span class="required">*</span>は必須項目となります。</p>
                <dl>
                <!-- name -->
                    <dt><label for="name">氏名</label><span class="required">*</span></dt>
                    <?php if($error['name']==='blank') :?>
                        <dd class="error">氏名は必須入力です。10文字以内でご入力ください。</dd>
                    <?php endif ?>
                    <dd><input type="text" name="name" id="name" placeholder="山田太郎" value="<?php echo $record->name;?>"></dd>

                <!-- kana -->
                    <dt><label for="kana">フリガナ</label><span class="required">*</span></dt>
                    <?php if($error['kana']==='blank') :?>
                        <dd class="error">フリナガは必須入力です。10文字以内でご入力ください。</dd>
                    <?php endif ?>
                    <dd><input type="text" name="kana" id="kana" placeholder="ヤマダタロウ" value="<?php echo $record->kana;?>"></dd>
                    

                <!-- tel -->
                    <dt><label for="tel">電話番号</label></dt>
                    <?php if($error['tel']==='blank') :?>
                        <dd class="error">電話番号は0-9の数字のみでご入力ください。</dd>
                    <?php endif ?>
                    <dd><input type="text" name="tel" id="tel" placeholder="09012345678" value="<?php echo $record->tel;?>"></dd>
                    
                    
                <!-- email -->
                    <dt><label for="email">メールアドレス</label><span class="required">*</span></dt>
                    <!-- <dd class="error">メールアドレスは正しくご入力ください。</dd> -->
                    <?php if($error['email']==='blank') :?>
                        <dd class="error">メールアドレスは必須入力です。</dd>
                    <?php endif ?>
                    <?php if($error['email']==='email') :?>
                        <dd class="error">メールアドレスは正しくご入力ください。</dd>
                    <?php endif ?>
                    <dd><input type="text" name="email" id="email" placeholder="test@test.co.jp" value="<?php echo $record->email;?>"></dd>
                    
                </dl>

                <!-- body -->
                <h3><label for="body">お問い合わせ内容をご記入ください<span class="required">*</span></label></h3>
                <dl class="body">
                    <!-- <dd class="error">お問い合わせ内容は必須入力です。</dd> -->
                    <?php if($error['body']==='blank') :?>
                        <dd class="error">お問い合わせ内容は必須入力です。</dd>
                    <?php endif ?>
                    
                    <dd><textarea name="body" id="body"><?php echo $record->body ;?></textarea></dd>
                    <dd><button type="submit">更　新</button></dd>
                </dl>
			</form>
        </div>
    </section>


    <!-- データ一覧 -->
    <!-- <div class="db">
        <table>
            <tr>
              <th>id</th>
              <th>名前</th>
              <th>フリガナ</th>
              <th>電話番号</th>
              <th>メールアドレス</th>
              <th>お問い合わせ内容</th>
              <th></th>
              <th></th>
            </tr>
            <?php
                foreach($result as $r){
                    echo '<tr>';
                    echo '<td>'.$r['id'].'</td>';
                    echo '<td>'.$r['name'].'</td>';
                    echo '<td>'.$r['kana'].'</td>';
                    echo '<td>'.$r['tel'].'</td>';
                    echo '<td>'.$r['email'].'</td>';
                    echo '<td>'.$r['body'].'</td>';
                    echo '<td><a>編集</a></td>';
                    echo '<td><a>削除</a></td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </div> -->
    



    <?php require_once('footer.php') ?>
</body>