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
<br><br><br><br><br><br><br><br><br><br><br><br><?php

if (isset($model)): ?>
    <button id="deleteModel">Delete</button>

    <div class="modal" tabindex="-1" role="dialog" id="realyDeleteModel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to remove this model, its trims and ratings?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="deleteModelForm">
                        <input type="hidden" value="<?= $model->getId() ?>" name="deleteModelId">
                        <input type="submit" value="Delete" class="btn btn-danger">
                        <input type="hidden" value="delete" name="carrankerAdminAction">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
