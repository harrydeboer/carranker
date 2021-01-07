@extends('admin.layout')

@section('content')
    @if (count($reviews) > 0)
        @include('errors.errors')
        <table>
            @foreach ($reviews as $review)
                <tr><td>{{ $review->getUser()->getName() }}</td><td>{{ $review->getContent() }}</td><td>
                        <form method="post" action="{{ route('admin.reviews.approve') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $review->getId() }}">
                            <input type="submit" class="btn btn-primary" value="Approve">
                        </form>
                    </td><td>
                        <form method="post" action="{{ route('admin.reviews.delete') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $review->getId() }}">
                            <input type="submit" class="btn btn-danger" value="Delete">
                        </form>
                    </td></tr>
            @endforeach
        </table>
        {!! $links !!}
    @else
        <section class="col-md-12 row justify-content-center text-center">
            <h3 class="col-md-6" id="reviewHeading">No pending reviews.</h3>
        </section>
    @endif
@endsection