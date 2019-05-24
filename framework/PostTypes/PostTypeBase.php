<?php


namespace PdkPluginBoilerplate\Framework\PostTypes;


use PdkPluginBoilerplate\Framework\Container\Application;
use PdkPluginBoilerplate\Framework\Traits\DecoratesAndMutatesPostObject;


/**
 * Class PostTypeBase
 * @package PdkPluginBoilerplate\Framework\PostTypes
 */
abstract class PostTypeBase {


	use DecoratesAndMutatesPostObject;


	public static function find( $post_id ) {
		if ( is_null( $post = get_post( $post_id ) ) ) {
			return null;
		}

		$instance = new static;
		$instance->set_post_object( $post );

		return $instance;
	}


	public function register() {
		register_post_type( $this->post_type, $this->get_args() );
	}


	public function get_args() {
		return Application::get_instance()['config']['post-types'][ $this->post_type ] ?? [];
	}


	public function save() {
		wp_insert_post( $this->post->to_array() );
	}


}