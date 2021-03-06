<?php 

namespace Comment\Lib;

use Comment\Model\Comments as Comment;

class Helper
{
	public static function single_comment($comment)
	{
		$s_comment = '';
		$user_class = ($comment['user_id'] != null) ? " user_comment" : "";
		$s_comment .= '<li id="comment-'. $comment['id'] .'" class="single_comment'. $user_class .'">';
		if ($comment['user_id'] != null) {
			$user = \Sentry::getUserProvider()->findById($comment['user_id']);
			$author_name = $user->first_name . ' ' . $user->last_name; 
			$author_email = $user->email;
			$author_link = rbUrl('user/profile/'.$user->id);
		} else {
			$author_name = $comment['name'];
			$author_email = $comment['email'];
			$author_link = $comment['url'];
		}
		$s_comment .= '<div class="author_info">';
		$default_img = assetPath('img', 'comment').'default_avatar.jpg';

		$s_comment .= '<div class="gravi">';
		if (checkOnline()) {
			$s_comment .= gravatar($author_email, \Setting::get('comment_gravatar_size'), $author_name);
		} else {
			$s_comment .= '<img src="'. $default_img .'" alt="'. $author_name .'" width="'.\Setting::get('comment_gravatar_size').'px" />';
		}
		$s_comment .= '</div>';
		
		$s_comment .= '<span class="author_name" id="comment_'.$comment['id'].'_author_name">';
		if (!empty($author_link)) {
			$s_comment .= '<a href="'. $author_link .'">'. $author_name .'</a>';
		} else {
			$s_comment .= $author_name;
		}
		$s_comment .= '</span>'; //end of author_name
		$s_comment .= '</div>'; //end of author_info
		$s_comment .= '<div class="comment_body">';
		$s_comment .= '<div class="comment_date">'. $comment['created_at'] .'</div>';
		$s_comment .= '<a href="#comment_form_wrapper" class="reply_link" onclick="setParentId('.$comment['id'].')">'. t('comment::comment.reply') .'</a>';
		$s_comment .= '<p class="comment_message">'.$comment['value'].'</p>';
		$s_comment .= '</div>'; //end of comment_body
		$s_comment .= '</li>'; // end of comment_wrapper

		return $s_comment;
	}

	public static function get_children($children)
	{
		$cc = '';
		$cc .= '<ul class="children" style="list-style:none;">';
		foreach ($children as $comment) {
			$cc .= self::single_comment($comment);
			if (isset($comment['children'])) {
				$cc .= self::get_children($comment['children']);
			}
		}
		$cc .= '</ul>';

		return $cc;
	}

	public static function getContentTitle($id, $content_type, $title_field = 'title')
	{
		$title = \DB::table($content_type)->where('id' , $id)->pluck($title_field);
    	return $title;
	}

	public static function getLatestComments($count) {

		$comments = Comment::where('status', '!=', 'spam')
							->orderBy('created_at', 'desc')
							->take($count)
							->get();
		$com = array();
							
		foreach ($comments as $comment) {
			if ($comment->name == null) {
				$user = \Sentry::getUserProvider()->findById($comment->user_id);
				$comment->name = $user->first_name.' '.$user->last_name;
			}
			$comment->content_title = self::getContentTitle($comment->content_id, $comment->module, $comment->content_title_field);
			$com[] = $comment;
		}
		return $com;
	}
}