<!doctype html>
<head>
    <?php \top_htmlhead('', $psTitle, 1, 0, [], [], 1, 1, 1); ?>
</head>
<body>
    <article class="ps_main">
        <h1 class="ps_title"><?= $psTitle ?></h1>
        <section>
        <?php
        if (empty($psProducts)) {
            echo $langs->trans('NoProductToDisplay');
        } else {
			$uncategorizdLabel = $langs->trans('Uncategorized');
            foreach ($psProducts as $categoryId => $products) {
                $categoryLabel = $psCategories[$categoryId] ?? $uncategorizdLabel;
            // $langs->translate('Uncategorized');
                ?>
            <div class="ps_category">
                <h2><?= $categoryLabel ?></h2>
                <?php
                foreach ($products as $product) {
                    if ($product->getImageName() === '') {
                        $imageBlock = '';
                    } else {
                        $imageURL = (\defined('DOL_MAIN_URL_ROOT') ? (DOL_MAIN_URL_ROOT . '/') : '')
                        . 'document.php?modulepart=product&entity='
                            . $product->getEntityId()
                            . '&attachment=0&file='
                            . $product->getReference() . '/'
                            . $product->getImageName();
                        $imageBlock = <<<HTML
                    <div class="ps_product_image_block">
                        <img class="ps_product_image" alt="Product image" src="{$imageURL}">
			    	</div>
HTML;
                    }
                    ?>
            <div class="ps_product">
                <h3 class="ps_product_label"><?= $product->getLabel() ?></h3>
                <p class="ps_product_price_stock">
                    <?= <<<HTML
				{$product->getPrice()} {$psCurrencySymbol} - {$langs->trans('Stock')} : {$product->getStock()}
HTML; ?>
                    <?= $imageBlock ?>
                <p class="ps_product_desc">
                    <?= $product->getDescription() ?>
                </p>
            </div>
                    <?php
                }
            }
            ?>
        </div>
            <?php
        }
        ?>
        </section>
    </article>
</body>
