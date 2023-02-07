<?php

class UserController extends Controller
{
    public static function login(): void
    {
        // on renvoie vers la page de connexion
        Controller::view(
            ['login']
        );
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();

        header('location: /login');
    }


    public static function verify(): void
    {
        session_unset();
        session_destroy();
        session_start();

        // on vérifie que tous les champs sont remplis
        if (
            !isset($_POST['username']) || empty($_POST['username']) ||
            empty($_POST['password']) || !isset($_POST['username'])
        ) {
            Messages::setMsg('Tous les champs doivent être remplis.', 'error');
            header('location: /login');
            exit();
        }
        // on vérifie qu'un mot de passe correspond à cet utilisateur
        if (!password_verify(
            $_POST['password'],
            User::where("name = '" . $_POST['username'] . "'")[0]->password
        )) {
            Messages::setMsg('Nom d\'utilisateur ou mot de passe incorrect.', 'error');
            header('location: /login');
            exit();
        }

        // si aucune erreur alors on renvoie vers l'accueil
        $_SESSION['id'] = User::where("name = '" . $_POST['username'] . "'")[0]->id;
        header('location: /');
    }


    // renvoie vers la page d'inscription
    public static function register(): void
    {
        self::view(['register']);
    }


    public static function store(): void
    {
        // on vérifie tous les champs
        if (
            !isset($_POST['email']) || !isset($_POST['username']) ||
            !isset($_POST['password']) || !isset($_POST['confirmed-password']) ||
            empty($_POST['email']) || empty($_POST['username']) ||
            empty($_POST['password']) || empty($_POST['confirmed-password'])
        ) {
            Messages::setMsg('Tous les champs doivent être remplis.', 'error');
            header('location: /register');
        } else {

            // on vérifie l'intégrité des champs
            if (
                self::checkPassword($_POST['password']) && self::checkUsername($_POST['username']) &&
                self::comparePasswords($_POST['password'], $_POST['confirmed-password']) &&
                self::checkEmail($_POST['email'])
            ) {
                $user = new User;
                $user->name = $_POST['username'];
                $user->email = $_POST['email'];
                $user->is_admin = 0;
                $user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                if (is_uploaded_file($_FILES['user-image']['tmp_name'])) {

                    // on enregistre la photo de profil
                    $target = './public/profile-pictures/' . basename($_FILES['user-image']['name']);
                    move_uploaded_file($_FILES['user-image']['tmp_name'], $target);
                    $user->image_path = $target;
                } else {
                    // on met une photo par défaut
                    $user->image_path = './resources/pictures/avatar.webp';
                }

                $user->created_at = date('Y-m-d H:i:s');
                $user->updated_at = date('Y-m-d H:i:s');
                $user->save();
                Messages::setMsg('Vous avez bien été inscrit', 'success');
            }
            header('location: /register');
            exit();
        }
    }


    public static function detectUser(): void
    {
        $name = $_REQUEST['name'];

        if (User::where("name = '" . $name . "'")[0]) {
            $response = 1;
        } else {
            $response = 0;
        }

        echo $response;
    }


    public static function profile(): void
    {
        if (!isset($_SESSION['id'])) {
            header('location: /login');
        } else {
            $user = User::where('id = ' . $_SESSION['id'])[0];
            Controller::view(
                ['profile', 'main'],
                [
                    'user' => $user,
                    'title' => 'Profil'
                ]
            );
        }
    }

    public static function update(): void
    {
        $user = User::where('id = ' . $_SESSION['id'])[0];

        if (password_verify($_POST['password'], $user->password)) {

            // on modifie le nom
            if (isset($_POST['username']) && self::checkUsername($_POST['username'])) {
                $user->name = $_POST['username'];
                Messages::setMsg('Votre nom d\'utilisateur a bien été modifié', 'success');

                // on modifie l'email
            } else if (isset($_POST['email']) && self::checkEmail($_POST['email'])) {
                $user->email = $_POST['email'];
                Messages::setMsg('Votre e-mail a bien été modifié', 'success');

                // on modifie le mdp
            } else if (isset($_POST['new-password']) && self::checkPassword($_POST['new-password'])) {
                $user->password = password_hash($_POST['new-password'], PASSWORD_BCRYPT);
                Messages::setMsg('Votre mot de passe a bien été modifié', 'success');
            }

            $user->save();
        } else {
            Messages::setMsg('Nom d\'utilisateur ou mot de passe incorrect.', 'error');
        }
        header('location: /profile');
        exit();
    }

    public static function updatePhoto(): void
    {

        $user = User::where('id = ' . $_SESSION['id'])[0];

        // on supprime d'abord la photo s'il y en une
        if ($target = $user->image_path) {
            unlink($target);
        }

        $target = './public/profile-pictures/' . basename($_FILES['user-image']['name']);
        move_uploaded_file($_FILES['user-image']['tmp_name'], $target);

        $user->image_path = $target;
        $user->save();

        header('location: /profile');
    }

    private static function checkPassword(string $password): bool
    {
        if (strlen($password) < 8) {
            Messages::setMsg('Le mot de passe doit contenir au moins 8 caractères.', 'error');
        }
        if (!preg_match('/[a-z]/', $password)) {
            Messages::setMsg('Le mot de passe doit contenir au moins 1 minuscule.', 'error');
        }
        if (!preg_match('/[A-Z]/', $password)) {
            Messages::setMsg('Le mot de passe doit contenir au moins 1 majuscule.', 'error');
        }
        if (!preg_match('/[0-9]/', $password)) {
            Messages::setMsg('Le mot de passe doit contenir au moins 1 chiffre', 'error');
        }
        if (!preg_match('/[\'^£€$%&*()}{@#~?><>,;.|=_+¬-]/', $password)) {
            Messages::setMsg('Le mot de passe doit contenir au moins 1 caractère spécial.', 'error');
        }
        if ($_SESSION['error']) {
            return false;
        }
        return true;
    }

    private static function checkUsername(string $username): bool
    {
        if (User::where("name =  '" . $username . "'")) {
            Messages::setMsg('Le nom d\'utilisateur n\'est pas disponible.', 'error');
            return false;
        }
        return true;
    }

    private static function comparePasswords(string $password1, string $password2)
    {
        if ($password1 !== $password2) {
            return false;
        }
        return true;
    }

    private static function checkEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Messages::setMsg('L\'adresse email est invalide.', 'error');
            return false;
        }
        return true;
    }

    public static function delete(): void
    {
        $user = User::where('id = ' . $_SESSION['id'])[0];

        if (password_verify($_POST['password'], $user->password)) {

            // trouver les recettes à supprimer
            $recipes = Recipe::where('user_id = ' . $user->id);

            foreach ($recipes as $recipe) {
                // on supprime les relations avec les catégories et les ingrédients
                $belongs = $recipe->belongs();
                foreach ($belongs as $belong) {
                    $belong->delete();
                }

                $needs = $recipe->needs();
                foreach ($needs as $need) {
                    $need->delete();
                }

                // on supprime la photo de la recette
                $target = $recipe->image_path;
                unlink($target);

                // on supprime la recette
                $recipe->delete();
            }

            // on supprime l'utilisateur et sa photo
            unlink($user->image_path);
            $user->delete();

            header('location: /logout');
        } else {
            Messages::setMsg('Mot de passe incorrect.', 'error');
            header('location: /profile');
        }
    }
}
