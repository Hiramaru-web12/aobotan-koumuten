// ローディング
$(function(){
const webStorage = function () {
  if (sessionStorage.getItem('visit')) {
    $(".loading").css("display", "none");
    } else {
      sessionStorage.setItem('visit', 'true'); 
      $(".loading").delay(1500).fadeOut('slow');
      }
      }
      webStorage();
       
  // ヘッダートグル
  $(".l-header__nav").click(function() {
    $(this).next().slideToggle();
    $(this).children('.l-header__nav__icon').toggleClass('is-open');
   });

  //ドロワー開閉
  $('.l-drawer__icon').click(function(e) {
    e.preventDefault();
    $('.l-drawer__icon, .l-drawer').toggleClass('is-active');
    return false;
  });

  
  $('.l-drawer__nav li a[href^="#"]').click(function(e) {
    e.preventDefault();
    $('.l-drawer__icon, .l-drawer').removeClass('is-active');
    
    return false;  
  });

  // スムーズスクロール
  $('a[href^="#"]').click(function() {
    let speed = 800;
    let id = $(this).attr('href');
    let target = $("#" == id ? "html" : id);
    let position = $(target).offset().top;
    $('html, body').animate(
      {
        scrollTop: position +   
        (window.innerWidth < 768 ? -120 : 0)
      },
      speed, "swing"
      );
      return false;
    });

  // swiper
 const swiper = new Swiper('.swiper', {
  // Optional parameters
  loop: true,
  loopAdditionalSlides: 1,
  spaceBetween: "4%",
  autoplay: {
    delay: 0,
    disableOnInteraction: false,
  },
  speed: 5000,
  grabCursor: true,
  breakpoints: {
    0: {
      slidesPerView: 1.5,
    },
    700: {
      slidesPerView: 'auto',
    },    
  },
 });
 
 //radioボタン
 var inputs = $('input');
 var checked = inputs.filter(':checked').val();
 
 inputs.click(function(){
     if($(this).val() === checked) {
         $(this).prop('checked', false);
         checked = '';
     } else {
         $(this).prop('checked', true);
         checked = $(this).val();
     }
 });

 // formの入力確認
 let $submit = $('#js-submit');
 var $require = $('#js-form input, .js-require');
 
 $require.on('change',function(){
   $submit.prop('disabled', false);
   
   if(
     $('#js-form input[type="radio"]').val() !== "" &&
     $('#js-form input[type="text"]').val() !== "" &&
     $('#js-form input[type="email"]').val() !== "" &&
     $('#js-form input[type="checkbox"]').prop('checked') === true
   )
   {
     //必須項目が入力されたとき
     $submit.prop('disabled', false);
     // $submit.addClass('.is-active'); 
   } else {
     //入力されていないとき
     $submit.prop('disabled', true);
     // $submit.removeClass('.is-active'); 
   }
 });

// micromodal
  MicroModal.init({
  openClass: 'is-open',
  disableScroll: true,
  disableFocus: true,
  awaitOpenAnimation: true,
  awaitCloseAnimation: true,
});
 });






