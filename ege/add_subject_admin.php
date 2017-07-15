<?php

error_reporting(E_ALL); 
ini_set("display_errors", 1); 

include_once($_SERVER['DOCUMENT_ROOT'].'/ege/conf.php');
$dbconnect = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB_NAME);
$dbconnect -> query ("set_client='utf8'");
$dbconnect -> query ("set character_set_results='utf8'");
$dbconnect -> query ("set collation_connection='utf8_general_ci'");
$dbconnect -> query ("SET NAMES utf8");

$id_all_subjects = [];
$title_all_subjects = [];
$task_amount_all_subjects = [];

$all_subjects = $dbconnect->query("SELECT * FROM subjects");

while ($subject_id = ($all_subjects -> fetch_array())) {
	array_push($id_all_subjects, $subject_id['id']);
	array_push($title_all_subjects, $subject_id['title']);
	array_push($task_amount_all_subjects, $subject_id['amount_tasks']);
}

if(@$_POST['create_sub'] && isset($_POST['sub_name']) && isset($_POST['tasks_amount'])) {

    $isExist = array_search($_POST['sub_name'], $title_all_subjects);
    // $check = $sub_check->num_rows;
    if (!$isExist) {

    	$dbconnect->query("INSERT INTO subjects VALUES('', '$_POST[tasks_amount]', '$_POST[sub_name]', 'test')");
    	$subject_id = mysqli_insert_id($dbconnect);

    	for ($i = 0; $i < $_POST['tasks_amount']; $i++) {
    		$group = "group$i";
    		$additional = "additional$i";
    		if (isset($_POST[$additional])) {
    			$dbconnect->query("INSERT INTO subject_task VALUES('', '$subject_id', '$i', '$_POST[$group]', '$_POST[$additional]')");
    		}
    	}
        
    }
}



?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form">
<input type="text" name="sub_name" id="sub_name" placeholder="Subject name">
<input type="number" max="50" min="1" name="tasks_amount" id="tasks_amount" placeholder="Tasks amount" oninput="setTasksAmount(this)">
<input type="submit" id="create_sub" name="create_sub" value="Создать">
<div id="type_of_answers"></div>


</form>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script type="text/javascript">
	$.fn.htmlTo = function(elem) {
	    return this.each(function() {
	        $(elem).html($(this).html());
	    });
	}

	function setTasksAmount(input) {
		if (input.value > 50) {
	        input.value = 50;
    	} else if (input.value < 1)
    		input.value = 1;
		


		generateTypeOfAnswer(input.value);
	}

	function generateTypeOfAnswer(amount) {
		var $table = $('<table class="table" id="table_subjects" style="margin-bottom: 0">');
        
        $table.append('<thead>').children('thead');

        var $tbody = $table.append('<tbody />').children('tbody');
        for (var i = 0; i < amount; i++) {
	        $tbody.append('<tr />').children('tr:last');
	        $tbody.append('<td style=\" padding:20px \"><fieldset id="group' + i + '"><input type="radio" onclick="setAdditionalInfo(true, ' + i + ')" value="checkboxes" name="group' + i + '">множественный выбор из предложенных вариантов(checkboxes)<br><input type="radio" value="radio_btn" onclick="setAdditionalInfo(true, ' + i + ')" name="group' + i + '">единичный выбор(radio_btn)<br><input type="radio" value="single_line" onclick="setAdditionalInfo(false, ' + i + ')" name="group' + i + '" checked>одно поле в одну строку для ввода текста(single_line)<br><input type="radio" onclick="setAdditionalInfo(true, ' + i + ')" value="few_line" name="group' + i + '">несколько полей в одну строку для ввода текста(few_line)<br><input type="radio" onclick="setAdditionalInfo(false, ' + i + ')" value="text_area" name="group' + i + '">большое поле в несколько строк для ввода текста(text_area)<br><input type="number" value="" name="additional' + i + '" id="additional' + i + '" style="display:none" value="1"></fieldset></td>');
    	}
        $table.htmlTo('#type_of_answers');
	}

	function setAdditionalInfo(show, index) {
		var id = "additional" + index.toString();
		var input = document.getElementById(id);
		if (show) {
			input.style.display = 'block';
		} else {
			input.style.display = 'none';
		}
	}


</script>