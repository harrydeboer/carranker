@if ($errors->any())
    <div class="alert alert-danger">
        <ul id="errorDisplay">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif