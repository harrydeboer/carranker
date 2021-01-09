@extends('admin.layout')

@section('content')
    @if (count($reviews) > 0)
        @include('errors.errors')
        @foreach ($reviews as $review)
            <div class="row">
                <div class="col-md-2 reviewName">{{ $review->getUser()->getName() }}</div>
                <div class="col-md-6 reviewContent">{{ $review->getContent() }}</div>
                <div class="col-md-2"><form method="post" action="{{ route('admin.reviews.approve') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $review->getId() }}">
                        <input type="submit" class="btn btn-primary" value="Approve">
                    </form>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger deleteReviewButton deleteButton"
                            data-action="{{ route('admin.reviews.delete') }}"
                            data-id="{{ $review->getId() }}">Delete</button>
                </div>
            </div>
        @endforeach
        {!! $links !!}
    @else
        <section class="col-md-12 row justify-content-center text-center">
            <h3 class="col-md-6" id="reviewHeading">No pending reviews.</h3>
        </section>
    @endif
@endsection