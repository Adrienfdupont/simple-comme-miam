<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <script src='https://cdn.tailwindcss.com'></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Page de connexion</title>
</head>

<body>
    <!-- conteneur pour centrer -->
    <div class='h-screen flex justify-center items-center'>

        <!-- tout le fond bleu -->
        <form class='bg-blue-200 p-2 text-sm w-60' method='POST' action='/login/verify'>

            <a href='/'>
                <p class='text-lg hover:underline'>Accueil</p>
            </a>

            <h1 class='mt-2 text-xl'>Page de connexion</h1>

            <!-- nom d'utilisateur et messages d'erreur -->
            <div class='mt-4'>
                <label>Nom d'utilisateur :</label>
                <input id='username' type='text' name='username' class='w-full border-2 focus:outline-none'>
            </div>

            <!-- mot de passe et messages d'erreur -->
            <div class='mt-4'>
                <label>Mot de passe :</label>
                <input id='password' type='password' name='password' class='w-full border-2 focus:outline-none'>
            </div>

            <!-- éventuels messages d'erreur du serveur -->
            <div class='text-red-500 mt-2'>
                <?php
                Messages::displayMsg();
                ?>
            </div>

            <div class='mt-4 flex justify-between items-center'>
                <a href='/register'>
                    <p class='text-base hover:underline'>Inscription</p>
                </a>
                <button disabled id='button' type='submit' class='opacity-50 bg-blue-500 rounded p-2 text-white hover:bg-blue-800'>Connexion</button>
            </div>
        </form>
    </div>

    <script src='../resources/js/login.js'></script>

</body>

</html>