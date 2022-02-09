<?php
require_once('inc/init.inc.php');
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
if(!connect())
{
    header('location: connexion.php');
}


require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');
?>

<h1 class="text-center my-5">Vos informations personnelles</h1>
<!-- exo : afficher l'ensemble des données de l'utilisateur sur la page web en passant par le fichier session de l'utilisateur ($_SESSION). Ne pas afficher l'id_membre -->


<!-- <div class="card col-5 mx-auto mb-5">

    <div class="card-body">
    
        <h3>Nom d'utilisateur : <?php echo $_SESSION['user']['pseudo'] ?></h3>
        <h3>Civilité : <?php echo $_SESSION['user']['civilite'] ?></h3>
        <h3>Prenom : <?php echo $_SESSION['user']['prenom'] ?></h3>
        <h3>Nom : <?php echo $_SESSION['user']['nom'] ?></h3>
        <h3>Adresse email : <?php echo $_SESSION['user']['email'] ?></h3>
        <h3>Adresse : <?php echo $_SESSION['user']['adresse'] ?></h3>
        <h3>Code postal : <?php echo $_SESSION['user']['code_postal'] ?></h3>
        <h3>Ville : <?php echo $_SESSION['user']['ville'] ?></h3>
    </div>
</div> -->


<div class="card col-5 mx-auto mb-5">

    <div class="card-body">
        <?php
        foreach($_SESSION['user'] as $key => $value):
            if($key !='id_membre' && $key !='statut'):
        ?>
        <p class="d-flex justify-content-between">
            <strong><?php echo ucfirst($key); ?> :</strong>
            <span><?= $value; ?></span>
        </p>
        <?php
            endif;
        endforeach;
        ?>

    </div>
</div>



<?php
require_once('inc/inc_front/footer.inc.php');