<?php

//Datenbank Verbindung
$dbconn = pg_connect("host= port= dbname= user= password=");

//Klasse wird einmalig eingebunden
require_once ('postgreSQL.class.php');

//Objekt der Klasse postgreSQL wird erzeugt
$database = new postgreSQL_query();

//Ent- und Verschl�sselung der Ticketnummer/Nutzername �ber GET Parameter
$getticket = $_GET[ticketnummer];
$getticketDecoded = base64_decode($getticket);
$vurl = base64_encode($getticketDecoded);
$getuser = $_GET[nutzername];

//"Hilfreich" in der DB setzen/entfernen
if(is_array($_POST) && !empty($_POST))
{
	foreach($_POST as $sIndex => $sValue)
	{
		if(preg_match('/^btnHilfreich_/', $sIndex))
		{
			$iAntwortId = substr($sIndex, 13);
			if(intval($iAntwortId)>0)
			{
				$sSQL = "UPDATE data2 SET hilfreich = true WHERE id = ".intval($iAntwortId).";";
				pg_query($dbconn, $sSQL);
			}
		}
	}
}
 
if(is_array($_POST) && !empty($_POST))
{
	foreach($_POST as $sIndex => $sValue)
	{
		if(preg_match('/^btnHilfreichEntfernen_/', $sIndex))
		{
			$iAntwortId = substr($sIndex, 22);	
			if(intval($iAntwortId)>0)
			{
				$sSQL = "UPDATE data2 SET hilfreich = false WHERE id = ".intval($iAntwortId).";";
				pg_query($dbconn, $sSQL);
			}				
		}
	}
}

//Ausgabe aus der DB der Fehlermeldung/Antworten
$abfrage = "SELECT artikelnummer, nutzername, datum, kategorie, betreff, fehlerbeschreibung FROM data WHERE artikelnummer=".$getticketDecoded;
$abfrage2 = "SELECT nutzername, datum, antwort, id, hilfreich FROM data2 WHERE ticketnummer=".$getticketDecoded."ORDER BY datum ASC;";
$ergebnis = pg_query($dbconn, $abfrage);
$ergebnis2 = pg_query($dbconn, $abfrage2);

//HTML
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>FixMyProblem</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
<body class="body2">
<script type="text/javascript">

	<!-- Funktion zum einblenden des "Hilfreich"-Sterns -->
	
	function show()
	{
		if(confirm("Antwort als \"Hilfreich\" markieren?"))
		{
			document.getElementById("stern").style.visibility = "visible";
			document.getElementById("deleteButton").style.visibility = "visible";
			return true;
		}
		else 
	    {
			return false;
		}
	}
	
		
	<!-- Funktion zum ausblenden des "Hilfreich"-Sterns -->
	
	function show2()
	{
		if(confirm("\"Hilfreich\" zur�cksetzen?"))
		{
			document.getElementById("stern").style.visibility = "hidden";
			document.getElementById("deleteButton").style.visibility = "hidden";
			return true;
		}
		else
	    {
			return false;
		}
	}
	
</script>
<form name="formularThread" method="post">
<br>
<br>
<br>
<br>
<table border="0" align="center" rules="none">
	<tbody>
		<tr>
			<td colspan="2" align="center">
				<input class="zurueckBtn" type="button" name="Zurueck" value="Zur�ck zu den gemeldeten Fehlern" onMouseout="this.style.background=\'darkgray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" title="Zur�ck zu den gemeldeten Fehlern" onclick="window.top.location=\'fixerror.php\'">
			</td>
		</tr>
	</tbody>
</table>
<br />
<br />';
while($ausgabe = pg_fetch_array($ergebnis)) 	
	{
		$datum_deutsch = date('d.m.Y H:i:s', strToTime($ausgabe[2]));
		
			if($ausgabe[3] == 1)
			{
				$ausgabe[3] = 'Softwarefehler';
			}
			elseif($ausgabe[3] == 2)
			{
				$ausgabe[3] = 'Hardwarefehler';
			}
			elseif($ausgabe[3] == 3)
			{
				$ausgabe[3] = 'Sonstige Probleme';
			}
			elseif($ausgabe[3] == 4) 
			{
				$ausgabe[3] = 'Hilfe';
			}
			elseif($ausgabe[3] == 5) 
			{
				$ausgabe[3] = 'Behobene Fehler';
			}
			elseif($ausgabe[3] == 6) 
			{
				$ausgabe[3] = 'Beobachtungsliste';
			}
			elseif($ausgabe[3] == 7) 
			{
				$ausgabe[3] = 'Schwierige Fehler';
			}
			elseif($ausgabe[3] == 10) 
			{
				$ausgabe[3] = 'Gel�schte Fehler';
			}
echo '<fieldset class="fieldsetThread2">
<legend>Thread von '.$ausgabe[1].':</legend>
<br />
<table border="0" cellpadding="0" cellspacing="0" rules="none" align="center" hspace="185" vspace="1">
	<tbody>							 
        <tr>
			<td align="right">
             	<input class="inputFeld" type="text" size="15" readonly value="Ticketnummer:" tabindex="-1" style="text-align:right;"> 
			</td>
			<td>
				<input class="inputFeld3" type="text" size="5" readonly value="'.$ausgabe[0].'" tabindex="-1">			
			</td>
		</tr>
		<tr>
			<td align="right">
				<input class="inputFeld" type="text" size="15"  readonly value="Nutzername:" tabindex="-1" style="text-align:right;"> 
			</td>
			<td>
				<input class="inputFeld3" type="text" readonly size="17" value="'.$ausgabe[1].'" tabindex="-1">			
			</td>
		</tr>
		<tr>
			<td align="right">
				<input class="inputFeld" type="text" size="15"  readonly value="Erstellt am:" tabindex="-1" style="text-align:right;"> 
			</td>
			<td>
				<input class="inputFeld3" type="text" size="16" readonly value="'.$datum_deutsch.'" tabindex="-1">			
			</td>
		</tr>
		<tr>
			<td align="right">
				<input class="inputFeld" type="text" size="15"  readonly value="Fehlerkategorie:" tabindex="-1" style="text-align:right;"> 
			</td>
			<td>
				<input class="inputFeld3" type="text" size="14" readonly value="'.$ausgabe[3].'" tabindex="-1">
			</td>
		</tr>
		<tr>
			<td align="right">
				<input class="inputFeld" type="text" size="15"  readonly value="Betreff:" tabindex="-1" style="text-align:right;"> 
			</td>
			<td>
				<input class="inputFeld3" type="text" readonly size="40" value="'.$ausgabe[4].'" tabindex="-1">			
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">
				<input class="inputFeld" type="text" size="15"  readonly value="Fehlerbeschreibung:" tabindex="-1" style="text-align:right;"> 
			</td>
			<td>
				<textarea class="textArea" cols="90" rows="15" readonly tabindex="-1">'.$ausgabe[5].'</textarea>
			</td>
		</tr>';
		}	
  echo '<tr>
			<td>
			 
			</td>
			<td align="left">
				<input class="antwortenBtn" title="Antworten sie auf diese Fehlermeldung" onMouseout="this.style.background=\'forestgreen\'; this.style.color=\'white\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'"  type="button" name="antworten" value="Antworten" onclick="window.top.location=\'feedback.php?ticketnummer='.$vurl.'&nutzername='.$getuser.'\'">	
			</td>
		</tr>
	</table>
	</fieldset>
	<table>
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
		</tr>
	</table>';
	while($ausgabe2 = pg_fetch_array($ergebnis2)) 	
		    {
		    	$datum_deutsch2 = date('d.m.Y H:i:s', strToTime($ausgabe2[1]));
				
			    	$sVisibleStatus = 'visible';
					$sVisibleStatusDeleteButton = 'hidden';
					
					if($ausgabe2[4] == 'f')
					{
						$sVisibleStatus = 'hidden';
						$sVisibleStatusSubmitButton = 'block';
					}
					elseif($ausgabe2[4] == 't')
					{
						$sVisibleStatusDeleteButton = 'visible';
						$sVisibleStatusSubmitButton = 'none';
					}
					
	echo '<fieldset class="fieldsetThread">
	  <legend>Antwort von '.$ausgabe2[0].':</legend>
	   <br />
		<table border="0" cellpadding="0" cellspacing="0" rules="none" align="center" hspace="185" vspace="1">
			<tr>
				<td align="right">
					<input class="inputFeld" type="text" size="15"  readonly value="Nutzername:" tabindex="-1" style="text-align:right;"> 
				</td>
				<td>
					<input class="inputFeld3" type="text" readonly size="16" value="'.$ausgabe2[0].'" tabindex="-1">
				</td>
			</tr>
			<tr>
				<td align="right">
					<input class="inputFeld" type="text" size="15"  readonly value="Geantwortet am:" tabindex="-1" style="text-align:right;"> 
				</td>
				<td>
					<input class="inputFeld3" type="text" readonly size="16" value="'.$datum_deutsch2.'" tabindex="-1">
				</td>
			</tr>
			<tr>
				<td align="right" valign="top">
					<input class="inputFeld" type="text" size="15"  readonly value="Antwort:" tabindex="-1" style="text-align:right;"> 
				</td>
				<td>
					<textarea class="textArea" cols="95" rows="15" readonly tabindex="-1">'.$ausgabe2[2].' </textarea>
				</td>
			</tr>
	        <tr>
				<td>
					<img src="Stern.png" align="center" id="stern" style="visibility:'.$sVisibleStatus.';" title="Hilfreiche Antwort" />
				</td>
				<td align="left">
					<input class="hilfreichBtn" type="submit" name="btnHilfreich_'.$ausgabe2[3].'" onMouseout="this.style.background=\'gold\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" style="display:'.$sVisibleStatusSubmitButton.';"  value="Hilfreich" title=\'Antwort als "Hilfreich" markieren\' onclick="return show();">
					<input class="hilfreichEntfBtn" type="submit" id="deleteButton" name="btnHilfreichEntfernen_'.$ausgabe2[3].'" onMouseout="this.style.background=\'red\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'"  style="visibility:'.$sVisibleStatusDeleteButton.';" value="Hilfreich zur�cksetzen" title=\'"Hilfreich" zur�cksetzen\' onclick="return show2();">
				</td>
	      </tbody>
	   </table>
	</fieldset>
  <br />
 <br />';
			  }
	echo '<table border="0" align="center" rules="none">
		<tbody>
			<tr>
				<td colspan="2" align="center">
					<br />
					<input class="zurueckBtn" type="button" name="Zurueck" onMouseout="this.style.background=\'darkgray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" value="Zur�ck zu den gemeldeten Fehlern" title="Zur�ck zu den gemeldeten Fehlern" onclick="window.top.location=\'fixerror.php\'">
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
		</tbody>
	</table>
  </fieldset>	
</form>
</body>
<br />
</html>';
?>