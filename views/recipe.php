<?php
// simplification
$recipe = Controller::$data['recipe'];
?>

<!-- card -->
<article id='<?= $recipe->id; ?>' class='bg-amber-100 p-2 drop-shadow flex flex-col justify-between mt-2'>

    <div class='md:flex flex-row-reverse justify-between'>
        <!-- section au-dessus de la photo de la recette -->
        <div class='md:w-1/2 md:ml-4 flex flex-col justify-between'>
            <!-- partie au-dessus des ingrédients -->
            <div class='flex justify-between'>

                <!-- titre et temps -->
                <div class='flex flex-col justify-between'>
                    <div>
                        <h1 class='text-xl'><?= $recipe->title; ?></h1>
                        <?php if ($_SESSION['id'] !== $recipe->user()->id) : ?>
                            <a <?= !$_SESSION['id'] ? "href='/login'" : null; ?>>
                                <p id='report-button' class='my-2 text-red-500 hover:underline cursor-pointer'>Signaler un problème</p>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div>
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

            <!-- ingrédients -->
            <div class='mt-4'>
                <?php
                $ingredients = [];
                foreach ($recipe->needs() as $need) {
                    $ingredients[] = $need->ingredient()->name . ' (' . $need->quantity . ' ' . $need->unit()->name . ')';
                }
                echo implode(', ', $ingredients);
                ?>
            </div>
        </div>

        <!-- photo de la recette -->
        <div class='md:w-1/2'>
            <img class='mt-4 md:mt-0 w-full aspect-[1/1] object-cover' src='<?= $recipe->image_path; ?>' alt='Photo de <?= $recipe->title; ?>'>
        </div>
    </div>

    <!-- description de la recette -->
    <div class='mt-2'>
        <?= $recipe->description; ?>
    </div>

</article>

<!-- pop-up de recherche de signalement -->
<div id='report-pop-up' class='hidden fixed inset-0 flex flex-col justify-center items-center'>

    <!-- fond noir transparent -->
    <div class='fixed inset-0 bg-black opacity-50'></div>

    <!-- contenu pop-up -->
    <div>
        <div class='flex justify-end'>
            <i class='close-pop-up material-icons text-white drop-shadow'>close</i>
        </div>

        <div class='bg-white text-black drop-shadow mx-2 p-2'>

            <p>Pour quelle raison souhaitez-vous signaler ce contenu ?</p>

            <div class='mt-4'>
                <ul>
                    <li>
                        <input class='radio' type='radio' name='reason' value='wrong ingredient or category'>
                        <label>Ingrédient ou catégorie inadaptée</label>
                    </li>
                    <li>
                        <input class='radio' type='radio' name='reason' value='wrong title'>
                        <label>Titre inadapté</label>
                    </li>
                    <li>
                        <input class='radio' type='radio' name='reason' value='photo or username innapropriate'>
                        <label>Photo ou nom d'utilisateur inaproprié</label>
                    </li>
                    <li>
                        <input class='radio' type='radio' name='reason' value='ingredient illicit'>
                        <label>Ingrédient illicite</label>
                    </li>
                    <li>
                        <input class='radio' type='radio' name='reason' value='spam or ad'>
                        <label>Spam ou contenu commercial</label>
                    </li>
                    <li>
                        <input class='radio' type='radio' name='reason' value='hate speech'>
                        <label>Contenu injurieux</label>
                    </li>
                </ul>

                <!-- réponse du serveur -->
                <div id='server-response' class='mx-4 text-green-500'></div>

                <div class='flex justify-end mt-4'>
                    <button id='submit-radio' class='bg-blue-500 rounded p-2 text-white hover:bg-blue-800'>Signaler</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src='../resources/js/recipe.js'></script>
