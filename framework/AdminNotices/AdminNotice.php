<?php


namespace PdkPluginBoilerplate\Framework\AdminNotices;


/**
 * Class AdminNotice
 * @package PdkPluginBoilerplate\Framework
 *
 * This class can either be instantiated by itself for simple usage or it can be extended at the application level for
 * more control.
 */
class AdminNotice {


	const TYPE_SUCCESS = 'success';
	const TYPE_WARNING = 'warning';
	const TYPE_ERROR = 'error';
	const TYPE_INFO = 'info';


	protected $type = self::TYPE_INFO;
	protected $message = '';
	protected $is_dismissible = true;
	protected $is_alt = false;
	protected $allow_html = false;


	/**
	 * HTML can be set here but the message will be sanitized using wp_kses_post() by default. If extending this class,
	 * sanitization can be customised by overriding the self::sanitize() method.
	 *
	 * The message is also passed to wp_autop() on render. If extending this class, you can change this by overriding
	 * the self::prepare_message() method.
	 *
	 * @param string $message
	 */
	public function set_message( $message ) {
		$this->message = $message;
	}


	public function set_is_dismissible( $is_dismissible = true ) {
		$this->is_dismissible = boolval( $is_dismissible );
	}


	public function set_is_alt( $is_alt = true ) {
		$this->is_alt = boolval( $is_alt );
	}


	public function set_type( $type = self::TYPE_SUCCESS ) {
		$this->type = $type;
	}


	public function set_success_type() {
		$this->type = self::TYPE_SUCCESS;
	}


	public function set_warning_type() {
		$this->type = self::TYPE_WARNING;
	}


	public function set_error_type() {
		$this->type = self::TYPE_ERROR;
	}


	public function set_info_type() {
		$this->type = self::TYPE_INFO;
	}


	public function init() {
		add_action( 'admin_notices', [ $this, '_render' ] );
	}


	/**
	 * This is the hooked render method.
	 *
	 * @see \PdkPluginBoilerplate\Framework\AdminNotice::init();
	 */
	public function _render() {
		if ( $this->should_render() ) {

			$translated_message = __( $this->message, PDK_PLUGIN_BOILERPLATE_PLUGIN_TEXT_DOMAIN );

			printf( '<div class="notice notice-%s %s">%s</div>',
				$this->type,
				$this->get_additional_classes(),
				$this->prepare_message( $translated_message )
			);
		}
	}


	/**
	 * Determines whether or not the message should render at all by checking both the visibility condition for the
	 * message AND the message property itself. It is much better to override the self::is_visible() method if you are
	 * just looking to set a condition for this notice to appear.
	 *
	 * @return bool
	 */
	protected function should_render() {
		return $this->message and $this->is_visible( get_current_screen() );
	}


	/**
	 * Assembles the list of additional CSS classes applied to the admin notice and returns them as a string. If you
	 * are extending the class and need to add your own CSS classes to your notice you could do so by overriding this
	 * method.
	 *
	 * @param bool $as_array Whether or not the classes should be returned as an array or a string
	 *
	 * @return string|array
	 */
	protected function get_additional_classes( $as_array = false ) {
		$classes = [];

		if ( $this->is_dismissible ) {
			$classes[] = 'is-dismissible';
		}

		if ( $this->is_alt ) {
			$classes[] = 'notice-alt';
		}

		return $as_array
			? $classes
			: implode( ' ', $classes );
	}


	/**
	 * Defines the condition as to when this notice will be shown to the user. This method is passed the results of
	 * get_current_screen() which makes it possible to evaluate which screen/s to reveal this message on.
	 *
	 * @param \WP_Screen|null $current_screen Current screen object or null when screen not defined.
	 *
	 * @return bool
	 */
	protected function is_visible( $current_screen ) {
		return true;
	}


	/**
	 * Override this method if you need to remove the wpautop functionality from the notice's message.
	 *
	 * @param $message
	 *
	 * @return string
	 */
	protected function prepare_message( $message ) {
		return wpautop( $this->sanitize( $message ) );
	}


	/**
	 * Override this method if you need to customize or remove santization of the message itself.
	 *
	 * @param $message
	 *
	 * @return string
	 */
	protected function sanitize( $message ) {
		return wp_kses_post( $message );
	}


}