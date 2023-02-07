<?php

class Category extends Model
{
    public $name;

    public function belongs()
    {
        return parent::join('Belong', 'belongs.category_id', 'categories.id');
    }
}
