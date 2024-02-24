<!doctype html>
<head>
    <?php
    $customCss = ($psTheme === '') ? [] : ['publicstock/css/' . $psTheme . '.css'];
    \top_htmlhead('', $psTitle, 0, 0, ['publicstock/js/publicstock.js'], $customCss, 1, 1, 1);
	if ($psCss !== '') {
	?>
	<style rel="stylesheet" type="text/css">
	<?= $psCss ?>
	</style>
	<?php
	}
	?>
</head>
<body>
    <article class="ps_main">
        <h1 class="ps_title"><?= $psTitle ?></h1>
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
                <?php
                if ($withCategories) {
                    ?>
                    <ul class="ps_tabs">
                    <?php
                    foreach ($psProducts as $categoryId => $products) {
                        $categoryLabel = $psCategories[$categoryId] ?? $uncategorizedLabel;
                        ?>
                        <li class="ps_tab_title">
                            <a href="#tab_<?= $categoryId ?>"><?= $categoryLabel ?></a>
                        </li>
                    <?php } ?>
                    </ul>
                <?php } ?>
            <?php
            foreach ($psProducts as $categoryId => $products) {
                $categoryLabel = $psCategories[$categoryId] ?? $uncategorizedLabel;
                ?>
                <div class="ps_category" id="tab_<?= $categoryId ?>">
                <?php
                foreach ($products as $product) {
                    if ($psShowImage && $product->getImageName() !== '') {
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
                    } else {
                        $imageBlock = '';
                    }
                    ?>
                    <div class="ps_product">
                        <h3 class="ps_product_label"><?= $product->getLabel() ?></h3>
                        <p class="ps_product_price">
                        	<?= $product->getPrice() . ' ' . $psCurrencySymbol ?>
						</p>
                        <?= $imageBlock ?>
					<?php if ($psShowDescription) { ?>
                        <p class="ps_product_desc">
    						<?= $product->getDescription() ?>
                        </p>
					<?php } ?>
						<ul>
						<?php if ($psShowStock) { ?>
                            <li class="ps_product_stock">
								<label><?= $langs->trans('Stock') ?></label>
							    <span><?= $product->getStock() ?></span>
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
                    <?php
                }
                ?>
                </div>
                <?php
            }
            ?>
            </div>
        <?php } ?>
        </section>
    </article>
</body>
