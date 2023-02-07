<?php

class Report extends Model
{
    public $reason;

    public $was_considered;

    public $user_id;

    public $recipe_id;

    public $admin_id;

    public function notifications()
    {
        return parent::join('Notification', 'notifications.report_id', 'reports.id');
    }

    public function recipe()
    {
        return parent::join('Recipe', 'reports.recipe_id', 'recipes.id')[0];
    }

    public function user()
    {
        return parent::join('User', 'reports.user_id', 'users.id')[0];
    }

    public function admin()
    {
        return parent::join('User', 'reports.admin_id', 'users.id')[0];
    }
}
