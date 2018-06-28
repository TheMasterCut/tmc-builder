<?php
namespace tmc\builder\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 27.06.2018
 * Time: 15:02
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;

class PageTemplates extends IComponent {

	/** @var string */
	protected $templatesDir;

	/** @var string[] - Key: file name; Value: description; */
	protected $supportedTemplates;

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Properties
		//  ----------------------------------------

		$this->templatesDir = $this::s()->getPath( 'src/WpPageTemplates' );

		$this->supportedTemplates = array(
			'tmc-builder-custom.php'    =>  __( 'TMC Builder Custom', 'tmc_builder' )
		);

		//  ----------------------------------------
		//  Filters
		//  ----------------------------------------

		add_filter( 'theme_page_templates',             array( $this, '_f_addCustomPageTemplates' ) );

		add_filter( 'template_include',                 array( $this, '_f_replaceTemplatePath') );

	}

	/**
	 * Returns absolute path to templates directory.
	 *
	 * @return string
	 */
	public function getTemplatesDir() {

		return $this->templatesDir;

	}

	/**
	 * Returns templates names added by this plugin.
	 *
	 * @return string[]
	 */
	public function getSupportedTemplates() {

		return $this->supportedTemplates;

	}

	//  ================================================================================
	//  FILTERS
	//  ================================================================================

	/**
	 * Inserts new templates for use in page template chooser.
	 * Called on theme_page_templates.
	 *
	 * @param array $templates - Keys: filenames. Values: names.
	 *
	 * @return array
	 */
	public function _f_addCustomPageTemplates( $templates ) {

		return array_replace( $templates, $this->getSupportedTemplates() );

	}

	/**
	 * Replaces default template file path with the one inside plugin directory.
	 * Called on template_include.
	 *
	 * @param string $templatePath
	 *
	 * @return string
	 */
	public function _f_replaceTemplatePath( $templatePath ) {

		$chosenPageTemplate = get_page_template_slug(); //  Page template chosen by user in editor.

		//  TODO - Add possibility to overwrite template inside theme.

		if( array_key_exists( $chosenPageTemplate, $this->getSupportedTemplates() ) ){

			//  Our custom templates are inside plugin directory.
			//  It might not be one we are talking about so check if this file exists.
			$newTemplatePath = trailingslashit( $this->getTemplatesDir() ) . $chosenPageTemplate;

			if( file_exists( $newTemplatePath ) ) return $newTemplatePath;

		}

		return $templatePath;

	}

}