@extends('master')

@section('content')
	<div class="col-lg-12">
	<h1>{{{ Thread::findOrFail($thread_id)->name }}}</h1>
	<p class="post-meta"><a href="/user/{{ $posts[0]->user->id }}" target="_blank">{{{ $posts[0]->user->username }}}</a> posted this on {{ $posts[0]->created_at }}</p>
	{{ Markdown::string(e($posts[0]->body)) }}
	<hr>
	<button type="submit" class="btn btn-success full-width reply-thread" onclick="thread_reply()">Reply to this thread</button>
	<div class="reply-box" style="display: none;">
		<form role="form" action="/posts/submit" method="POST">
		<input type="hidden" name="thread_id" value="{{ $thread_id }}">
		  <div class="form-group">
		    <input type="text" class="form-control" name="title" value="Re: {{ Thread::findOrFail($thread_id)->name }}">
		  </div>
		  <div class="form-group">
		  	<textarea class="form-control" name="body" rows="6" placeholder="Your insightful masterpiece goes here..."></textarea>
		  </div>
		  <button type="submit" class="btn btn-success">Submit Reply</button>
		  <button type="button" onclick="cancel_thread_reply()" class="btn btn-danger reply-cancel">Cancel</button>
		</form>
	</div>
	</div>
	{{ NULL; unset($posts[0]) }}
	<div class="col-lg-12 replies-list">
		<h3 class="pull-left">Replies: {{ $posts->count() }}</h3>
		<button class="btn btn-default pull-right">Load More</button>
	</div>
	
	@foreach ($posts as $post)
	<div id="post-{{ $post->id }}" class="post col-lg-12">
			<h4>{{ $post->title }}</h4>
			<p class="post-meta"><a href="/user/{{ $post->user->id }}" target="_blank">{{ $post->user->username }}</a> posted this on {{ $post->created_at }}</p>
			{{ Markdown::string(e($post->body)) }}
			<div class="btn-group btn-group-sm post-buttons">
			  <button type="button" onclick="post_reply({{ $post->id }}, {{ $thread_id }}, '{{ $post->title }}')" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span>
 Reply</button>
 			  @if ($post->user->id != Auth::user()->id)
			  <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-flag"></span>
 Flag</button>
 			  @endif
			  @if ($post->user->id == Auth::user()->id)
			  <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span>
 Edit</button>
			  <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span>
 Delete</button>
			  @endif
			</div>
	</div>
	{{ NULL; display_children($post->id, $thread_id, 0) }}
	@endforeach
	<hr>
	@if(isset($errors) && sizeof($errors) > 0)
	<div class="alert alert-danger alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	  @foreach ($errors as $error)
	   {{{ $error }}}<br>
	  @endforeach
	</div>
	@endif
@stop 

@section('javascript')
{{ HTML::script('js/posts.js') }}
@stop