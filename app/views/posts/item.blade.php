<?php

try {


?>
<div class="post-indent">
	@if ($level % 2 == 0)
		<div id="post-{{ $post->id }}" class="post col-lg-12 level-{{ $level }}"
		     parents="{{ $serialized_bread }}"
		     head="{{ $head->id or "" }}"
		     children="{{ $children }}">
	@else
		<div id="post-{{ $post->id }}" class="post col-lg-12 odd level-{{ $level }}" parents="{{ $serialized_bread }}" head="{{ $head->id or "" }}" children=" {{ $children }}">
	@endif
			{{--Head at: {{ $head->id or "" }}--}}
			{{--Post id: {{ $post->id }}--}}
			{{--Parents: {{ $serialized_bread }}--}}
			{{--Children: {{ $children }}--}}
		@include('posts.includes.breadcrumbs')
		<div class="panel panel-default post-panel @if ($post->is_unread) post-unread @endif">
		  <div class="panel-heading">
		  			<img class="profile-picture-sm" src="/uploads/profile/small_{{ $post->user->profile_picture }}"><a class="user-post-{{$post->id}}" href="/user/{{ $post->user->username }}" target="_blank">{{ $post->user->username }}</a> <span class="mobile-hide-text">posted </span> <span class="date" data-toggle="tooltip" data-placement="top" title="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</span>
		  			<small>
		  			@if ($post->children()->count())
				    Replies: {{ $post->children()->count() }} |
					@endif
					Weight: {{ $post->weight }} | <a class="meta-permalink" href='{{ $post->thread->permalink()."?page=".Input::get('page')."&noscroll=1#post-".$post->id }}'>Link</a>
					</small>
					 <small class="content-control content-control-{{ $post->id }}">
						 @if ($post->weight < Config::get('app.hidden_weight'))
						 	<span onclick="content_show({{ $post->id }})">[ + ]</span>
						 @else
						 	<span onclick="content_hide({{ $post->id }})">[ - ]</span>
						 @endif
					 </small>
			  @include('posts.includes.controls')
		  </div>
		  @if ($post->weight < Config::get('app.hidden_weight'))
		  <div class="panel-body content-block content-block-{{ $post->id }} hidden-post-content" id="{{ $post->id }}">
		  @else
		  <div class="panel-body content-block content-block-{{ $post->id }}" id="{{ $post->id }}">
		  @endif
		    <div class="post-content-{{ $post->id }}">
				@if ($post->trashed())
				<p><em>[deleted]</em></p>
				@else
					@if(Auth::check() && $post->user_id == Auth::user()->id)
					<div class="markdown-inline-edit post-{{ $post->id }}-markdown-edit">
						{{ $post->body }}
					</div>
					@else
						{{ $post->body }}
					@endif
				@endif

				@if (Auth::check())
		  			<div class="mobile-meta-buttons">
						   @if (Auth::check())
							@if (Auth::check()) {{-- ends --}}
							<a href="/votes/vote/?post_id={{ $post->id }}&vote=insightful" class="disabled-link" onclick="vote({{ $post->id }}, 'insightful')">
								@if (Vote::voted_insightful($post->id))
								<button type="button" class="disabled btn btn-default btn-xs insightful-{{ $post->id }}"><span class="glyphicon glyphicon-thumbs-up"></span> </button>
								@else
								<button type="button" class="btn btn-default btn-xs insightful-{{ $post->id }}"><span class="glyphicon glyphicon-thumbs-up"></span> </button>
								@endif
							</a>
							<a href="/votes/vote/?post_id={{ $post->id }}&vote=irrelevant" class="disabled-link" onclick="vote({{ $post->id }}, 'irrelevant')">
								@if (Vote::voted_irrelevant($post->id))
								<button type="button" class="disabled btn btn-default btn-xs irrelevant-{{ $post->id }}"><span class="glyphicon glyphicon-thumbs-down"></span> </button>
								@else
								<button type="button" class="btn btn-default btn-xs irrelevant-{{ $post->id }}"><span class="glyphicon glyphicon-thumbs-down"></span> </button>
								@endif
							</a>
							@endif {{-- ends --}}
						  <a href="/posts/reply/{{ $post->id }}" class="post-action-btn"><button type="button" onclick="post_reply({{ $post->id }}, {{ $thread_id }}, '{{ $post->title }}')" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-arrow-left"></span>
			 </button></a>
			 			  @if ($post->user->id != Auth::user()->id) {{-- ends --}}
			 			  	@if (!Input::has('page'))
			 			  		<a href="/posts/report/{{ $post->id }}/1" class="post-action-btn"><button type="button" onclick="post_flag({{ $post->id }})" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-flag"></span>
			 			  	@else
			 			  		<a href="/posts/report/{{ $post->id }}/{{ Input::get('page') }}" class="post-action-btn"><button type="button" onclick="post_flag({{ $post->id }})" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-flag"></span>
			 				@endif
			 				</button></a>
			 			  @endif {{-- ends --}}
						  @if ($post->user->id == Auth::user()->id) {{-- ends --}}
						  <a href="/posts/update/{{ $post->id }}" class="post-action-btn"><button type="button" onclick="post_edit({{ $post->id }}, {{ $thread_id }}, '{{ $post->title }}')" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span>
			 </button></a>
						  <a class="post-action-btn" href="/posts/delete/page/{{ $post->id }}"><button type="button" onclick="post_delete({{ $post->id }})" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span>
			 </button></a>
			 			  @endif {{-- ends --}}
						  @endif
						  </div>
			   @endif
			  			@if ($post->children()->count()) {{-- ends --}}
			  				@if ($post->weight < Config::get('app.hidden_weight'))
			  				<span class="mobile-drawer drawer-button drawer-buttons-{{ $post->id }} pull-right" style="display: none; padding-left: 5px; padding-top: 3px;"><span onClick="drawer_open({{ $post->id }})" class="glyphicon glyphicon-collapse-down"></span></span>
							@else
							<span class="mobile-drawer drawer-button drawer-buttons-{{ $post->id }} pull-right" style="display: none; padding-left: 5px; padding-top: 3px;"><span onClick="drawer_close({{ $post->id }})" class="glyphicon glyphicon-collapse-up"></span></span>
							@endif
						@endif {{-- ends --}}
			</div>
		  </div>
		</div>
		@include('posts.includes.reply')
		@include('posts.includes.nest')
</div>
<?php

}
catch(Exception $e)
{
    var_dump($e->getMessage().$e->getLine());
}
?>
