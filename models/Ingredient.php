<?php

class Ingredient extends Model
{
    public $name;

    public function needs()
    {
        return parent::join('Need', 'needs.ingredient_id', 'ingredients.id');
    }
}
