<div class="news-wrap">
    <div class="news__item">
        <div class="news__info n-info">
            <div class="n-info__icon">
                {!! $img !!}
            </div>
            <div class="n-info__detail">
                <h3 class="n-info__title">{!! $title !!}</h3>
                <div class="n-info__date"><span>{!! $date !!}</span><span>{!! $user !!}</span></div>
            </div>
        </div>
        <div class="news__desc">
            <p>{!! $desc !!}</p>
        </div>
        <div class="news__action">
            <a class="button button--accent" href="{!! $url !!}" title="Read More">Read More</a>
        </div>
    </div>
</div>