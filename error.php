<?php
//Klasse wird einmalig eingebunden
require_once ('postgreSQL.class.php');

//Objekt der Klasse postgreSQL wird erzeugt
$database = new postgreSQL_query();

//Variablen
$meldeButton = $_POST['melden'];
$user = $_POST['user'];
$betreff = $_POST['betreff'];
$fehlerbeschreibung = $_POST['fehlerbeschreibung'];
$suchmusterUser = '/^[a-zA-Z0-9[:space:]]+$/';
$suchmusterBetreff = '#[_§$`´+";@éèà$%&\'\\\*0-9]#';
$suchmusterBeschreibung = '#[_§$`´+";@éèà$%&\'\\\*]#';
$upload = $_POST['upload'];
$user = trim($_POST['user']);
$kategorie = $_POST['fehlerart'];
$betreff = trim($_POST['betreff']);
$fehlerbeschreibung = $_POST['fehlerbeschreibung'];
$datum = date('Y-m-d H:i:s');
$antwort = 0;

//Prüfung der Eingabe
if($meldeButton == 'Melden')
{
	if(!preg_match($suchmusterUser, $user)) 
	{
		echo '<script>window.alert("Der Nutzername darf nur aus Bustaben und Zahlen bestehen !")</script>';
	}
	elseif(preg_match($suchmusterBetreff, $betreff)) 
	{
		echo '<script>window.alert("Der Betreff darf nur aus Bustaben bestehen !")</script>';
	}
	elseif(preg_match($suchmusterBeschreibung, $fehlerbeschreibung)) 
	{
		echo '<script>window.alert("Die Fehlerbeschreibung darf nur folgende Sonderzeichen enthalten: \n \". , : - / ! ?\" ")</script>';
	}
	else 
	{
		
		//Wenn Prüfung OK, dann Eingabe in die DB
		$database->open_connection();	
		//Query Befehl, wird übergeben an SQL Klasse
		$sSQL =  "INSERT INTO data (betreff, nutzername, datum, answers, kategorie, fehlerbeschreibung) VALUES ('$betreff', '$user', '$datum', '$antwort', '$kategorie', '$fehlerbeschreibung');";
		
		//Wenn Query OK, weiterleitung an fixerror.php
		if(!$database->query_db($sSQL))
		{
			echo "<script>alert('Fehler konnte nicht gepostet werden.')</script>"; 
		}
		else
		{
			echo "<script>alert('Fehler wurde erfolgreich gepostet.')</script>";
			echo "<meta http-equiv='refresh' content='0; URL=fixerror.php'>";
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
	
			<!-- Prüffunktionen der Eingabefelder -->
			function check() 
			{
				if(document.formularError.fehlerart.value == 5)
				{
					alert("Bitte eine Kategorie wählen !");
					return false;
				}
				
			    if(document.formularError.user.value == 0)
				{
					alert("Bitte alle Felder füllen !");
					return false;
				}
				
				if(document.formularError.betreff.value == 0)
				{
					alert("Bitte alle Felder füllen !");
					return false;
				}
				
				if(document.formularError.fehlerbeschreibung.value == 0)
				{
					alert("Bitte alle Felder füllen !");
					return false;
				}
				
				if(document.formularError.betreff.value.length < 10)
				{ 
					alert("Der Betreff muss mindestens 10 Stellig sein ! ");
					return false;
				}
				
				if(document.formularError.fehlerbeschreibung.value.length < 30)
				{ 
					alert("Die Fehlerbeschreibung muss mindestens 30 Stellig sein ! ");
					return false;
				}
			    
			    if(document.formularError.user.value.length < 5)
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
	<form name="formularError" action="error.php" method="post" enctype="multipart/form-data">
		<br>
		<br>
		<table border="0" align="center" rules="none">
			<tr>
				<td colspan="2"  align="center">
					<input class="zurueckBtn" type="button" name="Zurueck" value="Zurück zur Startseite" onMouseout="this.style.background=\'darkgray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" onclick="window.top.location=\'home.php\'" tabindex="8">
				</td>
			</tr>
		</table>
		<br />
		<br />
			<fieldset class="fieldset">
				<legend>Fehler melden</legend>
				<table border="0" align="center" rules="none" frame="void" cellpadding="0" cellspacing="0" hspace="185" vspace="1">	
					<colgroup>
						<col width="250">
						<col width="400">
					</colgroup>
					<tr>
						<td align="right">
							<input class="inputFeld" type="text" size="18"  readonly value="Fehlerkategorie wählen:" tabindex="-1" style="text-align:right;"> 
						</td>
						<td align="left">
							<select class="selectBox" title="Wählen sie die Fehlerart" name="fehlerart" id="fehlerart" tabindex="1">
								<option value="5" style="display:none;">...Kategorie wählen... *</option>
								<optgroup label="---------------------------------------------">
								<option value="1">Softwarefehler</option>																																                                 
								<option value="2">Hardwarefehler</option>
					            <option value="3">Sonstige Probleme</option>
					            <option value="4">Hilfe</option>
								</optgroup>	
								<optgroup label="---------------------------------------------">
								</optgroup>		        
					        </select>
					    </td>	                                                                                                                         
					</tr>
					<tr>                   
						<td align="right"> 
							<input class="inputFeld" type="text" size="18"  readonly value="Nutzername:" tabindex="-1" style="text-align:right;"><br />
							<input class="inputFeld2" type="text" size="18" readonly value="(Max. 15 Zeichen)" tabindex="-1" style="text-align:right;"> 
					    </td>
						<td align="left">
							<input class="inputFeld3" type="text" name="user" id="user" placeholder="Nutzername *" maxlength="15" tabindex="2">
						</td> 
					</tr>	
					<tr>
						<td align="right">
							<input class="inputFeld" type="text" size="18"  readonly value="Betreff:" tabindex="-1" style="text-align:right;"><br /> 
							<input class="inputFeld2" type="text" size="18"  readonly value="(Max. 40 Zeichen)" tabindex="-1" style="text-align:right;">
						</td>
						<td align="left">
							<input class="inputFeld3" name="betreff" id="betreff" type="text" placeholder="Betreff *" maxlength="40" tabindex="3"> 
						</td>	
					</tr>	
					<tr>
						<td align="right" valign="top">
							<input class="inputFeld" type="text" size="18"  readonly value="Fehlerbeschreibung:" tabindex="-1" style="text-align:right;"><br />
					    	<input class="inputFeld2" type="text" size="18"  readonly value="(Max. 500 Zeichen)" tabindex="-1" style="text-align:right;"> 
					    </td>
					    <td align="left">
					    	<textarea class="textArea" placeholder=" Ausführliche Fehlerbeschreibung... *" name="fehlerbeschreibung" id="fehlerbeschreibung" cols="50" rows="10" tabindex="5"></textarea>
					    </td>
					</tr>
					<tr>
						<td>
							<input class="uploadBtn" title="Erlaubte Dateien: Text- und Bilddateien" type="file" name="upload" maxlength="10000000" size="10000000" accept="file_extension|audio/*|video/*|image/*|media_type" tabindex="4" onMouseout="this.style.background=\'darkgray\'; this.style.color=\'black\'"  onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'">
						</td>
					</tr>
			        <tr>
			        	<td>
			        		&nbsp;
			        	</td>
			        </tr>
			        <tr>
			        	<td>
			        		&nbsp;
			        	</td>
			        </tr>
					<tr>
						<td>
							&nbsp; 
						</td>
						<td>
							<input class="meldenBtn2" onMouseout="this.style.background=\'forestgreen\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" title="Posten sie ihren Fehler" type="submit" name="melden" value="Melden" onclick="return check()" tabindex="6">
							<input class="abbrechenBtn" onMouseout="this.style.background=\'red\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" title="Abbruch, zurück zur Startseite" type="reset" name="reset" value="Abbrechen" onclick="window.top.location=\'home.php\'" tabindex="7">
							<input class="zudenfehlernBtn" onMouseout="this.style.background=\'gray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" title="Gemeldete Fehler ansehen" type="button" name="backToErrors" value="Zu den gemeldeten Fehlern" onclick="window.top.location=\'fixerror.php\'" tabindex="8">
						</td> 
					</tr>
					<tr>
						<td>
						
						</td>
						<td>
							<p class="bemerkung"> * Pflichtfeld</p>
						</td>
					</tr>
				</table>
		</fieldset>	
	</form>	
  </body>
</html>';

?>