<!-- réponse du serveur -->
<div>
    <?= Messages::displayMsg(); ?>
</div>

<div class='ml-2 mt-2'>
    <h1 class='text-xl'>Profil</h1>
</div>

<div class='mt-2 bg-blue-100 p-2'>
    <form name='image-form' action='/user/update/photo' method='POST' enctype='multipart/form-data'>
        <!-- photo de profil -->
        <div class='flex justify-center'>
            <img id='uploaded-image' src='<?= Controller::$data['user']->image_path ? Controller::$data['user']->image_path : '../resources/pictures/avatar.webp'; ?>' class='rounded-full w-32 lg:w-56 aspect-[1/1] object-cover cursor-pointer' alt='Votre photo de profil'>
            <input id='upload' type='file' accept='.jpg, .jpeg, .png' name='user-image' class='mt-2 hidden'>
        </div>
    </form>

    <!-- nom d'utilisateur -->
    <div class='mt-4'>
        <h2>Nom d'utilisateur :</h2>
        <p class='font-light'><?= Controller::$data['user']->name; ?></p>
        <button value='username' class='edit-button bg-blue-500 rounded p-1 text-white hover:bg-blue-800'>Modifier</button>
    </div>

    <!-- adresse e-mail -->
    <div class='mt-4'>
        <h2>Adresse e-mail :</h2>
        <p class='font-light'><?= Controller::$data['user']->email; ?></p>
        <button value='email' class='edit-button bg-blue-500 rounded p-1 text-white hover:bg-blue-800'>Modifier</button>
    </div>

    <!-- mot de passe -->
    <div class='mt-4'>
        <h2>Mot de passe :</h2>
        <button value='password' class='edit-button bg-blue-500 rounded p-1 text-white hover:bg-blue-800'>Modifier</button>
    </div>

    <a href='/logout'>
        <p class='mt-4 text-red-500 hover:underline'>Déconnexion</p>
    </a>

    <button id='delete-button' class='text-red-500 hover:underline'>Supprimer le compte</button>

</div>

<!-- pop-up de modification -->
<section id='edit-pop-up' class='hidden fixed inset-0 flex flex-col justify-center items-center'>

    <!-- fond noir transparent -->
    <div class='fixed inset-0 bg-black opacity-50'></div>

    <!-- contenu pop-up -->
    <div class='mx-10'>
        <div class='flex justify-end'>
            <i class='close-pop-up material-icons text-white drop-shadow'>close</i>
        </div>

        <form class='bg-white text-black drop-shadow mx-2 p-2' method='POST' action='/user/update'>

            <!-- inputs -->
            <div>
                <label id='label'></label>
                <p class='text-red-500'></p>
                <input type='text' id='input' class='w-48 border-2 focus:outline-none'>
            </div>
            <div>
                <label>Votre mot de passe actuel :</label>
                <p class='text-red-500'></p>
                <input id='password' type='password' name='password' class='w-48 border-2 focus:outline-none'>
            </div>

            <div class='flex justify-end mt-4'>
                <button disabled id='submit-button' type='submit' class='opacity-50 bg-blue-500 rounded p-2 text-white hover:bg-blue-800'>Modifier</button>
            </div>

        </form>

    </div>

</section>

<!-- pop-up de suppresion -->
<section id='delete-pop-up' class='hidden fixed inset-0 flex flex-col justify-center items-center'>
    <div class='fixed inset-0 bg-black opacity-50'></div>

    <div>
        <div class='flex justify-end'>
            <i class='close-pop-up material-icons text-white drop-shadow'>close</i>
        </div>
        <div class='bg-white text-black drop-shadow w-48 p-2'>
            <p>Veuillez confirmer votre mot de passe pour pouvoir supprimer votre compte :</p>
            <form class='flex flex-col justify-end' method='POST' action='/user/delete'>
                <input type='password' name='password' class='border-2 border-black focus:outline-none'>
                <button class='mt-4 text-white bg-red-500 p-1 rounded w-24'>Supprimer</button>
            </form>
        </div>
    </div>

</section>

<script src='../resources/js/profile.js'></script>