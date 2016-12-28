<?php

//HTML
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>FixMyProblem</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
  <body class="body"> 
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
 <table border="0" align="center" cellpadding="4" cellspacing="5">
  <tr>
	<td align="center">
		<div>
			<button class="meldenBtn" title="Melden sie hier ihren Fehler" name="error" onMouseout="this.style.background=\'red\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'black\'" value="Fehler melden" onclick="window.location.href=\'error.php\'">
				<p><img src="buggy.png"><br>Fehler melden</p>
			</button>
		</div>
	</td>
	<td  align="center">
		<div>
			<button class="infoIcon" title="Information..." name="info" onMouseout="this.style.background=\'blue\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'black\'" onclick="alert(\'Willkommen auf FixMyProblem!\nSie haben die Möglichkeit eine Fehlermeldung aufzugeben und wir finden sicher eine Lösung !\nOder aber Sie helfen anderen Leuten deren Fehler zu beheben !\')">
			<p><img src="info3.png"><br>Info</p> 
		</div>
	</td>
	<td align="center">
		<div>
			<button class="ansehenBtn" title="Sehen sie hier gemeldete Fehler" name="fixerror" onMouseout="this.style.background=\'forestgreen\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'black\'" value="Fehler ansehen" onclick="window.location.href=\'fixerror.php\'">
				<p><img src="fixxy.png"><br>Fehler ansehen</p>
			</button>
		</div>
	</td>
   </tr>
  </body>
 </table>
</html>';

?>
