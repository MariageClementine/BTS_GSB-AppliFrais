<?php
$repInclude = './include/';
require($repInclude . "_init.inc.php");

// page inaccessible si visiteur non connect�
if (!estVisiteurConnecte()) {
    header("Location: cSeConnecter.php");
}
require($repInclude . "_entete.inc.html");
require($repInclude . "_sommaire.inc.php");

if (isset($_GET["refus"]) && $_GET["refus"] == "refuserLigneHF") {
    $id = $_GET['idLigneHF'];
    refusLigneHF($id);
}

if (isset($_POST["enregistrer"])) {
    if (isset($_POST['situ'])) {
        switch ($_POST['situ']) {
            case 'E' :
                $etatFiche = 'CL';
                break;
            case 'V' :
                $etatFiche = 'VA';
                break;
            case 'R' :
                $etatFiche = 'RB';
                break;
        }
    } else {
        $etatFiche = null;
    }
    $nbJust = (isset($_POST["hcMontant"])) ? $_POST["hcMontant"] : null;
    $idVis = $_POST['idVis'];
    $mois = $_POST['mois'];
    modifierEtatFicheFrais($mois, $idVis, $etatFiche, $nbJust);
}
?>

<html>
    <head>
        <title>Validation des frais de visite</title>
    </head>
    <body>
        <div id="contenu">
            <h1>Validation des Frais par Visiteur</h1>

<?php
//si on a valid� le visiteur
if (isset($_POST["action"]) && $_POST["action"] == "Valider") {

    $id = $_POST["lstVisiteur"];
    $requete = "select * from Visiteur where id='$id'";
    $resultat = mysql_query($requete);
    while ($maLigne = mysql_fetch_array($resultat)) {
        $nom = $maLigne["nom"];
        $prenom = $maLigne["prenom"];
    }
    ?>
                <input type="text" disabled value="<?= $nom . " " . $prenom ?>"/>
                <form name="formValidMois" method="POST" action="">
                    <input type="hidden" name="idVis" value="<?= $id ?>"/>
                    <label class="titre">Mois :</label>  
                    <select name="lstMois" class="zone">
    <?php
    $req = "select mois from FicheFrais where idVisiteur='$id'";
    $resultat = mysql_query($req);
    while ($maLigne = mysql_fetch_array($resultat)) {
        $mois = $maLigne["mois"];
        echo("<option value=$mois>$mois</option>");
    }
    ?>
                    </select>
                    <input type="submit" name="action" value="Afficher"/>
                </form>
                        <?php
                    } //fin if bouton valider
                    //si on a valid� le mois
                    elseif (isset($_POST["action"]) && $_POST["action"] == "Afficher") {
                        $id = $_POST["idVis"];
                        $mois = $_POST["lstMois"];
                        $req = "select * from Visiteur where id='" . $id . "'";
                        $resultat = mysql_query($req);
                        $maLigne = mysql_fetch_array($resultat);
                        $nom = $maLigne["nom"];
                        $prenom = $maLigne["prenom"];
                        ?>
                <table id="memo">
                    <tr><td>Nom:<input type="text" disabled value="<?= $nom . ' ' . $prenom ?>"/></td>
                        <td>Date:<input type="text" disabled value="<?= $mois ?>"/></td></tr>
                </table>

                <form name="formValidFrais" method="POST" action="">
                    <h2>Frais au forfait </h2>
    <?php
    $recupEtat = "select libelle, nbJustificatifs from Etat inner join FicheFrais on id=idEtat where FicheFrais.mois='$mois' and FicheFrais.idVisiteur='$id'";
    $resultEtat = mysql_query($recupEtat);
    $ligneEtat = mysql_fetch_array($resultEtat);
    ?>
                    <div class="titre"><label style="font-weight: bold"> Etat de la fiche de frais au forfait : </label><label><?= $ligneEtat['libelle'] ?></label></div>
                    <?php
                    //recup chacune des lignes avec une requete par ligne 
                    $recupFraisForfait = "select * from LigneFraisForfait where mois='" . $mois . "' and idVisiteur='" . $id . "'";
                    $resultFraisForfait = mysql_query($recupFraisForfait);
                    ?>
                    <input type="hidden" name="idVis" value="<?= $id ?>"/>
                    <input type="hidden" name="mois" value="<?= $mois ?>"/>
                    <table style="color:black;"border="1">
                        <thead><th>Repas midi</th><th>Nuit&eacute;e </th><th>Etape</th><th>Km </th><th>Situation</th></thead>
                        <tbody>
                            <tr>
    <?php
    while ($ligneFraisF = mysql_fetch_array($resultFraisForfait)) {
        switch ($ligneFraisF['idFraisForfait']) {
            case 'ETP' :
                ?><td width="80"> <input type="text" size="3" name="etape" disabled value="<?= $ligneFraisF['quantite'] ?>"/></td><?php
                                            break;
                                        case 'KM':
                                            ?><td width="80"> <input type="text" size="3" name="km" disabled value="<?= $ligneFraisF['quantite'] ?>"/></td><?php
                                            break;
                                        case 'NUI' :
                                            ?><td width="80"><input type="text" size="3" name="nuitee" disabled value="<?= $ligneFraisF['quantite'] ?>"/></td><?php
                                            break;
                                        case 'REP' :
                                            ?><td width="80" ><input type="text" size="3" name="repas" disabled value="<?= $ligneFraisF['quantite'] ?>"/></td><?php
                                                break;
                                        }
                                    }
                                    ?>


                                <td width="80">
                                    <select size="3" name="situ">
                                        <option value="E">Enregistr&eacute;</option>
                                        <option value="V">Valid&eacute;</option>
                                        <option value="R">Rembours&eacute;</option>
                                    </select></td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="titre" /><h2>Hors Forfait</h2>
    <?php
    //recup chacune des lignes avec une requete par ligne 
    $recupFraisHorsForfait = "select * from LigneFraisHorsForfait where mois='" . $mois . "' and idVisiteur='" . $id . "'";
    $resultFraisHorsForfait = mysql_query($recupFraisHorsForfait);
    ?>
                    <table style="color:black;" border="1">
                        <tr><th>Date</th><th>Libell&eacute; </th><th>Montant</th><th>Valide?</th></tr>
                    <?php
                    while ($ligneFraisHF = mysql_fetch_array($resultFraisHorsForfait)) {
                        ?>
                            <tr><td><input type="text" size="12" disabled name="hfDate1" value="<?= $ligneFraisHF["date"] ?>"/></td>
                                <td><input type="text" size="30" disabled name="hfLib1" value="<?= $ligneFraisHF["libelle"] ?>"/></td>
                                <td> <input type="text" size="10" disabled name="hfMont1" value="<?= $ligneFraisHF["montant"] ?>"/></td>
                            <?php
                            if ($ligneFraisHF['etat'] == 1) {
                                ?>
                                    <td><label>Refusé</label></td>
                                    <?php
                                } else {
                                    ?>
                                    <td> <a href="?refus=refuserLigneHF&idLigneHF=<?= $ligneFraisHF['id'] ?>"
                                            onclick="return confirm('Voulez-vous vraiment refuser cette ligne de frais hors forfait ?');"
                                            title="Refuser la ligne de frais hors forfait">Non Valide</a></td>
                                    <?php
                                }
                                ?> 
                            </tr>
                                    <?php
                                }
                                ?>
                    </table>
                    <p class="titre"></p>
                    <div class="titre">Nb Justificatifs</div><input type="text" class="zone" size="4" name="hcMontant" value="<?= $ligneEtat["nbJustificatifs"] ?>"/>
                    <p class="titre" /><label class="titre">&nbsp;</label><input class="zone" type="submit" name="enregistrer" />
                </form>

    <?php
}//fin elseif
else {
    ?>	

                <label class="titre">Choisir le visiteur :</label>
                <form name="formValidVis" action="formValidFrais.php" method="POST">
                    <select name="lstVisiteur" class="zone">
                <?php
                $req = "select id,nom,prenom from Visiteur where comptable=0";
                $resultat = mysql_query($req);
                while ($maLigne = mysql_fetch_array($resultat)) {
                    $id = $maLigne["id"];
                    $nom = $maLigne["nom"];
                    $prenom = $maLigne["prenom"];

                    echo(" <option value=$id>$nom  $prenom</option>");
                }
                ?>
                    </select>
                    <input type="submit" name="action" value="Valider"/>
                </form>
                        <?php
                    }//fin else
                    ?>		</div>
    </div>
</div>
</body>
</html>

<?php
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
?>

