<?php
require_once('../inc/init.inc.php');

// echo '<pre style="margin-left: 250px">'; print_r($_POST); echo '</pre>';
// echo '<pre style="margin-left: 250px">'; print_r($_FILES); echo '</pre>';
// Si l'internaute, n'est pas un 'admin' dans la session donc dans la BDD, il n'a rien a faire sur la page, on le redirige vers la page connexion
if(!adminConnect())
{
    header('location: ' . URL . 'connexion.php');
}
// QUPPRESSION PRODUITS
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    // echo "<p style= 'margin-left: 250px;'>Je veut supprimer ce produit</p>";
    // Exo : réaliser le traitement PHP + SQL permettant de supprimer le produit dans la BDD en fonction de l'id_produit transmit dans l'URL (prepare + bindvalue + execute)
    $deleteProduit = $bdd->prepare("DELETE FROM produit WHERE id_produit = :produit");
    $deleteProduit->bindValue(':produit', $_GET['id_produit'], PDO::PARAM_INT);
    $deleteProduit->execute();
    //On redéfinit l'indice 'action' dans l'URL afin d'entrer dans la condition IF permettant l'affichage des articles  
    $_GET['action'] = 'affichage';
    $msg = "<p class='col-7 bg-success text-white text-center mx-auto p-3'>L'article n° <strong>$_GET[id_produit]</strong> a été supprimé avec succès .</p>";

}

if(isset($_POST['reference'],$_POST['categorie'],$_POST['titre'],$_POST['description'],$_POST['couleur'],$_POST['taille'],$_POST['public'],$_POST['prix'],$_POST['stock']))
{ 
    
    $photoBdd = '';
    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        $photoBdd = $_POST['photo_actuelle'];
    }

    // TRAITEMENT / ENREGISTREMENT DE LA PHOTO PRODUIT
    if(!empty($_FILES['photo']['name']))
    {
        $nomPhoto = $_POST['reference'] . '-' . $_FILES['photo']['name'];
        // echo "<p style='margin-left: 250px'>$nomPhoto</p><hr>";

        //URL DE L'IMAGE
        $photoBdd = URL . "assets/uploads/$nomPhoto";
        // echo "<p style='margin-left: 250px'>$photoBdd</p><hr>";
        
        $photoDossier = RACINE_SITE . "assets/uploads/$nomPhoto";
        // echo "<p style='margin-left: 250px'>$photoDossier</p><hr>";

        // COPIE DE L'IMAGE DANS LE DOSSIER UPLOADS
        // copy() :  fonction prédéfinie permettant de copier un fichier uploadé dans un dossier sur le serveur 
        // Arguments :
        // 1. Le fichier temporaire de l'image disponible dans le dossier $_FILES
        // 2. Le chemin physique de l'image où elle doit être enregistrée sur le serveur
        copy($_FILES['photo']['tmp_name'], $photoDossier);

    }
    
    if(isset($_GET['action']) && $_GET['action'] == 'ajout')
    {
        
    // ENREGISTREMENT PRODUITS
    $inserProduit = $bdd->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");

    $_GET['action'] = 'affichage';

    $msg = "<p class='col-7 bg-success text-white text-center mx-auto p-3'>L'article référence <strong>$_POST[reference]</strong> à été enregistré avec succès.'</p>";
}
elseif(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    //MODIFICATION PRODUIT
    $inserProduit = $bdd->prepare("UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = :photo, prix = :prix, stock = :stock WHERE id_produit = :id_produit");

    $inserProduit->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);

    $msg = "<p class='col-7 bg-success text-white text-center mx-auto p-3'>L'article référence <strong>$_POST[reference]</strong> à été modifié avec succès.'</p>";

    $_GET['action'] = 'affichage';
}

    $inserProduit->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
    $inserProduit->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
    $inserProduit->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
    $inserProduit->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
    $inserProduit->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
    $inserProduit->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
    $inserProduit->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
    $inserProduit->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
    $inserProduit->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
    $inserProduit->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);

    $inserProduit->execute();

    
}

// MODIFICATION ARTICLE
if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    //  echo "<p style= 'margin-left: 250px;'>Je veut modifier ce produit</p>";
    $update = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $update->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $update->execute();

    // On récupere les donné dans un ARRAY
    $produitActuel = $update->fetch(PDO::FETCH_ASSOC);
    // echo '<pre style="margin-left: 250px">'; print_r($produitActuel); echo '</pre>';

    // On stock chaque valeur de l'article dans des variables distinctes afin de les injectées dans les attributs 'value' du formulaire HTML
    $reference = (isset($produitActuel['reference'])) ? $produitActuel['reference'] : '';
    // echo "<p style='margin-left: 250px'> $reference </p>";
    $categorie = (isset($produitActuel['categorie'])) ? $produitActuel['categorie'] : '';
    $titre = (isset($produitActuel['titre'])) ? $produitActuel['titre'] : '';
    $description = (isset($produitActuel['description'])) ? $produitActuel['description'] : '';
    $couleur = (isset($produitActuel['couleur'])) ? $produitActuel['couleur'] : '';
    $taille = (isset($produitActuel['taille'])) ? $produitActuel['taille'] : '';
    $public = (isset($produitActuel['public'])) ? $produitActuel['public'] : '';
    $photo = (isset($produitActuel['photo'])) ? $produitActuel['photo'] : '';
    $prix = (isset($produitActuel['prix'])) ? $produitActuel['prix'] : '';
    $stock = (isset($produitActuel['stock'])) ? $produitActuel['stock'] : '';
}

require_once('../inc/inc_back/header.inc.php');
require_once('../inc/inc_back/nav.inc.php');
?>
<div class="mt-3 text-center">
    <a href="?action=ajout" class="btn btn-secondary">Nouvel article</a>
    <a href="?action=affichage" class="btn btn-secondary">Affichage des articles</a>
</div>
<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')): ?>

<h1 class="text-center my-5"><?= ucfirst($_GET['action'])?> article</h1>

<?php if(isset($validInsert)) echo $validInsert; ?>

<!-- enctype="multipart/form-data" : permet de récuperer les données d'un fichier upload (nom, extendion, taille ect ...) accesssible en PHP via la superglobale $_FILES -->
<form method="post" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <label for="reference" class="form-label">Référence</label>
        <input type="text" class="form-control" id="reference" name="reference" value="<?php if(isset($reference)) echo $reference; ?>">
    </div>
    <div class="col-md-6">
        <label for="categorie" class="form-label">Catégorie</label>
        <input type="text" class="form-control" id="categorie" name="categorie" value="<?php if(isset($categorie)) echo $categorie; ?>">
    </div>
    <div class="col-12">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php if(isset($titre)) echo $titre; ?>">
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea type="text" class="form-control" id="description" name="description" rows="10"> <?php if(isset($description)) echo $description; ?>
    </textarea>
    </div>
    <div class="col-4">
        <label for="couleur" class="form-label">Couleur</label>
        <input type="color" class="form-control input-couleur" id="couleur" name="couleur" value="<?php if(isset($couleur)) echo $couleur; ?>">
    </div>
    <div class="col-4">
        <label for="taille" class="form-label">Taille</label>
        <select id="taille" class="form-select" name="taille">
            <option value="S" <?php if(isset($taille) && $taille == 'S') echo 'selected'; ?>>S</option>
            <option value="M" <?php if(isset($taille) && $taille == 'M') echo 'selected'; ?>>M</option>
            <option value="L" <?php if(isset($taille) && $taille == 'L') echo 'selected'; ?>>L</option>
            <option value="XL" <?php if(isset($taille) && $taille == 'XL') echo 'selected'; ?>>XL</option>
        </select>
    </div>
    <div class="col-4">
        <label for="public" class="form-label">Public</label>
        <select id="public" class="form-select" name="public">
            <option value="homme" <?php if(isset($public) && $public == 'homme') echo 'selected'; ?>>Homme</option>
            <option value="femme" <?php if(isset($public) && $public == 'femme') echo 'selected'; ?>>Femme</option>
            <option value="mixte" <?php if(isset($public) && $public == 'mixte') echo 'selected'; ?>>Mixte</option>
        </select>
    </div>
    <div class="col-4">
        <label for="photo" class="form-label">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo">
        
        <input type="text" id="photo_actuelle" name="photo_actuelle" value="<?php if(isset($photo)) echo $photo; ?>">
    </div>
    <div class="col-4">
        <label for="prix" class="form-label">Prix</label>
        <input type="text" class="form-control" id="prix" name="prix" value="<?php if(isset($prix)) echo $prix; ?>">
    </div>
    <div class="col-4">
        <label for="stock" class="form-label">Stock</label>
        <input type="text" class="form-control" id="stock" name="stock" value="<?php if(isset($stock)) echo $stock; ?>">
    </div>

    <?php if(isset($photo) && !empty($photo)): ?>
        <div class=" col-7 mx-auto d-flex flex-column align-items-center rounded shadow-sm border">
            <small class="fst-italic">Photo actuelle de l'article. Vous pouvez uploader une nouvelle photo si vous souhaitez la modifier.</small>
            <img src="<?= $photo?>" alt="" class="img-product-update">
        </div>
    <?php endif ?>

    <div class="col-12">
        <button type="submit" class="btn btn-dark"><?= ucfirst($_GET['action']) ?> article</button>
    </div>
</form>
<br>
<?php endif; 
if(isset($_GET['action']) && $_GET['action'] == 'affichage'):
?>
<!-- 
Exo : afficher sous forme de tableau de HTML l'ensemble des produits stockés en BDD
1. requete de selection (query)
2. Afficher le nombre de produit selectionnés en BDD (rowCount())
3. Récupérer les informations sous forme de tableau (fetchAll)
4. Déclarer le tableau HTML (<table>)
5. Afficher les entêtes du tableau (<th>) en passant par le résultat du fetchAll()
6. Afficher tout les produits de la BDD à l'aide de boucle (foreach) dans des lignes (<tr>) et cellules (<td>) du tableau 
7. Prévoir un lien de modification / suppression pour chaque produit dans le tableau HTML
-->



<!-- Liens produits -->
<h1 class="text-center my-5">Affichage articles</h1>

<?php
if(isset($msg)) echo $msg;
$data = $bdd->query("SELECT * FROM produit");


echo '<p><span class="badge bg-success">' . $data->rowCount() . '</span> article(s) enregistrés.</p>';

$empMulti = $data->fetchAll(PDO::FETCH_ASSOC);

echo '<table class="table table-bordered text-center"><tr>'; 

foreach($empMulti[0] as $key => $value)
{
    
    echo "<th>" . ucfirst($key) . "</th>";
}
echo '<th>Actions</th>';

echo '</tr>';
foreach($empMulti as $key => $tab)
{
    echo '<tr>'; 
    foreach($tab as $key2 => $value)
    {
        if($key2 == 'photo')
            echo"<td><img src='$value' alt='$tab[titre]' class='img-products'></td>";
        elseif($key2 == 'couleur')
        echo "<td style='background-color: $value;' class='text-secondary'>$value</td>";
        elseif($key2 == 'prix')
        echo "<td><strong>" . $value . "€</strong></td>";
        else
            echo "<td>$value</td>";
    }
    echo '<td class="text-center">';
        echo "<a href='?action=modification&id_produit=$tab[id_produit]'class='btn btn-primary mb-3'><i class='bi bi-pencil-square'></i></a>";
        echo '<br>';
        echo "<a href='?action=suppression&id_produit=$tab[id_produit]'class='btn btn-dark mb-3' onclick='return(confirm(\"En êtes vous certain ?\"))'><i class='bi bi-trash-fill'></i></a>";
    echo '</tr>';
}
echo '</table>';



endif;
require_once('../inc/inc_back/footer.inc.php');
?>