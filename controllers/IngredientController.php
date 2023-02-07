<?php

class IngredientController extends Controller
{
    public static function suggestions(): void
    {
        $q = $_REQUEST["q"];

        $suggestions = [];

        foreach (Ingredient::where("name LIKE '%" . $q . "%'") as $ingredient) {
            $suggestions[] = [$ingredient->id, $ingredient->name];
        }

        echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
