<?php


namespace PdkPluginBoilerplate\Framework\Ajax;


use PdkPluginBoilerplate\Framework\Traits\ClassNameAsIdentifier;


// todo - add nonce handling – perhaps as a Trait?
// todo - add inline script variable generation as an option
abstract class AjaxBase {


	use ClassNameAsIdentifier;


	/**
	 * @var string The AJAX action name
	 */
	protected $action;


	/**
	 * @var string|mixed    Set the name of the method that is hooked to wp_ajax_{$action} for handling AJAX requests
	 *                      made by authenticated users. If set to anything other than a string – e.g; FALSE, NULL, … –
	 *                      the priv handler won't be hooked and this AJAX action will not handle requests made by
	 *                      authenticated users.
	 */
	protected $priv_handler_method_name = 'priv';


	/**
	 * @var string|mixed    Set the name of the method that is hooked to wp_ajax_nopriv_{$action} for handling AJAX
	 *                      requests made by non-authenticated users. If set to anything other than a string – e.g;
	 *                      FALSE, NULL, … – the priv handler won't be hooked and this AJAX action will not handle
	 *                      requests made by non-authenticated users.
	 */
	protected $nopriv_handler_method_name = 'nopriv';


	/**
	 * Initialise the AJAX endpoint by hooking both priv and no priv handlers where configured. This is the only method
	 * that must be called in order to get the implementation running.
	 */
	public function init() {
		$action = $this->get_action();

		if ( is_string( $this->priv_handler_method_name ) ) {
			add_action( "wp_ajax_{$action}", [ $this, '_handle' ] );
		}

		if ( is_string( $this->nopriv_handler_method_name ) ) {
			add_action( "wp_ajax_nopriv_{$action}", [ $this, '_handle' ] );
		}
	}


	/**
	 * If no $action property is set on a child class, use the fully-qualified class name to generate a snake-cased
	 * action for the implementation.
	 *
	 * @return string
	 */
	public function get_action() {
		if ( ! $this->action ) {
			$this->action = $this->get_class_name_as_id();
		}

		return $this->action;
	}


	/**
	 * The hooked handler method.
	 */
	public function _handle() {
		$handler = null;
		$action  = $this->get_action();

		// todo - handle nonce authentication here

		if ( doing_action( "wp_ajax_{$action}" ) ) {
			$handler = $this->priv_handler_method_name;

		} elseif ( doing_action( "wp_ajax_nopriv_{$action}" ) ) {
			$handler = $this->nopriv_handler_method_name;
		}

		if ( $handler ) {
			$this->$handler();
			die();
		}

		wp_die( 'AJAX hooked handler method called directly outside of appropriate hook.', '', [ 'response' => 400 ] );
	}


	protected function priv() {
		wp_die( 'No endpoint handler defined for this action and context.', '', [ 'response' => 400 ] );
	}


	protected function nopriv() {
		wp_die( 'No endpoint handler defined for this action and context.', '', [ 'response' => 400 ] );
	}


}