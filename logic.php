<?php
session_start();

const GAME_ROUNDS = 50, LIMITS = array('FARMER' => 15, 'COW' => 10, 'BUNNY' => 8);
if(isset($_POST['start']) && !isset($_POST['feed'])) { // will be executed when start/restart button clicked

	if(isset($_SESSION['game_id'])) {
		session_destroy(); // destroy previous session game data
	}
	session_start();
	$_SESSION['game_id'] = rand(); // create random game id
	$_SESSION['entities'] = array('farmer', 'cow1', 'cow2', 'bunny1', 'bunny2', 'bunny3');
	$_SESSION['rounds'] = array(); // holds current rounds data
	$_SESSION['feed_status'] = array(); // holds feed status data
	$_SESSION['dead_entities'] = array(); // holds dead entities
	$_SESSION['game_rounds'] = GAME_ROUNDS; // total rounds for game
	$_SESSION['game_status'] = 'on'; // to maintain game statuss

} elseif(isset($_SESSION['game_id']) && isset($_POST['feed'])) {// will be executed when feed button clicked
	
	// stop game if game rounds over
	if(count($_SESSION['rounds']) == $_SESSION['game_rounds']-1){		
		$_SESSION['game_status'] = 'stop';
		$_SESSION['status_description'] = 'Rounds over. Game over.';		

		// checking status after all rounds done
		// farmer and atleast one animal should exists for win
		if(!in_array('farmer', $_SESSION['dead_entities']) && ((count($_SESSION['entities']) - 2) >= count($_SESSION['dead_entities']))) {			
			$_SESSION['status_description'] = 'Congratulations. You won the game.';	
		}		
	}

	$entities = $_SESSION['entities'];
	$live_entities = array_diff($entities, $_SESSION['dead_entities']); // decide live entities
	$feed_to = $live_entities[array_rand($live_entities)]; // randomly selecting live entity to feed

	$rounds_array = array();

	foreach ($entities as $value) {

		$rounds_array[$value] = '-'; // blank value

		// increment counter of feed status for each entity except dead ones
		if($_SESSION['feed_status'][$value] !== 'dead') {
			$_SESSION['feed_status'][$value] = isset($_SESSION['feed_status'][$value]) ? ($_SESSION['feed_status'][$value]+1) : 1; // increment counter
		}

		// set feed entry and reset feed status counter
		if($value === $feed_to) {
			$rounds_array[$value] = 'fed';
			$_SESSION['feed_status'][$value] = 0; // reset counter
		}
		
		// checking dead condition for farmer
		if((strpos($value, 'farmer') !== false) && $_SESSION['feed_status'][$value] >= LIMITS['FARMER']) {
			$_SESSION['game_status'] = 'stop';
			$_SESSION['status_description'] = 'Farmer died. Game over.';
			$_SESSION['feed_status'][$value] = 'dead';			
			$rounds_array[$value] = 'dead';
			array_push($_SESSION['dead_entities'], $value);
		}

		// checking dead condition for cow
		if((strpos($value, 'cow') !== false) && $_SESSION['feed_status'][$value] >= LIMITS['COW']) {
			$_SESSION['feed_status'][$value] = 'dead';
			$rounds_array[$value] = 'dead';
			array_push($_SESSION['dead_entities'], $value);
		}

		// checking dead condition for bunny
		if((strpos($value, 'bunny') !== false) && $_SESSION['feed_status'][$value] >= LIMITS['BUNNY']) {
			$_SESSION['feed_status'][$value] = 'dead';
			$rounds_array[$value] = 'dead';
			array_push($_SESSION['dead_entities'], $value);
		}		
	}
	
	array_push($_SESSION['rounds'], $rounds_array);
}

header('Location: index.php');