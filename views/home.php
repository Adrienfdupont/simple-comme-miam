<?php Messages::displayMsg(); ?>

<h2 class='m-2 text-xl'>Recherche par filtre</h2>

<!-- recette sur mesure -->
<form class='bg-amber-100 mt-2 p-2' method='get' action='/recipes/search'>

    <!-- recherche ingrédients -->
    <div class='md:flex md:flex-wrap'>
        <div class='w-80'>
            <input id='ingredients' type='text' class='w-full text-2xl' placeholder="Qu'avez-vous sous la main ?">
            <div class='suggestions bg-white drop-shadow-lg absolute z-10 max-h-60 w-60 overflow-scroll'></div>
        </div>
        <div class='md:ml-4 flex flex-wrap'></div>
    </div>

    <!-- recherche catégories -->
    <div class='mt-2 md:flex md:flex-wrap'>
        <div class='w-80'>
            <input id='categories' type='text' class='w-full text-2xl' placeholder="Quels genres vous tentent ?">
            <div class='suggestions drop-shadow-lg bg-white absolute z-10 max-h-60 w-60 overflow-scroll'></div>
        </div>
        <div class='md:ml-4 git flex flex-wrap'></div>
    </div>

    <!-- temps préparation -->
    <div>
        <label for='maxPrepTime'>Temps de préparation maximal :</label>
        <div class='flex flex-row justify-between items-center my-1'>
            <input id='max-prep-time' type='range' name='maxPrepTime' class='w-2/3 lg:w-3/4 h-1 bg-black' style='-webkit-appearance: none'>
            <p></p>
        </div>
    </div>

    <!-- temps cuisson -->
    <div>
        <label for='maxBakeTime'>Temps de cuisson maximal :</label>
        <div class='flex flex-row justify-between items-center my-1'>
            <input id='max-bake-time' type='range' name='maxBakeTime' class='w-2/3 lg:w-3/4 h-1 bg-black' style='-webkit-appearance: none'>
            <p></p>
        </div>
    </div>

    <!-- bouton -->
    <div class='flex justify-center mt-4'>
        <button id='filter-button' class='bg-blue-500 rounded p-2 text-white lg:hover:bg-blue-800'>À vos tabliers !</button>
    </div>
</form>

<h2 class='m-2 text-xl'>Recettes de saison</h2>

<!-- recettes de saison -->
<section class='mt-2 grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3'>

    <?php foreach (Controller::$data['recipes'] as $recipe) : ?>

        <!-- card -->
        <article class='bg-amber-100 p-2 drop-shadow flex flex-col justify-between'>

            <!-- titre, temps et photo de profil -->
            <div class='flex flex-row justify-between'>
                <div>

                    <!-- titre -->
                    <div>
                        <a href='/recipe?title=<?= $recipe->slug ?>'>
                            <h3 class='text-xl lg:hover:underline'><?= $recipe->title; ?></h3>
                        </a>
                    </div>

                    <!-- temps de préparation et de cuisson -->
                    <div class='mt-4'>
                        <p>
                            Préparation : <?= $recipe->prepare_time > 0 ? $recipe->prepare_time . ' minutes' : 'aucune'; ?>
                        </p>
                        <p>
                            Cuisson : <?= $recipe->bake_time > 0 ? $recipe->bake_time . ' minutes' : 'aucune' ?>
                        </p>
                    </div>
                </div>

                <!-- photo de profil -->
                <a href='recipes?user=<?= $recipe->user()->name; ?>'>
                    <div class='flex flex-col items-center'>
                        <img class='h-32 rounded-full aspect-[1/1] object-cover' src='<?= $recipe->user()->image_path ? $recipe->user()->image_path : '../resources/pictures/avatar.webp'; ?>' alt='Photo de <?= $recipe->user()->name; ?>'>
                        <div class='text-base'>
                            <?= $recipe->user()->name; ?>
                        </div>
                    </div>
                </a>
            </div>

            <!-- affichage des ingrédients -->
            <div class='mt-4'>
                <?php
                $ingredients = [];
                foreach ($recipe->needs() as $need) {
                    $ingredients[] = $need->ingredient()->name;
                }
                echo implode(', ', $ingredients);
                ?>
            </div>

            <!-- photo de la recette -->
            <div>
                <img class=' mt-4 w-full aspect-[1/1] object-cover' src='.<?= $recipe->image_path; ?>' alt='Photo de <?= $recipe->title; ?>'>
            </div>
        </article>

    <?php endforeach; ?>

</section>

<script src='../resources/js/home.js'></script>
