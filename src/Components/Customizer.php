<?php

namespace tmc\builder\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 28.06.2018
 * Time: 14:09
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;

class Customizer extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {
		// TODO: Implement onSetUp() method.
	}

	/**
	 * Returns unescaped direct URL for widgets editing.
	 *
	 * @param string $sidebarId
	 *
	 * @return string
	 */
	public function getWidgetsAreaCustomizerUrl( $sidebarId ) {

		$url        = admin_url( 'customize.php' );
		$queryArgs  = array(
			'autofocus[panel]'      =>  'widgets',
			'autofocus[section]'    =>  'sidebar-widgets-' . $sidebarId,
			'url'                   =>  esc_url( add_query_arg( array() ) )
		);

		return add_query_arg( $queryArgs, $url );

	}

}