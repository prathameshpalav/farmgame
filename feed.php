<?php
session_start();

include 'config.php';
include 'helper_functions.php';

if(isset($_SESSION['game_id']) && isset($_POST['feed'])) {// will be executed when feed button clicked
	
	// stop game if game rounds over
	if(count($_SESSION['rounds']) == GAME_ROUNDS-1){		
		$_SESSION['game_status'] = 'Rounds over. Game over.';		

		// checking status after all rounds done
		// farmer and atleast one animal should exists for win
		if(!in_array('farmer', $_SESSION['dead_entities'])) {

			$cow_array = preg_grep("/^cow(.*?)$/", $_SESSION['dead_entities']);
			$bunny_array = preg_grep("/^bunny(.*?)$/", $_SESSION['dead_entities']);

			if(!empty($cow_array) && !empty($bunny_array)) {
				$_SESSION['game_status'] = 'Congratulations. You won the game.';
			}	
		}		
	}

	$live_entities = array_diff(ENTITIES, $_SESSION['dead_entities']); // decide live entities
	$feed_to = $live_entities[array_rand($live_entities)]; // randomly selecting live entity to feed

	$rounds_array = array();

	foreach (ENTITIES as $entity) {

		$rounds_array[$entity] = '-'; // set default blank value

		// increment counter of feed status for each entity except dead ones
		if($_SESSION['feed_status'][$entity] !== 'dead') {
			$_SESSION['feed_status'][$entity] = isset($_SESSION['feed_status'][$entity]) ? ($_SESSION['feed_status'][$entity]+1) : 1; // increment counter
		}

		// set feed entry and reset feed status counter
		if($entity === $feed_to) {
			$rounds_array[$entity] = 'fed';
			$_SESSION['feed_status'][$entity] = 0; // reset counter
		}
		
		// checking dead condition for farmer
		if((strpos($entity, 'farmer') !== false) && $_SESSION['feed_status'][$entity] >= LIMITS['FARMER']) {
			$_SESSION['game_status'] = 'Farmer died. Game over.';
			makeDead($entity);
		}

		// checking dead condition for cow
		if((strpos($entity, 'cow') !== false) && $_SESSION['feed_status'][$entity] >= LIMITS['COW']) {
			makeDead($entity);
		}

		// checking dead condition for bunny
		if((strpos($entity, 'bunny') !== false) && $_SESSION['feed_status'][$entity] >= LIMITS['BUNNY']) {
			makeDead($entity);
		}		
	}
	
	array_push($_SESSION['rounds'], $rounds_array);
}

header('Location: index.php');
