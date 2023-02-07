<?php if (!Controller::$data['recipes']) : ?>

    <div class='bg-amber-100 p-2 mt-2 drop-shadow'>
        Aucune recette ne correspond à vos critères.
    </div>

<?php else : ?>

    <h1 class='mt-2 ml-2 text-xl'><?= Controller::$data['title'] ?></h1>

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
                                <h3 class='text-xl hover:underline'><?= $recipe->title; ?></h3>
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
                    <a href='/recipes?user=<?= $recipe->user()->name; ?>'>
                        <div class='flex flex-col items-center'>
                            <img class='h-32 rounded-full aspect-[1/1] object-cover' src='.<?= $recipe->user()->image_path ? $recipe->user()->image_path : '../resources/pictures/avatar.webp'; ?>' alt='Photo de <?= $recipe->user()->name; ?>'>
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

<?php endif; ?>
