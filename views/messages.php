<h1 class='text-xl m-2'><?= Controller::$data['title']; ?></h1>

<div class='mt-2 bg-blue-200 p-2'>
    <table class='w-full'>

        <tr>
            <th>Sujet</th>
            <th>Expéditeur</th>
            <th>Date</th>
        </tr>

        <?php if (Controller::$data['messages'] == null) : ?>
            <tr>
                <td>Pas de message à afficher</td>
            </tr>

        <?php else : ?>

            <?php foreach (Controller::$data['messages'] as $message) : ?>

                <tr>
                    <td class='border-2 border-black p-2'>
                        <a class='hover:underline' href='/messages/content?message=<?= $message->id; ?>'>
                            <?= $message->subject; ?>
                        </a>
                    </td>
                    <td class='border-2 border-black p-2'>
                        <?= $message->admin()->name; ?>
                    </td>
                    <td class='border-2 border-black p-2'>
                        <?= date("d/m/Y h:i", strtotime($message->created_at)); ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </table>
</div>
