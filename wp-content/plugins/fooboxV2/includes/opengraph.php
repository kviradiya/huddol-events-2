<?php
/**
 * Adds Opengraph Meta Tags
 *
 * @author 	Brad Vincent
 * @package 	foobox/includes
 * @version     1.2
 */

if (!class_exists('foo_opengraph')) {

  class foo_opengraph {

    function add_meta() {

      //only add meta tags for single posts
      the_post();

      $meta = array();
      $meta['og:title'] = $this->title();
      $meta['og:type'] = $this->type();
      $meta['og:url'] = $this->url();

      $meta['og:description'] = esc_attr( strip_tags( stripslashes( $this->description() ) ) );
      $meta['og:site_name'] = $this->site_name();;
      $meta['og:locale'] = $this->locale();

      if (current_theme_supports('post-thumbnails')) {
        if (is_singular() && has_post_thumbnail()) {
          $meta['og:image'] = wp_get_attachment_url(get_post_thumbnail_id());
        }
      }

      rewind_posts();

      $this->render($meta);

    }

    function render($meta) {
      foreach ( $meta as $key=>$value ) {
        $esc_value = esc_attr ( $value );
        echo "<meta property=\"{$key}\" content=\"{$esc_value}\" />\n";
      }
    }

    function is_aio_installed() {
      return function_exists('aiosp_meta');
    }

    function url() {
      return esc_url( $this->canonical() );
    }

	//borrowed from WordPress SEO
	function canonical( $un_paged = false, $no_override = false ) {
	  $canonical = false;
	  $skip_pagination = false;

	  // Set decent canonicals for homepage, singulars and taxonomy pages
	  if ( is_singular() ) {
		  $obj       = get_queried_object();
		  $canonical = get_permalink( $obj->ID );

		  // Fix paginated pages canonical, but only if the page is truly paginated.
		  if ( get_query_var( 'page' ) > 1 ) {
			  global $wp_rewrite;
			  $numpages = substr_count( $obj->post_content, '<!--nextpage-->' ) + 1;
			  if ( $numpages && get_query_var( 'page' ) <= $numpages ) {
				  if ( ! $wp_rewrite->using_permalinks() ) {
					  $canonical = add_query_arg( 'page', get_query_var( 'page' ), $canonical );
				  }
				  else {
					  $canonical = user_trailingslashit( trailingslashit( $canonical ) . get_query_var( 'page' ) );
				  }
			  }
		  }
	  }
	  else {
		  if ( is_search() ) {
			  $canonical = get_search_link();
		  }
		  else if ( is_front_page() ) {
			  $canonical = home_url( '/' );
		  }
		  else if ( is_home() && 'page' == get_option( 'show_on_front' ) ) {
			  $canonical = get_permalink( get_option( 'page_for_posts' ) );
		  }
		  else if ( is_tax() || is_tag() || is_category() ) {
			  $term      = get_queried_object();
			  $canonical = get_term_link( $term, $term->taxonomy );
		  }
		  else if ( function_exists( 'get_post_type_archive_link' ) && is_post_type_archive() ) {
			  $canonical = get_post_type_archive_link( get_query_var( 'post_type' ) );
		  }
		  else if ( is_author() ) {
			  $canonical = get_author_posts_url( get_query_var( 'author' ), get_query_var( 'author_name' ) );
		  }
		  else if ( is_archive() ) {
			  if ( is_date() ) {
				  if ( is_day() ) {
					  $canonical = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
				  }
				  else if ( is_month() ) {
					  $canonical = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
				  }
				  else if ( is_year() ) {
					  $canonical = get_year_link( get_query_var( 'year' ) );
				  }
			  }
		  }

		  if ( $canonical && $un_paged )
			  return $canonical;

		  if ( $canonical && ! $skip_pagination && get_query_var( 'paged' ) > 1 ) {
			  global $wp_rewrite;
			  if ( ! $wp_rewrite->using_permalinks() ) {
				  $canonical = add_query_arg( 'paged', get_query_var( 'paged' ), $canonical );
			  }
			  else {
				  if ( is_front_page() ) {
					  $base      = $GLOBALS['wp_rewrite']->using_index_permalinks() ? 'index.php/' : '/';
					  $canonical = home_url( $base );
				  }
				  $canonical = user_trailingslashit( trailingslashit( $canonical ) . trailingslashit( $wp_rewrite->pagination_base ) . get_query_var( 'paged' ) );
			  }
		  }
	  }

	  if ( $canonical && ! is_wp_error( $canonical ) ) {
		  return $canonical;
	  } else {
		  return false;
	  }
	}

    function title() {
      if (is_singular()) {
        if ( $this->is_aio_installed() ) {
          return get_post_meta(get_the_ID(), '_aioseop_title', true);
        }
        return get_the_title();
      }
      return $this->site_name();
    }

    function type() {
      return is_single() ? 'article' : 'website';
    }

    function description() {
      if (is_singular()) {
        if ( $this->is_aio_installed() ) {
          return get_post_meta(get_the_ID(), '_aioseop_description', true);
        }
        $desc = get_the_excerpt();
        return empty($desc) ? get_bloginfo('description') : $desc;
      }
      return get_bloginfo('description');
    }

    function site_name() {
      return strip_tags(get_bloginfo('name'));
    }

    function locale() {
      return strtolower(str_replace('-', '_', get_bloginfo('language')));
    }

  }

}