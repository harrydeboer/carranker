<h1>Mail User</h1>

<table>
    <tr>
        <td>
            New mail user:<form method="post" id="createMailUserForm">
                <input type="text" name="domain" placeholder="domain">
                <input type="password" name="password" placeholder="password">
                <input type="email" name="email" placeholder="email">
                <input type="email" name="forward" placeholder="forward">
                <input type="hidden" value="create" name="carrankerAdminAction">
                <input type="submit" value="Create">
            </form>
        </td>
        <td></td>
    </tr>
    <tr><td>All mail users:</td><td></td></tr>
	<?php foreach ($mailUsers as $mailUser): ?>
        <tr>
            <td>
                <form method="post" class="updateMailUserForm">
                    <input type="text" name="domain" value="<?= $mailUser->getDomain() ?>" placeholder="domain">
                    <input type="email" name="email" value="<?= $mailUser->getEmail() ?>" placeholder="email">
                    <input type="email" name="forward" value="<?= $mailUser->getForward() ?>" placeholder="forward">
                    <input type="hidden" value="update" name="carrankerAdminAction">
                    <input type="hidden" value="<?= $mailUser->getId() ?>" name="id">
                    <input type="submit" value="Update">
                </form></td><td>
                <form method="post">
                    Reset password
                    <input type="password" name="password">
                    <input type="hidden" name="id" value="<?= $mailUser->getId() ?>">
                    <input type="hidden" value="updatePassword" name="carrankerAdminAction">
                    <input type="submit" value="Update">
                </form>
            </td>
        </tr>
	<?php endforeach; ?>
</table>
<br>
<br>
<br>
<br>
<br>
<br>
<form method="post" id="deleteMailUserForm">
    <input type="email" name="deleteMailUserEmail">
    <input type="hidden" value="delete" name="carrankerAdminAction">
    <input type="submit" value="Delete">
</form>