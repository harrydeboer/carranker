<div class="navbar navbar-toggleable-md navbar-light bg-faded navbar-expand-lg">
    <button class="navbar-toggler navbar-toggler-right"
            type="button"
            data-toggle="collapse"
            data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbarCollapse">
        <ul class="nav navbar-nav" id="navigationAdmin">
            <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li><a href="{{ route('admin.reviews') }}">Reviews</a></li>
            <li><a href="{{ route('admin.mail.users') }}">Mail Users</a></li>
        </ul>
    </div>
</div>