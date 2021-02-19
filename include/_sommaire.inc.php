<?php
/** 
 * Contient la division pour le sommaire, sujet à des variations suivant la 
 * connexion ou non d'un utilisateur, et dans l'avenir, suivant le type de cet utilisateur 
 * @todo  RAS
 */

?>
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    <?php      
      if (estVisiteurConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $prenom = $lgUser['prenom'];           
	  $comptable=$lgUser['comptable']; 
    ?>
          <h2>
    <?php  
            echo $nom . " " . $prenom ;
    ?>
          </h2>
   <?
  	  if(!$comptable)
	  {
   ?>
   	       <h3>Visiteur médical</h3>        
   	  
   	  <!--   </div> -->  
	
	          <ul id="menuList">
	           <li class="smenu">
	              <a href="cAccueil.php" title="Page d'accueil">Accueil</a>
		           </li>
        	   <li class="smenu">
        	      <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
        	   </li>
        	   <li class="smenu">
        	      <a href="cSaisieFicheFrais.php" title="Saisie fiche de frais du mois courant">Saisie fiche de frais</a>
        	   </li>
        	   <li class="smenu">
        	      <a href="cConsultFichesFrais.php" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
        	   </li>
        	 </ul>
        	<?php
        	  // affichage des éventuelles erreurs déjà détectées
        	    if ( nbErreurs($tabErreurs) > 0 )
		  {
        	      echo toStringErreurs($tabErreurs) ;
        	  }//fin if nb erreurs
             }//fin if est visiteur 
  ?>
   	<!-- </div> -->
  <?  if($comptable)
	{ 
		
		
?>
		      <h3>Comptable</h3>        
   
		     <!-- </div> -->  
		        <ul id="menuList">
		           <li class="smenu">
		              <a href="cAccueil.php" title="Page d'accueil">Accueil</a>
		           </li>
		           <li class="smenu">
		              <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
		           </li>
        		   <li class="smenu">
        		      <a href="formValidFrais.php" title="Validation des  fiches de frais ">Validation fiche de frais</a>
       			    </li>
       			    <li class="smenu">
              			<a href="cSuiviFichesFrais.php" title="Suivi des fiches de frais">Suivi fiches de frais</a>
          		 </li>
        		 </ul>
     		   <?php
     		     // affichage des éventuelles erreurs déjà détectées
       		   if ( nbErreurs($tabErreurs) > 0 )
		   {
       		       echo toStringErreurs($tabErreurs) ;
        	   }//fin if erreurs
 	
        ?>
<?
	}//fin else
}//fin if est connecté
?>
</div>
</div>
