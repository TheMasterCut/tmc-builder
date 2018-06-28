<?php
namespace tmc\builder\src;

/**
 * @author jakubkuranda@gmail.com
 * Date: 26.06.2018
 * Time: 11:38
 */

use shellpress\v1_2_5\ShellPress;
use tmc\builder\src\Components\Customizer;
use tmc\builder\src\Components\PageTemplates;
use tmc\builder\src\Components\WidgetsAreas;

class App extends ShellPress {

	/** @var PageTemplates */
	public $pageTemplates;

	/** @var Customizer */
	public $customizer;

	/** @var WidgetsAreas */
	public $widgetsAreas;

	/**
	 * Called automatically after core is ready.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Definition
		//  ----------------------------------------

		$this::s()->autoloading->addNamespace( 'tmc\builder', dirname( $this::s()->getMainPluginFile() ) );

		//  ----------------------------------------
		//  Components
		//  ----------------------------------------

		$this->pageTemplates    = new PageTemplates( $this );
		$this->customizer       = new Customizer( $this );
		$this->widgetsAreas     = new WidgetsAreas( $this );

	}
}