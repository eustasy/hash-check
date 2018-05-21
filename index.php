<!DocType html>
<html>
<head>

	<meta charset="UTF-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="cleartype" content="on">

	<title>PHP Hash Check</title>

	<meta name="description" content="A speed benchmark and security comparison for all the available Hash Algorithms for any PHP version >=5.1.2 with advisory notices and assisting rankings.">
	<meta name="keywords" content="speed benchmark security comparison available Hash Algorithms PHP version >=5.1.2 > = 5.1.2 5 1 2 advisory notices assisting rankings eustasy labs org ltd uk">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="author" content="https://google.com/+LewisGoddard">
	<link rel="publisher" href="https://plus.google.com/+EustasyOrg">
	<link rel="canonical" href="https://labs.eustasy.org/hash-check/">
	<link rel="icon" href="https://labs.eustasy.org/favicon.ico">
	<link rel="shortcut icon" href="https://labs.eustasy.org/favicon.ico">
	<link rel="stylesheet" href="/assets/css/grid.min.css">
	<link rel="stylesheet" href="/assets/css/labs.css">

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-45667989-11', 'eustasy.org');
		ga('send', 'pageview');
	</script>

	<script>
		var jQl={q:[],dq:[],gs:[],ready:function(a){'function'==typeof a&&jQl.q.push(a);return jQl},getScript:function(a,c){jQl.gs.push([a,c])},unq:function(){for(var a=0;a<jQl.q.length;a++)jQl.q[a]();jQl.q=[]},ungs:function(){for(var a=0;a<jQl.gs.length;a++)jQuery.getScript(jQl.gs[a][0],jQl.gs[a][1]);jQl.gs=[]},bId:null,boot:function(a){'undefined'==typeof window.jQuery.fn?jQl.bId||(jQl.bId=setInterval(function(){jQl.boot(a)},25)):(jQl.bId&&clearInterval(jQl.bId),jQl.bId=0,jQl.unqjQdep(),jQl.ungs(),jQuery(jQl.unq()),'function'==typeof a&&a())},booted:function(){return 0===jQl.bId},loadjQ:function(a,c){setTimeout(function(){var b=document.createElement('script');b.src=a;document.getElementsByTagName('head')[0].appendChild(b)},1);jQl.boot(c)},loadjQdep:function(a){jQl.loadxhr(a,jQl.qdep)},qdep:function(a){a&&('undefined'!==typeof window.jQuery.fn&&!jQl.dq.length?jQl.rs(a):jQl.dq.push(a))},unqjQdep:function(){if('undefined'==typeof window.jQuery.fn)setTimeout(jQl.unqjQdep,50);else{for(var a=0;a<jQl.dq.length;a++)jQl.rs(jQl.dq[a]); jQl.dq=[]}},rs:function(a){var c=document.createElement('script');document.getElementsByTagName('head')[0].appendChild(c);c.text=a},loadxhr:function(a,c){var b;b=jQl.getxo();b.onreadystatechange=function(){4!=b.readyState||200!=b.status||c(b.responseText,a)};try{b.open('GET',a,!0),b.send('')}catch(d){}},getxo:function(){var a=!1;try{a=new XMLHttpRequest}catch(c){for(var b=['MSXML2.XMLHTTP.5.0','MSXML2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0','MSXML2.XMLHTTP','Microsoft.XMLHTTP'],d=0;d<b.length;++d){try{a= new ActiveXObject(b[d])}catch(e){continue}break}}finally{return a}}};if('undefined'==typeof window.jQuery){var $=jQl.ready,jQuery=$;$.getScript=jQl.getScript};
		jQl.loadjQ('//cdn.jsdelivr.net/g/jquery,tablesorter');
	</script>

	<script>
		$(document).ready(
			function() {
				$('#sort').tablesorter({
					sortList: [
						[1,1],
						[3,1]
					]
				});
			}
		);
	</script>

</head>
<body>

	<header class="whole grid">
		<div class="whole smablet-half float-center">
			<h1>PHP Hash Check</h1>
			<p class="sub-title">A speed benchmark and security comparison for all the available Hash Algorithms for any PHP version >=5.1.2 with advisory notices and assisting rankings.</p>
		</div>
	</header>

	<section class="whole grid">
		<div class="whole smablet-half">
			<p class="text-left">This page serves to speed benchmark all the available Hash Algorithms for this PHP version (<?php echo phpversion(); ?>). The PHP Script (<a href="https://github.com/eustasy/hash-check">Source available on GitHub</a>) randomly generates a 18 character password and 64 character salt from the following digits.</p>
			<br>
			<code>abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 !"£$%^&*()-_+=|`¬,.<>/?~#[]{}@'\</code>
			<p class="sub-title">Like you'd be so lucky as to get a user with a random password generated from these.</p>
		</div>
		<div class="whole smablet-half">
			<p class="text-left">It then hashes the password and salt, adds the resulting hashes together, then hashes that too (just for good measure). Then it does the whole things again, nine-hundred and ninety-nine more times. The resulting table (shown below) is automatically sorted by Hash Length and Time Taken (both of which are better longer).</p>
			<br>
			<code>$Hash_Result = hash( $Hash_Algo, hash( $Hash_Algo, $Pass, false) . hash( $Hash_Algo, $Salt, false ), false );</code>
			<p class="sub-title">This is how most of our user logins are handled, so provides a realistic benchmark.</p>
		</div>
	</div>

	<table id="sort" class="whole tablesorter">
		<thead>
			<tr>
				<th>Hash</th>
				<th>Length</th>
				<th>Timing (&micro;s per thousand)</th>
				<th>Advisory</th>
			</tr>
		</thead>
		<tbody><?php

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
				<td>'.$Hash_Algo.'</td>';

		// Hash Length Outputs
		echo '
				<td';
		if( $Hash_Length >= 128 ) { // Blue
			echo ' class="hi best"';
		} else if( $Hash_Length >= 64 ) { // Green
			echo ' class="hi good"';
		} else { // Red
			echo ' class="hi insecure"';
		}
		echo '>'.$Hash_Length.'</td>';

		// Time Output
		echo '
				<td';
		if( $Count > 4 ) { // Blue
			echo ' class="hi best"';
		} else if( $Count > 3 ) { // Green
			echo ' class="hi good"';
		} else {
			echo ' class="hi insecure"';
		}
		echo '>'.$Count.'</td>';

		// Advisory Outputs
		echo '
				<td';
		if(
			$Hash_Algo == 'adler32' ||
			$Hash_Algo == 'crc32' ||
			$Hash_Algo == 'crc32b'
		) {
			echo ' class="hi insecure" data-text="-3">Warning: Checksum. Not for passwords.';
		} elseif(
			$Hash_Algo == 'ripemd128' ||
			$Hash_Algo == 'ripemd256' ||
			$Hash_Algo == 'sha1'
		) {
			echo ' class="hi insecure" data-text="-2">Warning: Questionable Security.';
		} elseif(
			$Hash_Algo == 'md2' ||
			$Hash_Algo == 'md4' ||
			$Hash_Algo == 'md5' ||
			$Hash_Algo == 'haval128,3'
		) {
			echo ' class="hi insecure" data-text="-1">Warning: No longer considered secure.';
		} elseif(
			$Hash_Algo == 'sha384' ||
			$Hash_Algo == '256'
		) {
			echo ' class="hi good" data-text="1">Good: A shorter, faster version of what we use.';
		} elseif( $Hash_Algo == 'sha512' ) {
			echo ' class="hi best" data-text="3">Recommended: We use this.';
		} elseif( $Hash_Algo == 'whirlpool' ) {
			echo ' class="hi best" data-text="3">Recommended: Based on AES.';
		} elseif( $Hash_Algo == 'ripemd320' ) {
			echo ' class="hi good" data-text="2">Good: Based on original RIPEMD.';
		} elseif( $Hash_Algo == 'gost' ) {
			echo ' class="hi good" data-text="2">Good: Based on DES.';
		} else {
			echo '>';
		}
		echo '</td>';
		echo '
			</tr>';

	}

?>

		</tbody>
	</table>

	<footer>
		<p>
			<a href="https://github.com/eustasy/hash-check/blob/master/LICENSE.md">License</a> &nbsp;·&nbsp;
			<a href="https://github.com/eustasy/hash-check">GitHub</a> &nbsp;·&nbsp;
			<a href="https://github.com/eustasy/hash-check/releases">Releases</a>
		</p>
	</footer>

</body>
</html>
