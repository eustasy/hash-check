<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require __DIR__.'/generator.string.php';
require __DIR__.'/generator.string.secure.php';

?><!DocType html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="cleartype" content="on">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="author" content="https://google.com/+LewisGoddard">
	<link rel="publisher" href="https://plus.google.com/+EustasyOrg">
	<link rel="canonical" href="http://labs.eustasy.org/hash-check/">
	<link rel="icon" href="http://labs.eustasy.org/favicon.ico">
	<link rel="shortcut icon" href="http://labs.eustasy.org/favicon.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/normalize,colors.css">
	<style>
		body {
			margin: 10vh auto;
			max-width: 880px;
			width: 80vw;
		}
		input[type="number"],
		select {
			display: block;
			margin: 1vh 0;
			text-align: right;
		}
		.column-left,
		.column-right {
			float: left;
			max-width: 440px;
			width: 40vw;
		}
		input[type="number"] {
			-moz-appearance: textfield;
		}
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>
	<title>Hash Iterator</title>
</head>
<body>
	<h1>Hash Iterator</h1>
	<div class="column-left">
		<form action="" method="POST">
			<label for="pass">Password</label>
			<input required type="number" name="pass" placeholder="10" value="<?php echo !empty($_POST['pass']) ? $_POST['pass'] : '10'; ?>">
			<label for="pass">Salt</label>
			<input required type="number" name="salt" placeholder="64" value="<?php echo !empty($_POST['salt']) ? $_POST['salt'] : '64'; ?>">
			<label for="pass">Iterations</label>
			<input required type="number" name="iterate" placeholder="100000" value="<?php echo !empty($_POST['iterate']) ? $_POST['iterate'] : '100000'; ?>">
			<label for="pass">Algorithm</label>
			<select required name="algorithm">

			<?php

				$hash['options'] = hash_algos();

				foreach( $hash['options'] as $i ) {
					if (
						!empty($_POST['algorithm']) &&
						in_array($_POST['algorithm'], $hash['options'])
					) {
						$hash['preferred'] = $_POST['algorithm'];
					} else {
						$hash['preferred'] = 'sha512';
					}
					if ( $i == $hash['preferred'] ) {
						echo '<option value="'.$i.'" selected>'.$i.'</option>';
					} else {
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				}

			?>

			</select>
			<input type="reset">
			<input type="submit" value="Run">
		</form>
	</div>
	<div class="column-right">

<?php

if (
	!empty($_POST['pass']) &&
	!empty($_POST['salt']) &&
	!empty($_POST['iterate']) &&
	$_POST['pass'] > 0 &&
	$_POST['salt'] > 0 &&
	$_POST['iterate'] > 0 &&
	!empty($_POST['algorithm']) &&
	in_array($_POST['algorithm'], $hash['options'])
) {

	$hash['algorithm'] = $_POST['algorithm'];
	$hash['iterate'] = intval($_POST['iterate']);

	$hash['pass'] = Generator_String(intval($_POST['pass']), true, true);
	$hash['salt'] = Generator_String_Secure(intval($_POST['salt']), true, true);

	ob_start();
	$time['start'] = microtime(true);
	$hash['previous'] = '';

	for( $i = 0; $i < $hash['iterate']; $i++ ) {
		$hash['previous'] = hash(
			$hash['algorithm'],
			$hash['pass'].$hash['salt'].$hash['previous'],
			false //
		);
	}

	$time['end'] = microtime(true);
	$time['taken'] = $time['end'] - $time['start'];
	ob_end_clean();

	echo '<h3 class="secs">'.round($time['taken'], 1, PHP_ROUND_HALF_UP).' seconds</h3>';
	echo '<p class="faded micro">'.round($time['taken']*1000, 1, PHP_ROUND_HALF_UP).' microseconds</p>';

} else {
	echo '<h3>No valid POST data.</h3>';
	if ( empty($_POST['pass']) ) {
		echo '<p>Password was empty.</p>';
	} else if ( $_POST['pass'] <= 0 ) {
		echo '<p>Password was zero or negative.</p>';
	}
	if ( empty($_POST['salt']) ) {
		echo '<p>Salt was empty.</p>';
	} else if ( $_POST['salt'] <= 0 ) {
		echo '<p>Salt was zero or negative.</p>';
	}
	if ( empty($_POST['iterate']) ) {
		echo '<p>Iteration was empty.</p>';
	} else if ( $_POST['iterate'] <= 0 ) {
		echo '<p>Iteration was zero or negative.</p>';
	}
	if ( empty($_POST['algorithm']) ) {
		echo '<p>Algorithm was empty.</p>';
	} else if ( in_array($_POST['algorithm'], $hash['options']) ) {
		echo '<p>Algorithm was not available.</p>';
	}
}

?>

	</div>
</body>
</html>