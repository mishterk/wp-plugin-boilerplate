<?php


namespace PdkPluginBoilerplate\Framework\Traits;


/**
 * Trait DecoratesPostObject
 * @package PdkPluginBoilerplate\Framework\Traits
 *
 * This is a straight-up decorator for the WP_Post object that allows direct access to the decorated
 * object's properties as though they we a member of this same object. e.g;
 *
 *      $post = new WP_Post();
 *      $post->post_title = 'The title';
 *
 *      $decorator = new UsesDecoratesPostObject();
 *      $decorator->set_post_object( $post );
 *
 *      echo $decorator->post_title; // 'The title'
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
trait DecoratesPostObject {


	/**
	 * @var \WP_Post
	 */
	protected $post;


	public function __set( $name, $value ) {
		if ( ! property_exists( $this->post, $name ) ) {
			$trace = debug_backtrace();
			trigger_error(
				'Undefined property via __set(): ' . $name .
				' in ' . $trace[0]['file'] .
				' on line ' . $trace[0]['line'],
				E_USER_NOTICE );
		}

		$this->post->$name = $value;
	}


	public function __get( $name ) {
		if ( property_exists( $this->post, $name ) ) {
			return $this->post->$name;
		}

		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE );

		return null;
	}


	public function __isset( $name ) {
		return isset( $this->post->$name ); // todo - test
	}


	public function __unset( $name ) {
		unset( $this->post->$name ); // todo - test
	}


	public function __call( $name, $arguments ) {
		// TODO: Implement __call() method.
		// todo - call WP_Post method unless defined in current class
	}


	protected function set_post_object( \WP_Post $post ) {
		$this->post = $post;
	}


}