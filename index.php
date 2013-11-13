<!DocType html>
<html>
<head>

	<meta charset="UTF-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="cleartype" content="on">

	<title>PHP5 Hash Check</title>

	<meta name="description" content="A speed benchmark and security comparison for all the available Hash Algorithms for any PHP version >=5.1.2 with advisory notices and assisting rankings.">
	<meta name="keywords" content="speed benchmark security comparison available Hash Algorithms PHP version >=5.1.2 > = 5.1.2 5 1 2 advisory notices assisting rankings eustasy labs org ltd uk">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="author" content="https://google.com/+LewisGoddard">
	<link rel="publisher" href="https://plus.google.com/+EustasyOrg">
	<link rel="canonical" href="http://labs.eustasy.org/hash-check/">
	<link rel="icon" href="http://labs.eustasy.org/favicon.ico">
	<link rel="shortcut icon" href="http://labs.eustasy.org/favicon.ico">

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-45667989-11', 'eustasy.org');
	  ga('send', 'pageview');
	</script>

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
		code { font-family: 'Ubuntu Mono', monospace; word-wrap: break-word; }
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
		<p class="caption">A speed benchmark and security comparison for all the available Hash Algorithms for any PHP version >=5.1.2 with advisory notices and assisting rankings.</p>
		<br>
		<p>This page serves to speed benchmark all the available Hash Algorithms for this PHP version (<?php echo phpversion(); ?>). The PHP Script (<a href="https://github.com/eustasy/labs-hash-check">Source available on GitHub</a>) randomly generates a 18 character password and 64 character salt from the following digits.</p>
		<br>
		<code>abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 !"£$%^&*()-_+=|`¬,.<>/?~#[]{}@'\</code>
		<p class="caption">Like you'd be so lucky as to get a user with a random password generated from these.</p>
		<br>
		<p>It then hashes the password and salt, adds the resulting hashes together, then hashes that too (just for good measure). Then it does the whole things again, nine-hundred and ninety-nine more times. The resulting table (shown below) is automatically sorted by Hash Length and Time Taken (both of which are better longer).</p>
		<br>
		<code>$Hash_Result = hash( $Hash_Algo, hash( $Hash_Algo, $Pass, false) . hash( $Hash_Algo, $Salt, false ), false );</code>
		<p class="caption">This is how most of our user logins are handled, so provides a realistic benchmark.</p>
	</div>

	<table id="sort" class="tablesorter">
		<thead>
			<tr>
				<th>Hash</th>
				<th>Length</th>
				<th>Advisory</th>
				<th>Timing (&micro;s per thousand)</th>
			</tr>
		</thead>
		<tbody>
<?php

	$Characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 !"£$%^&*()-_+=|`¬,.<>/?~#[]{}@\'\\';
	$Characters_Count = strlen( $Characters );

	// Generate Pass
	$Pass = '';
	for( $c = 0; $c < 12; $c++ ) {
		$Pass .= $Characters[ rand( 0, $Characters_Count - 1 ) ];
	}

	// Generate Salt
	$Salt = '';
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

		// Hash Algorithm Output
		echo '
		<tr>
			<td>'.$Hash_Algo.'</td>
			<td';

		// Hash Length Outputs
		if( $Hash_Length>=128 ) { // Blue
			echo ' class="hi best"';
		} else if( $Hash_Length>=64 ) { // Green
			echo ' class="hi good"';
		} else { // Red
			echo ' class="hi insecure"';
		}
		echo '>'.$Hash_Length.'</td>
			<td';

		// Advisory Outputs
		if(
			$Hash_Algo == 'adler32' ||
			$Hash_Algo == 'crc32' ||
			$Hash_Algo == 'crc32b' ) {
			echo ' class="hi insecure">Warning: Checksum. Not for passwords.';
		} elseif(
			$Hash_Algo == 'ripemd128' ||
			$Hash_Algo == 'ripemd256' ||
			$Hash_Algo == 'sha1' ) {
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

		// Time Output
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

	<!--[if lt IE 9]> 
		<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript">window.jQuery || document.write('<script src="http://labs.eustasy.org/js/jquery-1.10.2.min.js"><\/script>');</script>
	<![endif]-->
	<!--[if IE 9]><!--> 
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="http://labs.eustasy.org/js/jquery-2.0.3.min.js"><\/script>');</script>
	<!--<![endif]-->
	<script src="http://labs.eustasy.org/jquery.tablesorter.min.js"></script>
	<script>$(document).ready(function() { $("#sort").tablesorter( {sortList: [[1,1], [3,1]]} ); });</script>

</body>
</html>
