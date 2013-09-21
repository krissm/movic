<?php
/**
* Helpers for the template file.
*/
$mo->data ['header'] = '
		<div id="banner"> 
        <a href=' . dirname ( $_SERVER ['SCRIPT_NAME'] ) . ' ><img class="site-logo" src="' . dirname ( $_SERVER ['SCRIPT_NAME'] ) . '/themes/core/img/mvclogo.jpg" alt="logo" width="120" height="120" /></a> 
      	<p class="site-title">PHPmvc</p> 
      	<p class="site-slogan">A PHP-based and MVC-inspired story</p>
    	</div> ';

// '<h1>Header: Movic</h1>';
// $mo->data['main'] = '<p>Main: Now with a theme engine, Not much more to report for now.</p>';
$mo->data ['footer'] = <<<EOD
		
		<p>Footer: &copy; Movic by Kristoffer Magnusson</p>

		<p>Tools: 
		<a href="http://validator.w3.org/check/referer">html5</a>
		<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">css3</a>
		<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css21">css21</a>
		<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">unicorn</a>
		<a href="http://validator.w3.org/checklink?uri={$mo->request->current_url}">links</a>
		<a href="http://qa-dev.w3.org/i18n-checker/index?async=false&amp;docAddr={$mo->request->current_url}">i18n</a>
		<!-- <a href="link?">http-header</a> -->
		<a href="http://csslint.net/">css-lint</a>
		<a href="http://jslint.com/">js-lint</a>
		<a href="http://jsperf.com/">js-perf</a>
		<a href="http://www.workwithcolor.com/hsl-color-schemer-01.htm">colors</a>
		<a href="http://dbwebb.se/style">style</a>
		</p>

		<p>Docs:
		<a href="http://www.w3.org/2009/cheatsheet">cheatsheet</a>
		<a href="http://dev.w3.org/html5/spec/spec.html">html5</a>
		<a href="http://www.w3.org/TR/CSS2">css2</a>
		<a href="http://www.w3.org/Style/CSS/current-work#CSS3">css3</a>
		<a href="http://php.net/manual/en/index.php">php</a>
		<a href="http://www.sqlite.org/lang.html">sqlite</a>
		<a href="http://www.blueprintcss.org/">blueprint</a>
		</p>

EOD;
