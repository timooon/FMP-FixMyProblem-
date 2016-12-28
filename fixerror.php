<?php

$dbconn = pg_connect('host= port= dbname= user= password=');

//Klasse wird einmalig eingebunden
require_once ('postgreSQL.class.php');

//Objekt der Klasse postgreSQL wird erzeugt
$database = new postgreSQL_query();

//Variablen
$radio = $_POST['suchKategorie'];
$row = 'hidden';
$row2 = 'hidden';
$aktualisierenButton = $_POST['senden'];
$fehlerkategorie = $_POST['fehlerkategorie'];
$suchwort = $_POST['suchfeld'];
$searchBtn = $_POST['searchBtn'];
$datumsFormat = '/^0[1-9]|[12][0-9]|3[01][-](0[1-9]|1[012])[-](19|20)[0-9]{2} $/';
$suchmusterNutzername = '/^[a-zA-Z0-9]+$/';
$suchmusterBetreff = '/^[a-zA-Z0-9[:space:]]+$/';

//Funktionen zum Markieren der Fehler(schwierig,behoben,beobachten,l�schen)
if(is_array($_POST) && !empty($_POST))
{
	foreach($_POST as $sIndex1 => $sValue1)
	{
		if(preg_match('/^l�schen_/', $sIndex1))
		{
			$iTicket1 = substr($sIndex1, 8);
			if(intval($iTicket1)>0)
			{
				$sSQL1 = "UPDATE data SET kategorie = '10' WHERE artikelnummer = ".intval($iTicket1).";";
				pg_query($dbconn, $sSQL1);
			}
		}
	}
}

if(is_array($_POST) && !empty($_POST))
{
	foreach($_POST as $sIndex2 => $sValue2)
	{
		if(preg_match('/^schwierig_/', $sIndex2))
		{
			$iTicket2 = substr($sIndex2, 10);
			if(intval($iTicket2)>0)
			{
				$sSQL2 = "UPDATE data SET kategorie = '7' WHERE artikelnummer = ".intval($iTicket2).";";
				pg_query($dbconn, $sSQL2);
			}
		}
	}
}

if(is_array($_POST) && !empty($_POST))
{
	foreach($_POST as $sIndex3 => $sValue3)
	{
		if(preg_match('/^behoben_/', $sIndex3))
		{
			$iTicket3 = substr($sIndex3, 8);
			if(intval($iTicket3)>0)
			{
				$sSQL3 = "UPDATE data SET kategorie = '5' WHERE artikelnummer = ".intval($iTicket3).";";
				pg_query($dbconn, $sSQL3);
			}
		}
	}
}

if(is_array($_POST) && !empty($_POST))
{
	foreach($_POST as $sIndex4 => $sValue4)
	{
		if(preg_match('/^beobachten_/', $sIndex4))
		{
			$iTicket4 = substr($sIndex4, 11);
			if(intval($iTicket4)>0)
			{
				$sSQL4 = "UPDATE data SET kategorie = '6' WHERE artikelnummer = ".intval($iTicket4).";";
				pg_query($dbconn, $sSQL4);
			}
		}
	}
}

//L�schfunktion aus der Datenbank
if($l�schenBtn = true)
{
	$database->open_connection();
	$deleteString = "DELETE FROM data WHERE artikelnummer = ".intval($iTicket1).";";
	pg_query($dbconn, $deleteString);
}
else
{
	!pg_query($dbconn, $deleteString);
	echo '<script>alert(\'Fehler konnte aus unbestimmtem Grund nicht aus dem System gel�scht werden.\')</script>';
	return false;
}

//Abfrage der Datenbank werte der ausgesuchten Kategorie
if($aktualisierenButton == 'Aktualisieren')
{
	 $database->open_connection();
	 $abfrage = "SELECT artikelnummer, betreff, nutzername, datum, answers, fehlerbeschreibung, kategorie FROM data WHERE kategorie=".$fehlerkategorie." ORDER BY artikelnummer ASC ";
	 $ergebnis = pg_query($dbconn, $abfrage);
}

//Suchfunktion Pr�fung/Abfrage
if($searchBtn == '1')
{	
	if(preg_match('#[_�$`�+";@���$%&\'\\\*]#', $suchwort)) 
	{
		echo '<script>window.alert("Sucheingabe darf nur folgende Sonderzeichen enthalten: \n \". , : - / ! ?\" ")</script>';
	}
	elseif(preg_match($datumsFormat, $suchwort)) 
	{
	   	$search = 'datum::timestamp::date'; date('Y-m-d', strToTime($suchwort));
		$suchwort1 = "'";
	}
	elseif(preg_match($datumsFormat, $suchwort)) 
	{
		$search = 'datum::timestamp::date'; date('d.m.Y', strToTime($suchwort));
		$suchwort1 = "'";
	}
	
	if(preg_match($suchmusterBetreff, $suchwort))
	{
		$search = 'betreff';
		$suchwort1 = "'";
	}
	
	if(is_numeric($suchwort))							
	{
		$search = 'artikelnummer';
		$suchwort1 = "'";
	}
	elseif(preg_match($suchmusterNutzername, $suchwort))
	{
		$search = 'nutzername';
		$suchwort1 = "' OR betreff='$suchwort'"; 
	}
	
	if($radio == '11')
	{
		$bedingung = 'artikelnummer';
		$operator = '>';
		$radio = '0';
		$radioValue11 = 'checked';
	}
	else 
	{
		$bedingung = 'kategorie';
		$operator = '=';
	}
		
	$database->open_connection();
	$abfrage3 = "SELECT artikelnummer, betreff, nutzername, datum, answers, fehlerbeschreibung, kategorie FROM data WHERE ".$search."='".$suchwort."".$suchwort1." AND ".$bedingung."".$operator."".$radio." ORDER BY artikelnummer ASC "; 
	$ergebnis3 = pg_query($dbconn, $abfrage3);
}

//Selectbox Fehlerkategorien "selected"
if($fehlerkategorie == '1')
{
	$kategorieWert1 = 'selected';  
}
elseif($fehlerkategorie == '2')
{
	$kategorieWert2 = 'selected';
}
elseif($fehlerkategorie == '3')
{
	$kategorieWert3 = 'selected';
}
elseif($fehlerkategorie == '4')
{
	$kategorieWert4 = 'selected';  
}
elseif($fehlerkategorie == '5')
{
	$kategorieWert5 = 'selected';
}
elseif($fehlerkategorie == '6')
{
	$kategorieWert6 = 'selected';
}
elseif($fehlerkategorie == '7')
{
	$kategorieWert7 = 'selected';
}
elseif($fehlerkategorie == '10')
{
	$kategorieWert10 = 'selected';
}
elseif($fehlerkategorie == '9') 
{
	$kategorieWert9 = 'selected';
}


//Radiobox "checked" der erweiterten Suche
if($radio == '1')
{
	$radioValue1 = 'checked';
}
elseif($radio == '2')
{
	$radioValue2 = 'checked';
}
elseif($radio == '3')
{
	$radioValue3 = 'checked';
}
elseif($radio == '4')
{
	$radioValue4 = 'checked';
}
elseif($radio == '5')
{
	$radioValue5 = 'checked';
}
elseif($radio == '6')
{
	$radioValue6 = 'checked';
}
elseif($radio == '7')
{
	$radioValue7 = 'checked';
}
elseif($radio == '0')
{
	$radioValue11 = 'checked';
}


//HTML	    	
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>FixMyProblem</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body class="body2">
		<script type="text/javascript">
		
			<!-- Java Script Funktionen zum markieren der Fehler (beobachten,behoben,schwierig,l�schen) -->
			
			function set(id, text)
			{
				if(confirm(text))
				{
					document.getElementById("kategorie").selectedIndex = id;
		            return true;
				}
				else
				{
					document.getElementById("kategorie").selectedIndex = "0";
				    return false;
				}  	
			}
			
		</script>
		<form name="formularFixerror" action="fixerror.php" method="post">
		  <br />
		  <br />
			<table border="0" align="center" rules="none">
				<tbody>
					<tr>
						<td colspan="3" align="center">
							<input class="zurueckBtn" type="button" name="Zurueck" title="Zur Startseite" value="Zur�ck zur Startseite" onMouseout="this.style.background=\'darkgray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" onclick="window.top.location=\'home.php\'"><br>
						</td>
					</tr>
					<tr>
						<td>
						     &nbsp;
						</td>
					</tr>
					<tr>
						<td align="right">
							<img src="info.png" onclick="window.alert(\' Suchkriterien:\n Ticketnummer \n Betreff \n Nutzername \n Datum(TT.MM.JJJJ) \n Sucheingabe darf nur folgende Sonderzeichen enthalten: \n . , : - / ! ? \')" style="cursor:help;">							
						</td>
						<td>
							<input class="suchfeld" type="text" name="suchfeld" id="suchfeld" placeholder="Suchbegriff" value="'.$suchwort.'" title="Suchkriterien: Ticketnummer, Betreff, Nutzername, Datum" maxlength="40" size="19">
						</td>
						<td align="center">
							<button class="suchBtn" title="Suche starten" type="submit" onMouseout="this.style.background=\'white\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" '.$searchBtn.' value="1" id="searchBtn" name="searchBtn" href="fixerror.php"><img src="lupe2.jpg" />  Suchen </button>
						</td>
					</tr>
					<tr>
						<td>
							
						</td>
						<td>
						
						</td>
						<td align="center">
							<input type="button" class="erweiterteSuche" title="Erweiterte Suchfunktion einblenden" value="Erweitert" onMouseout="this.style.background=\'white\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" onclick="Javascript:document.getElementById(\'table\').style.display=\'table\'">
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
					</tr>
				  </tbody>
				</table>
					<!-- Radio Buttons zur erweiterten Schfunktion -->
					
					<table class="table2" id="table" border="1" rules="none" align="center">
						<tr>
							<td class="radioBtn">
								<p align="center" style="font-weight:bold;">Suchen in:</p>
								---------------------------<br />
								<input name="suchKategorie" '.$radioValue11.' value="11" style="cursor:pointer;" type="radio" checked>Allen Kategorien<br />
								---------------------------<br />
								<input name="suchKategorie" '.$radioValue6.' value="6" style="cursor:pointer;" type="radio">Beobachtungsliste <br />
								<input name="suchKategorie" '.$radioValue5.' value="5" style="cursor:pointer;" type="radio">Behobene Fehler <br />
								<input name="suchKategorie" '.$radioValue7.' value="7" style="cursor:pointer;" type="radio">Schwierige Fehler <br />
								---------------------------<br />
								<input name="suchKategorie" '.$radioValue1.' value="1" style="cursor:pointer;" type="radio">Softwarefehler <br />
								<input name="suchKategorie" '.$radioValue2.' value="2" style="cursor:pointer;" type="radio">Hardwarefehler <br />
								<input name="suchKategorie" '.$radioValue3.' value="3" style="cursor:pointer;" type="radio">Sonstige Probleme <br />
								<input name="suchKategorie" '.$radioValue4.' value="4" style="cursor:pointer;" type="radio">Hilfe<br />
								---------------------------<br />
								<p align="center"><button type="button" class="erweitertZuklappen" title="Erweiterte Suchfunktion ausublenden" onMouseout="this.style.background=\'white\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" onclick="Javascript:document.getElementById(\'table\').style.display=\'none\'">Ausblenden</button></p>
							</td>
						</tr>
					</table>
				</tbody>
			</table>
			<table>
				<tbody>
			    	<tr>
				    	<td>
				    		<select class="selectBox" name="fehlerkategorie" id="kategorie" title="W�hlen sie eine Kategorie aus">
					    		<option  '.$kategorieWert9.'  value="9" style="display:none;" align="center">...Kategorie w�hlen...</option>	
					    		<optgroup label="---------------------------------------------">
					    		<option  '.$kategorieWert10.' selected value="10" align="center">Alle Fehlermeldungen</option> 
					    		</optgroup>
					    		<optgroup label="---------------------------------------------">
					    		<option  '.$kategorieWert6.'  value="6" align="center">Beobachtungsliste</option>
				                <option  '.$kategorieWert5.'  value="5" align="center">Behobene Fehler</option> 
				                <option  '.$kategorieWert7.'  value="7" align="center">Schwierige Fehler</option>
				                </optgroup>
				                <optgroup label="---------------------------------------------">
	                            <option  '.$kategorieWert1.'  value="1" align="center">Softwarefehler</option>
				                <option  '.$kategorieWert2.'  value="2" align="center">Hardwarefehler</option>
	                            <option  '.$kategorieWert3.'  value="3" align="center">Sonstige Probleme</option> 
	                            <option  '.$kategorieWert4.'  value="4" align="center">Hilfe</option>
	                            </optgroup>
	                            <optgroup label="---------------------------------------------">
	                            </optgroup>
							</select>
						</td>																						                         
						<td>
							<input class="aktBtn" id="aktBtn" type="submit" value="Aktualisieren" name="senden" onMouseout="this.style.background=\'white\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" title="Die Seite und ihre Inhalte wird neu geladen">
						</td>
			    	</tr>
		    	</tbody>
			</table>	
			<table class="table" border="2" align="center" cellspacing="2" cellpadding="1" frame="box" rules="all">
				<tbody class="tbody">
					<tr>
						<th style="width:8%;" title="Jedem Fehler wird eine Ticketnummer zugewiesen">
							<p>Ticketnummer</p>
						</th>
						<th style="width:3%;" title="Status der Fehlermeldung">
							<p>Status</p>
						</th>
						<th style="width:25%;" title="Knappe Fehlerbeschreibung">
							<p>Betreff</p>
						</th>
						<th style="width:18%;" title="Nutzername">
							<p>Erstellt von</p>
						</th>
						<th style="width:12%;" title="Datum">
							<p>Erstellt am</p>
						</th>
						<th style="width:2%;"  title="Anzahl der Antworten">
							<p>Antworten</p>
						</th>
						<th style="width:18%;" colspan="2" title="Auszuf�hrende Aktionen">
							<p>Aktionen</p>
						</th>
					</tr>';
							//Ausgabe von der DB der Hauptwerte
							if($aktualisierenButton == 'Aktualisieren')
		                    {
								while($ausgabe = pg_fetch_array($ergebnis)) 	
								{
									$url = ''.$ausgabe[0].'';	
									$vurl = base64_encode($url);		
									
									$user = ''.$ausgabe[2].'';
									$vuser = base64_encode($user);
														
									$sVisibleStatusAuge = 'none';
									$sVisibleStatusHaken = 'none';
									$sVisibleStatusFragezeichen = 'none';
									
									if($ausgabe[6] == 6 )
									{
										$sVisibleStatusAuge = 'block';
									}
									elseif($ausgabe[6] == 5 )
									{
										$sVisibleStatusHaken = 'block';
									}
									elseif($ausgabe[6] == 7 )
									{
										$sVisibleStatusFragezeichen = 'block';
									}

									$datum_deutsch = date('d.m.Y H:i:s', strToTime($ausgabe[3]));
										
									$database->open_connection();
									$abfrage2 = "SELECT count(antwort) FROM data2 WHERE ticketnummer=".$ausgabe[0];
			 						$ergebnis2 = pg_query($dbconn, $abfrage2);	
											
										while($ausgabe2 = pg_fetch_array($ergebnis2)) 	
			   							{
										echo '<tr onmouseover="this.style.background=\'darkgray\'" onmouseout="this.style.background=\'lightgray\'">
													<td align="center">
														'.$ausgabe[0].'
													</td>
													<td align="center">
														<img  src="auge1.png" style="display:'.$sVisibleStatusAuge.';vertical-align:middle;" title="Dieser Fehler ist auf der Beobachtungsliste">
														<img  src="haken.png" style="display:'.$sVisibleStatusHaken.';vertical-align:middle;" title="Dieser Fehler wurde als behoben markiert">
														<img  src="fragezeichen2.png" style="display:'.$sVisibleStatusFragezeichen.';vertical-align:middle;" title="Dieser Fehler wurde als schwer behebbar markiert">
													</td>
													<td align="center">
														'.$ausgabe[1].'
													</td>
													<td align="center">
														'.$ausgabe[2].'
													</td>
													<td align="center">
														'.$datum_deutsch.'
													</td>
													<td align="center">
														'.$ausgabe2[0].'
													</td>
													<td align="center">
									         	  		<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'"
									         	  		 onMouseover="this.style.background=\'white\'" name="beobachten_'.$ausgabe[0].'"
									         	  		 onclick="return set(2, \'Wollen sie den gemeldeten Fehler auf die Beobachtungsliste setzen?\');"
									         	  		 title="Gemeldete Fehler auf Beobachtungsliste setzen"><img align="right" src="auge1.png"></button>
									                	<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'"
									                	 onMouseover="this.style.background=\'white\'" name="behoben_'.$ausgabe[0].'"
									                	 onclick="return set(3, \'Wollen sie den gemeldeten Fehler als behoben markieren?\');"
									                	 title="Gemeldete Fehler als behoben markieren"><img align="right" src="haken.png"></button>
									                	<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'"
									                	 onMouseover="this.style.background=\'white\'" name="schwierig_'.$ausgabe[0].'"
									                	 onclick="return set(4, \'Wollen sie den gemeldeten Fehler als schwierig markieren?\');"
									                	 title="Gemeldete Fehler als schwierig markieren"><img align="right" src="fragezeichen2.png"></button>
									               	    <button class="aktionsBtn" '.$löschenBtn.' type="submit" onMouseout="this.style.background=\'lightgray\'"
									               	     onMouseover="this.style.background=\'white\'" name="löschen_'.$ausgabe[0].'"
									               	     onclick="return set(0, \'Wollen sie den gemeldeten Fehler wirklich aus dem System löschen? \n Dieser Vorgang ist undwiederruflich.\');"
									               	     title="Gemeldete Fehler löschen"><img align="right" src="müll.png"></button>
												    </td>
												    <td align="center">
										    			<div>
										    				<button class="pfeilBtn" type="button" title="Klicken um den Fehler anzusehen" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" onclick="window.top.location=\'thread.php?ticketnummer='.$vurl.'&nutzername='.$vuser.'\'"><img src="pfeil1.png">
										    				</button>
										    			</div>
										   			</td>
								 			</tr>';
										}
									}
						      } 				
				
								//Ausgabe der Suchergebnisse	
								if($searchBtn == '1')
								{
									$row = 'visible';
									
									while($ausgabe3 = pg_fetch_array($ergebnis3)) 	
									{
										
										$sVisibleStatusAuge2 = 'none';
										$sVisibleStatusHaken2 = 'none';
										$sVisibleStatusFragezeichen2 = 'none';
										
										if($ausgabe3[6] == 6 )
										{
											$sVisibleStatusAuge2 = 'block';
										}
										elseif($ausgabe3[6] == 5 )
										{
											$sVisibleStatusHaken2 = 'block';
										}
										elseif($ausgabe3[6] == 7 )
										{
											$sVisibleStatusFragezeichen2 = 'block';
										}
													
										$url2 = ''.$ausgabe3[0].'';	
										$vurl2 = base64_encode($url2);	
										
										$user2 = ''.$ausgabe3[2].'';
										$vuser2 = base64_encode($user2);
													 	
										$datum_deutsch2 = date('d.m.Y H:i:s', strToTime($ausgabe3[3]));
										
										$database->open_connection();
										$abfrage4 = "SELECT count(antwort) FROM data2 WHERE ticketnummer=".$ausgabe3[0];
										$ergebnis4 = pg_query($dbconn, $abfrage4);
										
										while($ausgabe4 = pg_fetch_array($ergebnis4)) 	
										{	
									 				
								echo '<tr '.$row.' onmouseover="this.style.background=\'darkgray\'" onmouseout="this.style.background=\'lightgray\'">
											<td align="center">
												'.$ausgabe3[0].'
											</td>
											<td align="center">
												<img  src="auge1.png" style="display:'.$sVisibleStatusAuge2.';vertical-align:middle;" title="Dieser Fehler ist auf der Beobachtungsliste">
												<img  src="haken.png" style="display:'.$sVisibleStatusHaken2.';vertical-align:middle;" title="Dieser Fehler wurde als behoben markiert">
												<img  src="fragezeichen2.png" style="display:'.$sVisibleStatusFragezeichen2.';vertical-align:middle;" title="Dieser Fehler wurde als schwer behebbar markiert">
											</td>
											<td align="center">
												'.$ausgabe3[1].'
											</td>
											<td align="center">
												'.$ausgabe3[2].'
											</td>
											<td align="center">
												'.$datum_deutsch2.'
											</td>
											<td align="center">
												'.$ausgabe4[0].'
											</td>
											<td align="center">
							         	  		<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="beobachten_'.$ausgabe3[0].'" onclick="return set(2, \'Wollen sie den gemeldeten Fehler auf die Beobachtungsliste setzen?\');" title="Gemeldete Fehler auf Beobachtungsliste setzen"><img align="right" src="auge1.png"></button>						 						     
							                	<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="behoben_'.$ausgabe3[0].'" onclick="return set(3, \'Wollen sie den gemeldeten Fehler als behoben markieren?\');" title="Gemeldete Fehler als behoben markieren"><img align="right" src="haken.png"></button>
							                	<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="schwierig_'.$ausgabe3[0].'" onclick="return set(4, \'Wollen sie den gemeldeten Fehler als schwierig markieren?\');" title="Gemeldete Fehler als schwierig markieren"><img align="right" src="fragezeichen2.png"></button>
							               	    <button class="aktionsBtn" '.$l�schenBtn.' type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="l�schen_'.$ausgabe3[0].'" onclick="return set(0, \'Wollen sie den gemeldeten Fehler wirklich aus dem System l�schen? \n Dieser Vorgang ist undwiederruflich.\');" title="Gemeldete Fehler l�schen"><img align="right" src="m�ll.png"></button>							             
										    </td>
										    <td align="center">
										    	<div>
										    		<button class="pfeilBtn" type="button" title="Klicken um den Fehler anzusehen" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" onclick="window.top.location=\'thread.php?ticketnummer='.$vurl2.'&nutzername='.$vuser2.'\'"><img src="pfeil1.png">
										    		</button>
										    	</div>
										    </td>
						 			</tr>';	
						 				}
						 			}
						 		}
				
								//Pr�ffunktion ob die Suche erfolgreich war
								if($searchBtn == '1')
								{
									if($ergebnis4 == null)
									{
										echo '<script>window.alert("Es konnten keine Suchergebnisse gefunden werden.")</script>';
									}
								}

								//Ausgabe ALLER in der Datenbank eingeschriebenen Daten
								if($fehlerkategorie == '10')
								{
									$row2 = 'visible';
									
									$database->open_connection();
								    $abfrage5 = "SELECT artikelnummer, betreff, nutzername, datum, answers, fehlerbeschreibung, kategorie FROM data WHERE artikelnummer>'1' ORDER BY artikelnummer ASC";
					 				$ergebnis5 = pg_query($dbconn, $abfrage5);
									
									while($ausgabe5 = pg_fetch_array($ergebnis5)) 	
									{
										
										$sVisibleStatusAuge3 = 'none';
										$sVisibleStatusHaken3 = 'none';
										$sVisibleStatusFragezeichen3 = 'none';
										
										if($ausgabe5[6] == 6 )
										{
											$sVisibleStatusAuge3 = 'block';
										}
										elseif($ausgabe5[6] == 5 )
										{
											$sVisibleStatusHaken3 = 'block';
										}
										elseif($ausgabe5[6] == 7 )
										{
											$sVisibleStatusFragezeichen3 = 'block';
										}
													
										$url3 = ''.$ausgabe5[0].'';	
										$vurl3 = base64_encode($url3);
										
										$user3 = ''.$ausgabe5[2].'';
										$vuser3 = base64_encode($user3);	
			
										$datum_deutsch3 = date('d.m.Y H:i:s', strToTime($ausgabe5[3]));
										
										$database->open_connection();
										$abfrage6 = "SELECT count(antwort) FROM data2 WHERE ticketnummer=".$ausgabe5[0];
										$ergebnis6 = pg_query($dbconn, $abfrage6);
																
										while($ausgabe6 = pg_fetch_array($ergebnis6)) 	
										{
											if($searchBtn == '1')
											{
												$row2 = 'hidden';
											}	
									 				
								echo '<tr '.$row2.' onmouseover="this.style.background=\'darkgray\'" onmouseout="this.style.background=\'lightgray\'">
											<td align="center">
												'.$ausgabe5[0].'
											</td>
											<td align="center">
												<img  src="auge1.png" style="display:'.$sVisibleStatusAuge3.';vertical-align:middle;" title="Dieser Fehler ist auf der Beobachtungsliste">
												<img  src="haken.png" style="display:'.$sVisibleStatusHaken3.';vertical-align:middle;" title="Dieser Fehler wurde als behoben markiert">
												<img  src="fragezeichen2.png" style="display:'.$sVisibleStatusFragezeichen3.';vertical-align:middle;" title="Dieser Fehler wurde als schwer behebbar markiert">
											</td>
											<td align="center">
												'.$ausgabe5[1].'
											</td>
											<td align="center">
												'.$ausgabe5[2].'
											</td>
											<td align="center">
												'.$datum_deutsch3.'
											</td>
											<td align="center">
												'.$ausgabe6[0].'
											</td>
											<td align="center">
							         	  		<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="beobachten_'.$ausgabe5[0].'" onclick="return set(2, \'Wollen sie den gemeldeten Fehler auf die Beobachtungsliste setzen?\');" title="Gemeldete Fehler auf Beobachtungsliste setzen"><img align="right" src="auge1.png"></button>						 						     
							                	<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="behoben_'.$ausgabe5[0].'" onclick="return set(3, \'Wollen sie den gemeldeten Fehler als behoben markieren?\');" title="Gemeldete Fehler als behoben markieren"><img align="right" src="haken.png"></button>
							                	<button class="aktionsBtn" type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="schwierig_'.$ausgabe5[0].'" onclick="return set(4, \'Wollen sie den gemeldeten Fehler als schwierig markieren?\');" title="Gemeldete Fehler als schwierig markieren"><img align="right" src="fragezeichen2.png"></button>
							               	    <button class="aktionsBtn" '.$l�schenBtn.' type="submit" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" name="l�schen_'.$ausgabe5[0].'" onclick="return set(0, \'Wollen sie den gemeldeten Fehler wirklich aus dem System l�schen? \n Dieser Vorgang ist undwiederruflich.\');" title="Gemeldete Fehler l�schen"><img align="right" src="m�ll.png"></button>							             
										    </td>
										    <td align="center">
										    	<div>
										    		<button class="pfeilBtn" type="button" title="Klicken um den Fehler anzusehen" onMouseout="this.style.background=\'lightgray\'" onMouseover="this.style.background=\'white\'" onclick="window.top.location=\'thread.php?ticketnummer='.$vurl3.'&nutzername='.$vuser3.'\'"><img src="pfeil1.png">
										    		</button>
										    	</div>
										    </td>
						 			</tr>';	
						 				}
						 		   }
						 	}	
		echo '</tbody>
			</table>
			<br />
			<br />
			<br />
			<br />
			<table border="0" align="center" rules="none">
				<tbody>
					<tr>
						<td colspan="2" align="center">
							<input class="zurueckBtn" type="button" name="Zurueck" onMouseout="this.style.background=\'darkgray\'; this.style.color=\'black\'" onMouseover="this.style.background=\'lightgray\'; this.style.color=\'red\'" title="Zur Startseite" value="Zur�ck zur Startseite" onclick="window.top.location=\'home.php\'"> 
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<br />
		</form>
	</body>
</html>';

?>