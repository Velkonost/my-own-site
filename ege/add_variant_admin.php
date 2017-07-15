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

$all_types_of_answers = [];
$all_additional = [];

$all_subjects_max_id_variant = [];

while ($subject_id = ($all_subjects -> fetch_array())) {
	array_push($id_all_subjects, $subject_id['id']);
	array_push($title_all_subjects, $subject_id['title']);
	array_push($task_amount_all_subjects, $subject_id['amount_tasks']);
	
	$variant = $dbconnect->query("SELECT MAX(variant_id) FROM subject_variants WHERE subject_id='". $subject_id['id'] ."' ");
		
	$variant_id = $variant->fetch_array();
	if(isset($variant_id[0])) {
		array_push($all_subjects_max_id_variant, $variant_id[0]);
	} else {
		array_push($all_subjects_max_id_variant, 0);
	}
	

	$types = [];
	$additional = [];
	for ($i = 0; $i < $subject_id['amount_tasks']; $i ++) {
		$task = $dbconnect->query("SELECT * FROM subject_task WHERE subject_id='". $subject_id['id'] ."' AND number='". $i ."'");
		$task_info = $task->fetch_array();
		array_push($types, $task_info['type_of_answer']);
		array_push($additional, $task_info['additional']);

	}
	array_push($all_types_of_answers, $types);
	array_push($all_additional, $additional);
}



$max_amount = max($task_amount_all_subjects);

if(@$_POST['add_variant'] && isset($_POST['sub_id']) && isset($_POST['variant_id'])) {
	$variant = $dbconnect->query("SELECT * FROM subject_variants WHERE variant_id='".$_POST['variant_id']."' AND subject_id='".$_POST['sub_id']."' ");
	$check_variant = $variant->fetch_array();
	
	if(!isset($check_variant['subject_id'])) {
		$dbconnect->query("INSERT INTO subject_variants VALUES('', '$_POST[sub_id]', '$_POST[variant_id]')");


		$index = array_search($_POST['sub_id'], $id_all_subjects);
		$tasks_amount = $task_amount_all_subjects[$index];
		
		$tasks_types = $all_types_of_answers[$index];
		$tasks_additionals = $all_additional[$index];
		
		for ($i = 0; $i < $tasks_amount; $i ++) {
			$title_name = "title".$i;
			$title = $_POST[$title_name][0];

			$description_name = "description".$i;
			$description = $_POST[$description_name][0];

			$answer_name = "answer".$i;
			$answer = $_POST[$answer_name][0];

			$dbconnect->query("INSERT INTO task_variants VALUES('', '$_POST[sub_id]', '$_POST[variant_id]', '$title', '$description', '$i', '$answer')");
			$task_id = mysqli_insert_id($dbconnect);

			if ($all_types_of_answers[$index][$i] == "checkboxes" || $all_types_of_answers[$index][$i] == "radio_btn") {
				for ($j = 0; $j < $tasks_additionals[$i]; $j ++) {
					$name = "task$i".$all_types_of_answers[$index][$i];
					$name = $_POST[$name][$j];
					echo $name;
					$dbconnect->query("INSERT INTO meta_tasks VALUES('', '$task_id', '$j', '$name')");
				}
			}
				
		}
	} 

}


?>
<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
	    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="material/chips.css">
<link rel="stylesheet" href="material/material.min.css">
<!-- <link rel="stylesheet" href="material/styles.css"> -->
<link rel="stylesheet" href="material/preloader.css">
<!-- <link rel="stylesheet" href="material/card.css"> -->
<!-- <link rel="stylesheet" href="css/media.css"> -->
<!-- <link rel="stylesheet" href="material/expand_card.css"> -->

<!-- <link rel="stylesheet" href="material/dialog.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="material/table.css"> -->

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<div id="add_new_sub" class="android-search-box mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right mdl-textfield--full-width right" style="margin:auto">
        			<label class="mdl-button mdl-js-button mdl-button--icon" for="input_new_sub" style="float:right;">
          				<i class="material-icons" id="add_new_sub_btn">add</i>
        			</label>
        			<div class="mdl-textfield__expandable-holder" style="float: right; width: 900px">
          				<input class="mdl-textfield__input autocomplete" name="input_new_sub" type="text" id="input_new_sub" oninput="searchSubjects()" style="padding: 0">
        			</div>
      			</div>
<div class="demo" id="demo_add" >
	<div class="">
	<input type='text' name='variant_id' id="variant_id" />
	<input type='submit' name='add_variant' id="add_variant" value='Добавить вариант' />
	<table class="table" id="table_subjects" style="margin-bottom: 0;">
	</table>
	<table class="table" id="table_answers" style="margin: 0; padding: 0">
	</table>
	  	
	</div>
</div>
<input type='hidden' name='sub_id' id="sub_id" style="display: none" />
<?php 
	for ($i = 0; $i < $max_amount; $i ++) {
		echo "<input type='hidden' name='task".$i."checkboxes[]' style=\"display: none\" />";		
		echo "<input type='hidden' name='task".$i."radio_btn[]' style=\"display: none\" />";		
		echo "<input type='hidden' name='task".$i."few_line[]' style=\"display: none\" />";
		echo "<input type='hidden' name='task".$i."single_line[]' style=\"display: none\" />";
		echo "<input type='hidden' name='task".$i."text_area[]' style=\"display: none\" />";
		echo "<input type=\"hidden\" name=\"title".$i."[]\">";
        echo "<input type=\"hidden\" name=\"description".$i."[]\">";
        echo "<input type=\"hidden\" name=\"answer".$i."[]\">";
	}

?> 
</form>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="material/material.min.js"></script>
<script src="material/table.js"></script>
<script src="material/card.js"></script>
<script src="material/dialog.js"></script>
<script src="material/expand_card.js"></script>
<script type="text/javascript">

	$.fn.htmlTo = function(elem) {
	    return this.each(function() {
	        $(elem).html($(this).html());
	    });
	}
	
	var subjects_title = [
		<?php
			for($i = 0; $i < sizeof($title_all_subjects); $i++) {
				echo "'$title_all_subjects[$i]',";
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

    var subjects_tasks_amount = [
        <?php
            for($i = 0; $i < sizeof($task_amount_all_subjects); $i++) {
                echo "'$task_amount_all_subjects[$i]',";
            }
         ?>
    ];

    var all_subjects_max_id_variant = [
    	<?php
            for($i = 0; $i < sizeof($all_subjects_max_id_variant); $i++) {
                echo "'$all_subjects_max_id_variant[$i]',";
            }
         ?>
    ];

    var all_types_of_answers = [];

	<?php for($i = 0; $i < sizeof($all_types_of_answers); $i++) { ?>
	var types_of_answer = [
		<?php
			for ($j = 0; $j < sizeof($all_types_of_answers[$i]); $j++) {
				echo "'" .$all_types_of_answers[$i][$j]."',";	
			} ?>
	];
	all_types_of_answers.push(types_of_answer);		
	<?php } ?>

	var all_additional = [];

	<?php for($i = 0; $i < sizeof($all_additional); $i++) { ?>
	var additional = [
		<?php
			for ($j = 0; $j < sizeof($all_additional[$i]); $j++) {
				echo "'" .$all_additional[$i][$j]."',";	
			} ?>
	];
	all_additional.push(additional);		
	<?php } ?>

	

	generateSubjectsTable(subjects_title, subjects_ids);
	function generateSubjectsTable(accepted_subjects_title, ids) {
		var $table = $('<table class="table" id="table_subjects" style="margin-bottom: 0">');
		
		$table.append('<thead>').children('thead')

		var $tbody = $table.append('<tbody />').children('tbody');

		var subjects_count = accepted_subjects_title.length;
		
		for (var i = 0; i < subjects_count;) {
			$tbody.append('<tr />').children('tr:last');
			if (i < subjects_count) {
				$tbody.append("<td><div class=\"md-chip\" name=\" chip_sub \" onclick=\"setSubject(" + ids[i] + ", this)\"><span class=\"md-chip-text\">" + accepted_subjects_title[i] + "</span></div></td>");
				i ++;
			}
			if (i < subjects_count) {
				$tbody.append("<td><div class=\"md-chip\" name=\" chip_sub \" onclick=\"setSubject(" + ids[i] + ", this)\"><span class=\"md-chip-text\">" + accepted_subjects_title[i] + "</span></div></td>");
				i ++;
			}
			if (i < subjects_count) {
				$tbody.append("<td><div class=\"md-chip\" name=\" chip_sub \" onclick=\"setSubject(" + ids[i] + ", this)\"><span class=\"md-chip-text\">" + accepted_subjects_title[i] + "</span></div></td>");
				i ++;
			}
			if (i < subjects_count) {
				$tbody.append("<td><div class=\"md-chip\" name=\" chip_sub \" onclick=\"setSubject(" + ids[i] + ", this)\"><span class=\"md-chip-text\">" + accepted_subjects_title[i] + "</span></div></td>");
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

	function setSubject(id, smth) {
		var chips = document.getElementsByName(' chip_sub ');
		for (var i = 0; i < chips.length; i++) {
			chips[i].style.background = '#ffffff';
		}

		document.getElementById('sub_id').value = id;
		
		var index = subjects_ids.indexOf(id.toString());
        var title = subjects_title[index];

        document.getElementById('variant_id').value = (Number(all_subjects_max_id_variant[index]) + 1);

        smth.style.background = '#8EB0BC';

        var $table = $('<table class="table" id="table_subjects" style="margin-bottom: 0">');
        $table.append('<thead>').children('thead')

        var $tbody = $table.append('<tbody />').children('tbody');

        for (var i = 0; i < all_types_of_answers[index].length; i++) {
        	if (i % 3 == 0) $tbody.append('<tr />').children('tr:last');
        	var to_append = "";
        	to_append += "Задание №" + (i + 1) + "<br>";
        	to_append += '<input type="text" placeholder="title" name="title' + i + '[]" id="title' + i + '"><br>';
        	to_append += '<input type="text" placeholder="description" name="description' + i + '[]" id="description' + i + '"><br>';
        	
        	switch(all_types_of_answers[index][i]) {
        		case 'checkboxes':
        			to_append += '<input type="text" placeholder="answer" name="answer' + i + '[]" id="answer' + i + '"><br>';
        			to_append += "Варианты ответов:<br>";
        			for (var j = 0; j < all_additional[index][i]; j++) {
        				to_append += "<input type=\"checkbox\" disabled><input type=\"text\" name=\"task" + i + all_types_of_answers[index][i] + "[]\" id=\"task" + i + all_types_of_answers[index][i] + j +" \"><br> ";
        			}
        			break;
        		case 'radio_btn':
        			to_append += '<input type="text" placeholder="answer" name="answer' + i + '[]" id="answer' + i + '"><br>';
        			to_append += 'Варианты ответов:<br><fieldset id="group' + i + '">';
        			for (var j = 0; j < all_additional[index][i]; j++) {
        				to_append += "<input type=\"radio\" disabled><input type=\"text\" name=\"task" + i + all_types_of_answers[index][i] + "[]\" id=\"task" + i + all_types_of_answers[index][i] + j +" \"><br> ";
        			}
        			to_append += "</fieldset>";
        			break;
        		case 'single_line':
        			to_append += '<input type="text" placeholder="answer" name="answer' + i + '[]" id="answer' + i + '"><br>';
        			break;
        		case 'few_line':
        			to_append += '<input type="text" placeholder="answer" name="answer' + i + '[]" id="answer' + i + '"><br>';
        			to_append += "Количество полей:<br>";
        			for (var j = 0; j < all_additional[index][i]; j++) {
        				to_append += "<input disabled type=\"text\" name=\"task" + i + all_types_of_answers[index][i] + " \" id=\"task" + i + all_types_of_answers[index][i] + j +" \"><br> ";
        			}
        			break;
        		case 'text_area':
        			to_append += '<textarea placeholder="answer" name="answer' + i + '[]" id="answer' + i + '"></textarea><br>';
        			break;

        	}
        	$tbody.append('<td style="padding:20px">' + to_append + '<hr></td>');
        	
       		
        
        }
        $table.htmlTo('#table_answers');

		// console.log(all_types_of_answers[index]);
	}

	function searchSubjects() {
		var to_search = $('input[name="input_new_sub"]').val();

        var accepted_subjects_title = [];
        var accepted_subjects_id = [];

        for (var i = 0; i < subjects_title.length; i++) {
            if (subjects_title[i].indexOf(to_search) != -1) {
                accepted_subjects_title.push(subjects_title[i]);
                accepted_subjects_id.push(subjects_ids[i]);
            }
        }
        
        generateSubjectsTable(accepted_subjects_title, accepted_subjects_id);
        if (accepted_subjects_title.length == 0) {
          generateSubjectWarning();
        }
	}

</script>
