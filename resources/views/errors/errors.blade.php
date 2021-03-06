<?php declare(strict_types=1) ?>

@if ($errors->any())
  <div class="alert alert-danger">
    <ul id="error-display">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
