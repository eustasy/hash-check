<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require __DIR__.'/generator.string.php';

if ( isset($_POST['iterate']) ) {
	$iterate = intval($_POST['iterate']);
} else {
	$iterate = 1000000;
}

for ( $i = 0; $i < $iterate; $i++ ) {
	$N = Generator_String(1);
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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/normalize,colors.css">
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
	<script src='Chart.Core.js'></script>
	<script src='Chart.Bar.js'></script>
	<title>Generator Checker</title>
</head>
<body>
	<h1>Generator Checker</h1>
	<form action="" method="POST">
		<label for="pass">Iterations</label>
		<input required type="number" name="iterate" placeholder="1000000" value="<?php echo !empty($_POST['iterate']) ? $_POST['iterate'] : '1000000'; ?>">
		<input type="submit" value="Run">
	</form>
	<hr>
	<?php
	highlight_string('<?php // Iterate the number of letters needed
for ( $Iterate = 0; $Iterate < $Length; $Iterate++ ) {
	$String .= $String_Characters[hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % $String_Characters_Count];
}');
	?>

	<hr>
	<canvas id="generated" width="880" height="500"></canvas>
	<pre><?php var_dump($Results); ?></pre>
	<script>
		var generatedData = {
			labels : [<?php
				foreach ( $Results as $Key => $Value ) {
					echo '
				"'.$Key.'",';
				}
			?>

			],
			datasets : [
				{
					label: "My Second dataset",
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
			barValueSpacing: 1,
		}
		var generated = document.getElementById('generated').getContext('2d');
		new Chart(generated).Bar(generatedData, options);
	</script>
</body>
</html>