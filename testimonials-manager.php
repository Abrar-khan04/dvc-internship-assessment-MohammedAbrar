<?php
/**
 * Plugin Name: Premium Testimonials Manager
 * Description: A complete testimonials management system with CPT, Meta Boxes, and a responsive Shortcode slider.
 * Version: 1.0.0
 * Author: Antigravity AI
 * Text Domain: premium-testimonials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Part A: Backend (WordPress Admin)
 * Register Custom Post Type "Testimonial"
 */
function tm_register_testimonial_cpt() {
	$labels = array(
		'name'               => _x( 'Testimonials', 'Post Type General Name', 'premium-testimonials' ),
		'singular_name'      => _x( 'Testimonial', 'Post Type Singular Name', 'premium-testimonials' ),
		'menu_name'          => __( 'Testimonials', 'premium-testimonials' ),
		'all_items'          => __( 'All Testimonials', 'premium-testimonials' ),
		'view_item'          => __( 'View Testimonial', 'premium-testimonials' ),
		'add_new_item'       => __( 'Add New Testimonial', 'premium-testimonials' ),
		'add_new'            => __( 'Add New', 'premium-testimonials' ),
		'edit_item'          => __( 'Edit Testimonial', 'premium-testimonials' ),
		'update_item'        => __( 'Update Testimonial', 'premium-testimonials' ),
		'search_items'       => __( 'Search Testimonials', 'premium-testimonials' ),
		'not_found'          => __( 'No testimonials found', 'premium-testimonials' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash', 'premium-testimonials' ),
	);

	$args = array(
		'label'               => __( 'Testimonials', 'premium-testimonials' ),
		'description'         => __( 'Client Testimonials', 'premium-testimonials' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-testimonial',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true, // Gutenberg support
	);

	register_post_type( 'testimonial', $args );
}
add_action( 'init', 'tm_register_testimonial_cpt', 0 );

/**
 * Part B: Custom Fields (Meta Box)
 */
function tm_add_testimonial_meta_boxes() {
	add_meta_box(
		'tm_testimonial_details',
		__( 'Client Details', 'premium-testimonials' ),
		'tm_render_testimonial_meta_box',
		'testimonial',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tm_add_testimonial_meta_boxes' );

function tm_render_testimonial_meta_box( $post ) {
	// Add nonce for security
	wp_nonce_field( 'tm_save_testimonial_meta', 'tm_testimonial_nonce' );

	$client_name     = get_post_meta( $post->ID, '_tm_client_name', true );
	$client_position = get_post_meta( $post->ID, '_tm_client_position', true );
	$company_name    = get_post_meta( $post->ID, '_tm_company_name', true );
	$rating          = get_post_meta( $post->ID, '_tm_rating', true );

	?>
	<style>
		.tm-meta-row { margin-bottom: 15px; }
		.tm-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
		.tm-meta-row input[type="text"], .tm-meta-row select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
	</style>
	<div class="tm-meta-container">
		<div class="tm-meta-row">
			<label for="tm_client_name"><?php _e( 'Client Name (Required)', 'premium-testimonials' ); ?></label>
			<input type="text" id="tm_client_name" name="tm_client_name" value="<?php echo esc_attr( $client_name ); ?>" required>
		</div>
		<div class="tm-meta-row">
			<label for="tm_client_position"><?php _e( 'Client Position/Title', 'premium-testimonials' ); ?></label>
			<input type="text" id="tm_client_position" name="tm_client_position" value="<?php echo esc_attr( $client_position ); ?>">
		</div>
		<div class="tm-meta-row">
			<label for="tm_company_name"><?php _e( 'Company Name', 'premium-testimonials' ); ?></label>
			<input type="text" id="tm_company_name" name="tm_company_name" value="<?php echo esc_attr( $company_name ); ?>">
		</div>
		<div class="tm-meta-row">
			<label for="tm_rating"><?php _e( 'Rating', 'premium-testimonials' ); ?></label>
			<select id="tm_rating" name="tm_rating">
				<option value="5" <?php selected( $rating, '5' ); ?>>5 Stars</option>
				<option value="4" <?php selected( $rating, '4' ); ?>>4 Stars</option>
				<option value="3" <?php selected( $rating, '3' ); ?>>3 Stars</option>
				<option value="2" <?php selected( $rating, '2' ); ?>>2 Stars</option>
				<option value="1" <?php selected( $rating, '1' ); ?>>1 Star</option>
			</select>
		</div>
	</div>
	<?php
}

function tm_save_testimonial_meta( $post_id ) {
	// Check nonce
	if ( ! isset( $_POST['tm_testimonial_nonce'] ) || ! wp_verify_nonce( $_POST['tm_testimonial_nonce'], 'tm_save_testimonial_meta' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save fields with sanitization
	if ( isset( $_POST['tm_client_name'] ) ) {
		update_post_meta( $post_id, '_tm_client_name', sanitize_text_field( $_POST['tm_client_name'] ) );
	}
	if ( isset( $_POST['tm_client_position'] ) ) {
		update_post_meta( $post_id, '_tm_client_position', sanitize_text_field( $_POST['tm_client_position'] ) );
	}
	if ( isset( $_POST['tm_company_name'] ) ) {
		update_post_meta( $post_id, '_tm_company_name', sanitize_text_field( $_POST['tm_company_name'] ) );
	}
	if ( isset( $_POST['tm_rating'] ) ) {
		update_post_meta( $post_id, '_tm_rating', sanitize_text_field( $_POST['tm_rating'] ) );
	}
}
add_action( 'save_post', 'tm_save_testimonial_meta' );

/**
 * Part C & D: Frontend Display & Shortcode
 */
function tm_testimonials_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'count'   => -1, // Use -1 for "all" in WP_Query
		'orderby' => 'date',
		'order'   => 'DESC',
	), $atts, 'testimonials' );

	$query_args = array(
		'post_type'      => 'testimonial',
		'posts_per_page' => (int) $atts['count'],
		'orderby'        => $atts['orderby'],
		'order'          => $atts['order'],
	);

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		return '<p>' . __( 'No testimonials found.', 'premium-testimonials' ) . '</p>';
	}

	ob_start();
	?>
	<style>
		.tm-slider-container { position: relative; max-width: 800px; margin: 40px auto; overflow: hidden; padding: 20px; font-family: sans-serif; }
		.tm-slides { display: flex; transition: transform 0.5s ease-in-out; }
		.tm-testimonial-card { min-width: 100%; box-sizing: border-box; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; }
		.tm-client-photo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid #eee; }
		.tm-rating { color: #f1c40f; font-size: 20px; margin-bottom: 15px; }
		.tm-text { font-style: italic; font-size: 1.1em; color: #555; line-height: 1.6; margin-bottom: 20px; }
		.tm-client-info { font-weight: bold; }
		.tm-client-meta { color: #888; font-size: 0.9em; display: block; margin-top: 5px; }
		.tm-nav { position: absolute; top: 50%; width: 100%; display: flex; justify-content: space-between; transform: translateY(-50%); pointer-events: none; left:0; }
		.tm-nav-btn { background: #fff; border: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; pointer-events: auto; display: flex; align-items: center; justify-content: center; }
		.tm-nav-btn:hover { background: #f9f9f9; }
	</style>

	<div class="tm-slider-container" id="tm-slider">
		<div class="tm-slides">
			<?php while ( $query->have_posts() ) : $query->the_post(); 
				$client_name     = get_post_meta( get_the_ID(), '_tm_client_name', true );
				$client_position = get_post_meta( get_the_ID(), '_tm_client_position', true );
				$company_name    = get_post_meta( get_the_ID(), '_tm_company_name', true );
				$rating          = get_post_meta( get_the_ID(), '_tm_rating', true );
				$photo           = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
				?>
				<div class="tm-testimonial-card">
					<?php if ( $photo ) : ?>
						<img src="<?php echo esc_url( $photo ); ?>" class="tm-client-photo" alt="<?php echo esc_attr( $client_name ); ?>">
					<?php endif; ?>
					
					<div class="tm-rating">
						<?php for ( $i = 1; $i <= 5; $i++ ) {
							echo ( $i <= $rating ) ? '★' : '☆';
						} ?>
					</div>

					<div class="tm-text">
						"<?php the_content(); ?>"
					</div>

					<div class="tm-client-info">
						<?php echo esc_html( $client_name ); ?>
						<span class="tm-client-meta">
							<?php echo esc_html( $client_position ); ?> 
							<?php echo ( $client_position && $company_name ) ? '|' : ''; ?> 
							<?php echo esc_html( $company_name ); ?>
						</span>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>

		<div class="tm-nav">
			<button class="tm-nav-btn" id="tm-prev">&lt;</button>
			<button class="tm-nav-btn" id="tm-next">&gt;</button>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const container = document.getElementById('tm-slider');
			if(!container) return;
			const slidesWrapper = container.querySelector('.tm-slides');
			const slides = container.querySelectorAll('.tm-testimonial-card');
			const nextBtn = document.getElementById('tm-next');
			const prevBtn = document.getElementById('tm-prev');
			let currentIndex = 0;

            if(slides.length <= 1) {
                nextBtn.style.display = 'none';
                prevBtn.style.display = 'none';
            }

			function updateSlider() {
				slidesWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
			}

			nextBtn.addEventListener('click', () => {
				currentIndex = (currentIndex + 1) % slides.length;
				updateSlider();
			});

			prevBtn.addEventListener('click', () => {
				currentIndex = (currentIndex - 1 + slides.length) % slides.length;
				updateSlider();
			});
		});
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'testimonials', 'tm_testimonials_shortcode' );
