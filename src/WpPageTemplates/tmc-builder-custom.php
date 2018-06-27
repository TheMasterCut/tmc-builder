<?php
/*
 * Template Name:   TMC Builder custom
 * Description:     The Page Template for TMC Builder widgets
 */

if( locate_template( 'tmc-builder-custom.php' ) ){

	get_template_part( 'tmc-builder', 'custom-template' );  //  Allow user-created custom template.

} else {

	get_header();

	printf( '<div %1$s>', implode( ' ', get_post_class() ) );   //  BEGIN : Post div

	if( have_posts() ){

		while( have_posts() ){

			the_post();

			$sidebarName = 'lol';

			if( is_active_sidebar( $sidebarName ) ){

				dynamic_sidebar( $sidebarName );

			} else {

				printf( '<div class="tmc-builder-sidebar-not-enabled"><p>%1$s</p></div>',
					sprintf( __( 'TMC Builder widgets area. Start adding widgets %1$s.' ),
						sprintf( '<a href="%1$s">%2$s</a>', '#', __( 'here', 'tmc_builder' ) )
					)
				);

			}

		}

	}

	echo '</div>';  //  END : Post div

	get_footer();

}
