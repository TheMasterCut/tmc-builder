<?php
/*
 * Template Name:   TMC Builder custom
 * Description:     The Page Template for TMC Builder widgets
 */

use tmc\builder\src\App;

if( locate_template( 'tmc-builder-custom.php' ) ){

	get_template_part( 'tmc-builder', 'custom' );  //  Allow user-created custom template.

} else {

	get_header();

	//  Prepare classes for outer div.

	$widgetsAreaClasses = array( 'tmc-builder-widgets-area' );
	$widgetsAreaClasses = array_merge( $widgetsAreaClasses, get_post_class() );

	//  BEGIN : Post div.

	printf( '<ul class="%1$s">', implode( ' ', $widgetsAreaClasses ) );

	if( have_posts() ){

		while( have_posts() ){

			the_post();

			$sidebarId = App::i()->widgetsAreas->getWidgetsAreaIdByPost( get_the_ID() );

			if( is_active_sidebar( $sidebarId ) ){

				dynamic_sidebar( $sidebarId );

			} else {

				printf( '<div class="tmc-builder-sidebar-not-enabled"><p>%1$s</p><p>%2$s</p></div>',
					sprintf( __( 'TMC Builder widgets area.' ) ),
					sprintf( '<a class="tmc-builder-button-add-new" href="%1$s">%2$s</a>', App::i()->customizer->getWidgetsAreaCustomizerUrl( $sidebarId ), __( 'Add new widgets here', 'tmc_builder' ) )
				);

			}

		}

	}

	echo '</div>';

	//  END : Post div.

	get_footer();

}
