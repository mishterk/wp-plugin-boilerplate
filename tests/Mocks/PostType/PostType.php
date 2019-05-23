<?php


namespace PdkPluginBoilerplate\Tests\Mocks\PostType;


use PdkPluginBoilerplate\Framework\PostTypes\PostTypeBase;


class PostType extends PostTypeBase {


	protected $post_type = 'post';


	public function get_post_title_attribute( $value ) {
		$value .= '-appended';

		return $value;
	}


	public function get_some_random_prop_attribute( $value ) {
		return 'custom value';
	}


}