<?php
include 'config.php';

if(isset($_POST['start']) && !isset($_POST['feed'])) { // will be executed when start/restart button clicked

	if(isset($_SESSION['game_id'])) {
		session_destroy(); // destroy previous session game data
	}

	session_start();

	$_SESSION['game_id'] = rand(); // create random game id
	$_SESSION['entities'] = ENTITIES; // entities in game
	$_SESSION['rounds'] = array(); // holds current rounds data
	$_SESSION['feed_status'] = array(); // holds feed status data
	$_SESSION['dead_entities'] = array(); // holds dead entities
	$_SESSION['game_status'] = ''; // hold game status
}

header('Location: index.php');