window.onload = function(){
// スクロールイベント
const targetElement = document.getElementsByClassName("header");
document.addEventListener("scroll" , function(){
      const getElementDistance = targetElement[0].getBoundingClientRect().top;
      // console.log(getElementDistance);
      scrollpos = window.scrollY;
      // 最初プラス。上とくっつくと０。要素との距離で制御
      if(-1 > getElementDistance){
          targetElement[0].classList.add("motion");
          nav.classList.add('nav-top');

      // スクロールの距離が50以下ならクラスを消す（topが０でfexedなので距離で制御できない。）
      }else if(50 > scrollpos){
        targetElement[0].classList.remove("motion");
        nav.classList.remove('nav-top');
    }    
})




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