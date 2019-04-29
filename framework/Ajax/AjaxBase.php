<?php


namespace PdkPluginBoilerplate\Framework\Ajax;


use PdkPluginBoilerplate\Framework\Traits\ClassNameAsIdentifier;
use PdkPluginBoilerplate\Framework\Traits\NonceCreationAndVerification;


// todo - add inline script variable generation as an option
abstract class AjaxBase {


	use ClassNameAsIdentifier;

	use NonceCreationAndVerification {
		verify_nonce as protected _verify_nonce;
	}


	/**
	 * @var string The AJAX action name
	 */
	protected $action;


	/**
	 * @var bool  Whether or not to look for and verify a nonce when handling requests.
	 */
	protected $use_nonce = true;


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
	 * The full URL for this AJAX action. If nonces are being used, the nonce is also added to the URL.
	 *
	 * @return string
	 */
	public function get_url() {
		$url = add_query_arg( 'action', $this->get_action(), admin_url( 'admin-ajax.php' ) );

		if ( $this->use_nonce ) {
			$url = $this->add_nonce_to_url( $url );
		}

		return $url;
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
		$this->verify_nonce();

		if ( $handler = $this->get_handler_method_name() ) {
			$this->$handler();
			die();
		}

		wp_die( 'AJAX hooked handler method called directly outside of appropriate hook.', '', [ 'response' => 400 ] );
	}


	protected function verify_nonce() {
		if ( $this->use_nonce and ! $this->_verify_nonce() ) {
			wp_die( 'Nonce invalid' );
		}
	}


	/**
	 * Determines which handler method should be invoked for this request.
	 *
	 * @return string|null The method name on success or NULL on failure
	 */
	protected function get_handler_method_name() {
		$action = $this->get_action();

		if ( doing_action( "wp_ajax_{$action}" ) ) {
			return $this->priv_handler_method_name;

		} elseif ( doing_action( "wp_ajax_nopriv_{$action}" ) ) {
			return $this->nopriv_handler_method_name;
		}

		return null;
	}


	// todo - determine best name
	protected function priv() {
		wp_die( 'No endpoint handler defined for this action and context.', '', [ 'response' => 400 ] );
	}


	// todo - determine best name
	protected function nopriv() {
		wp_die( 'No endpoint handler defined for this action and context.', '', [ 'response' => 400 ] );
	}


}