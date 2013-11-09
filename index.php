<!DocType html>
<html>
<head>
	<meta charset="UTF-8">
	<title>PHP5 Hash Check</title>
	<meta name="description" content="A speed benchmark and security comparison for all the available Hash Algorithms for any PHP version >=5.1.2">
	<meta name="keywords" content="speed benchmark security comparison available Hash Algorithms PHP version">
	<style>
		body {
			max-width: 980px;
			width: 90%;
			margin: 5% auto;
			padding: 0;
			font: 300 1em/1.4 Ubuntu, 'lucida sans unicode', 'lucida grande', 'Trebuchet MS', verdana, arial, helvetica, helve, sans-serif;
			text-align: center;
		}
		.description { max-width: 70%; margin: 3% auto; }
		h1 { font-weight: 300; }
		p { text-align: left; }
		a { color: #2980b9; text-decoration: none; }
		code { font-family: 'Ubuntu Mono', monospace; }
		.caption { font-size: 0.9em; font-style: italic; color: #7f8c8d; text-align: center; }
		table { width: 100%; border-spacing: 0; }
		th { cursor: pointer; font-weight: 300; padding: 20px 10px; }
		thead tr:nth-child(odd) { background: transparent; }
		tr:nth-child(odd) { background: #ecf0f1; }
		td { padding: 10px; font-weight: 300; }
		.hi { color: #fefefe; } 				/* White */
		.insecure { background: #e74c3c; } 	/* Red */
		.good { background: #27ae60; } 		/* Green */
		.best { background: #2980b9; } 		/* Blue */
		.header.headerSortUp:after { content: " ↑"; }
		.header.headerSortDown:after { content: " ↓"; }
	</style>
</head>
<body>
	<div class="description">
		<h1>PHP5 Hash Check</h1>
		<p class="caption">A speed benchmark and security comparison for all the available Hash Algorithms for any PHP version >=5.1.2</p>
		<br>
		<p>This page serves to speed benchmark all the available Hash Algorithms for this PHP version (<?php echo phpversion(); ?>). The PHP Script (<a href="https://github.com/eustasy/hash-check">Source available on GitHub</a>) randomly generates a 18 character password and 64 character salt from the following digits.</p>
		<br>
		<code>abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"£$%^&*()-_+=|`¬,.<>/?~#[]{}@'\</code>
		<p class="caption">Like you'd be so lucky as to get a user with a random password generated from these.</p>
		<br>
		<p>It then hashes the password and salt, adds the resulting hashes together, then hashes that too (just for good measure). Then it does the whole things again, nine-hundred and ninety-nine more times. The resulting table (shown below) is automatically sorted by Hash Length and Time Taken (both of which are better longer).</p>
		<br>
		<code>$Hash_Result = hash( $Hash_Algo, hash( $Hash_Algo, $Pass, false) . hash( $Hash_Algo, $Salt, false ), false );</code>
		<p class="caption">This is how most of our user logins are handled, so provides a realistic benchmark.</p>
		<br>
		<p></p>
	</div>
	<table id="sort" class="tablesorter">
		<thead>
			<tr>
				<th>Hash</th>
				<th>Length</th>
				<th>Notes</th>
				<th>Timing (&micro;s per thousand)</th>
			</tr>
		</thead>
		<tbody>
<?php

	$Characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"£$%^&*()-_+=|`¬,.<>/?~#[]{}@\'\\';
	$Characters_Count = strlen( $Characters );

	// Generate Pass
	for( $c = 0; $c < 12; $c++ ) {
		$Pass .= $Characters[ rand( 0, $Characters_Count - 1 ) ];
	}

	// Generate Salt
	for( $c = 0; $c < 64; $c++ ) {
		$Salt .= $Characters[ rand( 0, $Characters_Count - 1 ) ];
	}

	// Get the Available Hashes
	$Hash_Algos = hash_algos();

	foreach( $Hash_Algos as $Hash_Algo ) {

		// Start Counting
		ob_start();
		$Start = microtime(true);


		// Run 1,000 times
		$i = 0;
		while( $i < 1000 ) {
			// Hash
			$Hash_Result = hash( $Hash_Algo, hash( $Hash_Algo, $Pass, false) . hash( $Hash_Algo, $Salt, false ), false );
			++$i;
		}

		// Stop Counting
		$Count = round((microtime(true) - $Start)*1000, 1, PHP_ROUND_HALF_UP);
		ob_end_clean();
		$Hash_Length = strlen( $Hash_Result );

		echo '
		<tr>
			<td>'.$Hash_Algo.'</td>
			<td';
			if( $Hash_Length>=128 ) { // Blue
				echo ' class="hi best"';
			} else if( $Hash_Length>=64 ) { // Green
				echo ' class="hi good"';
			} else { // Red
				echo ' class="hi insecure"';
			}
		echo '>'.$Hash_Length.'</td>
			<td';

		// # Warnings #
		// Manual Overrides
		if(
			$Hash_Algo == 'adler32' ||
			$Hash_Algo == 'crc32' ||
			$Hash_Algo == 'crc32b' ) {
			echo ' class="hi insecure">Warning: Checksum. Not for passwords.';
		} elseif(
			$Hash_Algo == 'ripemd128' ||
			$Hash_Algo == 'ripemd256' ||
			$Hash_Algo == 'sha1' ||
			$Hash_Algo == 'snefru' ||
			$Hash_Algo == 'snefru256' ) {
			echo ' class="hi insecure">Warning: Questionable Security.';
		} elseif(
			$Hash_Algo == 'md2' ||
			$Hash_Algo == 'md4' ||
			$Hash_Algo == 'md5' ||
			$Hash_Algo == 'haval128,3' ) {
			echo ' class="hi insecure">Warning: No longer considered secure.';
		} elseif( $Hash_Algo == 'sha512' ) {
			echo ' class="hi best">Recommended: We use this.';
		} elseif( $Hash_Algo == 'whirlpool' ) {
			echo ' class="hi best">Recommended: Based on AES.';
		} elseif( $Hash_Algo == 'ripemd320' ) {
			echo ' class="hi good">Good: Based on original RIPEMD.';
		} elseif( $Hash_Algo == 'gost' ) {
			echo ' class="hi good">Good: Based on DES.';
		} else {
			echo '>';
		}

		echo '</td>
			<td';

		if( $Count>12 ) { // Blue
			echo ' class="hi best"';
		} else if( $Count>6 ) { // Green
			echo ' class="hi good"';
		} else {
			echo ' class="hi insecure"';
		}

		echo '>'.$Count.'</td>
		</tr>';

	}

?>
		</tbody>
	</table>
	<script src="http://labs.eustasy.org/jquery.min.js"></script>
	<script src="http://labs.eustasy.org/jquery.tablesorter.min.js"></script>
	<script>$(document).ready(function() { $("#sort").tablesorter( {sortList: [[1,1], [3,1]]} ); });</script>
</body>
</html>
