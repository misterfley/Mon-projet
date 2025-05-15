<?php
// view/cgu.php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>CGU – Roque’N’Roll</title>
    <?php include("header.php"); ?>
</head>

<body>
    <?php include("nav.php"); ?>

    <main class="flex-shrink-0">
        <div class="container py-5">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="homepage.php">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Conditions Générales d’Utilisation</li>
                </ol>
            </nav>

            <h1 class="mb-4">Conditions Générales d’Utilisation</h1>

            <section class="mb-5">
                <h2>1. Objet</h2>
                <p>
                    Les présentes Conditions Générales d’Utilisation (CGU) ont pour objet de définir les modalités et conditions
                    d’utilisation du jeu Roque’N’Roll, accessible à l’adresse <code>https://misterfley.fr/roquenroll</code>, ainsi que
                    les droits et obligations des joueurs. En jouant, vous acceptez sans réserve ces CGU.
                </p>
            </section>

            <section class="mb-5">
                <h2>2. Accès au jeu</h2>
                <p>
                    Le jeu est accessible gratuitement à tout utilisateur disposant d’une connexion Internet. Certaines fonctionnalités
                    multijoueur nécessitent un compte. L’éditeur se réserve le droit de suspendre ou interrompre l’accès à tout moment,
                    notamment pour maintenance.
                </p>
            </section>

            <section class="mb-5">
                <h2>3. Inscription</h2>
                <p>
                    Le joueur s’engage à fournir des informations exactes lors de l’inscription et à ne pas usurper l’identité d’un tiers.
                    Le compte est personnel et les identifiants doivent rester confidentiels.
                </p>
            </section>

            <section class="mb-5">
                <h2>4. Contenu utilisateur</h2>
                <p>
                    Les joueurs peuvent publier des avatars, messages ou historiques de parties. En publiant, ils garantissent être titulaires
                    des droits ou avoir obtenu les autorisations nécessaires. Ils autorisent Roque’N’Roll à diffuser ces contenus
                    gratuitement et pour une durée illimitée.
                </p>
            </section>

            <section class="mb-5">
                <h2>5. Règles de conduite</h2>
                <p>Il est interdit de :</p>
                <ul>
                    <li>Publier des contenus illégaux, violents, haineux ou discriminatoires;</li>
                    <li>Tenir des propos diffamatoires ou portant atteinte à autrui;</li>
                    <li>Partager du contenu publicitaire sans autorisation.</li>
                </ul>
                <p>
                    Les comptes diffusant de manière répétée des contenus non conformes pourront être suspendus.
                </p>
            </section>

            <section class="mb-5">
                <h2>6. Modération</h2>
                <p>
                    Le jeu dispose d’un système de modération. Les contenus peuvent être validés ou supprimés après signalement
                    pour garantir le respect des CGU.
                </p>
            </section>

            <section class="mb-5">
                <h2>7. Propriété intellectuelle</h2>
                <p>
                    Roque’N’Roll (design, code, graphismes) est protégé par les lois sur la propriété intellectuelle.
                    Toute reproduction, distribution ou utilisation non autorisée est interdite sans accord préalable.
                </p>
            </section>

            <section class="mb-5">
                <h2>8. Données personnelles</h2>
                <p>
                    Des données sont collectées pour l’inscription et le fonctionnement du jeu. Elles ne sont pas transmises à des tiers
                    sans consentement. Conformément au RGPD, vous pouvez exercer vos droits à l’adresse :
                    <a href="mailto:fleyocontact@gmail.com">fleyocontact@gmail.com</a>.
                </p>
            </section>

            <section class="mb-5">
                <h2>9. Cookies</h2>
                <p>
                    Roque’N’Roll peut utiliser des cookies pour la navigation et les statistiques. Vous pouvez modifier les paramètres
                    de votre navigateur pour bloquer les cookies.
                </p>
            </section>

            <section class="mb-5">
                <h2>10. Responsabilité</h2>
                <p>
                    Roque’N’Roll ne peut être tenu responsable :
                </p>
                <ul>
                    <li>Du contenu publié par les joueurs;</li>
                    <li>Des dommages liés à l’utilisation du jeu;</li>
                    <li>Des erreurs ou omissions dans les informations.</li>
                </ul>
            </section>

            <section class="mb-5">
                <h2>11. Liens externes</h2>
                <p>
                    Des liens vers d’autres sites peuvent être présents. Roque’N’Roll n’est pas responsable de leur contenu
                    ni de leurs pratiques.
                </p>
            </section>

            <section class="mb-5">
                <h2>12. Modification des CGU</h2>
                <p>
                    L’éditeur se réserve le droit de modifier ces CGU à tout moment. Les joueurs sont invités à les consulter régulièrement.
                </p>
            </section>

            <section class="mb-5">
                <h2>13. Droit applicable</h2>
                <p>
                    Ces CGU sont régies par le droit français. En cas de litige, les parties rechercheront une solution amiable
                    avant de saisir les tribunaux compétents.
                </p>
            </section>

            <p class="text-muted small">Dernière mise à jour : 04/2025</p>
        </div>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" defer></script>
</body>

</html>