<h1>Model</h1>
<form method="get" id="modelsForm" action="admin.php">
    <input type="hidden" value="model-admin-page" name="page">
    <select name="makename" id="selectMakes"><option value="">Make</option><?php
        foreach ($makenames as $makename) {
            if (isset($model) && $model->getMake() === $makename):
                echo '<option value="' . $makename . '" selected>' . $makename . '</option>';
            else:
                echo '<option value="' . $makename . '">' . $makename . '</option>';
            endif;
        } ?>
    </select>
    <select name="modelname" id="selectModels"><option value="">New Model</option>
    </select>
</form>
<?php require_once dirname(__DIR__) . '/form.php' ?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<form method="post" id="deleteModelForm">
    <input type="hidden" value="<?= isset($model) ? $model->getId() : '' ?>" name="deleteModelId">
    <input type="submit" value="Delete">
    <input type="hidden" value="delete" name="carrankerAdminAction">
</form><?php
if (isset($model)): ?>
    <script type="text/javascript">
        var make = "<?= $model->getMake() ?>";
        var model = "<?= $model->getName() ?>";
    </script><?php
endif; ?>
<script type="text/javascript">
    var modelnames = <?php echo json_encode($modelnames); ?>;
</script>
<script type="text/javascript" src="<?= plugins_url() ?>/carranker-admin-pages/js/model.js?<?=
filemtime(dirname(__DIR__, 3) . '/js/model.js') ?>">
</script>
