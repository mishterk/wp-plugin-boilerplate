<?php


namespace PdkPluginBoilerplate\Framework\PostTypes;


use PdkPluginBoilerplate\Framework\Container\Application;


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


	/**
	 * @var \WP_Post
	 */
	protected $post;


	public function __set( $name, $value ) {
		// todo - setter mutator
		$this->post->$name = $value;
	}


	public function __get( $name ) {
		if ( property_exists( $this->post, $name ) or $this->has_get_mutator( $name ) ) {
			return $this->apply_get_mutator( $name, $this->post->$name );
		}

		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE );

		return null;
	}


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


	protected function apply_get_mutator( $name, $value ) {
		return $this->has_get_mutator( $name )
			? $this->{"get_{$name}"}( $value )
			: $value;
	}


	protected function has_get_mutator( $name ) {
		return method_exists( $this, "get_{$name}" );
	}


}