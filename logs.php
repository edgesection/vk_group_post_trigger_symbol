<!DOCTYPE html>
<html>
<head>
	<title>ES System</title>
	<style>
		body{
			background: #e6e6e6;
		}
		
		div.active{
		    position: relative;
			padding: 10px 0px;
			display: block;
		}
		div.active span{
			position: relative;
			display: block;
		}
		
		div.table{
		    position: relative;
			border: 3px solid #303030;
			background: white
		}
		div.table div.name{
		    font-weight: bold;
			border-bottom: 3px solid #303030;
			padding: 5px;
			box-sizing: border-box;
		}
		div.table div.log{
		    padding: 5px;
			box-sizing: border-box;
		}
		div.table div.name span:first-child, div.table div.log span:first-child{
		    position: relative;
			width: 30%;
			display: inline-block;
		}
		div.table div.name span:last-child, div.table div.log span:last-child{
		    position: relative;
			display: inline-block;
			width: 69%;
		}
	</style>
</head>
<body>
<?php

	date_default_timezone_set('Europe/Moscow');

	$connect = mysqli_connect("localhost", "login", "password", "db") or die("<h3>err_connect</h3>");

	//Создание таблиц, если их нет
	mysqli_query($connect, "CREATE TABLE IF NOT EXISTS `lastActive` (`id` INT PRIMARY KEY AUTO_INCREMENT, `time` INT)");
	mysqli_query($connect, "CREATE TABLE IF NOT EXISTS `groups` (`id` INT PRIMARY KEY AUTO_INCREMENT, `idGroup` INT, `idPost` INT, `logs` TEXT, `time` INT)");

	$infoActive = mysqli_query($connect, "SELECT * FROM `lastActive`");

	$timeTask = array();

	echo '<div class="active">';
	while($ia = mysqli_fetch_assoc($infoActive)){
		if($ia['id'] == 1){
			echo '<span>Начало последней итерации: <b>'.date("H:i d.m.Y", $ia['time']).'</b></span>';
			array_push($timeTask, $ia['time']);
		}else if($ia['id'] == 2){
			echo '<span>Конец последней итерации: <b>'.date("H:i d.m.Y", $ia['time']).'</b></span>';
			array_push($timeTask, $ia['time']);
		}
	}
	if(($timeTask[1] - $timeTask[0]) < 0){
		echo '<span id="task">Время выполнения итерации: <b>выполняется</b></span>';
	}else{
		echo '<span id="task">Время выполнения итерации: <b>'.($timeTask[1] - $timeTask[0]).' seconds</b></span>';
	}
	echo '</div>';

	$token = ""; //Токен группы
	$userToken = ""; //Токен пользователя
	
	$getLogs = mysqli_query($connect, "SELECT * FROM `groups` ORDER BY `id` DESC LIMIT 50");
	
	echo "
		<div class='table'>
		<div class='name'>
			<span>Время</span>
			<span>Ссылка</span>
		</div>
	";
	
	while($log = mysqli_fetch_assoc($getLogs)){
		
		echo '
			<div class="log">
				<span>'.date("H:i d.m.Y", $log['time']).'</span>
				<span><a href="https://vk.com/public'.$log['idGroup'].'?w=wall-'.$log['idGroup'].'_'.$log['idPost'].'_r'.json_decode($log['logs'])->response->comment_id.'" target="_blank">https://vk.com/public'.$log['idGroup'].'?w=wall-'.$log['idGroup'].'_'.$log['idPost'].'_r'.json_decode($log['logs'])->response->comment_id.'</a></span>
			</div>
		';
		
	}
	
	echo "</div>";

?>
</body>
</html>