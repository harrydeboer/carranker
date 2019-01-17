@if ($maxPages != 1)
    <ul class="pagination pagination-sm row justify-content-center">

        <?php $pageNumber = $thisPage - 1 < 1 ? 1 : $thisPage - 1; ?>
        <li {{ $thisPage == 1 ? 'class="disabled"' : '' }}>
            <a href="/model/{{ $model->getMakename() . '/' . $model->getName() . '?page=' . $pageNumber }}">«&nbsp;</a>
        </li>

        @for ($i = 1; $i <= $maxPages; $i++)
            <li {{ $thisPage == $i ? 'class="active"' : '' }}>
                <a href="/model/{{ $model->getMakename() . '/' . $model->getName() . '?page=' . $i }}">&nbsp;{{ $i }}&nbsp;</a>
            </li>
        @endfor

        <?php $pageNumber = $thisPage + 1 <= $maxPages ? $thisPage + 1 : $thisPage; ?>
        <li {{ $thisPage == $maxPages ? 'class="disabled"' : ''}}>
            <a href="/model/{{ $model->getMakename() . '/' . $model->getName() . '?page=' . $pageNumber }}">&nbsp;»</a>
        </li>
    </ul>
@endif
<section>
    <h3 class="col-md-12 row justify-content-center" id="reviewHeading">
        @if (count($reviews) > 1)
            Reviews:
        @else
            Review:
        @endif
    </h3>
</section>
<div class="row justify-content-center">
    @foreach ($reviews as $key => $review)
        <article class="reviewArticle col-md-7">
            <h4>{!! $review->getUser()->getUsername() . ' on ' . $review->getTrim()->getYearBegin() . '-' . $review->getTrim()->getYearEnd() .
                        ' ' . $review->getTrim()->getFramework() . ' ' . $review->getTrim()->getName() . ' with ' . number_format($review->getRating(), 1) !!}
                <span class="fa fa-star"></span> at {{ $review->getDate() }}</h4>
            {!! $review->getContent() !!}
        </article>
    @endforeach
</div>