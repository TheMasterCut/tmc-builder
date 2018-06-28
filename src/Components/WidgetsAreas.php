<?php
namespace tmc\builder\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 28.06.2018
 * Time: 14:12
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;
use tmc\builder\src\App;

class WidgetsAreas extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		add_action( 'wp',           array( $this, '_a_init' ) );
		add_action( 'widgets_init', array( $this, '_a_registerWidgetsAreas' ) );

	}

	//  ================================================================================
	//  Actions
	//  ================================================================================

	/**
	 * Checks if current page is controlled by plugin.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_init() {

		if( ! App::i()->pageTemplates->isOnSupportedPage() ) return;    //  Bail out early.

	}

	/**
	 * Registers dynamic sidebars for all supported pages.
	 * Called on widgets_init.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_registerWidgetsAreas() {

		foreach( App::i()->pageTemplates->getAllSupportedPagesIds() as $pageId ){

			$page = get_post( $pageId );

			if( $page ){

				register_sidebar(
					array(
						'name'          =>  sprintf( '%1$s - %2$s (%3$s)',
												__( 'Page', 'tmc_builder' ),
												$page->post_title,
												__( 'widgets', 'tmc_builder' )
											),
						'id'            =>  $this::s()->getPrefix( '_widgets_' . $pageId ),
						'description'   =>  sprintf( __( 'Widgets area for page: %1$s', 'tmc_builder' ), $page->post_title )
					)
				);

			}

		}

	}

}