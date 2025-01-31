<?php
$customCss = ($psTheme === '') ? [] : ['publicstock/css/' . $psTheme . '.css'];
\top_htmlhead('', '', 0, 0, ['publicstock/js/publicstock.js'], $customCss, 1, 1, 1);
if ($psCss !== '') {
    ?>
    <style rel="stylesheet" type="text/css"><?= $psCss ?></style>
    <?php
}
?>
<body>
<article class="ps_main">
    <section>
        <?php
        if (empty($psProducts)) {
            echo $langs->trans('NoProductToDisplay');
        } else {
            $uncategorizedLabel = $langs->trans('Uncategorized');

            // Enable categories tabs only if there is more than one category
            if (\count($psProducts) > 1) {
                $withCategories = true;
                $cssCategories = 'ps_withCategories';
            } else {
                $withCategories = false;
                $cssCategories = 'ps_withoutCategories';
            }
            ?>
            <div id="ps_categories" class="<?= $cssCategories ?>">
                <!-- ✅ Onglet de recherche ajouté -->
                <ul class="ps_tabs">
                    <li class="ps_tab_title">
                        <a href="#tab_search"><?= $langs->trans('Recherche') ?></a>
                    </li>
                    <?php foreach ($psProducts as $categoryId => $products) { ?>
                        <li class="ps_tab_title">
                            <a href="#tab_<?= $categoryId ?>"><?= $psCategories[$categoryId] ?? $uncategorizedLabel ?></a>
                        </li>
                    <?php } ?>
                </ul>

                <!-- ✅ Contenu de l'onglet "Recherche" -->
                <div class="ps_category" id="tab_search">
                    <input type="text" id="productSearch" placeholder="Rechercher un produit..." onkeyup="filterProducts()" style="width: 100%; padding: 10px; margin-bottom: 20px;">
                    <div id="search_results"></div>
                </div>

                <!-- ✅ Contenu des autres onglets -->
<?php
foreach ($psProducts as $categoryId => $products) {
    // Skip uncategorized products if option to display them is not set
    if (!isset($psCategories[$categoryId]) && $psShowUncategorized === false) {
        continue;
    }
    $categoryLabel = $psCategories[$categoryId] ?? $uncategorizedLabel;
    ?>
    <div class="ps_category" id="tab_<?= $categoryId ?>">
        <?php
        foreach ($products as $product) {
            // ✅ Construction dynamique de l'URL de l'image
            $imageFolder = DOL_DOCUMENT_ROOT . '/documents/produit/' . $product->getReference() . '/';
            $imageURLBase = DOL_URL_ROOT . '/documents/produit/' . $product->getReference() . '/';

            $imageFile = '';
            if (is_dir($imageFolder)) {
                $files = scandir($imageFolder);
                foreach ($files as $file) {
                    if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                        $imageFile = $file; // ✅ Utiliser la première image trouvée
                        break;
                    }
                }
            }

            $imageURL = !empty($imageFile) ? $imageURLBase . $imageFile : DOL_URL_ROOT . '/theme/common/nophoto.png';

            ?>
            <div class="ps_product">
                <h3 class="ps_product_label"><?= $product->getLabel() ?></h3>
                <p class="ps_product_ref"><?= '(' . $product->getReference() . ')' ?></p>
                <!-- ✅ Image ajoutée -->
                <div class="ps_product_image_desc">
                    <div class="ps_product_image_block">
                        <img class="ps_product_image" alt="Product image"
                             src="<?= $imageURL ?>" style="max-width:150px; max-height:150px;">
                    </div>
                </div>
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
                    <?php if ($psShowNature) { ?>
                        <li class="ps_product_finished">
                            <label><?= $langs->trans('NatureOfProductShort') ?></label>
                            <span><?= $langs->trans($product->getNature()) ?></span>
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

    let foundProducts = new Set(); // ✅ Utilisation d'un Set pour éviter les doublons

    // Vérifier chaque produit dans toutes les catégories
    products.forEach(function (product) {
        var productLabelElement = product.querySelector(".ps_product_label");
        if (productLabelElement) {
            var productLabel = productLabelElement.textContent || productLabelElement.innerText;
            var normalizedLabel = productLabel.toUpperCase().trim();

            if (normalizedLabel.includes(filter) && !foundProducts.has(productLabel)) {
                // ✅ Ajouter au Set pour éviter les doublons
                foundProducts.add(productLabel);

                // ✅ Cloner le produit et l'ajouter aux résultats
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
    searchResults.style.flexWrap = "wrap"; // ✅ Mise en grille
    searchResults.style.gap = "10px"; // ✅ Espacement entre les produits
}
</script>
</body>
