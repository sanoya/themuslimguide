<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Educare
 */



if ( ! function_exists( 'educare_champtheme_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function educare_champtheme_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
    
    
    $posted_on = sprintf(
        esc_html_x( '%s', 'post date', 'educare-champtheme' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );   

	$byline = sprintf(
		esc_html_x( '%s', 'post author', 'educare-champtheme' ),
		'<span class="author vcard"> <i class="fa fa-user"></i><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);
    
    if(cs_get_option('blog_post_date') == true) {
	   echo '<span class="posted-on"> <i class="fa fa-calendar"></i>' . $posted_on . '</span>'; // WPCS: XSS OK.
    }
    
    if(cs_get_option('blog_post_by') == true) {
	   echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
    }

    
    if(cs_get_option('blog_post_comment') == true) {
        if (  !is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link"> <i class="fa fa-comments-o"></i>';
            /* translators: %s: post title */
            comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'educare-champtheme' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
            echo '</span>';
        }
    }

}
endif;

if ( ! function_exists( 'educare_champtheme_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function educare_champtheme_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
        
        if(cs_get_option('blog_post_category') == true) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( esc_html__( ', ', 'educare-champtheme' ) );
            if ( $categories_list && educare_champtheme_categorized_blog() ) {
                printf( '<span class="cat-links">' . esc_html__( 'Posted in: %1$s', 'educare-champtheme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
            }
		}

        if(cs_get_option('blog_post_tags') == true) {
            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html__( ', ', 'educare-champtheme' ) );
            if ( $tags_list && is_singular() ) {
                printf( '<span class="tag-links">' . esc_html__( 'Tagged: %1$s', 'educare-champtheme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
            }
		}
	}


    if(is_singular()){
        edit_post_link(
            sprintf(
                /* translators: %s: Name of current post */
                esc_html__( 'Edit %s', 'educare-champtheme' ),
                the_title( '<span class="screen-reader-text">"', '"</span>', false )
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function educare_champtheme_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'educare_champtheme_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'educare_champtheme_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so educare_champtheme_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so educare_champtheme_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in educare_champtheme_categorized_blog.
 */
function educare_champtheme_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'educare_champtheme_categories' );
}
add_action( 'edit_category', 'educare_champtheme_category_transient_flusher' );
add_action( 'save_post',     'educare_champtheme_category_transient_flusher' );
