@foreach($quick_actions as $key=>$value)
	<li class="m-nav__item">
		<a href="{!! url()->route($value['route']) !!}" class="m-nav__link">
			<i class="{!! $value['icon'] !!}"></i>
			<span class="m-nav__link-text">
                {!! $value['name'] !!}
            </span>
		</a>
	</li>
@endforeach