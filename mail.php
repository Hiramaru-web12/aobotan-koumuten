<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?php //error_reporting(E_ALL | E_STRICT);
##-----------------------------------------------------------------------------------------------------------------##
#
#  PHPメールプログラム　フリー版 ver2.0.4 最終更新日2024/05/24
#　改造や改変は自己責任で行ってください。
#	
#  HP: http://www.php-factory.net/
#
#  重要！！サイトでチェックボックスを使用する場合のみですが。。。
#  チェックボックスを使用する場合はinputタグに記述するname属性の値を必ず配列の形にしてください。
#  例　name="当サイトをしったきっかけ[]"  として下さい。
#  nameの値の最後に[と]を付ける。じゃないと複数の値を取得できません！
#
##-----------------------------------------------------------------------------------------------------------------##
if (version_compare(PHP_VERSION, '5.1.0', '>=')) {//PHP5.1.0以上の場合のみタイムゾーンを定義
	date_default_timezone_set('Asia/Tokyo');//タイムゾーンの設定（日本以外の場合には適宜設定ください）
}
/*-------------------------------------------------------------------------------------------------------------------
* ★以下設定時の注意点　
* ・値（=の後）は数字以外の文字列（一部を除く）はダブルクオーテーション「"」、または「'」で囲んでいます。
* ・これをを外したり削除したりしないでください。後ろのセミコロン「;」も削除しないください。
* ・また先頭に「$」が付いた文字列は変更しないでください。数字の1または0で設定しているものは必ず半角数字で設定下さい。
* ・メールアドレスのname属性の値が「Email」ではない場合、以下必須設定箇所の「$Email」の値も変更下さい。
* ・name属性の値に半角スペースは使用できません。
*以上のことを間違えてしまうとプログラムが動作しなくなりますので注意下さい。
-------------------------------------------------------------------------------------------------------------------*/


//---------------------------　必須設定　必ず設定してください　-----------------------

//サイトのトップページのURL　※デフォルトでは送信完了後に「トップページへ戻る」ボタンが表示され、そのリンク先です。
$site_top = "https://hiramaru.site/aobotan-koumuten/";

//管理者のメールアドレス（送信先） ※メールを受け取るメールアドレス(複数指定する場合は「,」で区切ってください 例 $to = "aa@aa.aa,bb@bb.bb";)
$to = "meg96.mcr2st@gmail.com";

//送信元（差出人）メールアドレス（管理者宛て、及びユーザー宛メールの送信元（差出人）メールアドレスです）
//必ず実在するメールアドレスでかつ出来る限り設置先サイトのドメインと同じドメインのメールアドレスとしてください（でないと「なりすまし」扱いされます）
//管理者宛てメールの返信先（reply）はユーザーが入力したメールアドレスになりますので返信時はユーザーのメールアドレスが送信先に設定されます）
$from = "megweb12@hiramaru.site";

//管理者宛メールの送信元（差出人）にユーザーが入力したメールアドレスを表示する(する=1, しない=0)
//ユーザーのメールアドレスを含めることでメーラー上で管理しやすくなる機能です。
//例 example@gmail.com <from@sample.jp>（example@gmail.comがユーザーメールアドレス、from@sample.jpが↑の$fromで設定したメールアドレスです）
$from_add = 0;

//フォームのメールアドレス入力箇所のname属性の値（name="○○"　の○○部分）
$Email = "メールアドレス";
//---------------------------　必須設定　ここまで　------------------------------------


//---------------------------　セキュリティ、スパム防止のための設定　------------------------------------

//スパム防止のためのリファラチェック（フォーム側とこのファイルが同一ドメインであるかどうかのチェック）(する=1, しない=0)
//※有効にするにはこのファイルとフォームのページが同一ドメイン内にある必要があります
$Referer_check = 1;

//リファラチェックを「する」場合のドメイン ※設置するサイトのドメインを指定して下さい。
//もしこの設定が間違っている場合は送信テストですぐに気付けます。
$Referer_check_domain = "hiramaru.site";

/*セッションによるワンタイムトークン（CSRF対策、及びスパム防止）(する=1, しない=0)
※ただし、この機能を使う場合は↓の送信確認画面の表示が必須です。（デフォルトではON（1）になっています）
※【重要】ガラケーは機種によってはクッキーが使えないためガラケーの利用も想定してる場合は「0」（OFF）にして下さい（PC、スマホは問題ないです）*/
$useToken = 1;
//---------------------------　セキュリティ、スパム防止のための設定　ここまで　------------------------------------


//---------------------- 任意設定　以下は必要に応じて設定してください ------------------------

// Bccで送るメールアドレス(複数指定する場合は「,」で区切ってください 例 $BccMail = "aa@aa.aa,bb@bb.bb";)
$BccMail = "";

// 管理者宛に送信されるメールのタイトル（件名）
$subject = "青牡丹工務店ホームページのお問い合わせ";

// 送信確認画面の表示(する=1, しない=0)
$confirmDsp = 0;

// 送信完了後に自動的に指定のページ(サンクスページなど)に移動する(する=1, しない=0)
// CV率を解析したい場合などはサンクスページを別途用意し、URLをこの下の項目で指定してください。
// 0にすると、デフォルトの送信完了画面が表示されます。
$jumpPage = 1;

// 送信完了後に表示するページURL（上記で1を設定した場合のみ）※httpから始まるURLで指定ください。（相対パスでも基本的には問題ないです）
$thanksPage = "https://hiramaru.site/aobotan-koumuten/thanks/index.html";

// 必須入力項目を設定する(する=1, しない=0)
$requireCheck = 0;

/* 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
値はシングルクォーテーションで囲み、複数の場合はカンマで区切ってください。フォーム側と順番を合わせると良いです。 
配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。*/
$require = array('お問い合わせの種類','お名前','メールアドレス','住所','確認');


//----------------------------------------------------------------------
//  自動返信メール設定(START)
//----------------------------------------------------------------------

// 差出人に送信内容確認メール（自動返信メール）を送る(送る=1, 送らない=0)
// 送る場合は、フォーム側のメール入力欄のname属性の値が上記「$Email」で指定した値と同じである必要があります
$remail = 1;

//自動返信メールの送信者欄に表示される名前　※あなたの名前や会社名など（もし自動返信メールの送信者名が文字化けする場合ここは空にしてください）
$refrom_name = "青牡丹工務店";

// 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
$re_subject = "送信ありがとうございました";

//フォーム側の「名前」箇所のname属性の値　※自動返信メールの「○○様」の表示で使用します。
//指定しない、または存在しない場合は、○○様と表示されないだけです。あえて無効にしてもOK
$dsp_name = 'お名前';

//自動返信メールの冒頭の文言 ※日本語部分のみ変更可
$remail_text = <<< TEXT

お問い合わせありがとうございました。
早急にご返信致しますので今しばらくお待ちください。

送信内容は以下になります。

TEXT;


//自動返信メールに署名（フッター）を表示(する=1, しない=0)※管理者宛にも表示されます。
$mailFooterDsp = 1;

//上記で「1」を選択時に表示する署名（フッター）（FOOTER～FOOTER;の間に記述してください）
$mailSignature = <<< FOOTER

──────────────────────
株式会社　青牡丹工務店　谷垣周平
〒000-0000 大阪府大阪市中央区北区南町5-6-7
URL: https://hiramaru.site/aobotan-koumuten
──────────────────────

FOOTER;


//----------------------------------------------------------------------
//  自動返信メール設定(END)
//----------------------------------------------------------------------

//メールアドレスの形式チェックを行うかどうか。(する=1, しない=0)
//※デフォルトは「する」。特に理由がなければ変更しないで下さい。メール入力欄のname属性の値が上記「$Email」で指定した値である必要があります。
$mail_check = 1;

//全角英数字→半角変換を行うかどうか。(する=1, しない=0)
$hankaku = 0;

//全角英数字→半角変換を行う項目のname属性の値（name="○○"の「○○」部分）
//※複数の場合にはカンマで区切って下さい。（上記で「1」を指定した場合のみ有効）
//配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。
$hankaku_array = array('電話番号','金額');

//-fオプションによるエンベロープFrom（Return-Path）の設定(する=1, しない=0)　
//※宛先不明（間違いなどで存在しないアドレス）の場合に 管理者宛に「Mail Delivery System」から「Undelivered Mail Returned to Sender」というメールが届きます。
//サーバーによっては稀にこの設定が必須の場合もあります。
//設置サーバーでPHPがセーフモードで動作している場合は使用できませんので送信時にエラーが出たりメールが届かない場合は「0」（OFF）として下さい。
$use_envelope = 0;

//機種依存文字の変換
/*たとえば㈱（かっこ株）や①（丸1）、その他特殊な記号や特殊な漢字などは変換できずに「？」と表示されます。それを回避するための機能です。
確認画面表示時に置換処理されます。「変換前の文字」が「変換後の文字」に変換され、送信メール内でも変換された状態で送信されます。（たとえば「㈱」の場合、「（株）」に変換されます） 
必要に応じて自由に追加して下さい。ただし、変換前の文字と変換後の文字の順番と数は必ず合わせる必要がありますのでご注意下さい。*/

//変換前の文字
$replaceStr['before'] = array('①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','№','㈲','㈱','髙');
//変換後の文字
$replaceStr['after'] = array('(1)','(2)','(3)','(4)','(5)','(6)','(7)','(8)','(9)','(10)','No.','（有）','（株）','高');

//------------------------------- 任意設定ここまで ---------------------------------------------


// 以下の変更は知識のある方のみ自己責任でお願いします。

//----------------------------------------------------------------------
//  関数実行、変数初期化
//----------------------------------------------------------------------
//トークンチェック用のセッションスタート
if($useToken == 1 && $confirmDsp == 1){
	session_name('PHPMAILFORMSYSTEM');
	session_start();
}
$encode = "UTF-8";//このファイルの文字コード定義（変更不可）
if(isset($_GET)) $_GET = sanitize($_GET);//NULLバイト除去//
if(isset($_POST)) $_POST = sanitize($_POST);//NULLバイト除去//
if(isset($_COOKIE)) $_COOKIE = sanitize($_COOKIE);//NULLバイト除去//
if($encode == 'SJIS') $_POST = sjisReplace($_POST,$encode);//Shift-JISの場合に誤変換文字の置換実行
$funcRefererCheck = refererCheck($Referer_check,$Referer_check_domain);//リファラチェック実行

//変数初期化
$sendmail = 0;
$empty_flag = 0;
$post_mail = '';
$errm ='';
$header ='';

if($requireCheck == 1) {
	$requireResArray = requireCheck($require);//必須チェック実行し返り値を受け取る
	$errm = $requireResArray['errm'];
	$empty_flag = $requireResArray['empty_flag'];
}
//メールアドレスチェック
if(empty($errm)){
	foreach($_POST as $key=>$val) {
		if($val == "confirm_submit") $sendmail = 1;
		if($key == $Email) $post_mail = h($val);
		if($key == $Email && $mail_check == 1 && !empty($val)){
			if(!checkMail($val)){
				$errm .= "<p class=\"error_messe\">【".$key."】はメールアドレスの形式が正しくありません。</p>\n";
				$empty_flag = 1;
			}
		}
	}
}
  
if(($confirmDsp == 0 || $sendmail == 1) && $empty_flag != 1){
	
	//トークンチェック（CSRF対策）※確認画面がONの場合のみ実施
	if($useToken == 1 && $confirmDsp == 1){
		if(empty($_SESSION['mailform_token']) || ($_SESSION['mailform_token'] !== $_POST['mailform_token'])){
			exit('ページ遷移が不正です');
		}
		if(isset($_SESSION['mailform_token'])) unset($_SESSION['mailform_token']);//トークン破棄
		if(isset($_POST['mailform_token'])) unset($_POST['mailform_token']);//トークン破棄
	}
	
	//差出人に届くメールをセット
	if($remail == 1) {
		$userBody = mailToUser($_POST,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode);
		$reheader = userHeader($refrom_name,$from,$encode);
		$re_subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($re_subject,"JIS",$encode))."?=";
	}
	//管理者宛に届くメールをセット
	$adminBody = mailToAdmin($_POST,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp);
	$header = adminHeader($post_mail,$BccMail);
	$subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($subject,"JIS",$encode))."?=";
	
	//-fオプションによるエンベロープFrom（Return-Path）の設定(safe_modeがOFFの場合かつ上記設定がONの場合のみ実施)
	if($use_envelope == 0){
		mail($to,$subject,$adminBody,$header);
		if($remail == 1 && !empty($post_mail)) mail($post_mail,$re_subject,$userBody,$reheader);
	}else{
		mail($to,$subject,$adminBody,$header,'-f'.$from);
		if($remail == 1 && !empty($post_mail)) mail($post_mail,$re_subject,$userBody,$reheader,'-f'.$from);
	}
}
else if($confirmDsp == 1){ 

/*　▼▼▼送信確認画面のレイアウト※編集可　オリジナルのデザインも適用可能▼▼▼　*/
?>

<!DOCTYPE HTML>
<html lang="ja">

<!-- ▲ Headerやその他コンテンツなど　※自由に編集可 ▲-->

<!-- ▼************ 送信内容表示部　※編集は自己責任で ************ ▼-->
<div id="formWrap">
  <?php if($empty_flag == 1){ ?>
  <div align="center">
    <h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
    <?php echo $errm; ?><br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()">
  </div>
  <?php }else{ ?>
  <h3>確認画面</h3>
  <p align="center">以下の内容で間違いがなければ、「送信する」ボタンを押してください。</p>
  <form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="POST">
    <table class="formTable">
      <?php echo confirmOutput($_POST);//入力内容を表示?>
    </table>
    <p align="center"><input type="hidden" name="mail_set" value="confirm_submit">
      <input type="hidden" name="httpReferer" value="<?php echo h($_SERVER['HTTP_REFERER']);?>">
      <input type="submit" value="　送信する　">
      <input type="button" value="前画面に戻る" onClick="history.back()">
    </p>
  </form>
  <?php } ?>
</div><!-- /formWrap -->
<!-- ▲ *********** 送信内容確認部　※編集は自己責任で ************ ▲-->

<!-- ▼ Footerその他コンテンツなど　※編集可 ▼-->
</body>

</html>
<?php
/* ▲▲▲送信確認画面のレイアウト　※オリジナルのデザインも適用可能▲▲▲　*/
}

if(($jumpPage == 0 && $sendmail == 1) || ($jumpPage == 0 && ($confirmDsp == 0 && $sendmail == 0))) { 

/* ▼▼▼送信完了画面のレイアウト　編集可 ※送信完了後に指定のページに移動しない場合のみ表示▼▼▼　*/
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: https://ogp.me/ns#">
  <!-- ここからmetaタグの記述 -->
  <meta property="og:url" content="https://hiramaru.site/aobotan-koumuten/thanks/" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="青牡丹工務店 お問い合わせ送信完了" />
  <meta property="og:description"
    content="大阪府大阪市に拠点をおく青牡丹工務店は、丁寧な家造りで理想を現実にします。住宅建築からリフォームまで、高品質なサービスと経験豊富なスタッフがお客様のニーズに応えます。お気軽にご相談ください。" />
  <meta property="og:site_name" content="青牡丹工務店" />
  <meta property="og:image" content="https://hiramaru.site/aobotan-koumuten/img/ogp.png" />
  <!-- ここからTwitterカードの記述 -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@hrmr_crt96" />
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="icon" href="../img/favicon.svg" sizes="any" type="img/svg+xml" />
  <link rel="apple-touch-icon" href="../img/apple-touch-icon.png" />
  <!-- 180x180px -->
  <meta name="viewport" content="width=device-width, initiap-scale=1.0" />
  <meta name="robots" content="noindex , nofollow" />
  <title>
    青牡丹工務店 | 大阪市北区の住宅建築・リフォーム・公共事業なら青牡丹工務店
  </title>
  <meta name="description"
    content="大阪府大阪市に拠点をおく青牡丹工務店は、丁寧な家造りで理想を現実にします。住宅建築からリフォームまで、高品質なサービスと経験豊富なスタッフがお客様のニーズに応えます。お気軽にご相談ください。" />
  <link rel="shortcut icon" href="../img/favicon.ico" />
  <!-- google font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@400;700&display=swap" rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <!-- swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <!-- original -->
  <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
  <header class="l-header">
    <div class="l-drawer__icon">
      <div class="l-drawer__bars">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
    <div class="l-header__inner">
      <h1 class="l-header__logo">
        <a href="../"><img src="../img/logo-pc.png" alt="青牡丹工務店" srcset="" /></a>
      </h1>
      <div class="l-header__wrap is-pc">
        <nav class="l-header__nav">
          私達について
          <div class="l-header__nav__icon"></div>
        </nav>
        <ul class="l-header__nav__list">
          <li><a href="../about#js-concept">経営理念</a></li>
          <li><a href="../about#js-company">会社概要</a></li>
          <li><a href="../about#js-safety">安全への取り組み</a></li>
        </ul>
        <nav class="l-header__nav">
          事業内容
          <div class="l-header__nav__icon"></div>
        </nav>
        <ul class="l-header__nav__list">
          <li>
            <a href="../service/#js-service-1">住宅建築・リフォーム</a>
          </li>
          <li>
            <a href="../service/#js-service-2">法人新築・リフォーム</a>
          </li>
          <li><a href="../service#js-service-3">公共工事</a></li>
        </ul>
        <nav class="l-header__nav"><a href="../#js-news">お知らせ</a></nav>
        <div></div>
        <nav class="l-header__nav">
          <span class="c-header__mail-icon"><img src="../img/mail-icon.svg" alt="メールアイコン" /></span><a
            href="../contact">お問い合わせ</a>
        </nav>
      </div>
    </div>

    <!-- ドロワーメニュー -->
    <div class="l-drawer">
      <div class="l-drawer__logo">
        <img src="../img/logo-pc.png" alt="青牡丹工務店" />
      </div>
      <div class="l-drawer__inner">
        <nav class="l-drawer__nav">
          <ul>
            <li><a href="../">トップページ</a></li>
            <li><a href="../about">私たちについて</a></li>
            <li><a href="../about/#js-company">会社概要</a></li>
            <li><a href="../about/#js-ceo">代表あいさつ</a></li>
            <li><a href="../about/#js-safety">安全への取り組み</a></li>
            <li><a href="../about/#js-about">事業内容</a></li>
            <li>
              <a href="../service/#js-service-1">住宅建築・リフォーム</a>
            </li>
            <li>
              <a href="../service/#js-service-2">法人新築・リフォーム</a>
            </li>
            <li><a href="../service/#js-service-3">公共工事</a></li>
            <li><a href="../#js-news">お知らせ</a></li>
            <li>
              <a href="../contact"><span class="c-mail-icon"><img src="../img/mail-icon.svg" alt="メールアイコン" /></span>
                お問い合わせ</a>
            </li>
          </ul>
        </nav>
        <p class="l-drawer__copyright">
          <small lang="en">©︎AOBOTAN INC.</small>
        </p>
      </div>
    </div>
  </header>

  <!-- 下層ページファーストビュー-->
  <div class="p-contact-page__fv c-fv">
    <div class="p-contact-page__fv__container c-fv__container">
      <div class="p-contact-page__fv-img"></div>
    </div>
  </div>

  <!-- お問い合わせ -->
  <section class="c-page-header">
    <div class="c-page-header__inner c-inner">
      <h1 class="c-page-header__title">送信完了</h1>
      <p class="c-page-header__text">
        お問い合わせありがとうございました。<br />
        後日担当者より返信させていただきますので、お待ち下さいませ。
      </p>
      <div class="p-contact__wrap">
        <div class="p-contact-tel__item">
          <p class="p-contact__message">お電話も受け付けています</p>
          <a href="tel:000-0000-0000">
            <div class="row-first">0000-000-0000</div>
          </a>
          <p class="row-second">営業時間10:00-20:00</p>
        </div>
        <div class="p-contact-status__container">
          <div class="p-contact-status__item">
            <span>01</span>
            <p>入力</p>
          </div>
          <span></span>
          <div class="p-contact-status__item u-current">
            <span>02</span>
            <p>送信完了</p>
          </div>
        </div>
      </div>
    </div>
    <div class="c-page-header__figure">
      <img src="../img/contact_01.png" alt="contact_01" />
    </div>
  </section>

  <section class="p-thanks__inner c-inner">
    <button class="p-toTop">
      <a href="../"> トップページに戻る</a>
    </button>
  </section>

  <section class="l-contact">
    <div class="l-contact__inner inner">
      <h2 class="c-head__title__wrap">
        <div class="c-head__title">お問い合わせ</div>
        <div class="c-head__title-en">CONTACT</div>
      </h2>
      <div class="l-contact__container">
        <div class="l-contact__wrap">
          <div class="l-contact__item">
            <p class="row-first">Tel</p>
            <p class="row-second">お電話</p>
          </div>
          <div class="l-contact__item">
            <a href="tel:000-0000-0000">
              <div class="row-first">0000-000-0000</div>
            </a>
            <p class="row-second">営業時間10:00-20:00</p>
          </div>
          <a href="../contact">
            <button class="c-contact__btn">
              <span class="c-mail-icon"><img src="../img/mail-icon.svg" alt="メールアイコン" /></span>
              メールフォームはこちら
            </button>
          </a>
        </div>
      </div>
      <div class="l-contact__figure">
        <img src="../img/financing_01.png" alt="financing_01" srcset="" />
      </div>
    </div>
  </section>

  <footer class="l-footer">
    <div class="l-footer__inner c-inner">
      <div class="l-footer__container">
        <div class="l-footer__logo">
          <img src="../img/logo-pc.png" alt="青牡丹工務店" />
        </div>
        <div class="l-footer__company">
          <div class="l-footer__row">
            <p class="row-title">〒000-0000</p>
            <p>大阪府大阪市中央区北区南町5-6-7</p>
          </div>
          <div class="l-footer__row u-flex">
            <p class="row-title">営業時間</p>
            <p>10:00~20:00</p>
          </div>
          <div class="l-footer__row u-flex">
            <p class="row-title">定休日</p>
            <p>日曜日、祝日、年末年始（12月31日から1月3日まで）</p>
          </div>
        </div>
      </div>
      <div class="l-footer__container">
        <div class="l-footer__nav__list">
          <ul>
            <li><a href="../">トップページ</a></li>
            <li><a href="../about/">私たちについて</a></li>
            <li><a href="../about/#js-company">会社概要</a></li>
          </ul>
          <ul>
            <li><a href="../about/#js-ceo">代表あいさつ</a></li>
            <li><a href="../about/#js-safety">安全への取り組み</a></li>
            <li><a href="../service/">事業内容</a></li>
            <li>
              <a href="../service/#js-service-1">住宅建築・リフォーム</a>
            </li>
          </ul>
          <ul>
            <li>
              <a href="../service/#js-service-2">法人新築・リフォーム</a>
            </li>
            <li><a href="../service/#js-service-3">公共工事</a></li>
            <li><a href="../#js-news">お知らせ</a></li>
            <li>
              <a href="../contact"><span class="c-mail-icon"><img src="../img/mail-icon.svg"
                    alt="メールアイコン" /></span>お問い合わせ</a>
            </li>
          </ul>
        </div>
        <p class="l-footer__copyright">
          <small lang="en">©︎AOBOTAN INC.</small>
        </p>
      </div>
    </div>
  </footer>
  <!-- jquery & iScroll -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!-- swiper -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js"></script>
  <!-- original -->
  <script src="../js/script.js"></script>
</body>

</html>


<body>
  <div align="center">
    <?php if($empty_flag == 1){ ?>
    <h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
    <div style="color:red"><?php echo $errm; ?></div>
    <br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()">
  </div>
</body>

</html>
<?php }else{ ?>
送信ありがとうございました。<br />
送信は正常に完了しました。<br /><br />
<a href="<?php echo $site_top ;?>">トップページへ戻る&raquo;</a>
</div>
<?php copyright(); ?>
<!--  CV率を計測する場合ここにAnalyticsコードを貼り付け -->
</body>

</html>
<?php 
/* ▲▲▲送信完了画面のレイアウト 編集可 ※送信完了後に指定のページに移動しない場合のみ表示▲▲▲　*/
  }
}
//確認画面無しの場合の表示、指定のページに移動する設定の場合、エラーチェックで問題が無ければ指定ページヘリダイレクト
else if(($jumpPage == 1 && $sendmail == 1) || $confirmDsp == 0) { 
	if($empty_flag == 1){ ?>
<div align="center">
  <h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
  <div style="color:red"><?php echo $errm; ?></div><br /><br /><input type="button" value=" 前画面に戻る "
    onClick="history.back()">
</div>
<?php 
	}else{ header("Location: ".$thanksPage); }
}

// 以下の変更は知識のある方のみ自己責任でお願いします。

//----------------------------------------------------------------------
//  関数定義(START)
//----------------------------------------------------------------------
function checkMail($str){
	$mailaddress_array = explode('@',$str);
	if(preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-zA-Z]+(\.[!#%&\-_0-9a-zA-Z]+)+$/", "$str") && count($mailaddress_array) ==2){
		return true;
	}else{
		return false;
	}
}
function h($string) {
	global $encode;
	return htmlspecialchars($string, ENT_QUOTES,$encode);
}
function sanitize($arr){
	if(is_array($arr)){
		return array_map('sanitize',$arr);
	}
	return str_replace("\0","",$arr);
}
//Shift-JISの場合に誤変換文字の置換関数
function sjisReplace($arr,$encode){
	foreach($arr as $key => $val){
		$key = str_replace('＼','ー',$key);
		$resArray[$key] = $val;
	}
	return $resArray;
}
//送信メールにPOSTデータをセットする関数
function postToMail($arr){
	global $hankaku,$hankaku_array;
	$resArray = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){ 
				//連結項目の処理
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
			
		}else{ $out = $val; }//チェックボックス（配列）追記ここまで
		
		if (version_compare(PHP_VERSION, '5.1.0', '<=')) {//PHP5.1.0以下の場合のみ実行（7.4でget_magic_quotes_gpcが非推奨になったため）
			if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		}
		
		//全角→半角変換
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		if($out != "confirm_submit" && $key != "httpReferer") {
			$resArray .= "【 ".h($key)." 】 ".h($out)."\n";
		}
	}
	return $resArray;
}
//確認画面の入力内容出力用関数
function confirmOutput($arr){
	global $hankaku,$hankaku_array,$useToken,$confirmDsp,$replaceStr;
	$html = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){ 
				//連結項目の処理
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
			
		}else{ $out = $val; }//チェックボックス（配列）追記ここまで
		
		if (version_compare(PHP_VERSION, '5.1.0', '<=')) {//PHP5.1.0以下の場合のみ実行（7.4でget_magic_quotes_gpcが非推奨になったため）
			if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		}
		
		//全角→半角変換
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		
		$out = nl2br(h($out));//※追記 改行コードを<br>タグに変換
		$key = h($key);
		$out = str_replace($replaceStr['before'], $replaceStr['after'], $out);//機種依存文字の置換処理
		
		$html .= "<tr><th>".$key."</th><td>".$out;
		$html .= '<input type="hidden" name="'.$key.'" value="'.str_replace(array("<br />","<br>"),"",$out).'" />';
		$html .= "</td></tr>\n";
	}
	//トークンをセット
	if($useToken == 1 && $confirmDsp == 1){
		$token = sha1(uniqid(mt_rand(), true));
		$_SESSION['mailform_token'] = $token;
		$html .= '<input type="hidden" name="mailform_token" value="'.$token.'" />';
	}
	
	return $html;
}

//全角→半角変換
function zenkaku2hankaku($key,$out,$hankaku_array){
	global $encode;
	if(is_array($hankaku_array) && function_exists('mb_convert_kana')){
		foreach($hankaku_array as $hankaku_array_val){
			if($key == $hankaku_array_val){
				$out = mb_convert_kana($out,'a',$encode);
			}
		}
	}
	return $out;
}
//配列連結の処理
function connect2val($arr){
	$out = '';
	foreach($arr as $key => $val){
		if($key === 0 || $val == ''){//配列が未記入（0）、または内容が空のの場合には連結文字を付加しない（型まで調べる必要あり）
			$key = '';
		}elseif(strpos($key,"円") !== false && $val != '' && preg_match("/^[0-9]+$/",$val)){
			$val = number_format($val);//金額の場合には3桁ごとにカンマを追加
		}
		$out .= $val . $key;
	}
	return $out;
}

//管理者宛送信メールヘッダ
function adminHeader($post_mail,$BccMail){
	global $from,$from_add;
	$header="From: ";
	if(!empty($post_mail) && $from_add == 1){
		$header .= mb_encode_mimeheader('"'.$post_mail.'"')." <".$from.">\n";
	}else{
		$header .= $from."\n";
	}
	if($BccMail != '') {
	  $header.="Bcc: $BccMail\n";
	}
	if(!empty($post_mail)) {
		$header.="Reply-To: ".$post_mail."\n";
	}
	$header.="Content-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
	return $header;
}
//管理者宛送信メールボディ
function mailToAdmin($arr,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp){
	$adminBody="「".$subject."」からメールが届きました\n\n";
	$adminBody .="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$adminBody.= postToMail($arr);//POSTデータを関数からセット
	$adminBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
	$adminBody.="送信された日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	$adminBody.="送信者のIPアドレス：".@$_SERVER["REMOTE_ADDR"]."\n";
	$adminBody.="送信者のホスト名：".getHostByAddr(getenv('REMOTE_ADDR'))."\n";
	if($confirmDsp != 1){
		$adminBody.="問い合わせのページURL：".@$_SERVER['HTTP_REFERER']."\n";
	}else{
		$adminBody.="問い合わせのページURL：".@$arr['httpReferer']."\n";
	}
	if($mailFooterDsp == 1) $adminBody.= $mailSignature;
	return mb_convert_encoding($adminBody,"JIS",$encode);
}

//ユーザ宛送信メールヘッダ
function userHeader($refrom_name,$to,$encode){
	$reheader = "From: ";
	if(!empty($refrom_name)){
		$default_internal_encode = mb_internal_encoding();
		if($default_internal_encode != $encode){
			mb_internal_encoding($encode);
		}
		$reheader .= mb_encode_mimeheader($refrom_name)." <".$to.">\nReply-To: ".$to;
	}else{
		$reheader .= "$to\nReply-To: ".$to;
	}
	$reheader .= "\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
	return $reheader;
}
//ユーザ宛送信メールボディ
function mailToUser($arr,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode){
	$userBody = '';
	if(isset($arr[$dsp_name])) $userBody = h($arr[$dsp_name]). " 様\n";
	$userBody.= $remail_text;
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.= postToMail($arr);//POSTデータを関数からセット
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.="送信日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	if($mailFooterDsp == 1) $userBody.= $mailSignature;
	return mb_convert_encoding($userBody,"JIS",$encode);
}
//必須チェック関数
function requireCheck($require){
	$res['errm'] = '';
	$res['empty_flag'] = 0;
	foreach($require as $requireVal){
		$existsFalg = '';
		foreach($_POST as $key => $val) {
			if($key == $requireVal) {
				
				//連結指定の項目（配列）のための必須チェック
				if(is_array($val)){
					$connectEmpty = 0;
					foreach($val as $kk => $vv){
						if(is_array($vv)){
							foreach($vv as $kk02 => $vv02){
								if($vv02 == ''){
									$connectEmpty++;
								}
							}
						}
						
					}
					if($connectEmpty > 0){
						$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
						$res['empty_flag'] = 1;
					}
				}
				//デフォルト必須チェック
				elseif($val == ''){
					$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
					$res['empty_flag'] = 1;
				}
				
				$existsFalg = 1;
				break;
			}
			
		}
		if($existsFalg != 1){
				$res['errm'] .= "<p class=\"error_messe\">【".$requireVal."】が未選択です。</p>\n";
				$res['empty_flag'] = 1;
		}
	}
	
	return $res;
}
//リファラチェック
function refererCheck($Referer_check,$Referer_check_domain){
	if($Referer_check == 1 && !empty($Referer_check_domain)){
		if(strpos($_SERVER['HTTP_REFERER'],$Referer_check_domain) === false){
			return exit('<p align="center">リファラチェックエラー。フォームページのドメインとこのファイルのドメインが一致しません</p>');
		}
	}
}
function copyright(){
	echo '<a style="display:block;text-align:center;margin:15px 0;font-size:11px;color:#aaa;text-decoration:none" href="http://www.php-factory.net/" target="_blank">- PHP工房 -</a>';
}
//----------------------------------------------------------------------
//  関数定義(END)
//----------------------------------------------------------------------
?>