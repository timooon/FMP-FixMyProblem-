<?php

//Klasse wird einmalig eingebunden
require_once ('postgreSQL.class.php');

//Objekt der Klasse postgreSQL wird erzeugt
$database = new postgreSQL_query();

//Variablen
$suchmuster = '/^[a-zA-Z0-9[:space:]]+$/';
$suchmuster2 = '#[_§$`´+";@éèà$%&\'\\\*]#';
$getticket2 = $_GET[ticketnummer];
$postButton = $_POST['posten'];
$user2 = $_POST['user2'];
$fehlerbeschreibung2 = $_POST['fehlerbeschreibung2'];
$datum2 = date('Y-m-d H:i:s');

//Ent- und Verschlüsselung der Ticketnummer/Nutzername über GET Parameter
$getticketCoded2 = base64_decode($getticket2);
$getticketCoded = base64_decode($getticket2);
$getuser = $_GET[nutzername];
$user = base64_decode($getuser);

//Eintrag in die DB der Antworten
if($postButton == 'Posten')
{
	if(!preg_match($suchmuster, $user2)) 
	{
		echo '<script>window.alert("Der Nutzername darf nur aus Bustaben und Zahlen bestehen !")</script>';
	}
	elseif(preg_match($suchmuster2, $fehlerbeschreibung2)) 
	{
		echo '<script>window.alert("Die Antwort darf nur folgende Sonderzeichen enthalten: \n \". , : - / ! ?\" ")</script>';
	}
	else 
	{
		//Wenn prüfung OK, dann wird die Antwort in die Datenbank geschrieben
		$database->open_connection();
		$sSQL = "INSERT INTO data2 (nutzername, antwort, datum, ticketnummer) VALUES ('$user2', '$fehlerbeschreibung2', '$datum2', '$getticketCoded')";
		
		if(!$database->query_db($sSQL))
		{
			echo "<script>alert('Antwort konnte nicht gepostet werden.')</script>"; 
		}
		else
		{
			echo "<script>alert('Antwort wurde erfolgreich gepostet.')</script>";
			echo "<meta http-equiv='refresh' content='0; URL=thread.php?ticketnummer=$getticket2'>";
			return false;
		}	
	}
}

//HTML
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>FixMyProblem</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
		<body class="body2">
			<script type="text/javascript">

				<!-- Prüffunktionen der Antworteingabefelder -->
				function check2() 
				{
					if(document.formularFeedback.user2.value == 0)
					{
						alert("Bitte alle Felder füllen ! ");
						return false;
				    }
				    
				    if(document.formularFeedback.fehlerbeschreibung2.value == 0)
				    {
				   		alert("Bitte alle Felder füllen ! ");
				   		return false;
				    }
				    
				    if(document.formularFeedback.fehlerbeschreibung2.value.length < 30)
					{ 
						alert("Die Antwort muss mindestens 30 Stellig sein ! ");
						return false;
					}
				
				    if(document.formularFeedback.user2.value.length < 5)
					{ 
						alert("Der Nutzername muss mindestens 5 Stellig sein ! ");
						return false;
					}
					else
					{
						return true;
					} 
				}

  			</script>
<form name="formularFeedback" action="feedback.php?ticketnummer='.$getticket2.'" method="post">
	<br>
	<br>
	<br>
	<br>
		<table border="0" align="center" rules="none">
			<tbody>
				<tr>
					<td colspan="2" align="center">
						<input class="zurueckBtn" type="button" name="Zurueck" onMouseout="this.style.background=\'darkgray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" value="Zurück zu den gemeldeten Fehlern" onclick="window.top.location=\'fixerror.php\'" tabindex="6">
					</td>
				</tr>
			</tbody>
		</table>
	  <br />
	<br />
	<fieldset class="fieldset">
		<legend>Antworten auf '.$user.'s Thread (Ticketnummer: '.$getticketCoded.')</legend>
		  <br />
			<table border="0" cellspacing="0" align="center" rules="none" cellpadding="0" cellspacing="0" hspace="185" vspace="1">
				<colgroup>
					<col width="250">
					<col width="400">
				</colgroup>
				<tbody>	
					<tr>
						<td align="right">
							<input class="inputFeld" type="text" size="13"  readonly value="Nutzername:" tabindex="-1" style="text-align:right;"><br />
							<input class="inputFeld2" type="text" size="13"  readonly value="(Max. 15 Zeichen)" tabindex="-1" style="text-align:right;"> 
						</td>
						<td>
							<input class="inputFeld3" type="text" name="user2" id="user2" maxlength="15" placeholder="Nutzername *" tabindex="1">
						</td>
					</tr>
					<tr>
						<td align="right">
							<input class="inputFeld" type="text" size="13"  readonly value="Antwort:" tabindex="-1" style="text-align:right;"><br />
							<input class="inputFeld2" type="text" size="13"  readonly value="(Max. 500 Zeichen)" tabindex="-1" style="text-align:right;">  
						</td>
						<td>
							<textarea class="textArea" placeholder=" Antwort... *" name="fehlerbeschreibung2" id="fehlerbeschreibung2" cols="50" rows="10" maxlength="500" tabindex="2"></textarea>
						</td>
					</tr>
					<tr>
						<td> </td>
						<td align="left">
							<input class="postenBtn" title="Posten sie ihre Antwort"  type="submit" name="posten" value="Posten" onMouseout="this.style.background=\'forestgreen\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" onclick="return check2()" tabindex="3">
							<input class="abbrechenBtn" title="Abbruch, zurück zu den gemeldeten Fehlern"  type="reset" name="reset" value="Abbrechen" onMouseout="this.style.background=\'red\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" onclick="window.top.location=\'fixerror.php\'" tabindex="4">
							<input class="zumfehlerBtn" title="Hier gelangen sie zum ursprünglichem Fehler"  type="button" name="backToError" value="Zum Ursprünglichen Fehler" onMouseout="this.style.background=\'gray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" onclick="window.top.location=\'thread.php?ticketnummer='.$getticket2.'\'" tabindex="5">
						</td>
					</tr>
					<tr>
						<td>
						
						</td>
						<td>
							<p class="bemerkung"> * Pflichtfeld</p>
						</td>
					</tr>
				</tbody>
			</table>
  </fieldset>
</form>
</body>
</html>';

?>
