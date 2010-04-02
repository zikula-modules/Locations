<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2008, Zikula Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Generated_Modules
 * @subpackage locations
 * @author Steffen Voss
 * @url http://kaffeeringe.de
 *
 * @param        array       $params       All attributes passed to this function from the template
 * @param        object      &$render      Reference to the Smarty object
 * @return       string      The output of the plugin
 */

function smarty_function_phonenumber_format($params, &$render)
{
    $phonenumber_formatted = '';
    $PhoneTester = new PhoneTester();
    if (isset($params['country'])) $PhoneTester->setLand($params['country']);
    if (array_key_exists('Din5008', $params) && $params['Din5008']) $PhoneTester->setDIN5008();
    if (array_key_exists('setArray', $params) && $params['setArray']) $PhoneTester->setArray();
    if (array_key_exists('assign', $params)) {
        $render->assign($params['assign'], $PhoneTester->PhoneOutput($params['input']));
    }
    else {
        return $PhoneTester->PhoneOutput($params['input']);
    }
}

/* -------------------------------------------------------------------------------------------------------*\
 ¦   Telefonnummern Testen und bereinigen / Aufbereitete Rückgabe, z.B. nach DIN 5008                       ¦
 ¦   Version 2.1 vom 09. September 2004   von Martin Scheiben - SCHEIBEN DESIGN                             ¦
 ¦   Diese Class darf in unveränderter Form frei verwendet werden.                                          ¦
 ¦                                                                                                          ¦
 ¦   ******************************************                                                             ¦
 ¦   *** JEDE HAFTUNG WIRD AUSGESCHLOSSEN!  ***                                                             ¦
 ¦   ******************************************                                                             ¦
 ¦                                                                                                          ¦
 ¦   Original unter http://1-2.ch/freeware/telefonnummern                                                   ¦
 ¦ -------------------------------------------------------------------------------------------------------- ¦
 ¦                                                                                                          ¦
 ¦  Laut DIN 5008 sollen Telefonnummern ohne Zweiergruppen von hinten nach vorne geschrieben werden.        ¦
 ¦  Die "Funktionsbezoge Schreibweise", wie es so schön heisst, ist nicht besonders lesbar...               ¦
 ¦  Da hält sich verständlicher Weise kaum einer an die Regeln, und selbst der Duden beschreibt die DIN     ¦
 ¦  nicht gleich wie beispielsweise Bertelsmann...                                                          ¦
 ¦  Aber da dies kaum jemand mag, unterteile ich die Nummern nach Wunsch jeweils in                         ¦
 ¦  kleine, lesbare portionen und werwende Klammern zur besseren Lesbarkeit.                                ¦
 ¦                                                                                                          ¦
 \*--------------------------------------------------------------------------------------------------------*/

class PhoneTester
{

    // Die nun folgenden Werte können an die Class übermittelt werden, um die vCard mit Daten zu füllen.
    // oder um einen bestimmten Wert zu erhalten.
    //--------------------------------------------------------------------------------------------

    var $PHONE_ERROR;          // Fehlermeldung
    var $FeedbackERROR;        // Fehlermeldung zurückgeben ? TRUE oder FALSE
    var $PHONE_WARNUNG;        // Warnung(en)
    var $PhoneInput;           // Die zu untersuchende Telefonnummer
    var $PhoneNumAnz;          // Die Länge (nur Zahlen)
    var $PhoneNumAnzIst;       // Die Länge, (nur Zahlen) die es Aufbereitet noch hat.
    var $Landesvorwahl;        // Die Landesvorwahl
    var $Ortsnetzkennzahl;     // Die Ortskenntahl
    var $Teilnehmerkennzahl;   // Die Teilnehmernummer, ohne Länder- oder Ortskenntahl und ohne Durchwahl.
    var $PhoneDurchwahl;       // Die Durchwahlnummer
    var $LandTabelle=array();  // TABELLE: Beinhaltet als Array alle Länderangaben
    var $Rufnummer=array();    // Rückgabe-Array mit allen fertigen Angaben
    var $TabID;                // TABELLE: Beinhaltet die ID der Tabellezeile, aus der gelesen werden soll
    var $PhoneTyp;             // National oder International
    var $NummerLesbar;         // Aufbereitete Telefonnummer im lesbaren Format
    var $NummerDin5008;        // Aufbereitete Telefonnummer im hässlichen, unpraktischen DIN5008-Format Format
    var $LandNameDeutsch;      // Das Land, (Ermittelt aufgrund der Vorwahlnummer)
    var $LandNameEnglisch;     // Das Land, englisch
    var $LandNameFranzoesisch; // Das Land, französisch
    var $LandNameItalienisch;  // Das Land, Italienisch
    var $LandSearch;           // Kann helfen, die Vorwahl zu finden.
    var $Laendercode;          // Ländercode, Domainendung
    var $PhoneArray;           // Wenn der Benutter eine Array mit den Angaben will

    //---------------------------------------------------------------------------------------------------------------------

    function setNumAnz($wert)     {$this->PhoneNumAnz =    trim($wert);}
    function setLand($wert)       {$this->LandSearch =     trim($wert);}
    function setDIN5008()         {$this->NummerDin5008 =  "TRUE";}
    function setArray()           {$this->PhoneArray =     "TRUE";}
    function setNoError()         {$this->FeedbackERROR =  "NO";}

    function PhoneAufbereiter()
    {
        // Zerlegt die Nummer in ihre Betsandteile und ergänzt fehlende Angaben
        $this->VorwahlArray();                                      // Array mit Ländervorwahlen-Angaben füllen
        $this->DurchwahlNummerSucher();                             // seppariere Durchwahlnummer
        $this->PhoneInput = $this->PhoneClean($this->PhoneInput);   // Bereinige die Rufnummer
        $this->ZerteileRufnummer();                                 // Ermittle die einzelnen Teile
        // Nun liegt die Rufnummer "Zerhackt" und bereinigt vor

        // Darfs ein bisschen mehr sein ?
        // Fehlen noch die Werte aus der Tabelle ? Dann ergänze diese
        if (!$this->TabID AND $this->Landesvorwahl)
        {
            $this->TabID =  $this->suche_ID_multi_array("Vorwahl", $this->Landesvorwahl);
            if ($this->TabID )
            {$this->Landesvorwahl = $this->LandTabelle[$this->TabID]["Vorwahl"];
            }
        }

        // Nichts passendes gefunden ? Dann eine Warnung zurückgeben
        if (!$this->TabID)
        { $this->PHONE_WARNUNG .= "Länderkennung sicherheitshalber prüfen!!"; }


        // National oder International?
        if (!ereg ("^\+", $this->PhoneInput))                // Kein Plus am Anfang = National
        {
            if ($this->LandSearch)                    // Vielleicht habe ich mit dem Land mehr Glück ...
            {
                $this->TabID =  $this->suche_ID_multi_array("Land", strtoupper($this->LandSearch)) ;
                if ($this->TabID) $this->Landesvorwahl = $this->LandTabelle[$this->TabID]["Vorwahl"];

                else
                { $this->TabID =  $this->suche_ID_multi_array("NameEnglisch", strtoupper($this->LandSearch)) ;
                if ($this->TabID) $this->Landesvorwahl = $this->LandTabelle[$this->TabID]["Vorwahl"];

                else
                {  $this->TabID =  $this->suche_ID_multi_array("NameFranzoesisch", strtoupper($this->LandSearch)) ;
                if ($this->TabID) $this->Landesvorwahl = $this->LandTabelle[$this->TabID]["Vorwahl"];

                else
                { $this->TabID =  $this->suche_ID_multi_array("NameItalienisch", strtoupper($this->LandSearch)) ;
                if ($this->TabID) $this->Landesvorwahl = $this->LandTabelle[$this->TabID]["Vorwahl"];
                else
                { $this->TabID =  $this->suche_ID_multi_array("Laendercode", strtoupper($this->LandSearch)) ;
                if ($this->TabID) $this->Landesvorwahl = $this->LandTabelle[$this->TabID]["Vorwahl"];
                }       }
                }
                }
            }
            else  $this->PhoneTyp   = "National";  // Der Typ / (Nationale Rufnummer)
        }
        else     {
            $this->PhoneTyp   = "International";         // Der Typ / (International Rufnummer)
        }

        // Land in verschiedenen
        $this->LandNameDeutsch= $this->LandTabelle[$this->TabID]["Land"];                   // Land Deutschsprachig
        $this->LandNameEnglisch= $this->LandTabelle[$this->TabID]["NameEnglisch"];          // Das Land, englisch
        $this->LandNameFranzoesisch= $this->LandTabelle[$this->TabID]["NameFranzoesisch"];  // Das Land, französisch
        $this->LandNameItalienisch= $this->LandTabelle[$this->TabID]["NameItalienisch"];    // Das Land, Italienisch

        // Ländercode
        $this->Laendercode= $this->LandTabelle[$this->TabID]["Laendercode"];    // Der Ländercode / Domainendung

        // Ermittle die Anzahl gültiger Zahlen. Das "(0)" ist nicht zu werten, ebensowenig nicht-numerische Zeichen.
        $Zahlenlaenge    = strlen(trim(eregi_replace("[^0-9]",  null, (eregi_replace("\(0)",  null, $this->MakeDIN5008())))));
        $Zahlenlaenge -= 2;
        if (!$this->PhoneNumAnz) $this->PhoneNumAnz = 6;  // Initialisiere wenn nötig die Anzahl erforderlicher Zahlen

        if ($Zahlenlaenge < $this->PhoneNumAnz)
        {$this->PHONE_ERROR .= "Die eingegebene Rufnummer besteht aus zu wenig Zahlen. (".($Zahlenlaenge)." statt $this->PhoneNumAnz )<br>";}
        $this->PhoneNumAnzIst =   $Zahlenlaenge;
    }

    function PhoneOutput($PhoneNr = "")
    {
        if ($PhoneNr ) $this->PhoneInput =   trim($PhoneNr);  // Wert zuweisen.

        // Diese Funktion kümmert sich um die Gestaltung der Rufnummer-Ausgabe
        // Rufnummer aufbereiten und fehlende Angaben ergänzen
        $this->PhoneAufbereiter();

        // Stimmt was nicht? Fehler zurückgeben!
        if ($this->PHONE_ERROR AND $this->FeedbackERROR != "NO")
        {
            $this->Rufnummer["ERROR"] = $this->PHONE_ERROR;
            if ($this->PhoneArray) return $this->Rufnummer;
            else                     return $this->PHONE_ERROR;
        }

        // Den Trennstrch hinzufügen, sollte eine Durchwahlnummer vorhanden sein.
        if ($this->PhoneDurchwahl) $this->PhoneDurchwahl= "-".$this->PhoneDurchwahl;

        if ($this->NummerDin5008)
        {
            // Der Benutzer will die Nummer nach DIN-Norm 5008.
            return $this->MakeDIN5008();
        }

        if ($this->PhoneArray)
        {  // Der Benutzer will eine Array mit allen Angaben
            $this->Rufnummer["DIN5008"]          = $this->MakeDIN5008();
            $this->Rufnummer["Rufnummer"]        = $this->MakePhoneLesbar();
            $this->Rufnummer["NameEnglisch"]     = $this->LandNameEnglisch;
            $this->Rufnummer["NameFranzoesisch"] = $this->LandNameFranzoesisch;
            $this->Rufnummer["NameItalienisch"]  = $this->LandNameItalienisch;
            $this->Rufnummer["Laendercode"]      = $this->Laendercode;
            $this->Rufnummer["LaengeNR"]         = $this->PhoneNumAnzIst;        // Die Länge (nur Zahlen) zurückgeben
            $this->Rufnummer["Land"]             = $this->LandNameDeutsch;       // Das Land, (Ermittelt aufgrund der Vorwahlnummer)
            $this->Rufnummer["Landvorwahl"]      = $this->Landesvorwahl;         // Die Ländervorwahl
            $this->Rufnummer["OrtVorwahl"]       = eregi_replace("\(0)",  "0", $this->Ortsnetzkennzahl);              // Nationale Vorwahl
            $this->Rufnummer["OrtVorwahl"]       = eregi_replace("[\()]",  null,$this->Rufnummer["OrtVorwahl"]);      // Nationale Vorwahl
            $this->Rufnummer["Teilnehmer"]       =eregi_replace("[^0-9]",  null,  $this->Teilnehmerkennzahl);          // Teilnehmernummer, ohne Ortsvorwahl oder Durchwahlnummer
            $this->Rufnummer["Durchwahl"]        = eregi_replace("-",  "", $this->Durchwahlnummer);         // Durchwahlnummer
            $this->Rufnummer["Laendercode"]      = $this->Laendercode;                                      // Ländercode / Domainendung
            $this->Rufnummer["Typ"]              = $this->PhoneTyp;
            $this->Rufnummer["Durchwahl"]        =  eregi_replace("[^0-9]",  null,  $this->PhoneDurchwahl);
            return $this->Rufnummer;
        }
         
        else
        { $this->Rufnummer["Durchwahl"] = ""; return $this->MakePhoneLesbar();}

    }

    function MakeDIN5008()
    {
        // Doppelte "-" entfernen. Kann passieren, wenn einer die Klasse mehrfach einbindet.
        $this->PhoneDurchwahl = preg_replace("/-+/", '-', $this->PhoneDurchwahl);
        $this->Teilnehmerkennzahl = trim(eregi_replace("[^0-9]",  null,  $this->Teilnehmerkennzahl));
        if ($this->Landesvorwahl) $this->Landesvorwahl = "+".trim(eregi_replace("[^0-9]",  null,  $this->Landesvorwahl));
        return   trim($this->Landesvorwahl." ".
        abs(trim(eregi_replace("\(0)",  "",  $this->Ortsnetzkennzahl)))." ".
        $this->Teilnehmerkennzahl.
        $this->PhoneDurchwahl);
    }

    function MakePhoneLesbar()
    {
        // Damit die Rufnummer optisch ansprechbar wird, gezielt leerzeichen in die Teilnehmernummer einfügen
        // ... Fehlt die Ortskennzahl, dann lass die Nummer lieber am Block ...
        if ($this->Ortsnetzkennzahl) {$this->Teilnehmerkennzahl = $this->SpaceMe($this->Teilnehmerkennzahl, 2);}

        if     (ereg ("^0",   trim($this->Ortsnetzkennzahl)) AND $this->Ortsnetzkennzahl ) $this->Ortsnetzkennzahl="(".$this->Ortsnetzkennzahl.")";
        // Manche geben trotz internationaler Vorwahl noch ne "0" vor die Vorwahl, z.B. +41 071 - 123 123 123
        else $this->Ortsnetzkennzahl= "(0". abs(trim(eregi_replace("\(0\)",  null,  $this->Ortsnetzkennzahl))).")";

        // Bei manchen Nummern wird nach all den Bereinigungsversuchen ein (00) übrigbleiben. Weg damit!
        $this->Ortsnetzkennzahl=  eregi_replace("\(00\)",  null,  $this->Ortsnetzkennzahl);

        return   trim($this->Landesvorwahl." ".
        $this->Ortsnetzkennzahl." ".
        $this->Teilnehmerkennzahl.
        $this->PhoneDurchwahl);
    }

    function DurchwahlNummerSucher()
    {
        # Es wird nach einer Durchwahlnummer gesucht. Wenn eine gefunden wird,
        # Soll diese getrennt von der restlichen Telefonnummer aufbewahrt werden
        # Dazu Zerlege ich den String angand des "-".
        # Hat es nur ein Trennzeichen, erhalte ich 2 Arrayfelder.
        # Habe ich mehr Felder erhalten, ersetze ich alle "-" durch Leerzeichen, da ich nicht
        # mehr davon ausgehen kann, dass es eine Durchwahlnummer ist.

        $DurchwahlTest = array_reverse(explode("-", $this->PhoneInput));
        $AnzTrennzeichen = count($DurchwahlTest);

        if ($AnzTrennzeichen > 2)
        { $this->PhoneInput = eregi_replace("-",  " ", $this->PhoneInput); }

        else                                                               // Nur ein Trennzeichen wurde gefunden.
        {
            if(!preg_match("/^[0-9]*$/",trim($DurchwahlTest[0])))
            {  // Besteht das Ergebniss nicht zu 100% aus Zahlenist es keine Durchwahlnummer. Also weg mit den "-"
                $this->PhoneInput = eregi_replace("-",  " ", $this->PhoneInput);
            }

            else                                                         // Es hat vermutlich eine Durchwahlnummer!
            {
                if (trim($DurchwahlTest[1]) != "")                     // Natürlich muss VOR der Durchwahlnummer auch eine Zahl sein ...
                {
                    $this->PhoneDurchwahl = trim($DurchwahlTest[0]);          // Merke die Durchwahlnummer
                    $this->PhoneInput = $DurchwahlTest[1];                    // Gib die Telefonnummer OHNE die Durchwahlnummer zurück
                }                                                         // (Diese setze ich erst ganz am Schluss wieder ein.)
            }
        }
    }


    function WarnungenAusgeben()
    {
        // Es muss nicht unbedingt eine ungültige Nummer sein,
        // aber gewisse Fehler können sich dennoch einschleichen.
        if ($this->PHONE_WARNUNG)
        return $this->PHONE_WARNUNG;
        else    return false ;
    }


    function PhoneClean($PhoneClean)
    {
        // Gruselkabinett der verschiedenen Vorwahl-Schreibweisen :
        // Es gibt unzählige Varianten. Wenigstens die Gebräuchlichsten davon sollen erkannt und bereinigt werden
         
        // Ersetze 00 oder (00 oder ( 00 durch ein + ,
        // was der Internationalen Vorwahl entspricht
        $PhoneClean = eregi_replace("^(00|\(00|\( 00)",  "+",          $PhoneClean);
         
        // Hat jemand 2x hintereinander ein + eingegeben,
        // ersetze es durch ein einzelnes +
        $PhoneClean = eregi_replace("\+\+",  "+",                      $PhoneClean);
         
        // Hat da jemand ein oder zwei Leerzeichen nach dem Plus eingegeben,
        // dann entferne auch diese.
        $PhoneClean = eregi_replace("^(\+ |\+  )",  "+",               $PhoneClean);

        // Wenn es Ländervorwal hat, beginnt diese nun mit einem +
        // Hat es ein Plus am Anfang ? Wenn ja,(0)entfernen.
        // (Diese wird bei Ortsvorwahlen gerne als Lesehilfe hinzugefügt)
        if (ereg ("^\+",  $PhoneClean))
        $PhoneClean = trim(eregi_replace("\(0)",  "",                   $PhoneClean));

        // Alle typischen Zeichen ausser den Numerischen und dem Plus durch Leerzeichen ersetzen,
        // entferne anschliessend unbekannte Zeichen und ersetze doppelte Leerzeichen duch einfache
        $PhoneClean  = eregi_replace("\(|)|/|\.", " ",                 $PhoneClean);
        $PhoneClean  = trim(eregi_replace("[^0-9\+[:space:]]",  null,  $PhoneClean));
        $PhoneClean  = trim(preg_replace("/ +/", ' ',                  $PhoneClean));
        return $PhoneClean;
    }

    function ZerteileRufnummer()
    {
        // I N T E R N A T I O N A L E  Schreibweise: -----------------------------------------------------------------
        if (ereg ("^\+", $this->PhoneInput))                                  // Ein Plus am Anfang = Ländervorwahl
        {
            $NutzFeld = 1;                                                     // >>> Ein Feld muss schon mal ausgelassen werden.

            $TelFragmente = explode(" ", $this->PhoneInput);                   // Trenne die Telefonnummer bei den Leerzeichen

            if (count($TelFragmente) > 1)                                  // Wenn die Nummer nicht "aus einem Guss" besteht
            {
                $this->Landesvorwahl = $TelFragmente[0];                   // Die Ländervorwahl Vorwahl ist im ersten Feld

                if (count($TelFragmente) > 2)                              // Die nationale Vorwahl ist im zweiten Feld. Gibt es diese hier ?
                {
                    $this->Ortsnetzkennzahl = "(0)".$TelFragmente[1];
                    $NutzFeld += 1;                                      //  >>> Also sind es 2 Felder, die nachher beim Zusammensetzen ausgelassen werden.
                }
                $this->Teilnehmerkennzahl = "";
                for($nn= $NutzFeld ;$nn<count($TelFragmente);$nn++)
                { $this->Teilnehmerkennzahl .= $TelFragmente[$nn]; } // Die Zahlen ohne Vorwahlen zusammenfügen

            }

            else
            {
                // Pech... Ohne Leerzeichen kann die Rufnummer nur schwer unterteilt werden.
                // Ich versuche wenigstens anhand der Vorwahl etwas zu erkennen.

                $l=strlen($this->PhoneInput);       // Länge der Telefonnummer ermitteln
                if ($l < 6)  {$AnzSuche = $l;}       // Ist die Rufnummer kleiner als das Suchmuster muss ich die Länge des Suchmusters kürzen.
                else {$AnzSuche = 6;}                // Sonst nehme ich mal die ersten 5 Zahlen sowie das Plus, um zu suchen.

                $Feldname     = "Vorwahl";
                for($n=$AnzSuche;$n>1;$n--)
                {
                    $SuchInVorwahl = substr($this->PhoneInput, 0, $n);                 // Als Suchmuster etwas von Vorderteil der Nummer nehmen
                    $x =  $this->suche_ID_multi_array($Feldname, $SuchInVorwahl);
                    if($x)                                                         // Glück gehabt. Die Vohrwahl passt zu einer in der Tabelle.
                    {
                        $this->Landesvorwahl= $SuchInVorwahl;
                        $this->TabID= $x;
                        $this->PHONE_WARNUNG .= "Landesvorwahl wurde erkannt, nicht aber die Ortsvorwahl !<br>";
                        $this->Teilnehmerkennzahl = substr($this->PhoneInput, $n);
                        break;                                                    // Schleife verlassen
                    }
                }


                if ($x > 1) {$this->Landesvorwahl = $this->LandTabelle[$x]["Vorwahl"];}   // Es wurde eine passende internationale Vorwahl gefunden
                else {
                    // Die Vorwahl kann nicht erraten werden und der Rest klebt an einem Block.
                    $this->PHONE_ERROR = "Kein g&uuml;ltiges Muster in der Rufnummer erkannt.<br>\n". // Nummer zur Formatierung unbrauchbar
                        " Bitte benutzen Sie Leerzeichen zur optischen Unterteilung";
                }
            }
        }

        // N A T I O N A L E  Schreibweise: ---------------------------------------------------------------------------
        else  /// Es hatte kein + am Anfang ...
        {

            $TelInlandBereich = explode(" ", $this->PhoneInput);
            if (trim($TelInlandBereich[1]) != "")                      // Ist im Feld eins nichts, ist die Nummer aus einem Guss. Sonst ...
            {
                $this->Ortsnetzkennzahl = $TelInlandBereich[0];        // Hier ist die Ortsvorwahl zu erwarten
                $this->Teilnehmerkennzahl = "";
                for($x=1;$x<count($TelInlandBereich);$x++)
                { $this->Teilnehmerkennzahl .= $TelInlandBereich[$x]; }

                $this->Teilnehmerkennzahl  = trim(eregi_replace("[^0-9]",  null,  $this->Teilnehmerkennzahl));

                if (!ereg ("^0", trim($this->Ortsnetzkennzahl)))
                $this->PHONE_WARNUNG .= "Ortsvorwahl vermutlich falsch! (Sie beginnt ohne \"0\") <br>";
            }
            else
            {
                $this->Teilnehmerkennzahl  = $this->PhoneInput;
                $this->PHONE_WARNUNG .= "Konnte keine Ortsvorwahl ermitteln. Die Nummer wird daher am Stück zurückgegeben !<br>";
            }
        }
    }


    function VorwahlArray()
    {
        // ______________________________######

        // Am Anfang stehen die Titel der Tabelle (etwa so wie in einer CSV-Datei)

        $Wertetabelle = 'Land; Vorwahl; Laendercode; NameEnglisch ; NameFranzoesisch; NameItalienisch
    Ägypten                        ; +20    ; eg    ; Egypt                   ; Égypte                   ; Egitto
    Äquartorialguinea              ; +240   ; gq    ; Equatorial Guinea       ; Guinée équatoriale       ; Guinea Equatoriale
    Äthiopien                      ; +251   ; et    ; Ethiopia                ; Éthiopie                 ; Etiopia
    Österreich                     ; +43    ; at    ; Austria                 ; Autriche                 ; Austria
    Afghanistan                    ; +93    ; af    ; Afghanistan             ; Afghanistan              ; Afganistan
    Albanien                       ; +335   ; al    ; Albania                 ; Albanie                  ; Albania
    Algerien                       ; +213   ; dz    ; Algeria                 ; Algérie                  ; Algeria
    Amerikanisch Samoa             ; +684   ; as    ; American Samoa          ; Samoa américaines        ; Samoa americane
    Amerikanische Jungferninseln   ; +1340  ; vi    ; US Virgin Islands       ; Iles Vierges américaines ; Isole Vergini americane
    Andorra                        ; +376   ; ad    ; Andorra                 ; Andorre                  ; Andorra
    Angola                         ; +244   ; ao    ; Angola                  ; Angola                   ; Angola
    Anguilla                       ; +1264  ; ai    ; Anguilla                ; Anguilla                 ; Anguilla
    Antarktis Casey                ; +67212 ; aq    
    Antarktis Davis                ; +67210 ; aq
    Antarktis Macquarrie Island    ; +67213 ; aq
    Antarktis Mawson               ; +67211 ; aq
    Antarktis                      ; +6721  ; aq    ; Antarctic               ; Antarctique             ; Antartide
    Antigua und Barbuda            ; +1268  ; ag    ; Antigua and Barbuda     ; Antigua-et-Barbuda      ; Antigua e Barbuda
    Argentinien                    ; +54    ; ar    ; Argentina               ; Argentine               ; Argentina
    Armenien                       ; +374   ; am    ; Armenia                 ; Arménie                 ; Armenia
    Aruba                          ; +297   ; aw    ; Aruba                   ; Aruba                   ; Aruba
    Ascension                      ; +247
    Atlantischer Ozean (Ost)       ; +871
    Atlantischer Ozean (West)      ; +874
    Aserbaidshan                   ; +994   ; az    ; Azerbaijan              ; Azerbaïdjan             ; Azerbaigian
    Australien                     ; +61    ; au    ; Australia               ; Australien              ; Australia
    Australien Norfolk Inseln      ; +6723  ; nf
    Bahamas                        ; +1242  ; bs    ; The Bahamas             ; Bahamas                ; Bahama
    Bahrain                        ; +973   ; bh    ; Bahrain                 ; Bahreïn                ; Bahrein
    Bangladesh                     ; +880   ; bd    ; Bangladesh              ; Bangladesh             ; Bangladesh
    Barbados                       ; +1246  ; bb    ; Barbados                ; Barbade                ; Barbados
    Belarus (Weissrussland)        ; +375   ; by    ; Belarus                 ; Biélorussie            ; Bielorussia
    Belgien                        ; +32    ; be    ; Belgium                 ; Belgique               ; Belgio
    Belize                         ; +501   ; bz    ; Belize                  ; Belize                 ; Belize
    Benin                          ; +229   ; bj    ; Benin                   ; Bénin                  ; Benin
    Bermuda                        ; +1441  ; bm    ; Bermuda                 ; Bermudes               ; Bermuda
    Bhutan                         ; +975   ; bt    ; Bhutan                  ; Bhoutan                ; Bhutan
    Bolivien                       ; +591   ; bo    ; Bolivia                 ; Bolivie                ; Bolivia
    Bosnien Herzegowina            ; +387   ; ba    ; Bosnia and Herzegovina  ; Bosnie-Herzégovine     ; Bosnia-Erzegovina
    Botsuana                       ; +267   ; bw    ; Botswana                ; Botswana               ; Botswana
    Brasilien                      ; +55    ; br    ; Brazil                  ; Brésil                 ; Brasile
    Britische Jungferninseln       ; +1284  ; vg    ; British Virgin Islands  ; Iles Vierges britanniques ; Isole Vergini britanniche
    Brunei Darussalam              ; +673   ; bn    ; Brunei                  ; Brunei                 ; Brunei
    Bulgarien                      ; +359   ; bg    ; Bulgaria                ; Bulgarie               ; Bulgaria
    Burkina Faso                   ; +226   ; bf    ; Burkina Faso            ; Burkina Faso           ; Burkina Faso
    Burma (Birma)                  ; +95    ; mm
    Burundi                        ; +257   ; bi    ; Burundi                 ; Burundi                ; Burundi
    Chile                          ; +56    ; cl    ; Chile                   ; Chili                  ; Cile
    China                          ; +86    ; cn    ; China                   ; Chine                  ; Cina
    Cookinseln                     ; +683   ; ck    ; Cook Islands            ; Iles Cook              ; Isole Cook
    Costa Rica                     ; +506   ; cr    ; Costa Rica              ; Costa Rica             ; Costa Rica
    Dänemark                       ; +45    ; dk    ; Denmark                 ; Danemark               ; Danimarca
    Deutschland                    ; +49    ; de    ; Germany                 ; Allemagne              ; Germania
    Dominica                       ; +1767  ; dm    ; Dominica                ; Dominique              ; Dominica
    Dominikanische Republik        ; +1809  ; do    ; Dominican Republic      ; République dominicaine ; Repubblica dominicana
    Dschibuti                      ; +253   ; dj    ; Djibouti                ; Djibouti               ; Gibuti
    Ecuador                        ; +593   ; ec    ; Ecuador                 ; Équateur               ; Ecuador
    El Salvador                    ; +503   ; sv    ; El Salvador             ; Salvador               ; El Salvador
    Elfenbeinküste                 ; +225   ; ci    ; Côte d\'Ivoire          ; Côte d\'Ivoire         ; Costa d\'Avorio
    Eritrea                        ; +291   ; er    ; Eritrea                 ; Érythrée               ; Eritrea
    Estland                        ; +371   ; ee    ; Estonia                 ; Estonie                ; Estonia
    Falkland Inseln                ; +500   ; fk    ; Falkland Islands        ; Iles Falkland          ; Isole Falkland
    Färöer                         ; +298   ; fo    ; Faeroe Islands          ; Iles Féroé             ; Føroyar
    Fidschi                        ; +679   ; fj    ; Fiji                    ; Iles Fidji             ; Figi
    Finnland                       ; +358   ; fi    ; Finland                 ; Finlande               ; Finlandia
    Frankreich                     ; +33    ; fr    ; France                  ; France                 ; Francia
    Französisch Guyana             ; +594   ; gf    ; French Guiana           ; Guyane française       ; Guiana francese
    Französisch Polynesien         ; +689   ; pf    ; French Polynesia        ; Polynésie française    ; Polinesia francese
    Französische Südgebiete        ;        ; tf    ; French Southern Territories; Terres australes françaises   ; Territori australi francesi
    Gabun                          ; +241   ; ga    ; Gabon                   ; Gabon                  ; Gabon
    Georgien                       ; +995   ; ge    ; Georgia                 ; Géorgie                ; Georgia
    Ghana                          ; +233   ; gh    ; Ghana                   ; Ghana                  ; Ghana
    Gibraltar                      ; +350   ; gi    ; Gibraltar               ; Gibraltar              ; Gibilterra
    Gambia                         ;        ; gm    ; The Gambia              ; Gambie                 ; Grenada
    Grenada                        ; +1473  ; gd    ; Grenada                 ; Grenade                ; Gambia
    Griechenland                   ; +30    ; gr    ; Greece                  ; Grèce                  ; Grecia
    Grönland                       ; +299   ; gl    ; Greenland               ; Groenland              ; Groenlandia
    Grossbritannien                ; +44    ; uk    ; Great Britain           ; La Grande-Bretagne     ; La Gran Bretagna
    Guadeloupe                     ; +590   ; gp    ; Guadeloupe              ; Guadeloupe             ; Guadalupa
    Guam                           ; +671   ; gu    ; Guam                    ; Guam                   ; Guam
    Guatemala                      ; +502   ; gt    ; Guatemala               ; Guatemala              ; Guatemala
    Guernsey, EnglischeKanalinseln ; +44    ; gg
    Guinea                         ; +224   ; gn    ; Guinea                  ; Guinée                 ; Guinea
    Guyana                         ; +592   ; gy
    Haiti                          ; +509   ; ht
    Heard-Insel und McDonald Inseln; +61    ; hm
    Honduras                       ; +504   ; hn
    Hongkong                       ; +852   ; hk
    Indien                         ; +91    ; in
    Indonesien                     ; +62    ; id
    Irak                           ; +964   ; iq    ; Iraq                    ; Iraq                  ; Iraq
    Iran                           ; +98    ; ir    ; Iran                    ; Iran                  ; Iran
    Irland                         ; +353   ; ie    ; Ireland                 ; Irlande               ; Irlanda
    Island                         ; +354   ; is    ; Iceland                 ; Islande               ; Islanda
    Israel                         ; +972   ; il
    Italien                        ; +39    ; it    ; Italy                   ; Italie                ; Italia
    Jamaika                        ; +1876  ; jm
    Japan                          ; +81    ; jp
    Jemen (arabische Republik)     ; +967   ; ye
    Jersey, EnglischeKanalinseln   ; +44    ; je
    Jordanien                      ; +962   ; jo
    Jugoslawien (Serbien)          ; +381   ; yu
    Kaimaninseln                   ; +1345  ; ky    ; Cayman Islands         ; Iles Cayman            ; Isole Cayman
    Kambodscha                     ; +855   ; kh
    Kamerun                        ; +237   ; cm
    Kanada                         ; +1     ; ca
    Kap Verde                      ; +238   ; cv
    Kasachstan                     ; +7     ; kz
    Katar                          ; +974   ; qa
    Kenia                          ; +254   ; ke    ; Kenya                  ; Kenya                 ; Kenya
    Kirgisistan                    ; +996   ; kg
    Kiribati                       ; +686   ; ki
    Kokos Inseln (Australien)      ; +6722  ; cc
    Kolumbien                      ; +57    ; co    ; Colombia              ; Colombie               ; Colombia
    Komoren                        ; +2697  ; km
    Kongo                          ; +242   ; cg
    Korea (DemokratischeRepublik)  ; +850   ; kp
    Korea (Republik)               ; +82    ; kr
    Kroatien                       ; +385   ; hr    ; Croatia               ; Croatie               ; Croazia
    Kuba                           ; +53    ; cu    ; Cuba                  ; Cuba                  ; Cuba
    Kuwait                         ; +965   ; kw
    Laos                           ; +856   ; la
    Lesotho                        ; +266   ; ls
    Lettland                       ; +371   ; lv
    Libanon                        ; +961   ; lb    ; Lebanon               ; Liban                  ; Libano
    Liberia                        ; +231   ; lr
    Libyen                         ; +218   ; ly
    Liechtenstein                  ; +423   ; li    ; Liechten              ; Liechten               ; Liechten
    Litauen                        ; +370   ; lt
    Luxemburg                      ; +352   ; lu    ; Luxembourg            ; Luxembourg             ; Lussemburgo
    Macao                          ; +853   ; mo
    Makedonien                     ; +389   ; mk
    Malavi                         ; +265   ; mw
    Malaysia                       ; +60    ; my
    Mali                           ; +223   ; ml
    Malta                          ; +356   ; mt
    Marokko                        ; +212   ; ma    ; Morocco               ; Maroc                  ; Marocco
    Marschallinseln                ; +692   ; mh
    Martinique                     ; +596   ; mq
    Mauretanien                    ; +222   ; mr
    Mauritius                      ; +230   ; mu
    Mayotte                        ; +269   ; yt
    Mexiko                         ; +52    ; mx    ; Mexico               ; Mexique                 ; Messico
    Mikronesien                    ; +691   ; fm
    Moldau                         ; +373   ; md
    Monaco                         ; +377   ; mc    ; Monaco               ; Monaco                 ; Monaco
    Mongolei                       ; +976   ; mn
    Montserrat                     ; +1664  ; ms
    Mosambik                       ; +258   ; mz
    Namibia                        ; +264   ; na
    Nauru                          ; +674   ; nr
    Nepal                          ; +977   ; np
    Neukaledonien                  ; +687   ; nc
    Neuseeland                     ; +64    ; nz    ; New Zealand         ; Nouvelle-Zélande         ; Nuova Zelanda
    Nicaragua                      ; +505   ; ni
    Niederlande                    ; +31    ; nl    ; Netherlands         ; Pays-Bas                 ; Paesi Bassi
    Niederländische Antillen       ; +599   ; an
    Niger                          ; +227   ; ne
    Nigeria                        ; +234   ; ng
    Niue (Neuseeland)              ; +683   ; nu
    Noerdliche Marianen            ; +1670  ; mp
    Norwegen                       ; +47    ; no    ; Norway              ; Norvège                  ; Norvegia
    Oman                           ; +968   ; om
    Ost-Timor                      ; +61    ; tp
    Pakistan                       ; +92    ; pk
    Palau                          ; +680   ; pw
    Panama                         ; +507   ; pa
    Papua Neuguinea                ; +675   ; pq
    Paraguay                       ; +595   ; py
    Peru                           ; +51    ; pe
    Philippinen                    ; +63    ; ph
    Pitcairn-Inseln                ;        ; pn
    Polen                          ; +48    ; pl    ; Poland            ; Pologne                  ; Polonia
    Portugal                       ; +351   ; pt    ; Portugal          ; Portugal                 ; Portogallo
    Puerto Rico                    ; +1787  ; pr
    Reunion (Frankreich)           ; +262   ; re
    Ruanda                         ; +250   ; rw
    Rumänien                       ; +40    ; ro    ; Romania           ; Roumanie                 ; Romania
    russische Föderation           ; +7     ; ru
    Sahara (Westsaharah)           ;        ; eh
    Saint Helena                   ; +290   ; sh
    Saint Kitts und Nevis          ; +1869  ; kn
    Saint Lucia                    ; +1758  ; lc
    Saint Pierre and Miguelon      ; +508   ; pm
    Saint Vincent u.Grenadinen     ; +1784  ; vc
    Salomonen                      ; +677   ; sb
    Sambia                         ; +260   ; zm
    Samoa (Westsamoa)              ; +685   ; ws
    San Marino                     ; +378   ; sm
    Sao Tome and Principe          ; +239   ; st
    Saudi-Arabien                  ; +966   ; sa
    Schweden                       ; +46    ; se
    Schweiz                        ; +41    ; ch    ; Switzerland       ; Suisse                  ; Svizzera
    Senegal                        ; +221   ; sn
    Seychellen                     ; +248   ; sc
    Sierra Leone                   ; +232   ; sl
    Singapur                       ; +65    ; sg
    Slowakei                       ; +421   ; sk    ; Slovakia           ; Slovaquie              ; Slovacchia
    Slowenien                      ; +386   ; si    ; Slovenia           ; Slovénie               ; Slovenia
    Somalia                        ; +252   ; so
    Spanien                        ; +34    ; es    ; Spain              ; Espagne                ; Spagna
    Somalia                        ; +252   ; so
    Sri Lanka                      ; +94    ; lk
    Südafrika                      ; +27    ; za
    Sudan                          ; +249   ; sd
    Sued-Georgien                  ;        ; gs
    Suedl.Sandwichinseln           ;        ; gs
    Surinam                        ; +597   ; sr
    Svalbard und Jan Mayen         ;        ; sj
    Swasiland                      ; +268   ; sz
    Syrien                         ; +963   ; sy
    Tadschikistan                  ; +7     ; tj
    Taiwan                         ; +886   ; tw    ; Taiwan            ; Taïwan                  ; Taiwan
    Tansania                       ; +255   ; tz
    Thailand                       ; +66    ; th    ; Thailand          ; Thaïlande               ; Thailandia
    Tonga                          ; +676   ; to
    Trinidad und Tobago            ; +1868  ; tt
    Tschad                         ; +235   ; td
    Tschechien                     ; +420   ; cz    ; Czech Republic     ; République tchèque      ; Repubblica ceca
    Tuirmenistan                   ; +993   ; tm
    Tunesien                       ; +216   ; tn    ; Tunisia            ; Tunisie                 ; Tunisia
    Türkei                         ; +90    ; tr    ; Turkey             ; Turquie                 ; Turchia
    Turks und Caicos Inseln        ; +1649  ; tc
    Tuvalu                         ; +688   ; tv
    Uganda                         ; +256   ; ug
    Ukraine                        ; +380   ; ua    ; Ukraine            ; Ukraine                 ; Ucraina
    Ungarn                         ; +36    ; hu    ; Hungary            ; Hongrie                 ; Ungheria
    Uruguay                        ; +598   ; uy
    USA entfernte Inseln           ;        ; um
    Usbekistan                     ; +998   ; uz
    Vanuatu                        ; +678   ; vu
    Vatikanstadt                   ; +396   ; va    ; Vatican City       ; Saint-Siège             ; Vaticano
    Venezuela                      ; +58    ; ve
    Vereinigte Arabische Emirate   ; +971   ; ae
    Vereinigte Staaten (USA)       ; +1     ; us    ; United States      ; États-Unis              ; Stati Uniti
    Vietnam                        ; +84    ; vn
    Wallis und Futuna              ; +681   ; wf
    Weinachtsinseln(Australien)    ; +6724  ; cx
    Zaire                          ; +243   ; zr
    Zentralafrikanische Republik   ; +236   ; cf
    Zimbabwe                       ; +263   ; zw
    Zypern (Nordzypern)            ; +90    ; cy
    Zypern                         ; +357   ; cy    ; Cyprus            ; Chypre                    ; Cipro
    ';

        // Erstelle die mehrdimensionalen, und assoziativen Arrays.

        $Zeile = explode(chr(13),$Wertetabelle);      // Zerlege die Einträge beim Zeilenumbruch
        $TitelZeile = explode(";",$Zeile[0]);         // In der ersten Zeile stehen die Titel.

        while(list($key, $val) = each($Zeile))     // Durchlaufe alle Zeilen.
        {
            $Zeilenwerte = explode(";",$val);
            $n = 0;
            while(list($feld, $Wert) = each($Zeilenwerte))
            {
                $this->LandTabelle[$key][trim($TitelZeile[$n])] = trim($Zeilenwerte[$n]);
                $n+= 1 ;
            }
        }
         
        // Nun habe ich eine mehrdimensionale Array, die im ersten Feld die ID hat
        // Habe ich die, kann ich den Rest Assoziativ auslesen.
        // Besonders spassig; ich kann jederzeit einen neuen Titel einfügen und neue Felder dazugeben :-)
        // es macht auch nichts, wenn mal eine Angabe fehlen sollte.

        // ______________________________######

    }


    function SpaceMe($SpaceString, $AnzZeichen = 2)
    {

        $l=strlen ($SpaceString);                               // Bestimme die Länge der Zeichenkette

        if ($l % 2 != 0)                                         // Ist die Länge eine ungerade Zahl ...
        { $SpaceString = " ".$SpaceString; }                  //... dann füge am Anfang ein Leerzeichen hinzu

        $SpaceString = wordwrap($SpaceString,$AnzZeichen," ",1); // Füge ein Leerzeichen nach einer bestimmten Anzahl Zeichen ein
        return  trim($SpaceString);                              // Entferne nun die überflüssigen Leerzeichen und gib den Wert zurück
    }


    function suche_ID_multi_array($Feldname, $SucheNach)
    {
        for($x= count($this->LandTabelle);$x>0;$x--)
        {
            if (strtoupper($this->LandTabelle[$x][$Feldname]) == strtoupper($SucheNach) )
            {
                return $x;
                break;
            }
        }
    }
}
//================================================================================================
