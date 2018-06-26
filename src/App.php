<?php
namespace tmc\builder\src;

/**
 * @author jakubkuranda@gmail.com
 * Date: 26.06.2018
 * Time: 11:38
 */

use shellpress\v1_2_5\ShellPress;

class App extends ShellPress {

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

	}
}