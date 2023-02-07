<?php Messages::displayMsg(); ?>

<h class='m-2 text-xl'>Mes recettes</h>

<section class='mt-2 grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3'>

    <?php foreach (Controller::$data['recipes'] as $recipe) : ?>

        <!-- card -->
        <article class='bg-amber-100 p-2 drop-shadow flex flex-col justify-between'>

            <h2 class='text-xl mb-4'><?= $recipe->title; ?></h2>

            <div>
                <img class='w-full aspect-[1/1] object-cover' src='.<?= $recipe->image_path; ?>' alt='Photo de <?= $recipe->title; ?>'>
            </div>

            <p class='my-4'>Publiée le <?= date("d/m/Y", strtotime($recipe->created_at)); ?></p>

            <!-- ingrédients -->
            <div>
                <?php
                $ingredients = [];
                foreach ($recipe->needs() as $need) {
                    $ingredients[] = $need->ingredient()->name . ' (' . $need->quantity . ' ' . $need->unit()->name . ')';
                }
                echo implode(', ', $ingredients);
                ?>
            </div>

            <div class='flex justify-between mt-4'>
                <button value='<?= $recipe->slug; ?>' class='delete-button text-white bg-red-500 hover:bg-red-800 p-1 rounded w-24'>Supprimer</button>

                <a class='flex justify-center w-24 bg-blue-500 rounded p-1 text-white hover:bg-blue-800' href='/posts/edit?recipe=<?= $recipe->slug; ?>'>Modifier</a>
            </div>

        </article>

    <?php endforeach; ?>

    <!-- pop-up de suppresion -->
    <section id='delete-pop-up' class='hidden fixed inset-0 flex flex-col justify-center items-center'>
        <div class='fixed inset-0 bg-black opacity-50'></div>

        <div>
            <div class='flex justify-end'>
                <i class='close-pop-up material-icons text-white drop-shadow'>close</i>
            </div>
            <div class='bg-white text-black drop-shadow w-48 p-2'>
                <p>Voulez-vous vraiment supprimer cette recette ?</p>
                <form class='flex justify-end' method='GET' action='/posts/delete'>
                    <button id='submit-button' name='recipe' class='mt-4 delete-button text-white bg-red-500 hover:bg-red-700 p-1 rounded w-24'>Supprimer</button>
                </form>
            </div>
        </div>

    </section>

</section>

<script src='../resources/js/posts.js'></script>
