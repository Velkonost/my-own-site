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

if(@$_POST['add_subject'] && isset($_POST['user_id']) && isset($_POST['sub_id'])) {

    $sub_check = $dbconnect->query("SELECT * FROM active_subjects WHERE user_id='".$_POST['user_id']."' AND subject_id='".$_POST['sub_id']."'");
    $check = $sub_check->num_rows;
    if ($check == 0) {
      $dbconnect->query("INSERT INTO active_subjects VALUES('', '$_POST[user_id]', '$_POST[sub_id]')");
      $dbconnect->query("INSERT INTO meta_current_variant VALUES('', '$_POST[user_id]', '$_POST[sub_id]', '1')");
    }
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
$subjects_ids = [];

while ($subject_id = ($activeSubjects -> fetch_array())) {
	array_push($subjects_ids, $subject_id['subject_id']);
}

// $subjects_ids = mysqli_fetch_assoc($activeSubjects);
// echo sizeof($subjects_ids);
$title_subjects = [];
$description_subjects = [];
$task_amount_subjects = [];


for ($i = 0; $i <= sizeof($subjects_ids) - 1; $i ++) {
	$subject = $dbconnect->query("SELECT * FROM subjects WHERE id='".$subjects_ids[$i]."'");
	$subject_data = $subject -> fetch_array();

	array_push($title_subjects, $subject_data['title']);
	array_push($description_subjects, $subject_data['description']);
	array_push($task_amount_subjects, $subject_data['amount_tasks']);
}

$id_all_subjects = [];
$title_all_subjects = [];
$description_all_subjects = [];
$task_amount_all_subjects = [];

$all_subjects = $dbconnect->query("SELECT * FROM subjects");

while ($subject_id = ($all_subjects -> fetch_array())) {
  array_push($id_all_subjects, $subject_id['id']);
	array_push($title_all_subjects, $subject_id['title']);
	array_push($description_all_subjects, $subject_id['description']);
	array_push($task_amount_all_subjects, $subject_id['amount_tasks']);
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
      <form action="try.php" method="post">
            <input type='hidden' name='try_sub_id' id="try_sub_id" style="display: none" />
            <input type='submit' name='go_try' id="go_try" style="display: none" />
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
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="">Главная</a>
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
        
        
			<?php for ($i = 0; $i <= sizeof($subjects_ids) - 1; $i ++) { ?>
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
						<button onclick="goNewTry(<?php echo $subjects_ids[$i]; ?>)">Добавить попытку</button>
					</div>
				</div>
			</aside>
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
			<?php } ?>
		
		   	<aside class="card" elevation="2" style="max-height: 52px; margin-top: 10px">
				<div class="android-header-spacer mdl-layout-spacer"></div>

      			<div id="add_new_sub" class="android-search-box mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right mdl-textfield--full-width right" style="margin:auto">
        			<label class="mdl-button mdl-js-button mdl-button--icon" for="input_new_sub" style="float:right;">
          				<i class="material-icons" id="add_new_sub_btn" onclick="clearSearchInput()">add</i>
        			</label>
        			<div class="mdl-textfield__expandable-holder" style="float: right; width: 900px">
          				<input class="mdl-textfield__input autocomplete" name="input_new_sub" type="text" id="input_new_sub" oninput="searchSubjects()" style="padding: 0">
        			</div>
      			</div>
			</aside> 
			<div class="demo" id="demo_add" style="height: 198px; overflow: scroll;">
				<div class="">
				<table class="table" id="table_subjects" style="margin-bottom: 0;">
				</table>
				  	
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
            <p class="mdl-typography--font-light">© 2017 Артём Клименко</p>
            <p class="mdl-typography--font-light">Нашли ошибку? Пишите: velkonost@gmail.com</p>
          </div>

          <div class="mdl-mega-footer--bottom-section">
          <!--   <a class="android-link android-link-menu mdl-typography--font-light" id="version-dropdown">
              Versions
              <i class="material-icons">arrow_drop_up</i>
            </a>
            <ul class="mdl-menu mdl-js-menu mdl-menu--top-left mdl-js-ripple-effect" for="version-dropdown">
              <li class="mdl-menu__item">5.0 Lollipop</li>
              <li class="mdl-menu__item">4.4 KitKat</li>
              <li class="mdl-menu__item">4.3 Jelly Bean</li>
              <li class="mdl-menu__item">Android History</li>
            </ul> -->
           <!--  <a class="android-link android-link-menu mdl-typography--font-light" id="developers-dropdown">
              For Developers
              <i class="material-icons">arrow_drop_up</i>
            </a>
            <ul class="mdl-menu mdl-js-menu mdl-menu--top-left mdl-js-ripple-effect" for="developers-dropdown">
              <li class="mdl-menu__item">App developer resources</li>
              <li class="mdl-menu__item">Android Open Source Project</li>
              <li class="mdl-menu__item">Android SDK</li>
              <li class="mdl-menu__item">Android for Work</li>
            </ul> -->
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

  var isAlertDialogExist = false;
	
	function goOut(){
	  $("#exit").click();
	}

	jQuery(window).load(function () {
	    $('#preloader').hide();
	});

	var $div = $("#add_new_sub");
	var observer = new MutationObserver(function(mutations) {
	    mutations.forEach(function(mutation) {
	        if (mutation.attributeName === "class") {
	        	var attributeValue = $(mutation.target).prop(mutation.attributeName);

	        	if (attributeValue.indexOf('is-focused') == -1 && attributeValue.indexOf('is-dirty') == -1) {
	        		document.getElementById('add_new_sub_btn').innerText = 'add';
	        	} else {
              document.getElementById('add_new_sub_btn').innerText = 'clear';  
            }
	        }
	    });
	});

	observer.observe($div[0],  {
	    attributes: true
	});


	$.fn.htmlTo = function(elem) {
	    return this.each(function() {
	        $(elem).html($(this).html());
	    });
	}

    var active_subjects_title = [
        <?php
            for($i = 0; $i < sizeof($title_subjects); $i++) {
                echo "'$title_subjects[$i]',";
            }
         ?>
    ];

    console.log(active_subjects_title);


	var subjects_title = [
		<?php
			for($i = 0; $i < sizeof($title_all_subjects); $i++) {
				echo "'$title_all_subjects[$i]',";
			}
		 ?>
	];

    var subjects_description = [
        <?php
            for($i = 0; $i < sizeof($description_all_subjects); $i++) {
                echo "'$description_all_subjects[$i]',";
            }
         ?>
    ];

    var subjects_ids = [
        <?php
            for($i = 0; $i < sizeof($id_all_subjects); $i++) {
                echo "'$id_all_subjects[$i]',";
            }
         ?>
    ];

	var accepted_subjects_title = [];
    var accepted_subjects_id = [];

	for (var i = 0; i < subjects_title.length; i++) {
        if (active_subjects_title.indexOf(subjects_title[i]) == -1) {
    		accepted_subjects_title.push(subjects_title[i]);
            accepted_subjects_id.push(subjects_ids[i]);
        }
	}

	generateSubjectsTable(accepted_subjects_title, accepted_subjects_id);
	function generateSubjectsTable(accepted_subjects_title, ids) {
		var $table = $('<table class="table" id="table_subjects" style="margin-bottom: 0">');
		
		$table.append('<thead>').children('thead')

		var $tbody = $table.append('<tbody />').children('tbody');

		var subjects_count = accepted_subjects_title.length;
		
		for (var i = 0; i < subjects_count;) {
			$tbody.append('<tr />').children('tr:last');
			if (i < subjects_count) {
				$tbody.append("<td><div class=\"md-chip\" onclick=\"generateAlertDialog(" + ids[i] + ")\"><span class=\"md-chip-text\">" + accepted_subjects_title[i] + "</span></div></td>");
				i ++;
			}
			if (i < subjects_count) {
				$tbody.append("<td><div class=\"md-chip\" onclick=\"generateAlertDialog(" + ids[i] + ")\"><span class=\"md-chip-text\">" + accepted_subjects_title[i] + "</span></div></td>");
				i ++;
			}
		}
		
		$table.htmlTo('#table_subjects');
	}

    function generateSubjectWarning() {
        var $table = $('<table class="table" id="table_subjects" style="margin-bottom: 0">');
        
        $table.append('<thead>').children('thead')

        var $tbody = $table.append('<tbody />').children('tbody');
        $tbody.append('<tr />').children('tr:last');
        $tbody.append("<td style=\" padding:20px \">Нет результатов</td>");
        $table.htmlTo('#table_subjects');
    }

	function searchSubjects() {
		var to_search = $('input[name="input_new_sub"]').val();

        var accepted_subjects_title = [];
        var accepted_subjects_id = [];

        for (var i = 0; i < subjects_title.length; i++) {
            if (subjects_title[i].indexOf(to_search) != -1  && (active_subjects_title.indexOf(subjects_title[i]) == -1)) {
                accepted_subjects_title.push(subjects_title[i]);
                accepted_subjects_id.push(subjects_ids[i]);
            }
        }
        
        generateSubjectsTable(accepted_subjects_title, accepted_subjects_id);
        if (accepted_subjects_title.length == 0) {
          generateSubjectWarning();
        }
	}


    $(document).click(function(event) { 
        if(!$(event.target).closest('#nominee_alert_dialog').length) {
            if (!isAlertDialogExist) {
                destroyAlertDialog();
            }

            isAlertDialogExist = !isAlertDialogExist;
        }        
    })
    function generateAlertDialog(id) {
        isAlertDialogExist = true;
        
        var index = subjects_ids.indexOf(id.toString());
        var title = subjects_title[index];
        var description = subjects_description[index];

        $('body').append('<div id="alert-dialog" style="z-index:1" class="nominee-alert"><div id="nominee_alert_dialog" class="nominee-alert-box"><div class="nominee-alert-box-title">' + title + '<i style="float:right" onclick="destroyAlertDialog()" class="material-icons">clear</i></div><p>' + description + '</p><div class="nominee-alert-buttons"><a href="javascript:void(0);" onclick="addSubject(' + id + ')">ДОБАВИТЬ</a></div></div></div>');

  }

  function destroyAlertDialog() {
    $('#alert-dialog').remove();
  }
  function addSubject(id) {
    $('#sub_id').val(id);
    $('#user_id').val("<?=$user_data['id']?>");

    $("#add_subject").click();
  }

  function clearSearchInput() {
    document.getElementById('input_new_sub').value = '';
    searchSubjects();
  }

  function goNewTry(sub_id) {
    document.getElementById('try_sub_id').value = sub_id;
    $("#go_try").click();
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