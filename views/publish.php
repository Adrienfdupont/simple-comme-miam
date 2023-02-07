<?php
Messages::displayMsg();

$recipe = Controller::$data['recipe'];
?>

<h1 class='m-2 text-lg'><?= Controller::$data['title']; ?></h1>

<form class='bg-amber-100 p-2' method='POST' action='<?= $recipe ? '/publish/update?recipe=' . $recipe->id : '/publish/store'; ?>' enctype='multipart/form-data'>
    <div class='flex flex-col md:flex-row md:justify-between'>
        <div>

            <!-- titre -->
            <section class='mt-2 flex flex-col'>
                <label>Titre de la recette :</label>
                <input type='text' name='title' value="<?= $recipe->title ? $recipe->title : null; ?>">
            </section>

            <!-- temps de préparation -->
            <section class='mt-2'>
                <label>Temps de préparation :</label>
                <div class='flex flex-row'>
                    <input type='text' name='prepTime' class='w-16 mr-2' value='<?= $recipe->prepare_time ? $recipe->prepare_time : null; ?>'>
                    <p>minutes</p>
                </div>
            </section>

            <!-- temps de cuisson -->
            <section class='mt-2'>
                <label>Temps de cuisson :</label>
                <div class='flex flex-row'>
                    <input type='text' name='bakeTime' class='w-16 mr-2' value='<?= $recipe->bake_time ? $recipe->bake_time : null; ?>'>
                    <p>minutes</p>
                </div>
            </section>

            <!-- ajout ingrédient -->
            <p class='mt-2'>Ajouter un ingrédient nécessaire :</p>
            <div class='flex flex-row mt-2'>
                <input id='quantity' type='text' class='w-16'>
                <select id='unit' class='mx-2'>
                    <?php foreach (Controller::$data['units'] as $unit) : ?>
                        <?= "<option value='" . $unit->id . "'>" . $unit->name . "</option>" ?>
                    <?php endforeach; ?>
                </select>
                <p>de</p>
            </div>
            <div class='mt-2'>
                <input id='ingredients' type='text' name='ingredients[]'>
                <div class='suggestions bg-white drop-shadow-lg absolute z-10 max-h-60 w-60 overflow-scroll'></div>
            </div>
            <div class='flex flex-wrap'>

                <!-- s'il s'agit d'une modification on affiche les ingrédients déjà présents -->
                <?php if ($recipe) : ?>

                    <?php foreach ($recipe->needs() as $need) : ?>
                        <div id='ingredients<?= $need->ingredient()->id; ?>' class='item ml-2'>
                            <input name='ingredients[]' value='<?= $need->quantity; ?>,<?= $need->unit()->id ?>,<?= $need->ingredient()->id; ?>' checked='true' type='checkbox' onclick='deleteItem(this.parentElement)'>
                            <label><?= $need->ingredient()->name; ?></label>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <!-- ajout catégorie -->
            <p class='mt-2'>Ajouter une catégorie (facultatif) :</p>
            <div class='mt-2'>
                <input id='categories' type='text' name='categories[]'>
                <div class='suggestions bg-white drop-shadow-lg absolute z-10 max-h-60 w-60 overflow-scroll'></div>
            </div>
            <div class='flex flex-wrap'>

                <!-- s'il s'agit d'une modification on affiche les catégories déjà présentes -->
                <?php if ($recipe) : ?>

                    <?php foreach ($recipe->belongs() as $belong) : ?>
                        <div id='categories<?= $belong->category()->id; ?>' class='item ml-2'>
                            <input name='categories[]' value='<?= $belong->category()->id; ?>' checked='true' type='checkbox' onclick='deleteItem(this.parentElement)'>
                            <label><?= $belong->category()->name; ?></label>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>

        <!-- input photo de la recette -->
        <div class='mt-2 md:w-1/2 md:ml-4'>
            <img id='uploaded-image' src='.<?= $recipe->image_path ? $recipe->image_path : './resources/pictures/image.png' ?>' alt='Photo de la recette' class='w-full aspect-[1/1] object-cover'>
            <input id='upload' type='file' accept='.jpg, .jpeg, .png' name='recipe-image' class='mt-2 hidden'>
        </div>
    </div>

    <div class='mt-2 flex flex-col'>
        <label>Racontez-nous les étapes :</label>
        <textarea name='description' class='w-full h-96'>
            <?= $recipe->description ? $recipe->description : null; ?>
        </textarea>
    </div>
    <div class='flex justify-end'>
        <button class='bg-blue-500 rounded p-2 text-white hover:bg-blue-800 mt-2'><?= $recipe ? 'Modifier' : 'Publier' ?></button>
    </div>
</form>

<script src='../resources/js/publish.js'></script>
