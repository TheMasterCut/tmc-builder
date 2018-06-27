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
	public $templatesDir;

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

		//  ----------------------------------------
		//  Filters
		//  ----------------------------------------

		add_filter( 'theme_page_templates',             array( $this, '_f_addCustomPageTemplates' ) );

		add_filter( 'template_include',                 array( $this, '_f_replaceTemplatePath') );

	}

	//  ================================================================================
	//  FILTERS
	//  ================================================================================

	/**
	 * @param array $templates - Keys: filenames. Values: names.
	 *
	 * @return array
	 */
	public function _f_addCustomPageTemplates( $templates ) {

		$templates['tmc-builder-custom.php'] = __( 'TMC Builder Custom', 'tmc_builder' );

		return $templates;

	}

	/**
	 * @param string $templatePath
	 *
	 * @return string
	 */
	public function _f_replaceTemplatePath( $templatePath ) {

		//  Our custom templates are inside plugin directory.
		//  It might not be one we are talking about so check if this file exists.
		$newTemplatePath = trailingslashit( $this->templatesDir ) . get_page_template_slug();

		return ( file_exists( $newTemplatePath ) ) ? $newTemplatePath : $templatePath;

	}

}