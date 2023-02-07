<?php

class NotificationController extends Controller
{
    public static function messages(): void
    {
        if (!isset($_SESSION['id'])) {
            header('location: /login');
        }

        $messages = Notification::where('user_id = ' . $_SESSION['id']);
        self::view(
            ['messages', 'main'],
            [
                'messages' => $messages,
                'title' => 'Messages'
            ]
        );
    }

    public static function message(): void
    {
        $message = Notification::where('id = ' . $_GET['message'])[0];
        self::view(
            ['message', 'main'],
            [
                'title' => $message->subject,
                'message' => $message
            ]
        );
    }
}
