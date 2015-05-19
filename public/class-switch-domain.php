<?php

class mSwitchDomain {
	
	const VERSION = MSD_VERSION; 
	
	protected static $instance = null;
	protected $plugin_slug = 'm-swich-domain';
	
	function __construct() {
		if ( is_multisite() ) {
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
			add_action( 'admin_bar_menu', array( $this, 'display_switch_domain_link' ), 30 );
		}
	}
	
	/**
	 * Adds the switch link.
	 *
	 * @since    1.0.0
	 */
	public function display_switch_domain_link( $wp_admin_bar ) {
		// Don't show for logged out users or single site mode.
		if ( ! is_user_logged_in() || ! is_multisite() || is_network_admin() )
			return;
	
		// Show only when the user has at least one site, or they're a super admin.
		if ( count( $wp_admin_bar->user->blogs ) < 1 && ! is_super_admin() )
			return;
		
		global $pagenow;
		
		$path = $pagenow;
		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$path .= '?' . $_SERVER['QUERY_STRING'];
		}

		$currentBlogID = get_current_blog_id();
		foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {
			if ( $currentBlogID != $blog->userblog_id ) {
				switch_to_blog( $blog->userblog_id );
		
				$menu_id  = 'blog-' . $blog->userblog_id;
		
				$wp_admin_bar->add_menu( array(
					'parent' => $menu_id,
					'id'     => $menu_id . '-msd',
					'title'  => __( 'Switch to Domain' ),
					'href'   => admin_url() . $path,
				) );
		
				restore_current_blog();
			}
		}

	}
	
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
		
		define( 'MSD_TEXT_DOMAIN', $domain );
	}


	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	public static function get_blog_ids() {
		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}
	
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
}


?>