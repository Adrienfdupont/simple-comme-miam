<?php

class Notification extends Model
{
    public $subject;

    public $content;

    public $report_id;

    public $user_id;

    public $admin_id;

    public $was_read;

    public function user()
    {
        return parent::join('User', 'notifications.user_id', 'users.id')[0];
    }

    public function report()
    {
        return parent::join('Report', 'notifications.report_id', 'reports.id')[0];
    }

    public function admin()
    {
        return parent::join('User', 'notifications.admin_id', 'users.id')[0];
    }
}
