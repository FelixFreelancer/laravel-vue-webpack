@extends('frontend.layouts.blog')

@section('contentHeader')

@stop

@section('content')
    <div class="post">
        <div class="post__cover"><img src="img/temp-parcel.jpg" alt="Parcel"/></div>
        <div class="post__header">
            <div class="post__info">
                <h3 class="post__title">Lorem ipsum dolor sit amet, ea rationibus.</h3>
                <div class="viewmore__header viewmore__header--post">
                    <div class="vdate"><a href="javascript:;">February 14, 2018</a><a href="javascript:;">John</a>
                    </div>
                    <a class="vcomments" href="javascript:;"><i class="fas fa-comment"></i><span>50 comments</span></a>
                </div>
            </div>
            <div class="post__share"><span>Share:</span>
                <ul class="ul-reset social-share">
                    <li><a href="javascript:;"><img src="{!! asset('img/facebook.png') !!}" alt="Facebook"/></a></li>
                    <li><a href="javascript:;"><img src="{!! asset('img/twitter.png') !!}" alt="Twitter"/></a></li>
                    <li><a href="javascript:;"><img src="{!! asset('img/linkedin.png') !!}" alt="Linkedin"/></a></li>
                </ul>
            </div>
        </div>
        <div class="post__content">
            <p class="post__text">Lorem ipsum dolor sit amet, ea rationibus deseruisse eum. Sed paulo facilisis
                constituto ei, ne dico perpetua reformidans cum, ea vim autem timeam scripserit. Facete melius at
                vim. Vel enim prompta eu, sit alii impetus cu.</p>
            <p class="post__text">Te impedit deterruisset consectetuer mei, menandri sapientem at sit. Aliquid
                salutandi id sed, no quo dolore scripta. Ut saepe vivendum vel, malorum maiorum argumentum has te.
                Ea nec percipit periculis. Has an scripta aperiri hendrerit. Vim summo feugait corrumpit cu, eum
                suas graeco an, alienum convenire tractatos an mea. At pro iudico blandit, causae aperiam et
                usu.</p>
            <p class="post__text">At dolor maiestatis usu, et soleat dolorum assueverit ius. Cibo partiendo eu mea,
                id summo indoctum per. Eam cetero sadipscing voluptatibus ei. Fugit mentitum officiis eos cu, dictas
                aliquip per ex. Ius no graece recteque, iusto accommodare ea vix. Iusto sanctus et vis. Cum autem
                aliquip eleifend ea, esse exerci habemus cum et.</p>
        </div>
    </div>
    <h3 class="related-title">Related Posts</h3>
    <ul class="blog-list ul-reset blog-list--related">
        <li><a class="blog-list__wrap b-item card-shadow" href="javacript:;">
                <div class="b-item__cover"><img src="{!! asset('img/temp-parcel.jpg') !!}" alt="Parcel"/>
                    <h3 class="b-item__title">Happy customer repeatable client</h3>
                </div>
            </a></li>
        <li><a class="blog-list__wrap b-item card-shadow" href="javacript:;">
                <div class="b-item__cover"><img src="{!! asset('img/temp-parcel.jpg') !!}" alt="Parcel"/>
                    <h3 class="b-item__title">Happy customer repeatable client</h3>
                </div>
            </a></li>
        <li><a class="blog-list__wrap b-item card-shadow" href="javacript:;">
                <div class="b-item__cover"><img src="{!! asset('img/temp-parcel.jpg') !!}" alt="Parcel"/>
                    <h3 class="b-item__title">Happy customer repeatable client</h3>
                </div>
            </a></li>
        <li><a class="blog-list__wrap b-item card-shadow" href="javacript:;">
                <div class="b-item__cover"><img src="{!! asset('img/temp-parcel.jpg') !!}" alt="Parcel"/>
                    <h3 class="b-item__title">Happy customer repeatable client</h3>
                </div>
            </a></li>
    </ul>
@stop

@section('contentFooter')

@stop