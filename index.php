<?php session_start(); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Farm Game</title>
	</head>
	<body>
		<?php
			if(isset($_SESSION['game_id'])) {
				?>
				<table border="1">
					<thead>
						<tr>
							<th>Round</th>
							<?php 
							foreach ($_SESSION['entities'] as $entity) {
								$style = in_array($entity, $_SESSION['dead_entities']) ?  "style='background-color:red'" : "";
								echo "<th $style>$entity</th>";
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(!empty($_SESSION['rounds'])) {
							foreach ($_SESSION['rounds'] as $key => $round) {
								echo "<tr>
									<td>".($key+1)."</td>";
									
								foreach ($round as $value) {
									echo "<td>$value</td>";
								}
								echo "</tr>";
							}
						} else {
							echo "<tr><td colspan='".(count($_SESSION['entities'])+1)."'>Click on Feed button to start</td></tr>";
						}
						?>
					</tbody>
				</table>

				
				<form action="feed.php" method="post">
					<?php 
						if(!empty($_SESSION['game_status'])) {
							echo $_SESSION['game_status']."<br>";
					 	} else {
							?>
							<input type="submit" name="feed" value="Feed">
							<?php 
						}
					?>
				</form>
				<?php 
			}
		?>
	
		<form method="post" action="start_game.php">	
			<input type="submit" name="start" value="<?php echo isset($_SESSION['game_id']) ? 'Restart Game' : 'Start Game';?>">
		</form>

	</body>
</html>