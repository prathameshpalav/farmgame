<?php

function makeDead($entity) {
	$_SESSION['feed_status'][$entity] = 'dead';
	$rounds_array[$entity] = 'dead';
	array_push($_SESSION['dead_entities'], $entity);
}