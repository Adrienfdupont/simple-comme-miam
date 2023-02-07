<?php

class User extends Model
{
    public $name;

    public $email;

    public $is_admin;

    public $banned_until;

    public $password;

    public $image_path;

    public function recipes()
    {
        return parent::join('Recipe', 'recipes.user_id', 'users.id');
    }

    public function notifications()
    {
        return parent::join('Notification', 'notifications.user_id', 'users.id');
    }

    public function reports()
    {
        return parent::join('Report', 'reports.user_id', 'users.id');
    }
}
