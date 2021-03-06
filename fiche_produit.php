<?php 
require_once('inc/init.inc.php');

// Si l'indice 'id_produit' est défini dans l'URL ($_GET) et que sa valeur est différente de vide, alors on entre dans le IF et on selectionne l'article en BDD
if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))
{
    $productPdoS = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $productPdoS->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $productPdoS->execute();

    if($productPdoS->rowCount() > 0)
    {
        $product = $productPdoS->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($product); echo '</pre>';
        
    }
    else 
    {
        header('location: boutique.php');
    }
}
else // Sinon l'indice 'id_produit' n'est pas défini dans l'URL ou sa valeur est vide, alors on redirige vers la boutique
{
    header('location: boutique.php');
}
require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');
?>

    <h1 class="text-center my-5">Détails de l'article</h1>

    <div class="row mb-5">
        <div class="bg-white shadow-sm rounded d-flex zone-card-fiche-produit">

            <a href="" data-lightbox="tee-shirt1" data-title="tee-shirt1" data-alt="tee-shirt1" class=""><img src="<?= $product['photo']?>" class="img-produit-fiche" alt="<?= $product['titre']?>"></a>

            <div class="col-12 col-sm-12 col-md-12 col-lg-9 card-body d-flex flex-column justify-content-center zone-card-body">
                <h5 class="card-title text-center fw-bold my-3"><?= $product['titre']?></h5>

                <p class="card-text"><?= $product['description']?></p>
                <p class="card-text fw-bold">Taille : <?= $product['taille']?></p>
                <div class="d-flex">

                    <p class="card-text fw-bold"><span class="me-3">Couleur :</span>
                        <div class="col-1 fp-couleur" style="background-color: <?= $product['couleur']?>"></div> 
                    </p>
                </div>
                <p class="card-text fw-bold"><?= $product['prix']?>€</p>

                <?php if($product['stock'] < 10 && $product['stock'] != 0): ?>

                    <p class="card-text fst-italic text-danger">Attention il ne reste que <?= $product['stock'] ?> exemplaire(s) en stock !</p>

                <?php elseif($product['stock'] >= 10): ?>
                    <p class="card-text fst-italic text-success">En stock</p>

                <?php endif; ?>

                <p class="card-text">

                    <?php if($product['stock'] > 0): ?>

                    <form action="panier.html" class="row g-3">
                        <div class="col-12 col-sm-7 col-md-4 col-lg-3 col-xl-3">
                            <label class="visually-hidden" for="autoSizingSelect">Quantité</label>
                            <select class="form-select" id="autoSizingSelect">
                                <option selected>Choisir une quantité...</option>
                                <?php for($q = 1; $q <= $product['stock'] && $q <= 30; $q++): ?>

                                    <option value="<?= $q ?>"><?= $q ?></option>

                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-sm">
                            <input type="submit" class="btn btn-dark" value="Ajouter au panier">
                        </div>
                    </form>
                    <?php else: ?>
                        <p class="fst-italic fw-bold text-danger">Y'en avait mais y'en a plus !</p>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <p class="mt-1"><a href="boutique.php?cat=<?= $product['categorie'] ?>" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la catégorie <?= $product['categorie'] ?></a></p>
        <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la boutique</a></p>
    </div>

<?php 
require_once('inc/inc_front/footer.inc.php'); 