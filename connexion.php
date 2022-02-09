<?php
require_once('inc/init.inc.php');

// echo '<pre>'; print_r($_GET); echo '</pre>';

if(connect())
{
    header('location: profil.php');
}


//Si l'indice 'action' est définit dans l'url et qu'il a pour valeur 'deconnexion', cela veut dire que l'internaute à cliqué sur le lien 'déconnexion' et donc transmite dans l'url les parametres 'action=deconnexion', alors on entre dans la condition IF et on supprime l'indice 'user' dans la session afin qu'il en soit plus authentifié sur le site
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    //echo "Je veut me déconnecter <hr>"; 
    unset($_SESSION['user']);
}
 
if(isset($_POST['pseudo_email'], $_POST['password'], $_POST['submit']))
{
    $verifUser = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
    $verifUser->bindValue(':pseudo', $_POST['pseudo_email'], PDO::PARAM_STR);
    $verifUser->bindValue(':email', $_POST['pseudo_email'], PDO::PARAM_STR);
    $verifUser->execute();

    //echo "nb resultat : " . $verifUser->rowCount() . '<hr>';
    if($verifUser->rowCount() > 0)
    {
        //echo "pseudo ou email OK ! <hr>";
        $user = $verifUser->fetch(PDO::FETCH_ASSOC); 

          //password_verify() : fonction prédéfinie permettant de comparer une clé de hachage à une chaine de caractere 
          // argument:
          //1. le mot de passe en clair, non haché 
          //2. la clé de hachage, le mot de passe crypté dans le BDD 
        if (password_verify($_POST['password'], $user['password']))
        {

            //Si l'internaute entre dans le IF ici, cela veut dire qu'il a correctement remplit le formulaire de connexion 
            // La boucle FOREACH permet de parcourir les données de l'utilisateur afin de les stocker dans son fichier de session
            // On crée un tableau multidimentionnel dans la session qui a pour valeur un tableau ARRAY contenant toute les données de l'internaute authentifié sur le site
            foreach($user as $key => $value)
            {
                if($key != 'password')
                {
                    $_SESSION['user'][$key] = $value;
                }
            }
            //echo '<pre>'; print_r($_SESSION); echo '</pre>';
            header('location: profil.php');
        }
        else
        {
            $error = "<p class='col-3 bg-danger text-white text-center mx-auto p-3 mt-3'>Identifiants invalide.'</p>";
        }
    }
    else 
    {
       $error = "<p class='col-3 bg-danger text-white text-center mx-auto p-3 mt-3'>Identifiants invalide.'</p>";
    }
}

require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');
?>
    <?php
    if(isset($_SESSION['valid_inscription'])) echo $_SESSION['valid_inscription'];
    if(isset($error)) echo $error;
    
    ?>
    <h1 class="text-center my-5">Identifiez-vous</h1>

    <form action="" method="post" class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4 mx-auto">
        <div class="mb-3">
            <label for="pseudo_email" class="form-label">Nom d'utilisateur / Email</label>
            <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" placeholder="Saisir votre Email ou votre nom d'utilisateur">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Saisir votre mot de passe">
        </div>
        <div>
            <p class="text-end mb-0"><a href="" class="alert-link text-dark">Pas encore de compte ? Cliquez ici</a></p>
            <p class="text-end m-0 p-0"><a href="" class="alert-link text-dark">Mot de passe oublié ?</a></p>
        </div>
        <input type="submit" name="submit" value="Continuer" class="btn btn-dark">
    </form>

<?php 
//On supprime dans la session l'indice 'valid_inscription' afin d'éviter que le message ne s'affiche tout le temps sur la page connexion
unset($_SESSION['valid_inscription']);
require_once('inc/inc_front/footer.inc.php');        