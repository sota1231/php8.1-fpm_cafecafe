window.onload = function(){

// クリックイベント
const humbergar = document.getElementById("humbergar");
const nav = document.getElementById('sp_nav_motion');

// nav
humbergar.addEventListener('click', function(){
    nav.classList.toggle('nav-open');
})

 
// signinの表示と背景を暗くする、ナビを消す処理
const signin = document.getElementsByClassName('signin');
const overlay = document.getElementById('overlay');
const signinBox = document.getElementById('signin_box');

signin[0].addEventListener('click', function(){
    overlay.classList.toggle('black');
    signinBox.classList.toggle('signin-open');
    nav.classList.remove('nav-open');
})
signin[1].addEventListener('click', function(){
    overlay.classList.toggle('black');
    signinBox.classList.toggle('signin-open');
    nav.classList.remove('nav-open');
})

// siginが表示されている状態で別のところをクリックしたらクラスを消す処理

overlay.addEventListener('click',function(){
    let hasClass = signinBox.classList.contains('signin-open');
    if(hasClass == true){
        overlay.classList.remove('black');
        signinBox.classList.remove('signin-open');
    }
})
}

// JS バリデーション
function subForm() {
    // アラート用配列
    let aaa= [];
    $aaa=[];
    //変数の定義
    const num =/^[0-9]+$/;
    // var pattern = /^[a-zA-Z0-9_+-]+(\.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/;
    const pattern = /^[a-z]+[a-z0-9_-]+@[a-z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/i;

    var name = document.getElementById("name");
    var kana = document.getElementById("kana");
    var tel = document.getElementById("tel");
    var email = document.getElementById("email");
    var body = document.getElementById("body");
    var isRight = true;
  
    // 名前　10文字以内か？ 空ではないか？
    if(name.value.length <= 11 && name.value.length >=1) {
    } else {
        aaa.push("氏名は必須入力です。10文字以内にご入力ください");
        isRight = false;
    }

    // 仮名　10文字以内か？ 空ではないか？
    if(kana.value.length <= 11 && kana.value.length >=1) {
    } else {
        aaa.push("フリガナは必須入力です。10文字以内にご入力ください");
        isRight = false;
    }
    // //電話番号　数値かどうか？（0~9か？）
    if(num.test(tel.value) && tel.value.length >=1 ) {
    } else {
        aaa.push("電話番号は0~9の半角英数字で入力してください。");
        isRight = false;
    }
    // //メールアドレス　空か？メールアドレスか？
    if (pattern.test(email.value) && email.value.length >=1){
    } else {
      aaa.push("メールアドレスは正しくご記入ください。");
      isRight = false;
    }
    //問い合わせ
    if(body.value.length <= 0) {
      aaa.push("お問い合わせ内容は必須内容です。");
      isRight = false;
    } else {
    }
    // aaa.forEach(function(data) {
    //     alert(data);
    // });

    if(!isRight){
        alert(`氏名は必須入力です。10文字以内にご入力ください。
フリガナは必須入力です。10文字以内にご入力ください。
電話番号は0~9の半角英数字で入力してください。
メールアドレスは正しくご記入ください。
お問い合わせ内容は必須内容です。
`)
    }
    return isRight;
}


// 削除 alert
function checkDelete(){
    const result = confirm('本当に削除しますか？');

    if( result ) {
       return true;
    }else {
       return false;
    }
    console.log('aaa');
}
