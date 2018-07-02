<?php
namespace tmc\builder\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 28.06.2018
 * Time: 14:12
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;
use tmc\builder\src\App;
use WP_Admin_Bar;
use WP_Post;

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

		add_action( 'wp',                   array( $this, '_a_init' ) );
		add_action( 'widgets_init',         array( $this, '_a_registerWidgetsAreas' ) );
		add_action( 'after_setup_theme',    array( $this, '_a_themeSupportDeclaration' ) );
		add_action( 'wp_enqueue_scripts',   array( $this, '_a_enqueueFrontEndScripts' ) );

	}

	/**
	 * Creates unique dynamic sidebar ID from post or its ID.
	 *
	 * @param WP_Post|int|null $post
	 *
	 * @return string
	 */
	public function getWidgetsAreaIdByPost( $post = null ) {

		if( $post ){

			$postId = ( is_numeric( $post ) ) ? (int) $post : $post->ID;

		} else {

			$postId = (int) get_the_ID();

		}

		return $this::s()->getPrefix( '_widgets_' . $postId );

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
	 * Registers theme support for dynamic sidebars.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_themeSupportDeclaration() {

		add_theme_support( 'widgets customize-selective-refresh-widgets' );

	}

	/**
	 * Called on wp_enqueue_scripts.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_enqueueFrontEndScripts() {

		if( ! App::i()->pageTemplates->isOnSupportedPage() ) return;

		wp_enqueue_style(
			$this::s()->getPrefix( '_front' ),
			$this::s()->getUrl( 'assets/css/frontEndStyle.css' ),
			array(),
			$this::s()->getFullPluginVersion()
		);

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
												get_post_type_labels( get_post_type_object( $page->post_type ) )->singular_name,    //  TODO - cache this.
												$page->post_title,
												__( 'widgets', 'tmc_builder' )
											),
						'id'            =>  $this->getWidgetsAreaIdByPost( $pageId ),
						'description'   =>  sprintf( __( 'Widgets area for page: %1$s', 'tmc_builder' ), $page->post_title )
					)
				);

			}

		}

	}

}