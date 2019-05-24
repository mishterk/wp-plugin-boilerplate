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


	public function set_post_excerpt_attribute( $value ) {
		$this->post->post_excerpt = $value . '-appended';
	}


	public function set_overloaded_attribute( $value ) {
		$this->post->overloaded = $value . '-appended';
	}


	// We need this getter on an overloaded property to avoid errors where an object returned from the DB doesn't
	// have the property set.
	public function get_overloaded_attribute( $value ) {
		return $value;
	}


}