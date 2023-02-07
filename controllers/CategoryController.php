<?php

class CategoryController extends Controller
{
    public static function suggestions(): void
    {
        $q = $_REQUEST["q"];

        $suggestions = [];

        foreach (Category::where("name LIKE '%" . $q . "%'") as $category) {
            $suggestions[] = [$category->id, $category->name];
        }

        echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
