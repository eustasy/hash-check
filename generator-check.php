<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require __DIR__.'/generator.string.secure.php';

if ( isset($_POST['iterate']) ) {
	$iterate = intval($_POST['iterate']);
} else {
	$iterate = 1000000;
}

if ( isset($_POST['length']) ) {
	$length = intval($_POST['length']);
} else {
	$length = 2;
}

for ( $i = 0; $i < $iterate; $i++ ) {
	$N = Generator_String_Secure($length);
	if ( isset($Results[$N]) ) {
		$Results[$N]++;
	} else {
		$Results[$N] = 1;
	}
}

ksort($Results, SORT_NATURAL);

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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/gh/eustasy/Colors.css@1/colors.min.css,gh/necolas/normalize.css@8/normalize.min.css">
	<style>
		body {
			margin: 10vh auto;
			max-width: 880px;
			text-align: left;
			width: 80vw;
		}
		input[type="number"] {
			display: block;
			margin: 1vh 0;
			text-align: right;
		}
		input[type="number"] {
			-moz-appearance: textfield;
		}
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
		hr {
			border: 1px solid #999;
			margin: 10vh auto;
		}
	</style>
	<script src="https://cdn.jsdelivr.net/gh/chartjs/Chart.js@2/dist/Chart.bundle.min.js"></script>
	<title>Generator Checker</title>
</head>
<body>
	<h1>Generator Checker</h1>
	<form action="" method="POST">
		<label for="length">Length</label>
		<input required type="number" name="length" placeholder="2" value="<?php echo !empty($_POST['length']) ? $_POST['length'] : '1'; ?>">
		<label for="pass">Iterations</label>
		<input required type="number" name="iterate" placeholder="1000000" value="<?php echo !empty($_POST['iterate']) ? $_POST['iterate'] : '1000000'; ?>">
		<input type="submit" value="Run">
	</form>
	<hr>
	<?php
	highlight_string('<?php
function Generator_String_Secure($Length = 64) {
	$String = substr(
		bin2hex(
			openssl_random_pseudo_bytes(
				ceil(
					$Length / 2
				)
			)
		),
		0,
		$Length
	);
	return $String;
}');
	?>

	<hr>
	<canvas id="generated" width="880" height="500"></canvas>
	<pre><?php var_dump($Results); ?></pre>
	<script>
		var data = {
			labels : [<?php
				foreach ( $Results as $Key => $Value ) {
					echo '
				"'.$Key.'",';
				}
			?>

			],
			datasets : [
				{
					label: "Occurences of Possibility",
					fillColor: "#333",
					highlightFill: "#666",
					data : [
					<?php
						foreach ( $Results as $Value ) {
							echo '
						'.$Value.',';
						}
					?>

					]
				}
			]
		}
		var options = {
			barShowStroke: false,
			barValueSpacing: 0,
			barShowLables: false,
		}
		var generated = document.getElementById('generated').getContext('2d');
		var myChart = new Chart(generated, {
			type: 'bar',
			data,
			options
		});
	</script>
</body>
</html>
