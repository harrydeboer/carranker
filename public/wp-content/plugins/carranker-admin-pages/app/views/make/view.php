<h1>Make</h1>
<form method="get" id="makesForm" action="admin.php">
    <input type="hidden" value="make-admin-page" name="page">
    <select name="makename" id="selectMakes"><option value="">New Make</option><?php
        foreach ($makenames as $makename):
            if (isset($make) && $makename === $make->getName()):
                echo '<option value="' . $makename . '" selected>' . $makename . '</option>';
            else:
                echo '<option value="' . $makename . '">' . $makename . '</option>';
            endif;
        endforeach; ?>
    </select>
</form>
<?php require_once dirname(__DIR__) . '/form.php' ?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<form method="post" id="deleteMakeForm">
    <input type="hidden" value="<?= isset($make) ? $make->getId() : '' ?>" name="deleteMakeId">
    <input type="hidden" value="delete" name="carrankerAdminAction">
    <input type="submit" value="Delete">
</form>
<script type="text/javascript" src="<?= plugins_url() ?>/carranker-admin-pages/js/make.js?<?=
filemtime(dirname(__DIR__, 3) . '/js/make.js') ?>">
</script>