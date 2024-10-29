<?php
/**
 * @package Adwords-Remarketing (wordpress)
 * @version 0.1.2
 */
/*
Plugin Name: Adwords-Remarketing
Plugin URI: http://de.online-solution.biz/blog/
Description: Embed specific Remarketing Code (from Google Adwords) to your wordpress posts or pages for retargeting.
Author: Andreas Rabuser
Version: 0.1.2
Author URI: http://www.online-solution.org/
License: GPL2
*/
function call_wpAdwordsRemarketing() 
{
    return new wpAdwordsRemarketing();
}
call_wpAdwordsRemarketing();
class wpAdwordsRemarketing
{
	public function __construct()
	{
		add_action('add_meta_boxes',array(&$this,'add_adwordsremarketing_meta_box'));
		add_action('save_post',array(&$this,'save_adwordsremarketing_meta_box_content'));
		add_action('wp_footer',array(&$this,'embed_adwordsremarketing_meta_box_content'));
	}
	public function add_adwordsremarketing_meta_box()
	{
		add_meta_box
		(
			'adwordsremarketing_meta_box_name',
			'Adwords Remarketing',
			array(&$this,'render_adwordsremarketing_meta_box_content'),
			'post',
			'normal',
			'low'
		);
		add_meta_box
		(
			'adwordsremarketing_meta_box_name',
			'Adwords Remarketing',
			array(&$this,'render_adwordsremarketing_meta_box_content'),
			'page',
			'normal',
			'low'
		);
	}
	public function render_adwordsremarketing_meta_box_content($post) 
	{
		$out='<label for="myplugin_new_field">Remarketing Code or Image URL</label> ';
		$out.='<input type="text" id="input_adwordsremarketing" name="input_adwordsremarketing" value="'.(get_post_meta($post->ID,'input_adwordsremarketing',true)).'" size="50" />';
		$out.=' <small>copy\'n\'paste from Google Adwords</small>';
		echo($out);
		return $out;
	}
	public function save_adwordsremarketing_meta_box_content($postid) 
	{
		if(defined('DOING_AUTOSAVE')&& DOING_AUTOSAVE)
			return;
		$code=$_POST['input_adwordsremarketing'];
		$code=stripslashes($code);
		$code=preg_replace('^.*<img.*src="^is','',$code);
		$code=preg_replace('^["|\'].*^is','',$code);
		add_post_meta($postid,'input_adwordsremarketing',$code,1);
		update_post_meta($postid,'input_adwordsremarketing',$code);
		return $code;
	}
	public function embed_adwordsremarketing_meta_box_content($post)
	{
		if(is_page() OR is_single() OR is_singular())
		{
			$postid=@$_REQUEST['p']; 
			GLOBAL $post;if(!COUNT(@$_REQUEST) AND $post AND $post->ID)$postid=$post->ID;
			if($postid)
				$code=get_post_meta($post->ID,'input_adwordsremarketing',true);
		}
		if($code)
			$code='<img src="'.$code.'" alt="" height="1" width="1" style="border-style:none;" />';
		else
			$code='';
		echo($code);
		return $code;
	}
}
?>
