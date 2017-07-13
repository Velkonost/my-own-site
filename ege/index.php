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

$activeSubjects = $dbconnect->query("SELECT subject_id FROM active_subjects WHERE user_id='".$user_data['id']."'");
$subjects_ids = $activeSubjects -> fetch_array();

$title_subjects = [];
$description_subjects = [];
$task_amount_subjects = [];


for ($i = 0; $i < sizeof($subjects_ids) - 1; $i ++) {
	$subject = $dbconnect->query("SELECT * FROM subjects WHERE id='".$subjects_ids[$i]."'");
	$subject_data = $subject -> fetch_array();

	array_push($title_subjects, $subject_data['title']);
	array_push($description_subjects, $subject_data['description']);
	array_push($task_amount_subjects, $subject_data['amount_tasks']);
}

$title_all_subjects = [];
$description_all_subjects = [];
$task_amount_all_subjects = [];

$all_subjects = $dbconnect->query("SELECT * FROM subjects");
echo $all_subjects -> fetch_array();

for ($i = 0; $i < sizeof($all_subjects) - 1; $i ++) {
	
	$subject_data = $all_subjects[$i] -> fetch_array();

	array_push($title_all_subjects, $subject_data['title']);
	array_push($description_all_subjects, $subject_data['description']);
	array_push($task_amount_all_subjects, $subject_data['amount_tasks']);

	echo $subject_data['title'];
}


?>

<!-- <?php echo $data_array[0]; ?> -->
<!doctype html>
<!-- <html lang="en">
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
          <div class="android-search-box mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right mdl-textfield--full-width">
            <label class="mdl-button mdl-js-button mdl-button--icon" for="search-field">
              <i class="material-icons">search</i>
            </label>
            <div class="mdl-textfield__expandable-holder">
              <input class="mdl-textfield__input" type="text" id="search-field">
            </div>
          </div>
          <!-- Navigation -->
          <div class="android-navigation-container">
            <nav class="android-navigation mdl-navigation">
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="">Главная</a>
              
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
          <a class="mdl-navigation__link" href="">Tablets</a>
          <a class="mdl-navigation__link" href="">Wear</a>
          <a class="mdl-navigation__link" href="">TV</a>
          <a class="mdl-navigation__link" href="">Auto</a>
          <a class="mdl-navigation__link" href="">One</a>
          <a class="mdl-navigation__link" href="">Play</a>
          <div class="android-drawer-separator"></div>
          <span class="mdl-navigation__link" href="">Прочее</span>
          <a class="mdl-navigation__link" href="">Настройки</a>
          <a class="mdl-navigation__link" onclick="goOut();" href="javascript:void(0);">Выход</a>
        </nav>
      </div>

      <div class="android-content mdl-layout__content">
        <a name="top"></a>
        
        
			<?php for ($i = 0; $i < sizeof($subjects_ids) - 1; $i ++) { ?>
	        <aside class="card" elevation="2">
				<div class="supplemental_actions">
					<!-- <button class="icon icon_more" style="float:left"><i class="material-icons">more</i></button> -->
					<button class="icon" style="float:left"><i class="material-icons" >arrow_drop_down_circle</i></button>
				
					<button style="float:left" disabled=""><?=$title_subjects[$i] ?></button>

					<div class="google-expando--wrap">
	  					<div class="google-expando">
	  						<div class="google-expando__icon">
								<button class="icon" disabled="" style="float:left"><i class="material-icons">info</i></button>
							</div>
							<div class="google-expando__card" aria-hidden="true">
								<?=$description_subjects[$i] ?>
							</div>
						</div>
					</div>
					<div class="right">
						<button>Добавить попытку</button>
					</div>
				</div>
			</aside>
			<?php } ?>
	<!-- двойная стрелочка(показывающая все попытки), стрелочка, название, иконка доп инфы(описание), кнопка "добавить новую попытку" -->
			<div class="demo">
				<div class="table-responsive-vertical shadow-z-1">
				  	<table class="table table-hover table-bordered table-mc-teal" style="margin-bottom: 0">
				      	<thead></thead>
				    	<tbody>
					        <tr id="table_title">
					          <td data-title="ID">1</td>
					          <td data-title="Name">2</td>
					          <td data-title="Name">3</td>
					          <td data-title="Name">4</td>
					          <td data-title="Name">5</td>
					        </tr>
					        <tr>
					          <td data-title="ID">47%</td>
					          <td data-title="ID">47%</td>
					          <td data-title="ID">47%</td>
					          <td data-title="ID">47%</td>
					          <td data-title="ID">47%</td>
					        </tr>
				    	</tbody>
				    </table>
				</div>
			</div>
		
		   	<aside class="card" elevation="2" style="max-height: 52px; margin-top: 10px">
				<div class="android-header-spacer mdl-layout-spacer"></div>
      			<div id="add_new_sub" class="android-search-box mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right mdl-textfield--full-width right" style="margin:auto">
        			<label class="mdl-button mdl-js-button mdl-button--icon" for="input_new_sub" style="float:right;">
          				<i class="material-icons" id="add_new_sub_btn">add</i>
        			</label>
        			<div class="mdl-textfield__expandable-holder" style="float: right; width: 900px">
          				<input class="mdl-textfield__input autocomplete" type="text" id="input_new_sub" style="padding: 0">
        			</div>
      			</div>
			</aside> 
			<div class="demo" id="demo_add">
				<div class="">
				<table class="table" id="table_subjects" style="margin-bottom: 0">
				</table>
				  	
				</div>
			</div>

		
        

      
        <div class="android-screen-section mdl-typography--text-center">
          <a name="screens"></a>
          <div class="mdl-typography--display-1-color-contrast">Powering screens of all sizes</div>
          <div class="android-screens">
            <div class="android-wear android-screen">
              <a class="android-image-link" href="">
                <img class="android-screen-image" src="images/wear-silver-on.png">
                <img class="android-screen-image" src="images/wear-black-on.png">
              </a>
              <a class="android-link mdl-typography--font-regular mdl-typography--text-uppercase" href="">Android Wear</a>
            </div>
            <div class="android-phone android-screen">
              <a class="android-image-link" href="">
                <img class="android-screen-image" src="images/nexus6-on.jpg">
              </a>
              <a class="android-link mdl-typography--font-regular mdl-typography--text-uppercase" href="">Phones</a>
            </div>
            <div class="android-tablet android-screen">
              <a class="android-image-link" href="">
                <img class="android-screen-image" src="images/nexus9-on.jpg">
              </a>
              <a class="android-link mdl-typography--font-regular mdl-typography--text-uppercase" href="">Tablets</a>
            </div>
            <div class="android-tv android-screen">
              <a class="android-image-link" href="">
                <img class="android-screen-image" src="images/tv-on.jpg">
              </a>
              <a class="android-link mdl-typography--font-regular mdl-typography--text-uppercase" href="">Android TV</a>
            </div>
            <div class="android-auto android-screen">
              <a class="android-image-link" href="">
                <img class="android-screen-image" src="images/auto-on.jpg">
              </a>
              <a class="android-link mdl-typography--font-regular mdl-typography--text-uppercase mdl-typography--text-left" href="">Coming Soon: Android Auto</a>
            </div>
          </div>
        </div>
        <div class="android-wear-section">
          <div class="android-wear-band">
            <div class="android-wear-band-text">
              <div class="mdl-typography--display-2 mdl-typography--font-thin">The best of Google built in</div>
              <p class="mdl-typography--headline mdl-typography--font-thin">
                Android works perfectly with your favourite apps like Google Maps,
                Calendar and YouTube.
              </p>
              <p>
                <a class="mdl-typography--font-regular mdl-typography--text-uppercase android-alt-link" href="">
                  See what's new in the Play Store&nbsp;<i class="material-icons">chevron_right</i>
                </a>
              </p>
            </div>
          </div>
        </div>
        <div class="android-customized-section">
          <div class="android-customized-section-text">
            <div class="mdl-typography--font-light mdl-typography--display-1-color-contrast">Customised by you, for you</div>
            <p class="mdl-typography--font-light">
              Put the stuff that you care about right on your home screen: the latest news, the weather or a stream of your recent photos.
              <br>
              <a href="" class="android-link mdl-typography--font-light">Customise your phone</a>
            </p>
          </div>
          <div class="android-customized-section-image"></div>
        </div>
        <div class="android-more-section">
          <div class="android-section-title mdl-typography--display-1-color-contrast">More from Android</div>
          <div class="android-card-container mdl-grid">
            <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone mdl-card mdl-shadow--3dp">
              <div class="mdl-card__media">
                <img src="images/more-from-1.png">
              </div>
              <div class="mdl-card__title">
                 <h4 class="mdl-card__title-text">Get going on Android</h4>
              </div>
              <div class="mdl-card__supporting-text">
                <span class="mdl-typography--font-light mdl-typography--subhead">Four tips to make your switch to Android quick and easy</span>
              </div>
              <div class="mdl-card__actions">
                 <a class="android-link mdl-button mdl-js-button mdl-typography--text-uppercase" href="">
                   Make the switch
                   <i class="material-icons">chevron_right</i>
                 </a>
              </div>
            </div>

            <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone mdl-card mdl-shadow--3dp">
              <div class="mdl-card__media">
                <img src="images/more-from-4.png">
              </div>
              <div class="mdl-card__title">
                 <h4 class="mdl-card__title-text">Create your own Android character</h4>
              </div>
              <div class="mdl-card__supporting-text">
                <span class="mdl-typography--font-light mdl-typography--subhead">Turn the little green Android mascot into you, your friends, anyone!</span>
              </div>
              <div class="mdl-card__actions">
                 <a class="android-link mdl-button mdl-js-button mdl-typography--text-uppercase" href="">
                   androidify.com
                   <i class="material-icons">chevron_right</i>
                 </a>
              </div>
            </div>

            <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone mdl-card mdl-shadow--3dp">
              <div class="mdl-card__media">
                <img src="images/more-from-2.png">
              </div>
              <div class="mdl-card__title">
                 <h4 class="mdl-card__title-text">Get a clean customisable home screen</h4>
              </div>
              <div class="mdl-card__supporting-text">
                <span class="mdl-typography--font-light mdl-typography--subhead">A clean, simple, customisable home screen that comes with the power of Google Now: Traffic alerts, weather and much more, just a swipe away.</span>
              </div>
              <div class="mdl-card__actions">
                 <a class="android-link mdl-button mdl-js-button mdl-typography--text-uppercase" href="">
                   Download now
                   <i class="material-icons">chevron_right</i>
                 </a>
              </div>
            </div>

            <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone mdl-card mdl-shadow--3dp">
              <div class="mdl-card__media">
                <img src="images/more-from-3.png">
              </div>
              <div class="mdl-card__title">
                 <h4 class="mdl-card__title-text">Millions to choose from</h4>
              </div>
              <div class="mdl-card__supporting-text">
                <span class="mdl-typography--font-light mdl-typography--subhead">Hail a taxi, find a recipe, run through a temple – Google Play has all the apps and games that let you make your Android device uniquely yours.</span>
              </div>
              <div class="mdl-card__actions">
                 <a class="android-link mdl-button mdl-js-button mdl-typography--text-uppercase" href="">
                   Find apps
                   <i class="material-icons">chevron_right</i>
                 </a>
              </div>
            </div>
          </div>
        </div>

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
            <p class="mdl-typography--font-light">Satellite imagery: © 2014 Astrium, DigitalGlobe</p>
            <p class="mdl-typography--font-light">Some features and devices may not be available in all areas</p>
          </div>

          <div class="mdl-mega-footer--bottom-section">
            <a class="android-link android-link-menu mdl-typography--font-light" id="version-dropdown">
              Versions
              <i class="material-icons">arrow_drop_up</i>
            </a>
            <ul class="mdl-menu mdl-js-menu mdl-menu--top-left mdl-js-ripple-effect" for="version-dropdown">
              <li class="mdl-menu__item">5.0 Lollipop</li>
              <li class="mdl-menu__item">4.4 KitKat</li>
              <li class="mdl-menu__item">4.3 Jelly Bean</li>
              <li class="mdl-menu__item">Android History</li>
            </ul>
            <a class="android-link android-link-menu mdl-typography--font-light" id="developers-dropdown">
              For Developers
              <i class="material-icons">arrow_drop_up</i>
            </a>
            <ul class="mdl-menu mdl-js-menu mdl-menu--top-left mdl-js-ripple-effect" for="developers-dropdown">
              <li class="mdl-menu__item">App developer resources</li>
              <li class="mdl-menu__item">Android Open Source Project</li>
              <li class="mdl-menu__item">Android SDK</li>
              <li class="mdl-menu__item">Android for Work</li>
            </ul>
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
    <script src="material/expand_card.js"></script>
-->
<script type="text/javascript">
	
	
</script>

  </body> 
</html>




<?php 
	} else {
		include ($_SERVER['DOCUMENT_ROOT'].'/ege/auth.php');
		exit();
	}
?>