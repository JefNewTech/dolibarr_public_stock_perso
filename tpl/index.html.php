<?php
$customCss = ($psTheme === '') ? [] : ['publicstock/css/' . $psTheme . '.css'];
\top_htmlhead('', '', 0, 0, ['publicstock/js/publicstock.js'], $customCss, 1, 1, 1);
if ($psCss !== '') {
    ?>
    <style rel="stylesheet" type="text/css"><?= $psCss ?></style>
    <?php
}
?>
<style>
/* ✅ Styles pour les images responsives */
.ps_product_image_block {
    width: 100%;
    height: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    max-width: 200px;
    max-height: 200px;
}

.ps_product_image {
    width: 100%;
    height: auto;
    object-fit: contain;
    max-width: 100%;
    max-height: 100%;
    transition: transform 0.3s ease-in-out;
    cursor: pointer;
}

/* ✅ Lightbox */
.lightbox {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 10%;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.8);
    text-align: center;
}

/* ✅ Image agrandie */
.lightbox img {
    max-width: 90%;
    max-height: 80vh;
    border-radius: 5px;
    transition: transform 0.3s ease-in-out;
}

/* ✅ Effet au survol */
.lightbox img:hover {
    transform: scale(1.05);
}

/* ✅ Bouton de fermeture */
.close-lightbox {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 40px;
    font-weight: bold;
    color: white;
    cursor: pointer;
}

.close-lightbox:hover {
    color: red;
}
</style>

<body>
<article class="ps_main">
    <section>
        <?php
        if (empty($psProducts)) {
            echo $langs->trans('NoProductToDisplay');
        } else {
            $uncategorizedLabel = $langs->trans('Uncategorized');

            // Trier les catégories par ordre alphabétique
            $sortedCategories = [];
            foreach ($psProducts as $categoryId => $products) {
                if (isset($psCategories[$categoryId]) || $psShowUncategorized) {
                    $sortedCategories[$categoryId] = $psCategories[$categoryId] ?? $uncategorizedLabel;
                }
            }
            asort($sortedCategories);
            ?>
            <div id="ps_categories" class="ps_withCategories">
                <!-- ✅ Onglet de recherche -->
                <ul class="ps_tabs">
                    <li class="ps_tab_title">
                        <a href="#tab_search"><?= $langs->trans('Recherche') ?></a>
                    </li>
                    <?php foreach ($sortedCategories as $categoryId => $categoryLabel) { ?>
                        <li class="ps_tab_title">
                            <a href="#tab_<?= $categoryId ?>"><?= $categoryLabel ?></a>
                        </li>
                    <?php } ?>
                </ul>

                <!-- ✅ Contenu de l'onglet "Recherche" -->
                <div class="ps_category" id="tab_search">
                    <div class="search-container">
                        <button class="clear-btn" onclick="clearSearch()">&#x1F9F9;</button>
                        <input type="text" id="productSearch" class="search-input" placeholder="Rechercher un produit..." onkeyup="filterProducts()">
                    </div>
		</div>
                <div id="search_results"></div>
                <!-- ✅ Contenu des autres onglets triés -->
                <?php foreach ($sortedCategories as $categoryId => $categoryLabel) { ?>
                    <div class="ps_category" id="tab_<?= $categoryId ?>">
                        <?php foreach ($psProducts[$categoryId] as $product) { ?>
                            <div class="ps_product">
                                <h3 class="ps_product_label"><?= $product->getLabel() ?></h3>
                                <p class="ps_product_ref"><?= '(' . $product->getReference() . ')' ?></p>

                                <!-- ✅ Image cliquable -->
                                <div class="ps_product_image_desc">
                                    <div class="ps_product_image_block">
                                        <?php
                                        $imageFolder = DOL_DOCUMENT_ROOT . '/documents/produit/' . $product->getReference() . '/';
                                        $imageURLBase = DOL_URL_ROOT . '/documents/produit/' . $product->getReference() . '/';

                                        $imageFile = '';
                                        if (is_dir($imageFolder)) {
                                            $files = scandir($imageFolder);
                                            foreach ($files as $file) {
                                                if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                                                    $imageFile = $file;
                                                    break;
                                                }
                                            }
                                        }
                                        $imageURL = !empty($imageFile) ? $imageURLBase . $imageFile : DOL_URL_ROOT . '/theme/common/nophoto.png';
                                        ?>
                                        <img class="ps_product_image" alt="<?= htmlspecialchars($product->getLabel()) ?>" 
                                             src="<?= $imageURL ?>" onclick="openLightbox(this)">
                                    </div>
                                </div>

                                <!-- ✅ Détails du produit -->
                                <ul class="ps_product_other">
                                    <?php if ($psPriceType === 'included' || $psPriceType === 'both') { ?>
                                        <li class="ps_product_price">
                                            <label><?= $langs->trans('Price') . ' (' . $langs->trans('TTC') . ')' ?></label>
                                            <span><?= $product->getPriceTTC() . $psCurrencySymbol ?></span>
                                        </li>
                                    <?php } ?>
                                    <?php if ($psPriceType === 'excluded' || $psPriceType === 'both') { ?>
                                        <li class="ps_product_price">
                                            <label><?= $langs->trans('Price') . ' (' . $langs->trans('HT') . ')' ?></label>
                                            <span><?= \round($product->getPrice(), 2) ?></span>
                                        </li>
                                    <?php } ?>
                                    <?php if ($psShowStock) { ?>
                                        <li class="ps_product_stock">
                                            <label><?= $langs->trans('Stock') ?></label>
                                            <span><?= \round($product->getStock(), 2) ?></span>
                                        </li>
                                    <?php } ?>
                                    <?php if ($psShowEmplacement) { ?>
                                        <li class="ps_product_emplacement">
                                            <label><?= $langs->trans('Emplacement') ?></label>
                                            <span>
                                                <?= !empty($product->getEmplacement()) ? htmlspecialchars($product->getEmplacement()) : $langs->trans('NotDefined') ?>
                                            </span>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
</article>

<!-- ✅ Lightbox -->
<div id="lightbox" class="lightbox">
    <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
    <img id="lightbox-img" src="" alt="Agrandissement de l'image">
</div>

<!-- ✅ Scripts -->
<script>
function openLightbox(imgElement) {
    var lightbox = document.getElementById("lightbox");
    var lightboxImg = document.getElementById("lightbox-img");
    
    lightbox.style.display = "block";
    lightboxImg.src = imgElement.src;
}

function closeLightbox() {
    document.getElementById("lightbox").style.display = "none";
}

document.getElementById("lightbox").addEventListener("click", function(event) {
    if (event.target === this) {
        closeLightbox();
    }
});
</script>

<!-- ✅ Script pour filtrer les produits -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    var searchInput = document.getElementById("productSearch");

    searchInput.addEventListener("keyup", function () {
        filterProducts();
    });

    // Activer l'onglet "Recherche" dès l'affichage de la page
    document.querySelector("a[href='#tab_search']").click();
});

function filterProducts() {
    var input = document.getElementById("productSearch");
    var filter = input.value.toUpperCase().trim();
    var products = document.querySelectorAll(".ps_product");
    var searchResults = document.getElementById("search_results");

    // Activer l'onglet "Recherche" et garder le focus
    document.querySelector("a[href='#tab_search']").click();
    setTimeout(() => { input.focus(); }, 100);

    // ✅ Vider les résultats pour éviter les doublons
    searchResults.innerHTML = "";

    // Si le champ est vide, masquer la section des résultats
    if (filter === "") {
        searchResults.style.display = "none";
        return;
    }

    let foundProducts = new Set();

    // Vérifier chaque produit dans toutes les catégories
    products.forEach(function (product) {
        var productLabelElement = product.querySelector(".ps_product_label");
        if (productLabelElement) {
            var productLabel = productLabelElement.textContent || productLabelElement.innerText;
            var normalizedLabel = productLabel.toUpperCase().trim();

            if (normalizedLabel.includes(filter) && !foundProducts.has(productLabel)) {
                foundProducts.add(productLabel);

                let clonedProduct = product.cloneNode(true);
                searchResults.appendChild(clonedProduct);
            }
        }
    });

    // Afficher un message si aucun produit n'est trouvé
    if (foundProducts.size === 0) {
        searchResults.innerHTML = "<p style='color: red;'>Aucun produit trouvé.</p>";
    }

    // ✅ Rendre visible la section des résultats
    searchResults.style.display = "flex";
    searchResults.style.flexWrap = "wrap";
    searchResults.style.gap = "10px";
}
</script>
<!-- ✅ Script pour effacer la recherche -->
<script>
function clearSearch() {
    document.getElementById("productSearch").value = "";
    filterProducts();
}
</script>
</body>
