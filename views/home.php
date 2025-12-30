<h1>Hello <?= $user ?></h1>

<?php foreach (['Msg1', 'Msg2', 'Msg3'] as $msg): ?>
    <p><?= $msg ?></p>
<?php endforeach; ?>