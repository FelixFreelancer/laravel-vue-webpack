<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
         data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            @foreach(config('admin_sidebar') as $key=>$value)
                @if(isset($value['value']))
                    <li class="m-menu__item  m-menu__item--submenu {!! route_to_reply_submenu($value['value'],'','m-menu__item--open') !!}"
                        aria-haspopup="true" data-menu-submenu-toggle="hover">
                        <a href="#" class="m-menu__link m-menu__toggle">
                            <i class="m-menu__link-icon flaticon-layers"></i>
                            <span class="m-menu__link-text">
                                    {!! $value['name'] !!}
                                </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="m-menu__submenu" {!! route_to_reply_submenu($value['value'],'','style="display: block;"') !!}>
                            <span class="m-menu__arrow"></span>
                            <ul class="m-menu__subnav">
                                <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                                        <span class="m-menu__link">
                                            <span class="m-menu__link-text">
                                                {!! $value['name'] !!}
                                            </span>
                                        </span>
                                </li>
                                @foreach($value['value'] as $submenu)
                                    @if(isset($submenu['roles']) && $submenu['roles']!='')
                                        @if(auth()->user()->hasAnyRole($submenu['roles']))
                                            <li class="m-menu__item m-menu__item--{!! route_to_reply_able($submenu['active_routes']) !!}"
                                                aria-haspopup="true">
                                                <a href="{!! url()->route($submenu['route']) !!}"
                                                   class="m-menu__link ">
                                                    <i class="{!! $submenu['icon'] !!} m-menu__link-icon">
                                                        <span></span>
                                                    </i>
                                                    <span class="m-menu__link-text">
                                                            {!! $submenu['name'] !!}
                                                        </span>
                                                </a>
                                            </li>
                                        @endif
                                    @else
                                        <li class="m-menu__item m-menu__item--{!! route_to_reply_able($submenu['active_routes']) !!}"
                                            aria-haspopup="true">
                                            <a href="{!! url()->route($submenu['route']) !!}" class="m-menu__link ">
                                                <i class="{!! $submenu['icon'] !!} m-menu__link-icon">
                                                    <span></span>
                                                </i>
                                                <span class="m-menu__link-text">
                                                            {!! $submenu['name'] !!}
                                                        </span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="m-menu__item  m-menu__item--{!! route_to_reply_able($value['active_routes']) !!}"
                        aria-haspopup="true">
                        <a href="{!! url()->route($value['route']) !!}" class="m-menu__link ">
                            <i class="m-menu__link-icon {!! $value['icon'] !!}"></i>
                            <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            {!! $value['name'] !!}
                                        </span>
                                        {{--<span class="m-menu__link-badge">
                                            <span class="m-badge m-badge--danger">
                                                2
                                            </span>
                                        </span>--}}
                                    </span>
                                </span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
