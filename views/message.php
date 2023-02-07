<?php
$message = Controller::$data['message'];
?>

<div class='mt-2 bg-blue-200 p-2'>

    <h1 class='text-xl'><?= Controller::$data['title']; ?></h1>

    <p><?= date("d/m/Y h:i", strtotime($message->created_at)); ?></p>

    <p class='mt-2'><?= $message->admin()->name; ?></p>

    <p class='mt-4'><?= $message->content; ?></p>

</div>