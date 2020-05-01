<h1>Profanities</h1>
<form method="post" id="createProfanityForm">
    <input type="text" name="name" required>
    <input type="hidden" value="create" name="carrankerAdminAction">
    <input type="submit" value="Create">
</form>

<form method="post" id="deleteProfanityForm">
    <input type="text" name="deleteProfanityName" required>
    <input type="hidden" value="delete" name="carrankerAdminAction">
    <input type="submit" value="Delete">
</form>

<form>
    <?php foreach (range('a', 'z') as $character): ?>
        <a href="admin.php?page=profanity-admin-page&character=<?= $character ?>"><?= strtoupper($character) ?></a>
    <?php endforeach; ?>
</form>

<?php foreach ($profanities as $profanity): ?>
    <form method="post" class="updateProfanityForm">
        <input type="text" name="name" value="<?= $profanity->getName() ?>" required>
        <input type="hidden" value="update" name="carrankerAdminAction">
        <input type="hidden" value="<?= $profanity->getId() ?>" name="id">
        <input type="submit" value="Update">
    </form>
<?php endforeach; ?>

