<?php

class RecipeController extends Controller
{
    // renvoie la vue home avec les recettes de saison
    public static function home(): void
    {
        switch (true) {
            case 1 <= date('m') && date('m') <= 3:
                $currentSeason = "Hiver";
                break;
            case 4 <= date('m') && date('m') <= 6:
                $currentSeason = "Printemps";
                break;
            case 7 <= date('m') && date('m') <= 9:
                $currentSeason = "Été";
                break;
            case 10 <= date('m') && date('m') <= 12:
                $currentSeason = "Automne";
                break;
        }

        $recipes = [];
        $belongs = Category::where("name = '" . $currentSeason . "'")[0]->belongs();
        foreach ($belongs as $belong) {
            $recipes[] = $belong->recipe();
        }


        Controller::view(
            ['home', 'main'],
            [
                'recipes' => $recipes,
                'title' => 'Accueil'
            ]
        );
    }

    // requête xmlhttp depuis la page home pour suggestions
    public static function suggestions(): void
    {
        $q = $_REQUEST["q"];

        $suggestions = [];

        foreach (Recipe::where("title LIKE '%" . $q . "%'") as $recipe) {
            $suggestions[] = [$recipe->slug, $recipe->title];
        }

        echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
    }

    // renvoie une vue avec une recette en fonction d'un slug
    public static function recipe(): void
    {
        $recipe = Recipe::where("slug = '" . $_GET['title'] . "'")[0];

        Controller::view(
            ['recipe', 'main'],
            [
                'recipe' => $recipe,
                'title' => $recipe->title
            ]

        );
    }

    // affiche toutes les recettes pubiées par un utilisateur
    public static function userRecipes(): void
    {
        $condition = "name = '" . $_GET['user'] . "'";
        $user = User::where($condition)[0];
        $recipes = $user->recipes();
        $title = 'Recettes publiées par ' . $user->name;
        self::view(
            ['recipes', 'main'],
            [
                'recipes' => $recipes,
                'bodyTitle' => $title,
                'title' => $user->name . ' - recettes'
            ]
        );
    }

    // retourner les résultats de la recherche avec filtres
    public static function search(): void
    {
        $conditions = [];
        $conditions[] = 'prepare_time <= ' . $_GET['maxPrepTime'];
        $conditions[] = 'AND bake_time <= ' . $_GET['maxBakeTime'];
        if (isset($_GET['ingredients'])) {
            $conditions[] = 'AND ingredients.id IN (' . implode(', ', $_GET['ingredients']) . ')';
        }
        if (isset($_GET['categories'])) {
            $conditions[] = 'AND categories.id IN (' . implode(', ', $_GET['categories']) . ')';
        }

        $recipes = Model::joinv2(
            'Recipe',
            [
                ['belongs', 'belongs.recipe_id', 'recipes.id'],
                ['categories', 'categories.id', 'belongs.category_id'],
                ['needs', 'needs.recipe_id', 'recipes.id'],
                ['ingredients', 'ingredients.id', 'needs.ingredient_id']
            ],
            $conditions
        );

        self::view(
            ['recipes', 'main'],
            [
                'title' => 'Résultat de la recherche',
                'recipes' => $recipes
            ]
        );
    }

    public static function publish()
    {
        if (isset($_SESSION['id'])) {
            $units = Unit::where('id > 0');
            self::view(
                ['publish', 'main'],
                [
                    'units' => $units,
                    'title' => 'Publication d\'une recette'
                ]
            );
        } else {
            // si l'utilisateur n'est pas connecté on le redirige
            header('location: /login');
        }
    }

    public static function store()
    {
        if (
            isset($_POST['title']) && isset($_POST['prepTime']) &&
            isset($_POST['bakeTime']) && isset($_POST['ingredients']) &&
            !empty($_POST['title']) && !empty($_POST['prepTime']) &&
            !empty($_POST['ingredients']) && is_uploaded_file($_FILES['recipe-image']['tmp_name'])
        ) {
            $recipe = new Recipe;

            // on enregistre l'image dans le répertoire du site
            $target = './public/recipe-pictures/' . basename($_FILES['recipe-image']['name']);
            move_uploaded_file($_FILES['recipe-image']['tmp_name'], $target);


            // on insert une ligne dans la table recipes
            $recipe->title = $_POST['title'];
            $recipe->description = $_POST['description'];
            $recipe->prepare_time = $_POST['prepTime'];
            $recipe->bake_time = $_POST['bakeTime'];
            $recipe->slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title']);
            $recipe->user_id = $_SESSION['id'];
            $recipe->image_path = $target;
            $recipe->created_at = $date = date('Y-m-d H:i:s');
            $recipe->updated_at = date('Y-m-d H:i:s');
            $recipe->save();

            // on insert une ligne dans la table needs pour chaque besoin d'ingrédient
            unset($_POST['ingredients'][0]);
            foreach ($_POST['ingredients'] as $ingredient) {
                $need = new Need;
                $need->recipe_id = Recipe::where("created_at = '" . $date . "'")[0]->id;
                $need->quantity = explode(',', $ingredient)[0];
                $need->unit_id = explode(',', $ingredient)[1];
                $need->ingredient_id = explode(',', $ingredient)[2];
                $need->created_at = date('Y-m-d H:i:s');
                $need->updated_at = date('Y-m-d H:i:s');
                $need->save();
            }

            // on insert une ligne dans la table needs pour chaque appartenance à une catégorie
            unset($_POST['categories'][0]);
            foreach ($_POST['categories'] as $category) {
                $belong = new Belong;
                $belong->recipe_id = Recipe::where("created_at = '" . $date . "'")[0]->id;
                $belong->category_id = $category;
                $belong->created_at = date('Y-m-d H:i:s');
                $belong->updated_at = date('Y-m-d H:i:s');
                $belong->save();
            }

            // on reidirige vers l'accueil avec une confirmation
            Messages::setMsg('Votre recette a bien été publiée.', 'success');
            header('location: /');
        } else {
            Messages::setMsg('Veuillez remplir tous les champs obligatoires.', 'error');
            header('location: /publish');
        }
    }

    // affiche les recettes publiées par l'utilisateur
    public static function posts(): void
    {
        if (!isset($_SESSION['id'])) {
            header('location: /login');
            exit;
        }

        $recipes = User::where('id = ' . $_SESSION['id'])[0]->recipes();
        self::view(
            ['posts', 'main'],
            ['recipes' => $recipes]
        );
    }

    public static function delete(): void
    {
        $recipe = Recipe::where("slug = '" . $_GET['recipe'] . "'")[0];

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

        Messages::setMsg('La recette a bien été supprimée.', 'success');
        header('location: /posts');
    }

    public static function edit()
    {
        $recipe = Recipe::where("slug = '" . $_GET['recipe'] . "'")[0];
        $units = Unit::where('id > 0');

        self::view(
            ['publish', 'main'],
            [
                'recipe' => $recipe,
                'units' => $units,
                'title' => 'Modification d\'une recette'
            ]
        );
    }

    public static function update()
    {
        if (
            isset($_POST['title']) && isset($_POST['prepTime']) &&
            isset($_POST['bakeTime']) && isset($_POST['ingredients']) &&
            !empty($_POST['title']) && !empty($_POST['prepTime']) && !empty($_POST['ingredients'])
        ) {
            // on récupère l'id de la recette qu'on modifie
            $recipe = Recipe::where('id = ' . $_GET['recipe'])[0];

            // on supprime les relations avec les catégories et les ingrédients
            $belongs = $recipe->belongs();
            foreach ($belongs as $belong) {
                $belong->delete();
            }

            $needs = $recipe->needs();
            foreach ($needs as $need) {
                $need->delete();
            }

            // on modifie la ligne de la recette
            $recipe->title = $_POST['title'];
            $recipe->description = $_POST['description'];
            $recipe->prepare_time = $_POST['prepTime'];
            $recipe->bake_time = $_POST['bakeTime'];
            $recipe->slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title']);

            if ($_FILES['recipe-image']['name'] != null) {
                // si la photo de profil a été changée, on met à jour

                // on supprime la photo de la recette
                $target = $recipe->image_path;
                unlink($target);

                // on enregistre la nouvelle image dans le répertoire du site
                $target = './public/recipe-pictures/' . basename($_FILES['recipe-image']['name']);
                move_uploaded_file($_FILES['recipe-image']['tmp_name'], $target);

                // et on stocke son chemin en bdd
                $recipe->image_path = $target;
            }

            $recipe->updated_at = date('Y-m-d H:i:s');
            $recipe->save();

            // on insert une ligne dans la table needs pour chaque besoin d'ingrédient
            unset($_POST['ingredients'][0]);
            foreach ($_POST['ingredients'] as $ingredient) {
                $need = new Need;
                $need->recipe_id = $recipe->id;
                $need->quantity = explode(',', $ingredient)[0];
                $need->unit_id = explode(',', $ingredient)[1];
                $need->ingredient_id = explode(',', $ingredient)[2];
                $need->created_at = date('Y-m-d H:i:s');
                $need->updated_at = date('Y-m-d H:i:s');
                $need->save();
            }

            // on insert une ligne dans la table needs pour chaque appartenance à une catégorie
            unset($_POST['categories'][0]);
            foreach ($_POST['categories'] as $category) {
                $belong = new Belong;
                $belong->recipe_id = $recipe->id;
                $belong->category_id = $category;
                $belong->created_at = date('Y-m-d H:i:s');
                $belong->updated_at = date('Y-m-d H:i:s');
                $belong->save();
            }

            // on reidirige vers l'accueil avec une confirmation
            Messages::setMsg('Votre recette a bien été modifiée.', 'success');
            header('location: /');
        } else {
            Messages::setMsg('Veuillez remplir tous les champs obligatoires.', 'error');
            header('location: /publish');
        }
    }
}
