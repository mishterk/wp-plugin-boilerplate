<?php


namespace PdkPluginBoilerplate\View;


/**
 * Class View
 * @package PdkPluginBoilerplate\Utils
 *
 * @method static View get_instance();
 */
class View extends \PdkPluginBoilerplate\Framework\View\ViewBase {


	protected function setup() {
		$this->set_view_base_dir( PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'templates' );
		$this->set_view_override_base_dir( get_stylesheet_directory() . '/pdkpluginbp' );

		// make all templates overridable
		$this->make_all_templates_overridable();

		// OR, specify which directories contain overridable templates
		$this->set_overridable_template_dirs( [ 'dir', 'some/dir' ] );
		$this->add_overridable_template_dir( 'icons' );
		$this->add_overridable_template_dir( 'widgets' );

		// AND/OR, mark specific templates as overridable
		$this->set_overridable_templates( [ 'icons/umbrella', 'icons/tophat' ] );
		$this->add_overridable_template( 'icons/umbrella' );
		$this->add_overridable_template( 'icons/tophat' );
	}


}
