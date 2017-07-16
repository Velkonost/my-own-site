<?php
error_reporting(E_ALL); 
ini_set("display_errors", 1); 
include_once($_SERVER['DOCUMENT_ROOT'].'/ege/conf.php');
$dbconnect = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB_NAME);
$dbconnect -> query ("set_client='utf8'");
$dbconnect -> query ("set character_set_results='utf8'");
$dbconnect -> query ("set collation_connection='utf8_general_ci'");
$dbconnect -> query ("SET NAMES utf8");

if (isset($_POST['check_reg']) && $_POST['check_reg'] == 'auth') {
	if (isset($_POST['login']) and isset($_POST['password']) and $_POST['password'] !== '' and $_POST['login'] !== '') {
		if (preg_match("/^[a-zA-Z0-9]{3,30}$/", $_POST['login'])) {
			$user = $dbconnect->query("SELECT * FROM users WHERE login='".$_POST['login']."'");
			$U = $user->num_rows;
			if ($U == 1){
				$user_data = $user->fetch_array();
				$password_hash = md5($_POST['password'].":".$user_data['secretkey']);
				if ($password_hash == $user_data['password']){
					$secret_key = uniqid();
					$new_password_hash = md5($_POST['password'].":".$secret_key);
					$curr_date = time();
					$user_update = $dbconnect->query("UPDATE users SET `password`='".$new_password_hash."',`secretkey`='".$secret_key."',`last_login_datetime`=".$curr_date." WHERE `login`='".$_POST['login']."'");
					if ($user_update){
						setcookie("WebEngineerRestrictedArea",$_POST['login'].":".md5($secret_key.":".$_SERVER['REMOTE_ADDR'].":".$curr_date),time()+60*60*24);
						header ("Location: ".$_SERVER['PHP_SELF']);
						exit();
					} else {
						$update_error = TRUE;
					}
				} else {
					$error_pass = TRUE;
				}
			} else {
				$error_login = TRUE;
			}
		} else {
			$syntax_error = TRUE;
		}
	}
} else if(isset($_POST['check_reg'])) {
	if (isset($_POST['login']) and isset($_POST['password']) and isset($_POST['rpt_password']) and $_POST['password'] !== '' and $_POST['login'] !== '' and $_POST['rpt_password'] !== '') {
		if (preg_match("/^[a-zA-Z0-9]{3,30}$/", $_POST['login'])) {
			$user = $dbconnect->query("SELECT * FROM users WHERE login='".$_POST['login']."'");
			$U = $user->num_rows;
			if ($U == 0){
				
				if ($_POST['password'] == $_POST['rpt_password']){
					$secret_key = uniqid();
					$new_password_hash = md5($_POST['password'].":".$secret_key);
					$curr_date = time();

					$user_update = $dbconnect->query("INSERT INTO users VALUES('', '$_POST[login]', '$new_password_hash', '$secret_key', '$curr_date')");
					if ($user_update){
						setcookie("WebEngineerRestrictedArea",$_POST['login'].":".md5($secret_key.":".$_SERVER['REMOTE_ADDR'].":".$curr_date),time()+60*60*24);
						header ("Location: ".$_SERVER['PHP_SELF']);
						exit();
					} else {
						$update_error = TRUE;
					}
				} else {
					$error_rpt_pass = TRUE;
				}
			} else {
				$error_reg_login = TRUE;
			}
		} else {
			$syntax_error = TRUE;
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Авторизация | Velkonost.ru</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
</head>

<body>

<div id="wrapper">
    <div class="user-icon"></div>
    <div class="pass-icon"></div>
    <div class="rptpass-icon"></div>
	
<form name="login-form" class="login-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">

    <div class="header">
		<h1>Авторизация</h1>
		<span>Введите ваши регистрационные данные для входа в ваш личный кабинет. </span>
    </div>

    <div class="content">
    	<input id="check_reg" name="check_reg" style="display: none" value="auth" />
		<input name="login" type="text" size="45" class="input username" placeholder="Логин"  />
		<input name="password" type="password" class="input password" placeholder="Пароль" />
		<input name="rpt_password" id="rpt_password" type="password" class="input rpt_password" placeholder="Повтор пароля" style="display: none" onfocus="this.value=''" />
    </div>

    <div class="footer">
		<input type="submit" name="submit" id="auth" value="ВОЙТИ" class="button" />
		<input type="button" name="submit" id="reg" value="Регистрация" class="register" />
    </div>

</form>

<?php
    if (@$error_login) {
	?>
    <div class="error">
    <span>Пользователь с таким логином не найден.</span>
    </div>
    <?php
	} elseif (@$error_pass){
	?>
    <div class="error">
    <span>Введенный пароль не верный.</span>
    </div>
	<?php
	} elseif (@$error_rpt_pass){
	?>
    <div class="error">
    <span>Пароли не совпадают.</span>
    </div>
	<?php
	} elseif (@$update_error){
	?>
    <div class="error">
    <span>Не удалось обновить информацию пользователя.</span>
    </div>
	<?php
	} elseif (@$syntax_error) {
	?>
    <div class="error">
    <span>Вы ввели некорректный логин.</span>
    </div>
	<?php 
	} elseif (@$error_reg_login) {
	?>
	<div class="error">
    <span>Такой логин уже существует.</span>
    </div>
<?php } ?>


</div>
<div class="gradient"></div>

<script type="text/javascript">
	var isRptPassOpen = false;


	$("#reg").click(function () {
 		if ($("#rpt_password").is(":hidden")) {
 			document.getElementById('auth').value = 'РЕГИСТР-Я';
			document.getElementById('reg').value = 'Войти';
			document.getElementById('check_reg').value = 'reg';
			$("#rpt_password").slideDown("fast");
      	} else {
      		document.getElementById('auth').value = 'ВОЙТИ';
       		document.getElementById('reg').value = 'Регистрация';
       		document.getElementById('check_reg').value = 'auth';
        	$("#rpt_password").hide();
      	}
	});

	$(document).ready(function() {
		$(".username").focus(function() {
			$(".user-icon").css("left","-48px");
		});
		$(".username").blur(function() {
			$(".user-icon").css("left","0px");
		});
		
		$(".password").focus(function() {
			$(".pass-icon").css("left","-48px");
		});
		$(".password").blur(function() {
			$(".pass-icon").css("left","0px");
		});

		$(".rpt_password").focus(function() {
			$(".rptpass-icon").css("left","-48px");
		});
		$(".rpt_password").blur(function() {
			$(".rptpass-icon").css("left","0px");
		});
	});
</script>
</body>
</html>