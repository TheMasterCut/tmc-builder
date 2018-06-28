<?php
namespace tmc\builder\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 27.06.2018
 * Time: 15:02
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;
use tmc\builder\src\App;
use WP_Post;
use WP_Query;

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

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		add_action( 'save_post_page',                   array( $this, '_a_refreshSupportedPagesCache' ) );

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
	public function getSupportedPageTemplates() {

		return $this->supportedTemplates;

	}

	/**
	 * Checks if current page uses supported page template.
	 * It should be called after wp post has been set up.
	 *
	 * @return bool
	 */
	public function isOnSupportedPage() {

		return is_page() && array_key_exists( $this->getCurrentPageTemplateSlug(), $this->getSupportedPageTemplates() );

	}

	/**
	 * Returns current page template slug.
	 *
	 * @return string|false
	 */
	public function getCurrentPageTemplateSlug() {

		return get_page_template_slug();

	}

	/**
	 * Returns saved ids.
	 *
	 * return int[]
	 */
	public function getAllSupportedPagesIds() {

		return (array) $this::s()->options->get( 'internal/allSupportedPagesIds', array() );

	}

	/**
	 * Performs database query. Looks for all pages with supported page templates.
	 *
	 * @return void
	 */
	public function refreshAllSupportedPagesIds() {

		$pages = get_pages( array(
			'post_status'   =>  array( 'publish', 'pending', 'private', 'draft' )
		) );

		$pageIds = array();

		// Check each page template and add its ID to array.
		foreach( $pages as $page ){ /** @var WP_Post $page */

			$pageTemplate = get_page_template_slug( $page );

			if( $pageTemplate && array_key_exists( $pageTemplate, $this->getSupportedPageTemplates() ) ){
				$pageIds[] = $page->ID;
			}

		}

		$this::s()->options->set( 'internal/allSupportedPagesIds', $pageIds );
		$this::s()->options->flush();

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

		return array_replace( $templates, $this->getSupportedPageTemplates() );

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

		//  TODO - Add possibility to overwrite template inside theme.

		if( $this->isOnSupportedPage() ){

			//  Our custom templates are inside plugin directory.
			//  It might not be one we are talking about so check if this file exists.
			$newTemplatePath = trailingslashit( $this->getTemplatesDir() ) . $this->getCurrentPageTemplateSlug();

			if( file_exists( $newTemplatePath ) ) return $newTemplatePath;

		}

		return $templatePath;

	}

	/**
	 * Refreshes all supported pages. It may has performance issues.
	 * Called on save_post_page.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_refreshSupportedPagesCache() {

		$this->refreshAllSupportedPagesIds();

	}

}