<?php
/**
 * Technical SEO: meta description, canonical, Open Graph/Twitter, JSON-LD.
 *
 * Never emits fabricated ratings, review counts, awards, or success-rate
 * statistics (Master Prompt §39) — only real, admin-supplied data.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a plain-text description for the current view.
 *
 * @return string
 */
function dq_get_meta_description() {
	if ( is_singular() ) {
		global $post;
		if ( $post instanceof WP_Post ) {
			$excerpt = has_excerpt( $post ) ? $post->post_excerpt : wp_strip_all_tags( $post->post_content );
			$excerpt = wp_trim_words( $excerpt, 30, '…' );
			if ( '' !== trim( $excerpt ) ) {
				return $excerpt;
			}
		}
	}

	$tagline = dq_get_setting( 'seo_default_description' );
	if ( '' !== $tagline ) {
		return $tagline;
	}

	return get_bloginfo( 'description' );
}

/**
 * Output <meta name="description">, canonical, and social cards.
 */
function dq_output_seo_meta() {
	$description = wp_strip_all_tags( dq_get_meta_description() );
	$title       = wp_get_document_title();
	$canonical   = is_singular() ? get_permalink() : home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );

	$image_url = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url( get_the_ID(), 'dq-card' );
	}
	if ( '' === $image_url ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$logo = wp_get_attachment_image_src( $logo_id, 'medium' );
			if ( $logo ) {
				$image_url = $logo[0];
			}
		}
	}

	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
	}

	printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $canonical ) );

	printf( '<meta property="og:type" content="%s" />' . "\n", esc_attr( is_singular() ? 'article' : 'website' ) );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $canonical ) );
	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	if ( '' !== $image_url ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image_url ) );
	}

	printf( '<meta name="twitter:card" content="%s" />' . "\n", esc_attr( '' !== $image_url ? 'summary_large_image' : 'summary' ) );
	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $description ) );
}
add_action( 'wp_head', 'dq_output_seo_meta', 1 );

/**
 * Organization JSON-LD — built only from real, admin-filled Theme Settings.
 * Fields left empty by the site owner are simply omitted, never invented.
 */
function dq_output_organization_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$whatsapp = dq_get_setting( 'whatsapp_number' );
	$email    = dq_get_setting( 'contact_email' );
	$phone    = dq_get_setting( 'contact_phone' );
	$address  = dq_get_setting( 'contact_address' );

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'EducationalOrganization',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
	);

	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$logo = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $logo ) {
			$schema['logo'] = $logo[0];
		}
	}

	$contact_point = array( '@type' => 'ContactPoint' );
	if ( '' !== $phone ) {
		$contact_point['telephone'] = $phone;
	}
	if ( '' !== $email ) {
		$contact_point['email'] = $email;
	}
	if ( count( $contact_point ) > 1 ) {
		$schema['contactPoint'] = $contact_point;
	}

	if ( '' !== $address ) {
		$schema['address'] = $address;
	}

	$same_as = array_filter( array(
		dq_get_setting( 'social_instagram' ),
		dq_get_setting( 'social_facebook' ),
		dq_get_setting( 'social_tiktok' ),
		dq_get_setting( 'social_youtube' ),
	) );
	if ( ! empty( $same_as ) ) {
		$schema['sameAs'] = array_values( $same_as );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dq_output_organization_schema', 5 );

/**
 * FAQPage JSON-LD, built only from currently published `faq` posts.
 */
function dq_output_faq_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$faqs = get_posts( array(
		'post_type'      => 'faq',
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
	) );

	if ( empty( $faqs ) ) {
		return;
	}

	$entities = array();
	foreach ( $faqs as $faq ) {
		$answer = wp_strip_all_tags( $faq->post_content );
		if ( '' === trim( $faq->post_title ) || '' === trim( $answer ) ) {
			continue;
		}
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $faq->post_title,
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $answer,
			),
		);
	}

	if ( empty( $entities ) ) {
		return;
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dq_output_faq_schema', 6 );
