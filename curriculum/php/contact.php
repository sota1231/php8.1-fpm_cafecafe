<?php 

// db
function connect_db(){
    //ホスト名、データベース名、文字コードの３つを定義する
    require_once('db.php');

    try{
        //上のデータを引数に入れて、PDOインスタンスを作成,DBにアクセス
        $pdo = new PDO($dsn, $user, $pass);

    }catch(PDOException $e){
        echo $e->getMessage();
    }

    //PDOインスタンスを返す
    return $pdo;
}

//データベースと接続して、PDOインスタンスを取得
$pdo = connect_db();

//実行したいSQLを準備する
$sql = 'SELECT * FROM contacts';
$stmt = $pdo->prepare($sql);

//SQLを実行
$stmt->execute();

//データベースの値を取得
$result = $stmt->fetchall();


function sanitize_br($str){
    return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
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
    $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

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

    // すべてのキーにエラーがなければセッションに入れて次のページへ
    if($error['name']=="" && $error['kana']=="" && $error['email']=="" &&$error['tel']=="" && $error['body']=="" ){
        $_SESSION['form']=$post;
        header('Location: confirm.php');
        exit();
    }
    
// アクセスがPOST以外なら
}else{

    // getで入ってきた際にセッションに値が入っていたら※[戻るボタン]
    if(isset($_SESSION['form'])){
        $post = $_SESSION['form'];
    
        $error['name']='';
        $error['kana']='';
        $error['tel']='';
        $error['email']='';
        $error['tel']='';
        $error['body']='';

        // getでアクセスしたときにid持っていればidのレコードを削除
    }else if(isset($_GET['id'])){
        try {
            $id = intval($_GET['id']);
    
            // 接続処理
            require('db.php');

            $dbh = new PDO($dsn, $user, $pass);
    
            // SELECT文を発行
            $sql = "DELETE FROM contacts WHERE id = :id";
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
            <h2>お問い合わせ</h2>
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
                    <dd><input type="text" name="name" id="name" placeholder="山田太郎" value="<?php echo htmlspecialchars($post['name']);?>"></dd>

                <!-- kana -->
                    <dt><label for="kana">フリガナ</label><span class="required">*</span></dt>
                    <?php if($error['kana']==='blank') :?>
                        <dd class="error">フリナガは必須入力です。10文字以内でご入力ください。</dd>
                    <?php endif ?>
                    <dd><input type="text" name="kana" id="kana" placeholder="ヤマダタロウ" value="<?php echo htmlspecialchars($post['kana']);?>"></dd>
                    

                <!-- tel -->
                    <dt><label for="tel">電話番号</label></dt>
                    <?php if($error['tel']==='blank') :?>
                        <dd class="error">電話番号は0-9の数字のみでご入力ください。</dd>
                    <?php endif ?>
                    <dd><input type="text" name="tel" id="tel" placeholder="09012345678" value="<?php echo htmlspecialchars($post['tel']);?>"></dd>
                    
                    
                <!-- email -->
                    <dt><label for="email">メールアドレス</label><span class="required">*</span></dt>
                    <!-- <dd class="error">メールアドレスは正しくご入力ください。</dd> -->
                    <?php if($error['email']==='blank') :?>
                        <dd class="error">メールアドレスは必須入力です。</dd>
                    <?php endif ?>
                    <?php if($error['email']==='email') :?>
                        <dd class="error">メールアドレスは正しくご入力ください。</dd>
                    <?php endif ?>
                    <dd><input type="text" name="email" id="email" placeholder="test@test.co.jp" value="<?php echo htmlspecialchars($post['email']);?>"></dd>
                    
                </dl>

                <!-- body -->
                <h3><label for="body">お問い合わせ内容をご記入ください<span class="required">*</span></label></h3>
                <dl class="body">
                    <!-- <dd class="error">お問い合わせ内容は必須入力です。</dd> -->
                    <?php if($error['body']==='blank') :?>
                        <dd class="error">お問い合わせ内容は必須入力です。</dd>
                    <?php endif ?>
                    
                    <dd><textarea name="body" id="body"><?php echo htmlspecialchars($post['body']);?></textarea></dd>
                    <dd><button type="submit">送　信</button></dd>
                </dl>
			</form>
        </div>
    </section>


    <!-- データ一覧 -->
    <div class="db">
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
            <?php foreach($result as $r){ ?>
                <tr>
                    <td><?php echo $r['id'] ?></td>
                    <td><?php echo $r['name']?></td>
                    <td><?php echo $r['kana']?></td>
                    <td><?php echo $r['tel']?></td>
                    <td><?php echo $r['email']?></td>
                    <td><?php echo sanitize_br($r['body'])?></td>
                    <td><a href="edit.php?id=<?php echo $r['id']?>">編集</a></td>
                    <td><a href="contact.php?id=<?php echo $r['id']?>" onClick = "return checkDelete()">削除</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    



    <?php require_once('footer.php') ?>
</body>