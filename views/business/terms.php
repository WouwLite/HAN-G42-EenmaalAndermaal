<!-- /views/public/terms.php -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/app.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/main.inc.php');

$pageTitle = 'Algemene Voorwaarden';

?>
<div class="container-float">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=$app_url?>">Thuis</a></li>
        <li class="breadcrumb-item"><a href="<?=$app_url?>/views/business/about.php">Bedrijf</a></li>
        <li class="breadcrumb-item active"><?=$pageTitle?></li>
    </ol>
</div>
<div class="container">
    <h1><?=$pageTitle?></h1>

    <h2>Algemene regels voor het plaatsen van veilingen</h2>
    <p>Om ervoor te zorgen dat er op EenmaalAndermaal geen foutieve/incorrecte veilingen worden geplaatst zijn er een aantal regels opgesteld. Ook is het niet de bedoeling dat er foutief gedrag op de site plaats vindt.
    Hieronder volgen de regels:</p>
    <ol>
        <li>Geen incorrecte advertenties plaatsen op EenmaalAndermaal.</li>
        <li>Geen valse aanbiedingen/biedingen.</li>
        <li>Biedingen zijn niet bindend. Dit wil zeggen dat een verkoper een bod niet hoeft te accepteren.</li>
    </ol>

    <h3>Algemene gebruikersvoorwaarden</h3>
    <p>In dit hoofdstuk staan de algemene gebruikersvoorwaarden van EenmaalAndermaal.</p>
    <ol>
        <h5><li>Welkom bij EenmaalAndermaal!</li></h5>
        <p>Zodra u deze site bezoekt, gaat u akkoord met de gebruikersvoorwaarden. EenmaalAndermaal raadt iedereen aan op de gebruikersvoorwaarden te lezen voordat er gebruikt gemaakt wordt van de website.</p>

        <h5><li>Bescherming van uw privacy</li></h5>
        <p>In het door ons gemaakte <a href="<?= $app_url ?>/views/business/privacy.php">Privacybeleid</a> leggen wij uit hoe wij gebruik maken van de verzamelde gegevens.</p>

        <h5><li>Minderjarigen</li></h5>
        <p>De diensten van EenmaalAndermaal kunnen alleen bezocht worden door gebruikers met een volwassenstatus (ouder dan 18 jaar). Echter kunnen er ook minderjarigen op onze website dingen verkopen/kopen.
        Dit kan echter alleen met toestemming van de wettelijke vertegenwoordiger. Ook is het de bedoeling dat de minderjarigen zelfstandig een actie uitvoeren op de website.</p>

        <h5><li>Misbruik van de website en de gevolgen daarvan.</li></h5>
        <p>Voor de veiligheid worden de gegevens van de klant afgeschermd, men ziet alleen de gebruikersnaam. Ook wordt de website schoon gehouden van illegale en inbreuk makende advertenties.
        Beledigende problemen en andere problemen verzoeken wij u ervan gebruik te maken om dit duidelijk te maken bij de admin's. Dit kan door middel van een mail te sturen naar EenmaalAndermaal.
        Indien er door ons klachten worden ontvangen van andere gebruikers waaruit op te merken is dat de desbetreffende gebruiker bepaalde regels niet naleeft. Dan kunnen wij doen besluiten om
        de gebruiker te bannen. Hierdoor wordt zijn account automatisch van de site gehaald. Ook worden de advertenties die zijn geplaatst door deze gebruiker verwijderd. De gebruikers die op
        de desbetreffende advertentie hebben geboden krijgen een mailtje waarin staat dat de veiling is afgebroken.

        Stel dat er diefstal wordt gepleegd, dit wil zeggen dat de spullen zijn geleverd maar er wordt niet betaald. Of het geval dat er is betaald maar de spullen niet worden geleverd.
        Dan kan EenmaalAndermaal de persoonlijke gegevens van de desbetreffende gebruiker doorgeven aan de politie.</p>

        <h5><li>EenmaalAndermaal geeft geen garanties</li></h5>
        <p>Er kan niet gegarandeerd worden dat de diensten van onze website altijd 100% zullen voldoen aan uw verwachtingen. Ook kan er niet gegarandeerd worden dat de website altijd 100%
        foutloos functioneert en dat er altijd 100% veilige toegang is.

        Alle informatie en getallen die op deze website staan zijn onder voorbehoud van typefouten en spelfouten.</p>

                <h5><li>Aansprakelijkheid van EenmaalAndermaal</></li></h5>
        <p>Er worden door ons een aantal dingen uitgesloten waar een gebruiker niet het recht van aansprakelijkheid kan doen op EenmaalAndermaal
        Wij sluiten hierdoor alle onderstaande dingen uit waar de gebruiker schade door lijdt:</p>
            <ol>
                <li>Gebruik van de diensten van EenmaalAndermaal.</li>
                <li>Onjuiste informatie op EenmaalAndermaal.</li>
                <li>Wijzigingen op EenmaalAndermaal.</li>
                <li>Als delen van EenmaalAndermaal niet 100% veilig beschikbaar zijn.</li>
            </ol>
        <p>Hier kunnen wij dus niet voor aansprakelijk worden gesteld.</p>

        <h5><li>Wijzigen van EenmaalAndermaal</li></h5>
        <p>Wij als EenmaalAndermaal kunnen altijd wijzigingen op de website aanbrengen. Wij zullen er naar streven om wijzigingen tijdig van te voren aan te geven, zodat hier rekening
        mee gehouden kan worden door de gebruikers.</p>

                <h5><li>Overige belangrijke voorwaarden</li></h5>
        <p>Wij, EenmaalAndermaal, kunnen de gebruikersvoorwaarden ten allen tijde veranderen. Wij zullen proberen om zo'n verandering binnen een redelijke termijn van invoering aankondigen.
        Dit wordt gedaan zodat gebruikers er tijdig van op de hoogte kunnen zijn. </p>
    </ol>
</div>
<br>
<br>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/include/sidebar.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php');
?>