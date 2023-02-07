<?php

class Belong extends Model
{
    public $recipe_id;

    public $category_id;

    public function category()
    {
        return parent::join('Category', 'belongs.category_id', 'categories.id')[0];
    }

    public function recipe()
    {
        return parent::join('Recipe', 'belongs.recipe_id', 'recipes.id')[0];
    }
}
