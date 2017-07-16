<?php
error_reporting(E_ALL); 
ini_set("display_errors", 1); 

include_once($_SERVER['DOCUMENT_ROOT'].'/ege/conf.php');
$dbconnect = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB_NAME);
$dbconnect -> query ("set_client='utf8'");
$dbconnect -> query ("set character_set_results='utf8'");
$dbconnect -> query ("set collation_connection='utf8_general_ci'");
$dbconnect -> query ("SET NAMES utf8");

if(@$_POST['exit']) {
	setcookie('WebEngineerRestrictedArea', '', time()-60*60*24); 
	header ("Location: ".$_SERVER['PHP_SELF']);
	exit();
}

if (isset($_COOKIE['WebEngineerRestrictedArea'])){
	$data_array = explode(":",$_COOKIE['WebEngineerRestrictedArea']);
	if (preg_match("/^[a-zA-Z0-9]{3,30}$/", $data_array[0])) {
		$user = $dbconnect->query("SELECT * FROM users WHERE login='".$data_array[0]."'");
		$U = $user->num_rows;
		if ($U == 1) {
			$cookies_hash = $data_array[1]; 
			$user_data = $user->fetch_array();
			$evaluate_hash = md5($user_data['secretkey'].":".$_SERVER['REMOTE_ADDR'].":".$user_data['last_login_datetime']);
			if ($cookies_hash == $evaluate_hash) {
				$access = TRUE;
			} 
		} 
	} else {
		$access = FALSE;
	}
}

if (isset($access) and $access = TRUE) {?>

<?php

if(@$_POST['go_try'] && isset($_POST['try_sub_id'])) {
	$current_variant = $dbconnect->query("SELECT * FROM meta_current_variant WHERE user_id='".$user_data['id']."' AND subject_id='".$_POST['try_sub_id']."' ");
	$variant_number = $current_variant->fetch_array();
	$variant_number = $variant_number['variant'];

	$tasks_title = [];
	$tasks_description = [];
	$tasks_answer = [];
	$tasks_type_answer = [];
	$tasks_additional = [];

	$tasks = $dbconnect->query("SELECT * FROM task_variants WHERE subject_variant_id='".$variant_number."' AND subject_id='".$_POST['try_sub_id']."' ");

	while ($task = ($tasks -> fetch_array())) {
		// array_push($subjects_ids, $subject_id['subject_id']);
		echo $task['id'];
	}

}

?>

<!doctype html>
<html lang="en">
  	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="description" content="Introducing Lollipop, a sweet new take on Android.">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <script src="https://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>

	    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
	    <title>title</title>

	    <!-- Page styles -->
	    <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
	    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


	    <link rel="stylesheet" href="material/material.min.css">
	    <link rel="stylesheet" href="material/styles.css">
	    <link rel="stylesheet" href="material/preloader.css">
	    <link rel="stylesheet" href="material/card.css">
	    <link rel="stylesheet" href="css/media.css">
	    <link rel="stylesheet" href="material/expand_card.css">
	    <link rel="stylesheet" href="material/chips.css">
      <link rel="stylesheet" href="material/dialog.css">
	    <link rel="stylesheet" type="text/css" href="material/table.css">
	    <style>
	    #view-source {
	      position: fixed;
	      display: block;
	      right: 0;
	      bottom: 0;
	      margin-right: 40px;
	      margin-bottom: 40px;
	      z-index: 900;
	    }
	    </style>
	</head>
	<body style="background-color: #8EB0BC">
  		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
  			<input type='submit' name='exit' id="exit" value='Выйти' style="display: none" />
            <input type='hidden' name='user_id' id="user_id" style="display: none" />
            <input type='hidden' name='sub_id' id="sub_id" style="display: none" />
            <input type='submit' name='add_subject' id="add_subject" value='Добавить предмет' style="display: none" />
		</form>

  		<header id="preloader">
  			<div aria-busy="true" aria-label="Loading, please wait..." role="progressbar"></div>
		</header>

    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

      <div class="android-header mdl-layout__header mdl-layout__header--waterfall">
        <div class="mdl-layout__header-row">
          <span class="android-title mdl-layout-title">
            <img class="android-logo-image" src="images/android-logo.png">
          </span>
          <!-- Add spacer, to align navigation to the right in desktop -->
          <div class="android-header-spacer mdl-layout-spacer"></div>
          
          <!-- Navigation -->
          <div class="android-navigation-container">
            <nav class="android-navigation mdl-navigation">
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="/ege/">Главная</a>
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="">Создать</a>
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="">Голосовать</a>
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="">Пользователи</a>
              
            </nav>
          </div>
          <span class="android-mobile-title mdl-layout-title">
            <img class="android-logo-image" src="images/android-logo.png">
          </span>
          <button class="android-more-button mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="more-button">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" for="more-button">
            <li class="mdl-menu__item">Настройки</li>
            <li class="mdl-menu__item" onclick="goOut();">Выход</li>
          </ul>
        </div>
      </div>

      <div class="android-drawer mdl-layout__drawer">
        <span class="mdl-layout-title">
          <img class="android-logo-image" src="images/android-logo-white.png">
        </span>
        <nav class="mdl-navigation">
          <a class="mdl-navigation__link" href="">Главная</a>
          <a class="mdl-navigation__link" href="">Создать</a>
          <a class="mdl-navigation__link" href="">Голосовать</a>
          <a class="mdl-navigation__link" href="">Пользователи</a>
          <div class="android-drawer-separator"></div>
          <span class="mdl-navigation__link" href="">Прочее</span>
          <a class="mdl-navigation__link" href="">Настройки</a>
          <a class="mdl-navigation__link" onclick="goOut();" href="javascript:void(0);">Выход</a>
        </nav>
      </div>

      <div class="android-content mdl-layout__content">
        <a name="top"></a>
        
        <footer class="android-footer mdl-mega-footer">
          <div class="mdl-mega-footer--top-section">
            <div class="mdl-mega-footer--left-section">
              <button class="mdl-mega-footer--social-btn"></button>
              &nbsp;
              <button class="mdl-mega-footer--social-btn"></button>
              &nbsp;
              <button class="mdl-mega-footer--social-btn"></button>
            </div>
            <div class="mdl-mega-footer--right-section">
              <a class="mdl-typography--font-light" href="#top">
                Back to Top
                <i class="material-icons">expand_less</i>
              </a>
            </div>
          </div>

          <div class="mdl-mega-footer--middle-section">
            <p class="mdl-typography--font-light">© 2017 Артём Клименко</p>
            <p class="mdl-typography--font-light">Нашли ошибку? Пишите: velkonost@gmail.com</p>
          </div>

          <div class="mdl-mega-footer--bottom-section">
            <a class="android-link mdl-typography--font-light" href="https://velkonost.ru">Разработчик</a>
            <a class="android-link mdl-typography--font-light" href="">Privacy Policy</a>
          </div>

        </footer>
      </div>
    </div>
    <div class="gradient"></div>
      <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
 

    <script src="material/material.min.js"></script>
    <script src="material/table.js"></script>
    <script src="material/card.js"></script>
    <script src="material/dialog.js"></script>
    <script src="material/expand_card.js"></script>
<script type="text/javascript">

	
	function goOut(){
	  $("#exit").click();
	}

	jQuery(window).load(function () {
	    $('#preloader').hide();
	});

	$.fn.htmlTo = function(elem) {
	    return this.each(function() {
	        $(elem).html($(this).html());
	    });
	}

</script>

  </body>
</html>




<?php 
	} else {
		include ($_SERVER['DOCUMENT_ROOT'].'/ege/auth.php');
		exit();
	}
?>