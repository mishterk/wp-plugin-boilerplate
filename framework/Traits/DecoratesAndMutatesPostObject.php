<?php


namespace PdkPluginBoilerplate\Framework\Traits;


/**
 * Trait DecoratesAndMutatesPostObject
 * @package PdkPluginBoilerplate\Framework\Traits
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
trait DecoratesAndMutatesPostObject {


	/**
	 * @var \WP_Post
	 */
	protected $post;


	public function __set( $name, $value ) {

		if ( $this->has_set_mutator( $name ) ) {
			$this->apply_set_mutator( $name, $value );

			return;

		} elseif ( property_exists( $this->post, $name ) ) {
			$this->post->$name = $value;

			return;
		}

		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __set(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE );
	}


	public function __get( $name ) {

		if ( $this->has_get_mutator( $name ) ) {
			return $this->apply_get_mutator( $name, $this->post->$name );

		} elseif ( property_exists( $this->post, $name ) ) {
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


	protected function set_post_object( \WP_Post $post ) {
		$this->post = $post;
	}


	protected function apply_get_mutator( $name, $value ) {
		return $this->has_get_mutator( $name )
			? $this->{"get_{$name}_attribute"}( $value )
			: $value;
	}


	protected function apply_set_mutator( $name, $value ) {
		return $this->has_set_mutator( $name )
			? $this->{"set_{$name}_attribute"}( $value )
			: $value;
	}


	protected function has_get_mutator( $name ) {
		return method_exists( $this, "get_{$name}_attribute" );
	}


	protected function has_set_mutator( $name ) {
		return method_exists( $this, "set_{$name}_attribute" );
	}


}