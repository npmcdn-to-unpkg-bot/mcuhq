@extends('layouts.sidebar')

@section('header')
    <header class="main-header">
        <div class="container">

            <ol class="breadcrumb ">
                <li><a href="/">Home</a></li>
                <li><a href="/vendors/{{$vendor->slug}}">{{$vendor->name}}</a></li>
                <li class="active">{{ $post->title }}</li>
            </ol>
        </div>
    </header>



@endsection


@section('center')

    <div class="panel-default post-show">
        <div class="panel-title">
            <h2>
                @if($post)
                    {{{ $post->title }}}
                    @if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin()))
                        <button class="btn" style="float: right"><a href="{{ url('edit/'.$post->id .'/'.$post->slug)}}">Edit Post</a>
                        </button>
                    @endif
                @else
                    Page does not exist
                @endif

            </h2>
            <p>{{ $post->created_at->format('M d,Y') }} By <a
                        href="{{ url('/user/'.$post->author_id)}}">{{{ $post->author->name }}}</a></p>
        </div>
        <div class="panel-body">
            @if($post)
                <div>
                    {!! $post->body_html !!}
                </div>
                <div>
                    <h2>Leave a comment</h2>
                </div>
                @if(Auth::guest())
                    <p>Login to Comment</p>
                @else
                    <div class="panel-body">
                        <form method="post" action="/comment/add">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="on_post" value="{{ $post->id }}">
                            <input type="hidden" name="slug" value="{{{ $post->slug }}}">

                            <div class="form-group">
                                <textarea required="required" placeholder="Enter comment here" name="body"
                                          class="form-control"></textarea>
                            </div>
                            <input type="submit" name='post_comment' class="btn btn-success" value="Post"/>
                        </form>
                    </div>
                @endif
                <div id="comments">
                    @if($comments)
                        <ul style="list-style: none; padding: 0">
                            @foreach($comments as $comment)
                                <li class="panel-body">
                                    <div class="list-group">
                                        <div class="list-group-item author-name">
                                            <h3>{{{ $comment->author->name }}}</h3>

                                            <p>{{ $comment->created_at->format('M d,Y \a\t h:i a') }}</p>
                                        </div>
                                        <div class="list-group-item">
                                            <p>{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @else
                404 error
            @endif

        </div>
    </div>
@endsection

@section('right_sidebar')
    <div class="">
        <button type="button" class="btn btn-block btn-ar btn-primary">Download Source</button>
        <div class="panel-item">
                <ul class="list-unstyled">
                    <li><strong>Author:</strong> <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></li>
                    <li><strong>Created:</strong> {{ $post->created_at->format('M d,Y') }}</li>
                    <li><strong>Updated:</strong> {{ $post->updated_at->format('M d,Y') }}</li>
                    @if(($post->more_info_link) != "")
                    <li><strong>Follow: </strong><a href="{{$post->more_info_link}}">{{$post->more_info_link}}</a></li>
                    @endif
                    <li><strong>Views:</strong> {{$post->view_counter}}</li>
                    <li><strong>Comments:</strong> <a href="#comments">{{$post->comments->count()}}</a></li>
                </ul>
            <hr>
            <ul class="list-unstyled">
                <li><strong>Micro:</strong> <a href="{{url('vendors/'.$vendor->slug.'/?mcu='.$mcu->slug)}}">{{$mcu->name}}</a></li>
                <li><strong>Vendor:</strong> <a href="{{url('vendors/'.$vendor->slug)}}">{{$vendor->name}}</a></li>
                <li><strong>Arch:</strong> {{{$arch->name}}}</li>
                @if(isset($languages))
                <li><strong>Language(s): </strong>
                    @foreach($languages as $language)

                        <a href="{{url('vendors/'.$vendor->slug.'/?lan='.$language->slug)}}">{{{$language->name}}}</a>
                    @endforeach
                </li>
                @endif
                @if(isset($compiler))
                <li><strong>Compiler:</strong>
                        <a href="{{url('vendors/'.$vendor->slug.'/?compiler='.$compiler->slug)}}">{{{$compiler->name}}}</a>
                </li>
                @endif
                <li><strong>Categories:</strong>
                    @foreach($categories as $cat)

                    <a href="{{url('categories/'.$cat->slug)}}">{{{ $cat->name }}}</a>
                    @endforeach

                </li>

            </ul>
            <div class="tags-cloud">
                @foreach($post->tags as $tag)
                    <a href="{{url('tags/'.$tag->slug)}}" class="tag">{{{strtolower($tag->name)}}}</a>
                @endforeach
            </div>

            <h3 class="section-title">Related</h3>
            <ul class="list-unstyled related">
                @foreach($related as $post)
                    <li><a href="{{url($post->id.'/'.$post->slug)}}">{{{$post->title}}}</a></li>
                @endforeach
            </ul>

        </div>
    </div>
@endsection

@section('script')
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-lightbox/0.7.0/bootstrap-lightbox.min.js"></script>--}}

    <script>
        // So to make any large images fit inside viewing area
        $( ".main-content img" ).addClass( "img-responsive img-thumbnail center-block" );
        //$( "img" ).wrap("<a href='" + $("img").closest("img").attr("src") + "'>");

        $('.main-content').find('img').each(function() {
            //for each img add the width plus a specific value, in this case 20
            $( this ).wrap("<a href='" + $(this).attr("src") + "' " + " data-toggle='lightbox'>");

        });


    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.js"></script>

    <script>

        // delegate calls to data-toggle="lightbox"
        $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
            event.preventDefault();
            return $(this).ekkoLightbox({
                onShown: function() {
                    if (window.console) {
                        return console.log('onShown event fired');
                    }
                },
                onContentLoaded: function() {
                    if (window.console) {
                        return console.log('onContentLoaded event fired');
                    }
                },
                onNavigate: function(direction, itemIndex) {
                    if (window.console) {
                        return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                    }
                }
            });
        });
    </script>


@endsection