<h1>Trim</h1>
<form method="get" id="trimsForm" action="admin.php">
    <input type="hidden" value="trim-admin-page" name="page">
    <select name="makename" id="selectMakes"><option value="">Make</option><?php
        foreach ($makenames as $makename) {
            if (isset($model) && $makename === $model->getMake()) {
                echo '<option value="' . $makename . '" selected>' . $makename . '</option>';
            } else {
                echo '<option value="' . $makename . '">' . $makename . '</option>';
            }
        } ?>
    </select>
    <select name="modelname" id="selectModels"><option value="">New Model</option>
    </select>
    <?php if (isset($generationsSeriesTrims)): ?>
        <select id="selectGeneration">
            <option value="">Generation</option>
            <?php foreach($generationsSeriesTrims as $keyGen => $generation) {
                if (isset($generationTrim) && $keyGen === $generationTrim) {
                    echo '<option value="' . $keyGen . '" selected>' . $keyGen . '</option>';
                } else {
                    echo '<option value="' . $keyGen . '">' . $keyGen . '</option>';
                }
            } ?>
        </select>
        <select id="selectSerie" name="serieTrimId">
            <?php if (isset($hasTrimTypes) && $hasTrimTypes): ?>
                <option value="">Serie</option>
            <?php else: ?>
                <option value="">New Serie</option>
            <?php endif ?>
        </select>
        <?php if (isset($hasTrimTypes) && $hasTrimTypes) { ?>
            <select id="selectTrimType" name="trimTypeId">
                <option value="">New Trim Type</option>
            </select>
        <?php }?>
    <?php endif ?>
</form>
<?php require_once dirname(__DIR__) . '/form.php' ?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<form method="post" id="deleteTrimForm">
    <input type="hidden" value="<?= isset($trim) ? $trim->getId() : '' ?>" name="deleteTrimId">
    <input type="submit" value="Delete">
    <input type="hidden" value="delete" name="carrankerAdminAction">
</form>
<?php
if (isset($model)): ?>
    <script type="text/javascript">
        var model = "<?= $model->getName() ?>";
        var make = "<?= $model->getMake() ?>";
        var trimIdSelect = <?= isset($trim) ? $trim->getId() : 0 ?>;
        var generationsSeriesTrims = <?= isset($generationsSeriesTrims) ? json_encode($generationsSeriesTrims) : '' ?>;
        var hasTrimTypes = <?= isset($hasTrimTypes) ? $hasTrimTypes : 0 ?>;
    </script><?php
endif;

if (isset($hasTrimTypes)): ?>
    <script type="text/javascript">
        var hasTrimTypes = <?= $hasTrimTypes ?>;
    </script><?php
endif; ?>
<script type="text/javascript">
    var modelnames = <?php echo json_encode($modelnames); ?>;
</script>
<script type="text/javascript" src="<?= plugins_url() ?>/carranker-admin-pages/js/model.js?<?=
filemtime(dirname(__DIR__, 3) . '/js/model.js') ?>">
</script>
<script type="text/javascript" src="<?= plugins_url() ?>/carranker-admin-pages/js/trim.js?<?=
filemtime(dirname(__DIR__, 3) . '/js/trim.js') ?>">
</script>
