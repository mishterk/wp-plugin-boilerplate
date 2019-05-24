<?php


namespace PdkPluginBoilerplate\Framework\PostTypes;


use PdkPluginBoilerplate\Framework\Container\Application;
use PdkPluginBoilerplate\Framework\Traits\DecoratesAndMutatesPostObject;


/**
 * Class PostTypeBase
 * @package PdkPluginBoilerplate\Framework\PostTypes
 *
 * @property int ID
 * @property string post_author
 * @property string post_date
 * @property string post_date_gmt
 * @property string post_content
 * @property string post_title
 * @property string post_excerpt
 * @property string post_status
 * @property string comment_status
 * @property string ping_status
 * @property string post_password
 * @property string post_name
 * @property string to_ping
 * @property string pinged
 * @property string post_modified
 * @property string post_modified_gmt
 * @property string post_content_filtered
 * @property int post_parent
 * @property string guid
 * @property int menu_order
 * @property string post_type
 * @property string post_mime_type
 * @property string comment_count
 */
abstract class PostTypeBase {


	use DecoratesAndMutatesPostObject;


	public static function find( $post_id ) {
		if ( is_null( $post = get_post( $post_id ) ) ) {
			return null;
		}

		$instance       = new static;
		$instance->post = $post;

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