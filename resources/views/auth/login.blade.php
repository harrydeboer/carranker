<div class="row justify-content-center">
    <div class="col-md-8 col-md-offset-2">
        {!! $page->getContent() ?? '' !!}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::open(['route' => 'loginattempt', 'id' => 'login-form']) !!}
        {!! Form::label('user_email', 'Email', ['class' => 'control-label']) !!}
        {!! Form::email('user_email', null, ['class' => 'form-control', 'required']) !!}
        {!! Form::label('password', 'Password', ['class' => 'control-label']) !!}
        {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Login">
        </div>
        Not registered yet? Go to <a href="/register">this</a> link.
        {!! Form::close() !!}
    </div>
</div>