<?php


namespace PdkPluginBoilerplate\Tests\Mocks\PostType;


use PdkPluginBoilerplate\Framework\PostTypes\PostTypeBase;


class PostType extends PostTypeBase {


	protected $post_type = 'post';


	public function get_post_title( $value ) {
		$value .= '-appended';

		return $value;
	}


	public function get_some_random_prop( $value ) {
		return 'custom value';
	}


}