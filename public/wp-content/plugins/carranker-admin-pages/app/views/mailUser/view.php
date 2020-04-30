<h1>Mail User</h1>

<table>
    <tr>
        <td>
            Make a new mail user:<form method="post" id="createMailUserForm">
                <input type="text" name="domain" placeholder="domain" value="carranker.com" required>
                <input type="password" name="password" placeholder="password" required>
                <input type="email" name="email" placeholder="email" required>
                <input type="email" name="forward" placeholder="forward">
                <input type="hidden" value="create" name="carrankerAdminAction">
                <input type="submit" value="Create">
            </form>
        </td>
        <td></td>
    </tr>
	<?php if (!empty($mailUsers)) { ?>
        <tr><td>All mail users:</td><td></td></tr>
	<?php } else { ?>
		<tr><td>No mail users have been created so far.</td><td></td></tr>
    <?php } ?>
	<?php foreach ($mailUsers as $mailUser): ?>
        <tr>
            <td>
                <form method="post" class="updateMailUserForm">
                    <input type="text" name="domain" value="<?= $mailUser->getDomain() ?>" placeholder="domain" required>
                    <input type="email" name="email" value="<?= $mailUser->getEmail() ?>" placeholder="email" required>
                    <input type="email" name="forward" value="<?= $mailUser->getForward() ?>" placeholder="forward">
                    <input type="hidden" value="update" name="carrankerAdminAction">
                    <input type="hidden" value="<?= $mailUser->getId() ?>" name="id">
                    <input type="submit" value="Update">
                </form></td><td>
                <form method="post">
                    Reset password
                    <input type="password" name="password" required>
                    <input type="hidden" name="id" value="<?= $mailUser->getId() ?>">
                    <input type="hidden" value="updatePassword" name="carrankerAdminAction">
                    <input type="submit" value="Update">
                </form>
            </td>
        </tr>
	<?php endforeach; ?>
</table>
<?php if (!empty($mailUsers)) { ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    Delete a mail user by email.
    <form method="post" id="deleteMailUserForm">
        <input type="email" name="deleteMailUserEmail" required>
        <input type="hidden" value="delete" name="carrankerAdminAction">
        <input type="submit" value="Delete">
    </form>
<?php } ?>