<?php

class Messages
{
    public static $type;

    public static function setMsg(string $msg, string $type): void
    {

        if ($type === 'error') {
            $_SESSION['error'][] = $msg;
        } else if ($type === 'success') {
            $_SESSION['success'][] = $msg;
        }
    }

    public static function displayMsg()
    {
        if (isset($_SESSION['error'])) {
            foreach ($_SESSION['error'] as $msg) {
                echo "<p class='text-red-500'>" . $msg . "</p>";
            }
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            foreach ($_SESSION['success'] as $msg) {
                echo "<p class='text-green-500'>" . $msg . "</p>";
            }
            unset($_SESSION['success']);
        }
    }
}
