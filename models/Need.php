<?php

class Need extends Model
{
    public $recipe_id;

    public $ingredient_id;

    public $quantity;

    public $unit_id;

    public function ingredient()
    {
        return parent::join('Ingredient', 'needs.ingredient_id', 'ingredients.id')[0];
    }

    public function recipe()
    {
        return parent::join('Recipe', 'needs.recipe_id', 'recipes.id')[0];
    }

    public function unit()
    {
        return parent::join('Unit', 'needs.unit_id', 'units.id')[0];
    }
}
