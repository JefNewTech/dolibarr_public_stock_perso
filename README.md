# PublicStock plugin for [Dolibarr ERP CRM](https://www.dolibarr.org)

## English

*Coming soon...*

## Français

### Fonctionnalités

Ce module permet de mettre en place une vitrine dans laquelle rendre visibles publiquement les produits figurant dans votre Dolibarr. Ce n'est **pas** un module d'e-commerce. Il est principalement destiné à des organisations avec un point de vente physique, souhaitant simplement rendre le contenu de leur stock public.

Il crée une page publique (pas d'authentification à Dolibarr nécessaire) que vous pouvez utiliser telle quelle ou plus probablement inclure dans votre site web.

<!--
![Screenshot publicstock](img/screenshot_publicstock.png?raw=true "PublicStock"){imgmd}
-->

Dans le détail, le module permet de :
- Classer les produits en fonction de leur catégorie (un onglet par catégorie)
- Choisir les données que vous souhaitez afficher sur chaque produit (en particulier si les prix sont affichés TTC ou hors taxe)
- Choisir d'afficher ou non les produits non disponibles à la vente
- Choisir d'afficher ou non les produits en rupture de stock
- Utiliser un des thèmes visuels livrés avec le module
- Personnaliser ce thème ou créer le vôtre (nécessite de connaître le langage [CSS](https://developer.mozilla.org/fr/docs/Web/CSS))

La page d'administration du module permet de paramétrer toutes ces options et vous fournit également l'adresse de la page publique crée.

### Contribuer

Le projet est hébergé sur [Codeberg](https://codeberg.org/halibut/dolibarr_public_stock).

#### Traductions

Le module est actuellement disponible en français et en anglais.

Pour le traduire dans une autre langue, vous pouvez ajouter un sous-dossier dans "langs" avec le [code de la langue](https://fr.wikipedia.org/wiki/Liste_des_codes_ISO_639-1) et le [code du pays](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements). Par exemple `es_AR` pour l'espagnol d'Argentine. Copiez-y ensuite le fichier "publicstock.lang" du dossier "fr_FR" ou "en_US" et remplacez les traductions par les vôtres.

Si vous le souhaitez, vous pouvez ensuite soumettre cette traduction dans une [pull request](https://docs.codeberg.org/collaborating/pull-requests-and-git-flow/]) pour en faire profiter la communauté.

#### Thèmes

Pour créer un nouveau thème, ajouter un fichier {nomTheme}.css dans le répertoire CSS avec vos styles. Pour le rendre disponible dans l'écran de configuration du module, il faut ensuite modifier le fichier **admin/setup.php** et ajouter le nom d'affichage et le nom du fichier CSS de votre thème dans la définition du champ `PUBLICSTOCK_THEME`.

Là aussi, nous vous invitons si vous le souhaitez à soumettre une pull request pour en faire profiter la communauté.

#### Fonctionnalités / Corrections de bugs

Ce module a été principalement conçu pour répondre aux besoins de l'association française [Artifaille](https://artifaille.fr/). Ses fonctionnalités ne seront pas forcément suffisantes pour d'autres organisations avec d'autres besoins. N'hésitez pas à créer [un ticket](https://docs.codeberg.org/getting-started/issue-tracking-basics/) pour signaler un bug ou proposer une évolution.

### Licences

#### Code source

Le code source est sous licence GPL v3. Voir le fichier COPYING (en anglais) pour plus d'information.

#### Documentation

La documentation est sous licence [GFDL v1.3](https://www.gnu.org/licenses/fdl-1.3.en.html)
