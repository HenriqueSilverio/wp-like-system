<?php
/**
 * @package   WP Like System
 * @author    Henrique Silvério <contato@henriquesilverio.com>
 * @license   GPL-2.0+
 * @link      http://blog.henriquesilverio.com
 * @copyright 2014 Henrique Silvério
 *
 * @wordpress-plugin
 * Plugin Name:       WP Like System
 * Plugin URI:        https://github.com/HenriqueSilverio/wp-like-system
 * Description:       Rating system for posts, based on Facebook likes. Its not dependent of Facebook.
 * Version:           1.1.0
 * Author:            Henrique Silvério
 * Author URI:        http://blog.henriquesilverio.com
 * Text Domain:       wp-like-system
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/HenriqueSilverio/wp-like-system
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
* Text domain
*/
load_plugin_textdomain( 'wp-like-system', false, 'wp-like-system/languages' );


/**
* Register styles and scripts
*/
function has_wpls_register_styles_scripts() {
	wp_register_style(
		'has_wpls_public',
		plugins_url( 'public/assets/css/public.css', __FILE__ )
	);

	wp_register_script(
		'has_wpls_public',
		plugins_url( 'public/assets/js/public.min.js', __FILE__ )
	);
}

add_action( 'wp_enqueue_scripts', 'has_wpls_register_styles_scripts' );


/**
* Enqueue styles and scripts
*/
function has_wpls_enqueue_style_scripts() {
	wp_enqueue_style(
		'has_wpls_public',
		plugins_url( 'public/assets/css/public.css', __FILE__ )
	);

	wp_enqueue_script(
		'has_wpls_public',
		plugins_url( 'public/assets/js/public.min.js', __FILE__ ),
		array( 'jquery' ),
		false,
		true
	);

	wp_localize_script(
		'has_wpls_public',
		'wplsAjax',
		array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'wpls-ajax' )
		)
	);
}

add_action( 'wp_enqueue_scripts', 'has_wpls_enqueue_style_scripts' );


/**
* Ajax hooks
*/
function has_wpls_like_post() {
	$nonce  = esc_attr( $_POST['nonce'] );

	if( false == wp_verify_nonce( $nonce, 'wpls-ajax' ) ) {
		_e( 'You should not be trying to do this.', 'wp-like-system' );
	}

	if( isset( $_POST['doLike'] ) ) {
		$user_IP = $_SERVER['REMOTE_ADDR'];
		$post_ID = esc_attr( $_POST['postID'] );

		$meta_IP  = get_post_meta( $post_ID, 'voted_IP' );
		$voted_IP = $meta_IP[0];

		if( false == is_array( $voted_IP ) ) {
			$voted_IP = array();
		}

		$meta_count = get_post_meta( $post_ID, 'votes_count', true );

		if( false == alreadyVoted( $post_ID ) ) { // first vote
			$voted_IP[$user_IP] = time();

			update_post_meta( $post_ID, 'voted_IP', $voted_IP );
			update_post_meta( $post_ID, 'votes_count', ++$meta_count );

			if( $meta_count >= 3 ) {
				$msg_like = __( 'You and other', 'wp-like-system' ) . ' ' . ( $meta_count - 1 ) . ' ' . __( 'people like that', 'wp-like-system' ) . '.';
			}

			if( $meta_count == 2 ) {
				$msg_like = __( 'You and other people like that.', 'wp-like-system' );
			}

			if( $meta_count == 1 ) {
				$msg_like = __( 'You like that.', 'wp-like-system' );
			}

			$data = array(
				'msg_btn'  => __( 'Like [Undo]', 'wp-like-system' ),
				'msg_like' => $msg_like,
				'total'    => $meta_count
			);

			echo json_encode( $data );
		} else { // already voted
			delete_post_meta( $post_ID, 'voted_IP', $voted_IP );
			update_post_meta( $post_ID, 'votes_count', --$meta_count );

			if( $meta_count >= 2 ) {
				$msg_like = $meta_count . ' ' . __( 'people like that', 'wp-like-system' ) . '.';
			}

			if( $meta_count == 1 ) {
				$msg_like = __( 'One people like that.', 'wp-like-system' );
			}

			if( empty( $meta_count ) ) {
				$msg_like = __( 'Be the first to like!', 'wp-like-system' );
			}

			$data = array(
				'msg_btn'  => __( 'Like', 'wp-like-system' ),
				'msg_like' => $msg_like,
				'total'    => $meta_count
			);

			echo json_encode( $data );
		}
	}

	exit;
}

add_action( 'wp_ajax_nopriv_like-post', 'has_wpls_like_post' );
add_action( 'wp_ajax_like-post', 'has_wpls_like_post' );


/**
 * Check if user already voted
 */
function alreadyVoted( $post_id ) {
	$meta_IP  = get_post_meta( $post_id, 'voted_IP' );
	$voted_IP = $meta_IP[0];

	if( false == is_array( $voted_IP ) ) {
		$voted_IP = array();
	}

	$user_ip = $_SERVER['REMOTE_ADDR'];

	if( in_array( $user_ip, array_keys( $voted_IP ) ) ) { // already voted
		return true;
	} else {
		return false;
	}
}


/**
* Public view
*/
function has_wpls_show_likes( $post_id ) {
	$meta_count = get_post_meta( $post_id, 'votes_count', true );

	if( alreadyVoted( $post_id ) ) {
		$msg_btn = __( 'Like [Undo]', 'wp-like-system' );

		if( $meta_count >= 3 ) {
			$msg_like = __( 'You and other', 'wp-like-system' ) . ' ' . ( $meta_count - 1 ) . ' ' . __( 'people like that', 'wp-like-system' ) . '.';
		}

		if( $meta_count == 2 ) {
			$msg_like = __( 'You and other people like that.', 'wp-like-system' );
		}

		if( $meta_count == 1 ) {
			$msg_like = __( 'You like that.', 'wp-like-system' );
		}
	} else {
		$msg_btn = __( 'Like', 'wp-like-system' );

		if( $meta_count >= 2 ) {
			$msg_like =  $meta_count . ' ' . __( 'people like that', 'wp-like-system' ) . '.';
		}

		if( $meta_count == 1 ) {
			$msg_like = __( 'One people like that.', 'wp-like-system' );
		}

		if( empty( $meta_count ) ) {
			$msg_like = __( 'Be the first to like!', 'wp-like-system' );
		}
	}

	$output = '
		<div class="has_wpls_box">
			<p class="has_wpls_box__item">
				<a id="btn-like" class="has_wpls_box__link" data-postid="' . $post_id . '" href="#">
					<i class="has_wpls_box__icon icon-thumbs-up2"></i>
					<span class="msg-btn">' . $msg_btn . '<span>
				</a>
			</p>
			<p id="msg-like" class="has_wpls_box__item has_wpls_box__item--small">
				' . $msg_like . '
			</p>
		</div>
	';

	echo $output;
}
