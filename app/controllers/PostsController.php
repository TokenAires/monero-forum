<?php

class PostsController extends \BaseController {

	public function submit() {

		$validator = Post::validate(Input::all());

		$thread = Thread::findOrFail(Input::get('thread_id'));
		$thread_id = $thread->id;
		$thread_slug = $thread->slug();
		$forum_id = $thread->forum->id;
		$forum_slug = $thread->forum->slug();
		$posts = $thread->posts();

		if (!$validator->fails())
		{
			$post = new Post();
			$post->user_id = Auth::user()->id;
			$post->thread_id = Input::get('thread_id');
			$post->title = Input::get('title');
			$post->body = Input::get('body');

			if (Input::get('post_id', false))
				$post->parent_id = Input::get('post_id');

			$post->save();

			return Redirect::to($thread->permalink());
		}

		else
			return View::make('content.thread', array('errors' => $validator->messages()->all(), 'posts' => $posts, 'forum_id' => $forum_id, 'forum_slug' => $forum_slug, 'thread_id' => $thread_id, 'thread_slug' => $thread_slug));
	}

	public function delete($post_id) {
		$post = Post::findOrFail($post_id);

		if ($post->user_id == Auth::user()->id)
		{
			$post->delete();
			return 'true';
		}
		else {
			return 'false';
		}

	}

	public function update() {
		$validator = Post::validate(Input::all());

		$thread = Thread::findOrFail(Input::get('thread_id'));
		$thread_id = $thread->id;
		$thread_slug = $thread->slug();
		$forum_id = $thread->forum->id;
		$forum_slug = $thread->forum->slug();
		$posts = $thread->posts();

		if (!$validator->fails())
		{
			$post = Post::findOrFail(Input::get('post_id'));

			$post->title = Input::get('title');
			$post->body = Input::get('body');

			$post->save();

			return Redirect::to($thread->permalink());
		}

		else
			return View::make('content.thread', array('errors' => $validator->messages()->all(), 'posts' => $posts, 'forum_id' => $forum_id, 'forum_slug' => $forum_slug, 'thread_id' => $thread_id, 'thread_slug' => $thread_slug));
	}

	public function get($post_id) {
		$post = Post::find($post_id);
		if ($post)
			return $post->body;
		else
			return 'false';
	}

	//Standalone Pages

	public function getDeletePage($post_id) {

		$post = Post::findOrFail($post_id);
		$thread = $post->thread;

		if ($post->user_id == Auth::user()->id)
		{
			$post->delete();
			return Redirect::to($thread->permalink());

		}
		else {
			return View::make('errors.permissions');
		}

	}

	public function getReplyPage($post_id) {
		$post = Post::findOrFail($post_id);
		return View::make('content.reply', array('post' => $post));
	}

	public function getUpdatePage($post_id) {
		$post = Post::findOrFail($post_id);
		if ($post->user_id == Auth::user()->id)
		{
			return View::make('content.update', array('post' => $post));
		}
		else {
			return View::make('errors.permissions');
		}
	}

	public function getReportPage($post_id) {
		return View::make('content.report');
	}

	//Refresh and Load More

	public function listPosts($thread_id, $posts_num) {
		$thread = Thread::find($thread_id);
		
		$posts_list = '';

		if(!$thread) //check if thread exists.
			return 'false';
			
		else if ($posts_num == 'all')
			$posts_list .= display_posts(NULL, $thread_id, 0);

		else if ($posts_num)
			$posts_list .= display_posts(NULL, $thread_id, 0, $posts_num);

		else
			$posts_list = 'false';

		return $posts_list;
	}

}