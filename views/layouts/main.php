<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://cdn.tailwindcss.com'></script>
    <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
    <title> <?= Controller::$data['title']; ?> </title>
</head>

<body>

    <header class='bg-red-700 p-2 lg:px-32 xl:px-64'>

        <!-- contenu header -->
        <div class='flex flex-row justify-between text-white'>

            <!-- titre et sous-titre -->
            <div>
                <h1 class='text-lg md:text-xl lg:text-2xl'>Simple comme miam</h1>
                <p class='italic text-sm md:text-base lg:text-xl'>La recette adaptée à vos besoins</p>
            </div>

            <!-- menu burger (<md) -->
            <div class='md:hidden'>
                <i id='open-menu' class='material-icons text-5xl'>menu</i>
            </div>

            <!-- navbar et recherche (>md) -->
            <div class='hidden md:flex flex-col items-end'>

                <nav>
                    <ul class='flex flex-row lg:text-lg'>
                        <a href='/'>
                            <li class='ml-4 lg:ml-8 hover:underline'>Accueil</li>
                        </a>
                        <a href='/messages'>
                            <li class='ml-4 lg:ml-8 hover:underline'>Messages</li>
                        </a>
                        <a href='/posts'>
                            <li class='ml-4 lg:ml-8 hover:underline'>Publications</li>
                        </a>
                        <a href='/profile'>
                            <li class='ml-4 lg:ml-8 hover:underline'>Profil</li>
                        </a>
                        <a href='/publish'>
                            <li class='ml-4 lg:ml-8 hover:underline'>Publier</li>
                        </a>
                    </ul>
                </nav>

                <!-- recherche nom de recette -->
                <div class='mt-4'>
                    <input type='text' id='recipe-input' placeholder='Rechercher une recette' class='w-60 text-black border-2 border-gray-500 focus:outline-none'>
                    <div class='suggestions bg-white drop-shadow-lg text-black absolute w-60 max-h-60 overflow-scroll z-10'></div>
                </div>
            </div>
        </div>
    </header>

    <!-- menu déroulé (>md) -->
    <section id='menu' class='fixed top-0 right-0 bg-white p-5 drop-shadow hidden z-50'>
        <div class='flex justify-end'>
            <i id='close-menu' class='material-icons text-5xl'>close</i>
        </div>
        <ul class='text-xl leading-loose'>
            <a href='/'>
                <li>Accueil</li>
            </a>
            <li id='search'>Rechercher</li>
            <a href='/messages'>
                <li>Messages</li>
            </a>
            <a href='/posts'>
                <li>Publications</li>
            </a>
            <a href='/profile'>
                <li>Profil</li>
            </a>
            <a href='/publish'>
                <li>Publier</li>
            </a>
        </ul>
    </section>

    <!-- contenu de chaque vue -->
    <main class='lg:mx-32 xl:mx-64 text-base  md:text-base'>
        <?php require Controller::$content; ?>
    </main>

    <!-- pop-up de recherche de recette -->
    <section id='search-pop-up' class='hidden fixed inset-0 flex flex-col justify-center items-center'>
        <div class='fixed inset-0 bg-black opacity-50'></div>

        <div>
            <div class='flex justify-end'>
                <i class='close-pop-up material-icons text-white drop-shadow'>close</i>
            </div>
            <div class='bg-white text-black drop-shadow w-48'>
                <input id='md-recipe-input' type='text' name='recipe-id' class='w-48 focus:outline-none'>
                <div class='suggestions bg-white absolute w-48'></div>
            </div>
        </div>

    </section>

    <script src='../../resources/js/main.js'></script>

</body>

</html>
