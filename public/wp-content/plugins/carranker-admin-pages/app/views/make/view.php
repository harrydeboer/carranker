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
<br><br><br><br><br><br><br><br><br><br><br><br><?php
if (isset($make)): ?>
    <button id="deleteMake">Delete</button>

    <div class="modal" tabindex="-1" role="dialog" id="realyDeleteMake">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure you want to remove this make, its models, trims and ratings?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="deleteMakeForm">
                    <input type="hidden" value="<?= $make->getId() ?>" name="deleteMakeId">
                    <input type="submit" value="Delete" class="btn btn-danger">
                    <input type="hidden" value="delete" name="carrankerAdminAction">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    </div><?php
endif; ?>
<script type="text/javascript" src="<?= plugins_url() ?>/carranker-admin-pages/js/make.js?<?=
filemtime(dirname(__DIR__, 3) . '/js/make.js') ?>">
</script>