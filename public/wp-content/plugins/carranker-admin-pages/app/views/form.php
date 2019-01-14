<?php
foreach ($form->errors as $error):
    echo '<h3>' . $error . '</h3>';
endforeach; ?>
<form method="post" id="createUpdateForm">
    <table>
        <?php foreach ($form->textFields as $key => $field): ?>
            <tr><td><label><?= $key ?></label></td><td>
                    <input type="text" name="<?= $key ?>" style="width:400px;" value="<?= $field ?>"></td></tr>
        <?php endforeach;
        foreach ($form->selectFields as $key => $field): ?>
            <tr><td><label><?= $key ?></label></td><td>
                    <select name="<?= $key ?>" <?= $key === 'make' || $key === 'model' ? 'id="' . $key . 'Select"': '' ?>>
                        <?php foreach ($form->selectChoices[$key] as $choice):
                            if ($choice === $field): ?>
                                <option value="<?= $choice ?>" selected><?= $choice ?></option>
                            <?php
                            else: ?>
                                <option value="<?= $choice ?>"><?= $choice ?></option><?php
                            endif;
                        endforeach; ?>
                    </select></td></tr>
        <?php endforeach;
        foreach ($form->integerFields as $key => $field): ?>
            <tr><td><label><?= $key ?></label></td><td>
                    <input type="number" step="1" name="<?= $key ?>" style="width:400px;" value="<?= $field ?>"></td></tr>
        <?php endforeach;
        foreach ($form->floatFields as $key => $field): ?>
            <tr><td><label><?= $key ?></label></td><td>
                    <input type="number" step=any name="<?= $key ?>" style="width:400px;" value="<?= $field ?>"></td></tr>
        <?php endforeach;
        if ($form->hasContentField): ?>
            <tr><td><label>Content</label></td><td><?php wp_editor('', 'carranker_admin_pages_content',
                        ['textarea_name' => 'content', 'media_buttons' => false]) ?></td></tr>
        <?php endif; ?>
    </table>
    <?php foreach ($form->hiddenFields as $key => $field): ?>
        <input type="hidden" name="<?= $key ?>" value="<?= $field ?>">
    <?php endforeach; ?>
    <input type="submit" value="<?= $form->hiddenFields['carrankerAdminAction'] === 'update' ? 'Update' : 'Create' ?>">
</form><?php
if ($form->hasContentField): ?>
<script>
    jQuery("#carranker_admin_pages_content").val(<?= json_encode($form->contentField) ?>);
</script><?php
endif;