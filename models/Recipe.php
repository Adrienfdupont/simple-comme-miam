<?php

class Recipe extends Model
{
    public $title;

    public $slug;

    public $description;

    public $prepare_time;

    public $bake_time;

    public $user_id;

    public $image_path;

    public function needs()
    {
        return parent::join('Need', 'needs.recipe_id', 'recipes.id');
    }

    public function belongs()
    {
        return parent::join('Belong', 'belongs.recipe_id', 'recipes.id');
    }

    public function user()
    {
        return parent::join('User', 'recipes.user_id', 'users.id')[0];
    }

    public function reports()
    {
        return parent::join('Report', 'reports.recipe_id', 'recipes.id');
    }
}
