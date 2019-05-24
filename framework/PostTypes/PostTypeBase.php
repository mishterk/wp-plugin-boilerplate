<?php


namespace PdkPluginBoilerplate\Framework\PostTypes;


use PdkPluginBoilerplate\Framework\Container\Application;
use PdkPluginBoilerplate\Framework\Traits\DecoratesAndMutatesPostObject;
use WP_Post;


/**
 * Class PostTypeBase
 * @package PdkPluginBoilerplate\Framework\PostTypes
 */
abstract class PostTypeBase {


	use DecoratesAndMutatesPostObject;


	/**
	 * Find a post by ID
	 *
	 * @param $post_id
	 *
	 * @return PostTypeBase|null
	 */
	public static function find( $post_id ) {
		$post = get_post( $post_id );

		return ( $post instanceof WP_Post )
			? self::make( $post )
			: null;
	}


	/**
	 * Decorate an existing post object
	 *
	 * @param WP_Post $post
	 *
	 * @return PostTypeBase
	 */
	public static function make( \WP_Post $post ) {
		$instance = new static;
		$instance->set_post_object( $post );

		return $instance;
	}


	/**
	 * Register this post type
	 */
	public function register() {
		register_post_type( $this->post_type, $this->get_args() );
	}


	/**
	 * Get post type args from config
	 *
	 * @return array
	 */
	public function get_args() {
		return Application::get_instance()['config']['post-types'][ $this->post_type ] ?? [];
	}


	/**
	 * Insert/update this post object
	 */
	public function save() {
		wp_insert_post( $this->post->to_array() );
	}


}