<?php declare(strict_types=1) ?>

<div class="modal" id="really-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Do you really want to delete this item?</h3>
            </div>
            <form method="post" action="" id="delete-form">
                @csrf
                <input type="hidden" name="id" id="delete-id">
                <div class="text-center">
                    <input type="submit" class="btn btn-danger" id="delete-submit" value="Delete">
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ fileUrl('/js/app.min.js') }}"></script>
@if (env('APP_ENV') === 'local')
    <script src="{{ fileUrl('/js/' . $controller . '.js') }}"></script>
@else
    <script src="{{ fileUrl('/js/' . $controller . '.min.js') }}"></script>
@endif
