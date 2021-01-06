<script src="{{ fileUrl('/js/app.min.js') }}"></script>
@if (env('APP_ENV') === 'local')
    <script src="{{ fileUrl('/js/main.js') }}"></script>
    <script src="{{ fileUrl('/js/' . $controller . '.js') }}"></script>
@else
    <script src="{{ fileUrl('/js/main.min.js') }}"></script>
    <script src="{{ fileUrl('/js/' . $controller . '.min.js') }}"></script>
@endif

