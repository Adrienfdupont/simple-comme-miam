<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <script src='https://cdn.tailwindcss.com'></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Page d'inscription</title>
</head>

<body>
    <!-- conteneur pour centrer -->
    <div class='h-screen flex justify-center items-center'>
        <!-- tout le fond bleu -->
        <form class='bg-blue-200 p-2 text-sm w-60' method='post' action='/register/store' enctype='multipart/form-data'>

            <!-- éventuels messages du serveur -->
            <div>
                <?php
                Messages::displayMsg();
                ?>
            </div>

            <a href='/'>
                <p class='text-lg hover:underline'>Accueil</p>
            </a>

            <h1 class='text-xl mt-2'>Page d'inscription</h1>


            <!-- input photo de profil -->
            <div class='mt-2 md:w-1/2 md:ml-4 flex justify-center'>
                <img id='uploaded-image' src='../resources/pictures/avatar.webp' alt='Photo de la recette' class='rounded-full w-24 aspect-[1/1] object-cover'>
                <input id='upload' type='file' accept='.jpg, .jpeg, .png' name='user-image' class='mt-2 hidden'>
            </div>

            <!-- email et messages d'erreur -->
            <section class='mt-2'>
                <label>Email :</label>
                <p class='text-xs text-red-500 hidden'>
                    L'adresse email doit être valide.
                </p>
                <input id='email' type='email' name='email' class='w-full border-2 focus:outline-none'>
            </section>

            <!-- nom d'utilisateur et messages d'erreur -->
            <section class='mt-2'>
                <label>Nom d'utilisateur :</label>
                <p class='text-xs text-red-500 hidden'>
                    Le nom d'utilisateur doit être unique.
                </p>
                <input id='username' type='text' name='username' class='w-full border-2 focus:outline-none'>
            </section>

            <!-- mot de passe et messages d'erreur -->
            <section class='mt-2'>
                <label>Mot de passe :</label>
                <p class='text-xs text-red-500 hidden'>
                    Le mot de passe doit contenir au moins 8 caractères, dont 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.
                </p>
                <input id='password' type='password' name='password' class='w-full border-2 focus:outline-none'>
            </section>

            <!-- confirmation mot de passe et messages d'erreur -->
            <section class='mt-2'>
                <label>Confirmez votre mot de passe :</label>
                <p class='text-xs text-red-500 hidden'>
                    Les mots de passe doivent correspondre.
                </p>
                <input id='confirmed-password' type='password' name='confirmed-password' class='w-full border-2 focus:outline-none'>
            </section>


            <div class='mt-4 flex justify-between items-center mt-4'>
                <a href='/login'>
                    <p class='text-base hover:underline'>Connexion</p>
                </a>
                <button disabled id='register-button' class='opacity-50 bg-blue-500 rounded p-2 text-white hover:bg-blue-800' type='submit'>Inscription</button>
            </div>
        </form>
    </div>

</body>

<script src='../resources/js/register.js'></script>

</html>