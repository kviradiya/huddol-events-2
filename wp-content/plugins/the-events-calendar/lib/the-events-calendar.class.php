<?php
/**
 * Main Tribe Events Calendar class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'TribeEvents' ) ) {

	/**
	 * The Events Calendar Class
	 *
	 * This is where all the magic happens, the unicorns run wild and the leprechauns use WordPress to schedule events.
	 */
	class TribeEvents {
		const EVENTSERROROPT      = '_tribe_events_errors';
		const OPTIONNAME          = 'tribe_events_calendar_options';
		const OPTIONNAMENETWORK   = 'tribe_events_calendar_network_options';
		const TAXONOMY            = 'tribe_events_cat';
		const POSTTYPE            = 'tribe_events';
		const VENUE_POST_TYPE     = 'tribe_venue';
		const ORGANIZER_POST_TYPE = 'tribe_organizer';

		const VERSION       = '3.8.1';
		const FEED_URL      = 'http://tri.be/category/products/feed/';
		const INFO_API_URL  = 'http://wpapi.org/api/plugin/the-events-calendar.php';
		const WP_PLUGIN_URL = 'http://wordpress.org/extend/plugins/the-events-calendar/';

		/**
		 * Notices to be displayed in the admin
		 * @var array
		 */
		protected $notices = array();

		/**
		 * Maybe display data wrapper
		 * @var array
		 */
		private $show_data_wrapper = array( 'before' => true, 'after' => true );

		/**
		 * Args for the event post type
		 * @var array
		 */
		protected $postTypeArgs = array(
			'public'          => true,
			'rewrite'         => array( 'slug' => 'event', 'with_front' => false ),
			'menu_position'   => 6,
			'supports'        => array(
				'title',
				'editor',
				'excerpt',
				'author',
				'thumbnail',
				'custom-fields',
				'comments'
			),
			'taxonomies'      => array( 'post_tag' ),
			'capability_type' => array( 'tribe_event', 'tribe_events' ),
			'map_meta_cap'    => true
		);

		/**
		 * Args for venue post type
		 * @var array
		 */
		public $postVenueTypeArgs = array(
			'public'              => false,
			'rewrite'             => array( 'slug' => 'venue', 'with_front' => false ),
			'show_ui'             => true,
			'show_in_menu'        => 0,
			'supports'            => array( 'title', 'editor' ),
			'capability_type'     => array( 'tribe_venue', 'tribe_venues' ),
			'map_meta_cap'        => true,
			'exclude_from_search' => true
		);

		protected $taxonomyLabels;

		/**
		 * Args for organizer post type
		 * @var array
		 */
		public $postOrganizerTypeArgs = array(
			'public'              => false,
			'rewrite'             => array( 'slug' => 'organizer', 'with_front' => false ),
			'show_ui'             => true,
			'show_in_menu'        => 0,
			'supports'            => array( 'title', 'editor' ),
			'capability_type'     => array( 'tribe_organizer', 'tribe_organizers' ),
			'map_meta_cap'        => true,
			'exclude_from_search' => true
		);

		public static $tribeUrl = 'http://tri.be/';
		public static $addOnPath = 'products/';

		public static $dotOrgSupportUrl = 'http://wordpress.org/tags/the-events-calendar';

		protected static $instance;
		public $rewriteSlug = 'events';
		public $rewriteSlugSingular = 'event';
		public $taxRewriteSlug = 'event/category';
		public $tagRewriteSlug = 'event/tag';
		protected $monthSlug = 'month';

		// @todo remove in 4.0
		protected $upcomingSlug = 'upcoming';
		protected $pastSlug = 'past';

		protected $listSlug = 'list';
		public $daySlug = 'day';
		public $todaySlug = 'today';
		protected $postExceptionThrown = false;

		protected static $options;
		protected static $networkOptions;
		public $displaying;
		public $pluginDir;
		public $pluginPath;
		public $pluginUrl;
		public $pluginName;
		public $date;
		protected $tabIndexStart = 2000;

		public $metaTags = array(
			'_EventAllDay',
			'_EventStartDate',
			'_EventEndDate',
			'_EventDuration',
			'_EventVenueID',
			'_EventShowMapLink',
			'_EventShowMap',
			'_EventCurrencySymbol',
			'_EventCurrencyPosition',
			'_EventCost',
			'_EventURL',
			'_EventOrganizerID',
			'_EventPhone',
			'_EventHideFromUpcoming',
			self::EVENTSERROROPT
		);

		public $venueTags = array(
			'_VenueVenue',
			'_VenueCountry',
			'_VenueAddress',
			'_VenueCity',
			'_VenueStateProvince',
			'_VenueState',
			'_VenueProvince',
			'_VenueZip',
			'_VenuePhone',
			'_VenueURL'
		);

		public $organizerTags = array(
			'_OrganizerOrganizer',
			'_OrganizerEmail',
			'_OrganizerWebsite',
			'_OrganizerPhone'
		);

		public $currentPostTimestamp;

		public $daysOfWeekShort;
		public $daysOfWeek;
		public $daysOfWeekMin;
		public $monthsFull;
		public $monthsShort;

		public $singular_venue_label;
		public $plural_venue_label;

		public $singular_organizer_label;
		public $plural_organizer_label;

		public static $tribeEventsMuDefaults;

		/**
		 * Static Singleton Factory Method
		 * @return TribeEvents
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				$className      = __CLASS__;
				self::$instance = new $className;
			}

			return self::$instance;
		}

		/**
		 * Initializes plugin variables and sets up WordPress hooks/actions.
		 */
		protected function __construct() {
			$this->pluginPath = trailingslashit( dirname( dirname( __FILE__ ) ) );
			$this->pluginDir  = trailingslashit( basename( $this->pluginPath ) );
			$this->pluginUrl  = plugins_url( $this->pluginDir );

			add_action( 'init', array( $this, 'loadTextDomain' ), 1 );

			if ( self::supportedVersion( 'wordpress' ) && self::supportedVersion( 'php' ) ) {

				if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
					register_deactivation_hook( __FILE__, array( $this, 'on_deactivate' ) );
				}

				$this->addHooks();
				$this->loadLibraries();
			} else {
				// Either PHP or WordPress version is inadequate so we simply return an error.
				add_action( 'admin_head', array( $this, 'notSupportedError' ) );
			}
		}

		/**
		 * Load all the required library files.
		 */
		protected function loadLibraries() {
			// Exceptions Helper
			require_once 'tribe-event-exception.class.php';

			// Tribe common resources
			require_once $this->pluginPath . 'vendor/tribe-common-libraries/tribe-common-libraries.class.php';

			// Load Template Tags
			require_once $this->pluginPath . 'public/template-tags/query.php';
			require_once $this->pluginPath . 'public/template-tags/general.php';
			require_once $this->pluginPath . 'public/template-tags/month.php';
			require_once $this->pluginPath . 'public/template-tags/loop.php';
			require_once $this->pluginPath . 'public/template-tags/google-map.php';
			require_once $this->pluginPath . 'public/template-tags/organizer.php';
			require_once $this->pluginPath . 'public/template-tags/venue.php';
			require_once $this->pluginPath . 'public/template-tags/date.php';
			require_once $this->pluginPath . 'public/template-tags/link.php';
			require_once $this->pluginPath . 'public/template-tags/widgets.php';
			require_once $this->pluginPath . 'public/template-tags/meta.php';
			require_once $this->pluginPath . 'public/template-tags/tickets.php';

			// Load Advanced Functions
			require_once $this->pluginPath . 'public/advanced-functions/event.php';
			require_once $this->pluginPath . 'public/advanced-functions/venue.php';
			require_once $this->pluginPath . 'public/advanced-functions/organizer.php';

			// Load Deprecated Template Tags
			if ( ! defined( 'TRIBE_DISABLE_DEPRECATED_TAGS' ) ) {
				require_once $this->pluginPath . 'public/template-tags/deprecated.php';
			}

			// Load Classes
			require_once 'tribe-meta-factory.class.php';
			require_once 'widget-list.class.php';
			require_once 'tribe-admin-events-list.class.php';
			require_once 'tribe-date-utils.class.php';
			require_once 'tribe-template-factory.class.php';
			require_once 'tribe-templates.class.php';
			require_once 'tribe-event-api.class.php';
			require_once 'tribe-event-query.class.php';
			require_once 'tribe-view-helpers.class.php';
			require_once 'tribe-events-bar.class.php';
			require_once 'tribe-support.class.php';
			require_once 'tribe-amalgamator.php';
			require_once 'tribe-events-update.class.php';
			require_once 'EmbeddedMaps.php';
			require_once 'Backcompat.php';

			// Load Template Classes
			require_once 'template-classes/month.php';
			require_once 'template-classes/list.php';
			require_once 'template-classes/day.php';
			require_once 'template-classes/single-event.php';

			// caching
			require_once 'tribe-events-cache.class.php';

			// App Shop
			if ( ! defined( 'TRIBE_HIDE_UPSELL' ) || TRIBE_HIDE_UPSELL !== true ) {
				require_once 'tribe-app-shop.class.php';
			}

			// Tickets
			require_once 'tickets/tribe-tickets-pro.php';
			require_once 'tickets/tribe-ticket-object.php';
			require_once 'tickets/tribe-tickets.php';
			require_once 'tickets/tribe-tickets-metabox.php';

			// CSV Importer
			require_once 'io/csv/ecp-events-importer.php';

			// PUE
			require_once 'pue/pue-client.php';

			// Load multisite defaults
			if ( is_multisite() ) {
				$tribe_events_mu_defaults = array();
				if ( file_exists( WP_CONTENT_DIR . '/tribe-events-mu-defaults.php' ) ) {
					require_once WP_CONTENT_DIR . '/tribe-events-mu-defaults.php';
				}
				self::$tribeEventsMuDefaults = apply_filters( 'tribe_events_mu_defaults', $tribe_events_mu_defaults );
			}
		}

		/**
		 * before_html_data_wrapper adds a persistant tag to wrap the event display with a
		 * way for jQuery to maintain state in the dom. Also has a hook for filtering data
		 * attributes for inclusion in the dom
		 *
		 * @param  string $html
		 *
		 * @return string
		 */
		function before_html_data_wrapper( $html ) {
			global $wp_query;

			if ( ! $this->show_data_wrapper['before'] ) {
				return $html;
			}

			$tec = TribeEvents::instance();

			$data_attributes = array(
				'live_ajax'         => tribe_get_option( 'liveFiltersUpdate', true ) ? 1 : 0,
				'datepicker_format' => tribe_get_option( 'datepickerFormat' ),
				'category'          => is_tax( $tec->get_event_taxonomy() ) ? get_query_var( 'term' ) : ''
			);
			// allow data attributes to be filtered before display
			$data_attributes = (array) apply_filters( 'tribe_events_view_data_attributes', $data_attributes );

			// loop through the attributes and build the html output
			foreach ( $data_attributes as $id => $attr ) {
				$attribute_html[] = sprintf(
					'data-%s="%s"',
					sanitize_title( $id ),
					esc_attr( $attr )
				);
			}

			$this->show_data_wrapper['before'] = false;

			// return filtered html
			return apply_filters( 'tribe_events_view_before_html_data_wrapper', sprintf( '<div id="tribe-events" class="tribe-no-js" %s>%s', implode( ' ', $attribute_html ), $html ), $data_attributes, $html );
		}

		/**
		 * after_html_data_wrapper close out the persistant dom wrapper
		 *
		 * @param  string $html
		 *
		 * @return string
		 */
		function after_html_data_wrapper( $html ) {
			if ( ! $this->show_data_wrapper['after'] ) {
				return $html;
			}

			$html .= '</div><!-- #tribe-events -->';
			$html .= tribe_events_promo_banner( false );
			$this->show_data_wrapper['after'] = false;

			return apply_filters( 'tribe_events_view_after_html_data_wrapper', $html );
		}

		/**
		 * Add filters and actions
		 */
		protected function addHooks() {
			add_action( 'init', array( $this, 'init' ), 10 );

			// Frontend Javascript
			add_action( 'wp_enqueue_scripts', array( $this, 'loadStyle' ) );
			add_filter( 'tribe_events_before_html', array( $this, 'before_html_data_wrapper' ) );
			add_filter( 'tribe_events_after_html', array( $this, 'after_html_data_wrapper' ) );

			// Styling
			add_filter( 'post_class', array( $this, 'post_class' ) );
			add_filter( 'body_class', array( $this, 'body_class' ) );
			add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );


			add_filter( 'query_vars', array( $this, 'eventQueryVars' ) );
			add_filter( 'bloginfo_rss', array( $this, 'add_space_to_rss' ) );
			add_filter( 'post_updated_messages', array( $this, 'updatePostMessage' ) );

			/* Add nav menu item - thanks to http://wordpress.org/extend/plugins/cpt-archives-in-nav-menus/ */
			add_filter( 'nav_menu_items_' . TribeEvents::POSTTYPE, array( $this, 'add_events_checkbox_to_menu' ), null, 3 );
			add_filter( 'wp_nav_menu_objects', array( $this, 'add_current_menu_item_class_to_events' ), null, 2 );

			add_filter( 'generate_rewrite_rules', array( $this, 'filterRewriteRules' ) );
			add_filter( 'template_redirect', array( $this, 'redirect_past_upcoming_view_urls' ), 11 );

			/* Setup Tribe Events Bar */
			// add_filter( 'tribe-events-bar-views', array( $this, 'setup_listview_in_bar' ), 1, 1 );
			add_filter( 'tribe-events-bar-views', array( $this, 'setup_gridview_in_bar' ), 5, 1 );
			// add_filter( 'tribe-events-bar-views', array( $this, 'setup_dayview_in_bar' ), 15, 1 );

			add_filter( 'tribe-events-bar-filters', array( $this, 'setup_date_search_in_bar' ), 1, 1 );
			add_filter( 'tribe-events-bar-filters', array( $this, 'setup_keyword_search_in_bar' ), 1, 1 );

			add_filter( 'tribe-events-bar-views', array( $this, 'remove_hidden_views' ), 9999, 2 );
			/* End Setup Tribe Events Bar */

			add_filter( 'admin_footer_text', array( $this, 'tribe_admin_footer_text' ), 1, 2 );
			add_action( 'admin_menu', array( $this, 'addEventBox' ) );
			add_action( 'wp_insert_post', array( $this, 'addPostOrigin' ), 10, 2 );
			add_action( 'save_post_' . self::POSTTYPE, array( $this, 'addEventMeta' ), 15, 2 );

			add_action( 'save_post_' . self::VENUE_POST_TYPE, array( $this, 'save_venue_data' ), 16, 2 );
			add_action( 'save_post_' . self::ORGANIZER_POST_TYPE, array( $this, 'save_organizer_data' ), 16, 2 );
			add_action( 'save_post_' . self::POSTTYPE, array( $this, 'maybe_update_known_range' ) );
			add_action( 'tribe_events_csv_import_complete', array( $this, 'rebuild_known_range' ) );
			add_action( 'publish_' . self::POSTTYPE, array( $this, 'publishAssociatedTypes' ), 25, 2 );
			add_action( 'delete_post', array( $this, 'maybe_rebuild_known_range' ) );
			add_action( 'parse_query', array( $this, 'setDisplay' ), 51, 0 );
			add_action( 'tribe_events_post_errors', array( 'TribeEventsPostException', 'displayMessage' ) );
			add_action( 'tribe_settings_top', array( 'TribeEventsOptionsException', 'displayMessage' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'addAdminScriptsAndStyles' ) );
			add_filter( 'tribe_events_register_event_type_args', array( $this, 'setDashicon' ) );
			add_action( "trash_" . TribeEvents::VENUE_POST_TYPE, array( $this, 'cleanupPostVenues' ) );
			add_action( "trash_" . TribeEvents::ORGANIZER_POST_TYPE, array( $this, 'cleanupPostOrganizers' ) );
			add_action( 'wp_ajax_tribe_event_validation', array( $this, 'ajax_form_validate' ) );
			add_action( 'tribe_debug', array( $this, 'renderDebug' ), 10, 2 );
			add_action( 'tribe_debug', array( $this, 'renderDebug' ), 10, 2 );
			add_action( 'plugins_loaded', array( 'TribeEventsCacheListener', 'instance' ) );
			add_action( 'plugins_loaded', array( 'TribeEventsCache', 'setup' ) );

			// Load organizer and venue editors
			add_action( 'admin_menu', array( $this, 'addVenueAndOrganizerEditor' ) );
			add_action( 'tribe_venue_table_top', array( $this, 'displayEventVenueDropdown' ) );
			add_action( 'tribe_organizer_table_top', array( $this, 'displayEventOrganizerDropdown' ) );

			add_action( 'template_redirect', array( $this, 'template_redirect' ) );

			// noindex grid view
			add_action( 'wp_head', array( $this, 'noindex_months' ) );
			add_action( 'wp', array( $this, 'issue_noindex_on_404' ), 10, 0 );
			add_action( 'plugin_row_meta', array( $this, 'addMetaLinks' ), 10, 2 );
			// organizer and venue
			if ( ! defined( 'TRIBE_HIDE_UPSELL' ) || ! TRIBE_HIDE_UPSELL ) {
				add_action( 'wp_dashboard_setup', array( $this, 'dashboardWidget' ) );
				add_action( 'tribe_events_cost_table', array( $this, 'maybeShowMetaUpsell' ) );
			}
			// option pages
			add_action( '_network_admin_menu', array( $this, 'initOptions' ) );
			add_action( '_admin_menu', array( $this, 'initOptions' ) );
			add_action( 'tribe_settings_do_tabs', array( $this, 'doSettingTabs' ) );
			add_action( 'tribe_settings_do_tabs', array( $this, 'doNetworkSettingTab' ), 400 );
			add_action( 'tribe_settings_content_tab_help', array( $this, 'doHelpTab' ) );
			add_action( 'tribe_settings_validate_tab_network', array( $this, 'saveAllTabsHidden' ) );

			add_action( 'load-tribe_events_page_tribe-events-calendar', array( 'Tribe_Amalgamator', 'listen_for_migration_button' ), 10, 0 );
			add_action( 'tribe_settings_after_save', array( $this, 'flushRewriteRules' ) );
			add_action( 'load-edit-tags.php', array( $this, 'prepare_to_fix_tagcloud_links' ), 10, 0 );

			// add-on compatibility
			if ( is_multisite() ) {
				add_action( 'network_admin_notices', array( $this, 'checkAddOnCompatibility' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'checkAddOnCompatibility' ) );
			}

			add_action( 'wp_before_admin_bar_render', array( $this, 'addToolbarItems' ), 10 );
			add_action( 'admin_notices', array( $this, 'activationMessage' ) );
			add_action( 'all_admin_notices', array( $this, 'addViewCalendar' ) );
			add_action( 'admin_head', array( $this, 'setInitialMenuMetaBoxes' ), 500 );
			add_action( 'plugin_action_links_' . trailingslashit( $this->pluginDir ) . 'the-events-calendar.php', array( $this, 'addLinksToPluginActions' ) );
			add_action( 'admin_menu', array( $this, 'addHelpAdminMenuItem' ), 50 );

			add_action( 'tribe_events_pre_get_posts', array( $this, 'set_tribe_paged' ) );

			// Upgrade material.
			add_action( 'admin_init', array( $this, 'checkSuiteIfJustUpdated' ) );

			if ( defined( 'WP_LOAD_IMPORTERS' ) && WP_LOAD_IMPORTERS ) {
				add_filter( 'wp_import_post_data_raw', array( $this, 'filter_wp_import_data_before' ), 10, 1 );
				add_filter( 'wp_import_post_data_processed', array( $this, 'filter_wp_import_data_after' ), 10, 1 );
			}


			add_action( 'plugins_loaded', array( $this, 'init_ical' ), 2, 0 );
			add_action( 'plugins_loaded', array( $this, 'init_day_view' ), 2 );


		}

		public function tribe_admin_footer_text( $footer_text ) {
			global $current_screen;

			// only display custom text on Tribe Admin Pages
			if ( isset( $current_screen->id ) && strpos( $current_screen->id, 'tribe' ) !== false ) {
				return sprintf( __( 'Rate <strong>The Events Calendar</strong> <a href="%1$s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%1$s" target="_blank">WordPress.org</a> to keep this plugin free.  Thanks from the friendly folks at Modern Tribe.', 'tribe-events-calendar' ), __( 'http://wordpress.org/support/view/plugin-reviews/the-events-calendar?filter=5', 'tribe-events-calendar' ) );
			} else {
				return $footer_text;
			}
		}

		/**
		 * Load the ical template tags
		 * Loaded late due to potential upgrade conflict since moving them from pro
		 * @TODO move this require to be with the rest of the template tag includes in 3.9
		 */
		public function init_ical() {
			//iCal
			if ( ! class_exists( 'TribeiCal' ) ) {
				require_once 'tribe-ical.class.php';
				TribeiCal::init();
				require_once $this->pluginPath . 'public/template-tags/ical.php';
			}
		}

		/**
		 * Allow users to specify their own plural label for Venues
		 * @return string
		 */
		public function get_venue_label_plural() {
			return apply_filters( 'tribe_venue_label_plural', __( 'Venues', 'tribe-events-calendar' ) );
		}

		/**
		 * Allow users to specify their own singular label for Venues
		 * @return string
		 */
		public function get_venue_label_singular() {
			return apply_filters( 'tribe_venue_label_singular', __( 'Venue', 'tribe-events-calendar' ) );
		}

		/**
		 * Allow users to specify their own plural label for Organizers
		 * @return string
		 */
		public function get_organizer_label_plural() {
			return apply_filters( 'tribe_organizer_label_plural', __( 'Organizers', 'tribe-events-calendar' ) );
		}

		/**
		 * Allow users to specify their own singular label for Organizers
		 * @return string
		 */
		public function get_organizer_label_singular() {
			return apply_filters( 'tribe_organizer_label_singular', __( 'Organizer', 'tribe-events-calendar' ) );
		}

		/**
		 * Load the day view template tags
		 * Loaded late due to potential upgrade conflict since moving them from pro
		 * @TODO move this require to be with the rest of the template tag includes in 3.9
		 */
		public function init_day_view() {
			// load day view functions
			require_once $this->pluginPath . 'public/template-tags/day.php';
		}


		/**
		 * Add code to tell search engines not to index the grid view of the
		 * calendar.  Users were seeing 100s of months being indexed.
		 */
		public function noindex_months() {
			if ( get_query_var( 'eventDisplay' ) == 'month' ) {
				$this->print_noindex_meta();
			}
		}

		public function issue_noindex_on_404() {
			if ( is_404() ) {
				global $wp_query;
				if ( ! empty( $wp_query->tribe_is_event_query ) ) {
					add_action( 'wp_head', array( $this, 'print_noindex_meta' ), 10, 0 );
				}
			}
		}


		public function print_noindex_meta() {
			echo ' <meta name="robots" content="noindex,follow" />' . "\n";
		}

		/**
		 * Run on applied action init
		 */
		public function init() {
			$this->pluginName                                 = __( 'The Events Calendar', 'tribe-events-calendar' );
			$this->rewriteSlug                                = $this->getRewriteSlug();
			$this->rewriteSlugSingular                        = $this->getRewriteSlugSingular();
			$this->taxRewriteSlug                             = $this->getTaxRewriteSlug();
			$this->tagRewriteSlug                             = $this->getTagRewriteSlug();
			$this->monthSlug                                  = sanitize_title( __( 'month', 'tribe-events-calendar' ) );
			$this->listSlug                               	  = sanitize_title( __( 'list', 'tribe-events-calendar' ) );
			$this->upcomingSlug                               = sanitize_title( __( 'upcoming', 'tribe-events-calendar' ) );
			$this->pastSlug                                   = sanitize_title( __( 'past', 'tribe-events-calendar' ) );
			$this->daySlug                                    = sanitize_title( __( 'day', 'tribe-events-calendar' ) );
			$this->todaySlug                                  = sanitize_title( __( 'today', 'tribe-events-calendar' ) );
			$this->singular_venue_label                       = $this->get_venue_label_singular();
			$this->plural_venue_label                         = $this->get_venue_label_plural();
			$this->singular_organizer_label                   = $this->get_organizer_label_singular();
			$this->plural_organizer_label                     = $this->get_organizer_label_plural();
			$this->postTypeArgs['rewrite']['slug']            = sanitize_title( $this->rewriteSlugSingular );
			$this->postVenueTypeArgs['rewrite']['slug']       = sanitize_title( $this->singular_venue_label );
			$this->postVenueTypeArgs['show_in_nav_menus']     = class_exists( 'TribeEventsPro' ) ? true : false;
			$this->postOrganizerTypeArgs['rewrite']['slug']   = sanitize_title( $this->singular_organizer_label );
			$this->postOrganizerTypeArgs['show_in_nav_menus'] = class_exists( 'TribeEventsPro' ) ? true : false;
			$this->postVenueTypeArgs['public']                = class_exists( 'TribeEventsPro' ) ? true : false;
			$this->postOrganizerTypeArgs['public']            = class_exists( 'TribeEventsPro' ) ? true : false;
			$this->currentDay                                 = '';
			$this->errors                                     = '';

			TribeEventsQuery::init();
			Tribe__Events__Backcompat::init();
			$this->registerPostType();

			self::debug( sprintf( __( 'Initializing Tribe Events on %s', 'tribe-events-calendar' ), date( 'M, jS \a\t h:m:s a' ) ) );
			$this->maybeMigrateDatabase();
			$this->maybeSetTECVersion();
		}

		/**
		 * Upgrade the database if an older version of events was installed.
		 *
		 */
		public function maybeMigrateDatabase() {
			// future migrations should actually check the db_version

			$installed_version = get_option( 'tribe_events_db_version' );
			if ( ! $installed_version ) {
				global $wpdb;
				// rename option
				update_option( self::OPTIONNAME, get_option( 'sp_events_calendar_options' ) );
				delete_option( 'sp_events_calendar_options' );

				// update post type names
				$wpdb->update( $wpdb->posts, array( 'post_type' => self::POSTTYPE ), array( 'post_type' => 'sp_events' ) );
				$wpdb->update( $wpdb->posts, array( 'post_type' => self::VENUE_POST_TYPE ), array( 'post_type' => 'sp_venue' ) );
				$wpdb->update( $wpdb->posts, array( 'post_type' => self::ORGANIZER_POST_TYPE ), array( 'post_type' => 'sp_organizer' ) );

				// update taxonomy names
				$wpdb->update( $wpdb->term_taxonomy, array( 'taxonomy' => self::TAXONOMY ), array( 'taxonomy' => 'sp_events_cat' ) );
				$installed_version = '2.0.1';
				update_option( 'tribe_events_db_version', $installed_version );
			}

			if ( version_compare( $installed_version, '2.0.6', '<' ) ) {
				$option_names     = array(
					'spEventsTemplate'   => 'tribeEventsTemplate',
					'spEventsBeforeHTML' => 'tribeEventsBeforeHTML',
					'spEventsAfterHTML'  => 'tribeEventsAfterHTML',
				);
				$old_option_names = array_keys( $option_names );
				$new_option_names = array_values( $option_names );
				$new_options      = array();
				$current_options  = self::getOptions();
				for ( $i = 0; $i < count( $old_option_names ); $i ++ ) {
					$new_options[$new_option_names[$i]] = $this->getOption( $old_option_names[$i] );
					unset( $current_options[$old_option_names[$i]] );
				}
				$this->setOptions( wp_parse_args( $new_options, $current_options ) );
				$installed_version = '2.0.6';
				update_option( 'tribe_events_db_version', $installed_version );
			}

			if ( version_compare( get_option( 'tribe_events_db_version' ), '3', '<' ) ) {
				$installed_version = '3.0.0';
				update_option( 'tribe_events_db_version', $installed_version );
			}
		}

		/**
		 * Set the Calendar Version in the options table if it's not already set.
		 *
		 */
		public function maybeSetTECVersion() {
			if ( version_compare( $this->getOption( 'latest_ecp_version' ), self::VERSION, '<' ) ) {
				$previous_versions   = $this->getOption( 'previous_ecp_versions' ) ? $this->getOption( 'previous_ecp_versions' ) : array();
				$previous_versions[] = ( $this->getOption( 'latest_ecp_version' ) ) ? $this->getOption( 'latest_ecp_version' ) : '0';

				$this->setOption( 'previous_ecp_versions', $previous_versions );
				$this->setOption( 'latest_ecp_version', self::VERSION );
			}
		}

		/**
		 * Check add-ons to make sure they are supported by currently running TEC version.
		 *
		 * @return void
		 */
		public function checkAddOnCompatibility() {
			// Variable for storing output to admin notices.
			$output = '';
			// Array to store any plugins that are out of date.
			$bad_versions = array();
			// Array to store all addons and their required CORE versions.
			$tec_addons_required_versions = array();
			// Array to store NAMES ONLY of any plugins that are out of date.
			$out_of_date_addons = array();
			// Is Core the thing that is out of date?
			$tec_out_of_date = false;

			// Get the addon information.
			$tec_addons_required_versions = (array) apply_filters( 'tribe_tec_addons', $tec_addons_required_versions );
			// Foreach addon, make sure that it is compatible with current version of core.
			foreach ( $tec_addons_required_versions as $plugin ) {
				if ( ! strstr( self::VERSION, $plugin['required_version'] ) ) {
					if ( isset( $plugin['current_version'] ) ) {
						$bad_versions[] = $plugin;
					}
					if ( ( isset( $plugin['plugin_dir_file'] ) ) ) {
						$addon_short_path = $plugin['plugin_dir_file'];
					} else {
						$addon_short_path = null;
					}
				}
				// Check to make sure Core isn't the thing that is out of date.
				if ( version_compare( $plugin['required_version'], self::VERSION, '>' ) ) {
					$tec_out_of_date = true;
				}
			}
			// If Core is out of date, generate the proper message.
			if ( $tec_out_of_date == true ) {
				$plugin_short_path = basename( dirname( dirname( __FILE__ ) ) ) . '/the-events-calendar.php';
				$upgrade_path      = wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'upgrade-plugin',
							'plugin' => $plugin_short_path
						), get_admin_url() . 'update.php'
					), 'upgrade-plugin_' . $plugin_short_path
				);
				$output .= '<div class="error">';
				$output .= '<p>' . sprintf( __( 'Your version of The Events Calendar is not up-to-date with one of your The Events Calendar add-ons. Please %supdate now.%s', 'tribe-events-calendar' ), '<a href="' . $upgrade_path . '">', '</a>' ) . '</p>';
				$output .= '</div>';
			} else {
				// Otherwise, if the addons are out of date, generate the proper messaging.
				if ( ! empty( $bad_versions ) ) {
					foreach ( $bad_versions as $plugin ) {
						if ( $plugin['current_version'] ) {
							$out_of_date_addons[] = $plugin['plugin_name'] . ' ' . $plugin['current_version'];
						} else {
							$out_of_date_addons[] = $plugin['plugin_name'];
						}
					}
					$output .= '<div class="error">';
					$link = add_query_arg(
						array(
							'utm_campaign' => 'in-app',
							'utm_medium'   => 'plugin-tec',
							'utm_source'   => 'notice'
						), self::$tribeUrl . 'version-relationships-in-modern-tribe-pluginsadd-ons/'
					);
					$output .= '<p>' . sprintf( __( 'The following plugins are out of date: <b>%s</b>. All add-ons contain dependencies on The Events Calendar and will not function properly unless paired with the right version. %sWant to pair an older version%s?', 'tribe-events-calendar' ), join( $out_of_date_addons, ', ' ), "<a href='$link' target='_blank'>", '</a>' ) . '</p>';
					$output .= '</div>';
				}
			}
			// Make sure only to show the message if the user has the permissions necessary.
			if ( current_user_can( 'edit_plugins' ) ) {
				echo apply_filters( 'tribe_add_on_compatibility_errors', $output );
			}
		}

		/**
		 * Init the settings API and add a hook to add your own setting tabs
		 *
		 * @return void
		 */
		public function initOptions() {
			require_once( 'tribe-settings.class.php' );
			require_once( 'tribe-settings-tab.class.php' );
			require_once( 'tribe-field.class.php' );
			require_once( 'tribe-validate.class.php' );
			require_once( 'Activation_Page.php' );

			TribeSettings::instance();
			Tribe__Events__Activation_Page::init();
		}

		/**
		 * Trigger is_404 on single event if no events are found
		 * @return void
		 */
		function template_redirect() {
			global $wp_query;
			if ( $wp_query->tribe_is_event_query && TribeEvents::instance()->displaying == 'single-event' && empty( $wp_query->posts ) ) {
				$wp_query->is_404 = true;
			}
		}

		/**
		 * Create setting tabs
		 *
		 * @return void
		 */
		public function doSettingTabs() {

			include_once( $this->pluginPath . 'admin-views/tribe-options-general.php' );
			include_once( $this->pluginPath . 'admin-views/tribe-options-display.php' );

			$showNetworkTabs = $this->getNetworkOption( 'showSettingsTabs', false );

			$link = add_query_arg(
				array(
					'utm_campaign' => 'in-app',
					'utm_medium'   => 'plugin-tec',
					'utm_source'   => 'notice'
				), self::$tribeUrl . 'license-keys/'
			);

			$tribe_licences_tab_fields = array(
				'info-start'               => array(
					'type' => 'html',
					'html' => '<div id="modern-tribe-info">'
				),
				'info-box-title'           => array(
					'type' => 'html',
					'html' => '<h2>' . __( 'Licenses', 'tribe-events-calendar' ) . '</h2>',
				),
				'info-box-description'     => array(
					'type' => 'html',
					'html' => sprintf(
						__( '<p>The license key you received when completing your purchase from %s will grant you access to support and updates until it expires. You do not need to enter the key below for the plugins to work, but you will need to enter it to get automatic updates. <strong>Find your license keys at <a href="%s" target="_blank">%s</a></strong>.</p> <p>Each paid add-on has its own unique license key. Simply paste the key into its appropriate field on below, and give it a moment to validate. You know you\'re set when a green expiration date appears alongside a "valid" message.</p> <p>If you\'re seeing a red message telling you that your key isn\'t valid or is out of installs, visit <a href="%s" target="_blank">%s</a> to manage your installs or renew / upgrade your license.</p><p>Not seeing an update but expecting one? In WordPress, go to <a href="%s">Dashboard > Updates</a> and click "Check Again".</p>', 'tribe-events-calendar' ),
						self::$tribeUrl,
						$link,
						self::$tribeUrl . 'license-keys/',
						$link,
						self::$tribeUrl . 'license-keys/',
						admin_url( '/update-core.php' )
					),
				),
				'info-end'                 => array(
					'type' => 'html',
					'html' => '</div>'
				),
				'tribe-form-content-start' => array(
					'type' => 'html',
					'html' => '<div class="tribe-settings-form-wrap">'
				),
				// TODO: Figure out how properly close this wrapper after the license content
				'tribe-form-content-end'   => array(
					'type' => 'html',
					'html' => '</div>'
				)
			);
			new TribeSettingsTab( 'general', __( 'General', 'tribe-events-calendar' ), $generalTab );
			new TribeSettingsTab( 'display', __( 'Display', 'tribe-events-calendar' ), $displayTab );
			// If none of the addons are activated, do not show the licenses tab.

			$addons = apply_filters( 'tribe_licensable_addons', array() );
			if ( ! empty( $addons ) ) {
				$license_fields = apply_filters( 'tribe_license_fields', $tribe_licences_tab_fields );
				if ( is_multisite() ) {
					new TribeSettingsTab( 'licenses', __( 'Licenses', 'tribe-events-calendar' ), array(
						'priority'      => '40',
						'network_admin' => true,
						'fields'        => $license_fields
					) );
				} else {
					new TribeSettingsTab( 'licenses', __( 'Licenses', 'tribe-events-calendar' ), array(
						'priority' => '40',
						'fields'   => $license_fields
					) );
				}
			}
			new TribeSettingsTab( 'help', __( 'Help', 'tribe-events-calendar' ), array(
				'priority'  => 60,
				'show_save' => false
			) );
		}

		/**
		 * Create the help tab
		 */
		public function doHelpTab() {
			include_once( $this->pluginPath . 'admin-views/tribe-options-help.php' );
		}

		/**
		 * Test PHP and WordPress versions for compatibility
		 *
		 * @param string $system - system to be tested such as 'php' or 'wordpress'
		 *
		 * @return boolean - is the existing version of the system supported?
		 */
		public function supportedVersion( $system ) {
			if ( $supported = wp_cache_get( $system, 'tribe_version_test' ) ) {
				return $supported;
			} else {
				switch ( strtolower( $system ) ) {
					case 'wordpress' :
						$supported = version_compare( get_bloginfo( 'version' ), '3.0', '>=' );
						break;
					case 'php' :
						$supported = version_compare( phpversion(), '5.2', '>=' );
						break;
				}
				$supported = apply_filters( 'tribe_events_supported_version', $supported, $system );
				wp_cache_set( $system, $supported, 'tribe_version_test' );

				return $supported;
			}
		}

		/**
		 * Display a WordPress or PHP incompatibility error
		 */
		public function notSupportedError() {
			if ( ! self::supportedVersion( 'wordpress' ) ) {
				echo '<div class="error"><p>' . sprintf( __( 'Sorry, The Events Calendar requires WordPress %s or higher. Please upgrade your WordPress install.', 'tribe-events-calendar' ), '3.0' ) . '</p></div>';
			}
			if ( ! self::supportedVersion( 'php' ) ) {
				echo '<div class="error"><p>' . sprintf( __( 'Sorry, The Events Calendar requires PHP %s or higher. Talk to your Web host about moving you to a newer version of PHP.', 'tribe-events-calendar' ), '5.2' ) . '</p></div>';
			}
		}

		/**
		 * Add a menu item class to the event
		 *
		 * @param array $items
		 * @param array $args
		 *
		 * @return array
		 */
		public function add_current_menu_item_class_to_events( $items, $args ) {
			foreach ( $items as $item ) {
				if ( $item->url == $this->getLink() ) {
					if ( is_singular( TribeEvents::POSTTYPE )
						 || is_singular( TribeEvents::VENUE_POST_TYPE )
						 || is_tax( TribeEvents::TAXONOMY )
						 || ( ( tribe_is_upcoming()
								|| tribe_is_past()
								|| tribe_is_month() )
							  && isset( $wp_query->query_vars['eventDisplay'] ) )
					) {
						$item->classes[] = 'current-menu-item current_page_item';
					}
					break;
				}
			}

			return $items;
		}

		/**
		 * Add a checkbox to the menu
		 *
		 * @param array  $posts
		 * @param array  $args
		 * @param string $post_type
		 *
		 * @return array
		 */
		public function add_events_checkbox_to_menu( $posts, $args, $post_type ) {
			global $_nav_menu_placeholder, $wp_rewrite;
			$_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval( $_nav_menu_placeholder ) - 1 : - 1;
			$archive_slug          = $this->getLink();

			array_unshift(
				$posts, (object) array(
					'ID'           => 0,
					'object_id'    => $_nav_menu_placeholder,
					'post_content' => '',
					'post_excerpt' => '',
					'post_title'   => $post_type['args']->labels->all_items,
					'post_type'    => 'nav_menu_item',
					'type'         => 'custom',
					'url'          => $archive_slug,
				)
			);

			return $posts;
		}

		/**
		 * Tribe debug function. usage: TribeEvents::debug( 'Message', $data, 'log' );
		 *
		 * @param string      $title  Message to display in log
		 * @param string|bool $data   Optional data to display
		 * @param string      $format Optional format (log|warning|error|notice)
		 *
		 * @return void
		 */
		public static function debug( $title, $data = false, $format = 'log' ) {
			do_action( 'tribe_debug', $title, $data, $format );
		}

		/**
		 * Render the debug logging to the php error log. This can be over-ridden by removing the filter.
		 *
		 * @param string      $title  - message to display in log
		 * @param string|bool $data   - optional data to display
		 * @param string      $format - optional format (log|warning|error|notice)
		 *
		 * @return void
		 */
		public function renderDebug( $title, $data = false, $format = 'log' ) {
			$format = ucfirst( $format );
			if ( $this->getOption( 'debugEvents' ) ) {
				error_log( $this->pluginName . " $format: $title" );
				if ( $data && $data != '' ) {
					error_log( $this->pluginName . " $format: " . print_r( $data, true ) );
				}
			}
		}

		/**
		 * Define an admin notice
		 *
		 * @param string $key
		 * @param string $notice
		 *
		 * @return bool
		 */
		public static function setNotice( $key, $notice ) {
			self::instance()->notices[$key] = $notice;

			return true;
		}

		/**
		 * Check to see if an admin notice exists
		 *
		 * @param string $key
		 *
		 * @return bool
		 */
		public static function isNotice( $key ) {
			return ! empty( self::instance()->notices[$key] ) ? true : false;
		}

		/**
		 * Remove an admin notice
		 *
		 * @param string $key
		 *
		 * @return bool
		 */
		public static function removeNotice( $key ) {
			if ( self::isNotice( $key ) ) {
				unset( self::instance()->notices[$key] );

				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get the admin notices
		 *
		 * @return array
		 */
		public static function getNotices() {
			return self::instance()->notices;
		}

		/**
		 * Get the event taxonomy
		 *
		 * @return string
		 */
		public function get_event_taxonomy() {
			return self::TAXONOMY;
		}

		/**
		 * Add space to the title in RSS
		 *
		 * @param string $title
		 *
		 * @return string
		 */
		public function add_space_to_rss( $title ) {
			global $wp_query;
			if ( get_query_var( 'eventDisplay' ) == 'upcoming' && get_query_var( 'post_type' ) == TribeEvents::POSTTYPE ) {
				return $title . ' ';
			}

			return $title;
		}

		/**
		 * Sorts the meta to ensure we are getting the real start date
		 * @deprecated since 3.4
		 *
		 * @param int $postId
		 *
		 * @return string
		 * @todo remove - unused
		 */
		public static function getRealStartDate( $postId ) {
			return TribeEvents::get_series_start_date( $postId );
		}

		/**
		 * Update body classes
		 *
		 * @param array $classes
		 *
		 * @return array
		 * @TODO move this to template class
		 */
		public function body_class( $classes ) {
			if ( get_query_var( 'post_type' ) == self::POSTTYPE ) {
				if ( ! is_admin() && tribe_get_option( 'liveFiltersUpdate', true ) ) {
					$classes[] = 'tribe-filter-live';
				}
			}

			return $classes;
		}

		/**
		 * Update post classes
		 *
		 * @param array $classes
		 *
		 * @return array
		 * @TODO move this to template class
		 */
		public function post_class( $classes ) {
			global $post;
			if ( is_object( $post ) && isset( $post->post_type ) && $post->post_type == self::POSTTYPE && $terms = get_the_terms( $post->ID, self::TAXONOMY ) ) {
				foreach ( $terms as $term ) {
					$classes[] = 'cat_' . sanitize_html_class( $term->slug, $term->term_taxonomy_id );
				}
			}

			// Remove the .hentry class if it is a single event page (it is positioned elsewhere in the template markup)
			if ( tribe_is_event( $post->ID ) && is_singular() && in_array( 'hentry', $classes ) ) {
				unset( $classes[array_search( 'hentry', $classes )] );
			}

			return $classes;
		}

		/**
		 * Add capabilities to Events
		 *
		 * @return void
		 */
		private function addCapabilities() {
			$role = get_role( 'administrator' );
			if ( $role ) {
				$role->add_cap( 'edit_tribe_event' );
				$role->add_cap( 'read_tribe_event' );
				$role->add_cap( 'delete_tribe_event' );
				$role->add_cap( 'delete_tribe_events' );
				$role->add_cap( 'edit_tribe_events' );
				$role->add_cap( 'edit_others_tribe_events' );
				$role->add_cap( 'delete_others_tribe_events' );
				$role->add_cap( 'publish_tribe_events' );
				$role->add_cap( 'edit_published_tribe_events' );
				$role->add_cap( 'delete_published_tribe_events' );
				$role->add_cap( 'delete_private_tribe_events' );
				$role->add_cap( 'edit_private_tribe_events' );
				$role->add_cap( 'read_private_tribe_events' );

				$role->add_cap( 'edit_tribe_venue' );
				$role->add_cap( 'read_tribe_venue' );
				$role->add_cap( 'delete_tribe_venue' );
				$role->add_cap( 'delete_tribe_venues' );
				$role->add_cap( 'edit_tribe_venues' );
				$role->add_cap( 'edit_others_tribe_venues' );
				$role->add_cap( 'delete_others_tribe_venues' );
				$role->add_cap( 'publish_tribe_venues' );
				$role->add_cap( 'edit_published_tribe_venues' );
				$role->add_cap( 'delete_published_tribe_venues' );
				$role->add_cap( 'delete_private_tribe_venues' );
				$role->add_cap( 'edit_private_tribe_venues' );
				$role->add_cap( 'read_private_tribe_venues' );

				$role->add_cap( 'edit_tribe_organizer' );
				$role->add_cap( 'read_tribe_organizer' );
				$role->add_cap( 'delete_tribe_organizer' );
				$role->add_cap( 'delete_tribe_organizers' );
				$role->add_cap( 'edit_tribe_organizers' );
				$role->add_cap( 'edit_others_tribe_organizers' );
				$role->add_cap( 'delete_others_tribe_organizers' );
				$role->add_cap( 'publish_tribe_organizers' );
				$role->add_cap( 'edit_published_tribe_organizers' );
				$role->add_cap( 'delete_published_tribe_organizers' );
				$role->add_cap( 'delete_private_tribe_organizers' );
				$role->add_cap( 'edit_private_tribe_organizers' );
				$role->add_cap( 'read_private_tribe_organizers' );
			}

			$editor = get_role( 'editor' );
			if ( $editor ) {
				$editor->add_cap( 'edit_tribe_event' );
				$editor->add_cap( 'read_tribe_event' );
				$editor->add_cap( 'delete_tribe_event' );
				$editor->add_cap( 'delete_tribe_events' );
				$editor->add_cap( 'edit_tribe_events' );
				$editor->add_cap( 'edit_others_tribe_events' );
				$editor->add_cap( 'delete_others_tribe_events' );
				$editor->add_cap( 'publish_tribe_events' );
				$editor->add_cap( 'edit_published_tribe_events' );
				$editor->add_cap( 'delete_published_tribe_events' );
				$editor->add_cap( 'delete_private_tribe_events' );
				$editor->add_cap( 'edit_private_tribe_events' );
				$editor->add_cap( 'read_private_tribe_events' );

				$editor->add_cap( 'edit_tribe_venue' );
				$editor->add_cap( 'read_tribe_venue' );
				$editor->add_cap( 'delete_tribe_venue' );
				$editor->add_cap( 'delete_tribe_venues' );
				$editor->add_cap( 'edit_tribe_venues' );
				$editor->add_cap( 'edit_others_tribe_venues' );
				$editor->add_cap( 'delete_others_tribe_venues' );
				$editor->add_cap( 'publish_tribe_venues' );
				$editor->add_cap( 'edit_published_tribe_venues' );
				$editor->add_cap( 'delete_published_tribe_venues' );
				$editor->add_cap( 'delete_private_tribe_venues' );
				$editor->add_cap( 'edit_private_tribe_venues' );
				$editor->add_cap( 'read_private_tribe_venues' );

				$editor->add_cap( 'edit_tribe_organizer' );
				$editor->add_cap( 'read_tribe_organizer' );
				$editor->add_cap( 'delete_tribe_organizer' );
				$editor->add_cap( 'delete_tribe_organizers' );
				$editor->add_cap( 'edit_tribe_organizers' );
				$editor->add_cap( 'edit_others_tribe_organizers' );
				$editor->add_cap( 'delete_others_tribe_organizers' );
				$editor->add_cap( 'publish_tribe_organizers' );
				$editor->add_cap( 'edit_published_tribe_organizers' );
				$editor->add_cap( 'delete_published_tribe_organizers' );
				$editor->add_cap( 'delete_private_tribe_organizers' );
				$editor->add_cap( 'edit_private_tribe_organizers' );
				$editor->add_cap( 'read_private_tribe_organizers' );
			}

			$author = get_role( 'author' );
			if ( $author ) {
				$author->add_cap( 'edit_tribe_event' );
				$author->add_cap( 'read_tribe_event' );
				$author->add_cap( 'delete_tribe_event' );
				$author->add_cap( 'delete_tribe_events' );
				$author->add_cap( 'edit_tribe_events' );
				$author->add_cap( 'publish_tribe_events' );
				$author->add_cap( 'edit_published_tribe_events' );
				$author->add_cap( 'delete_published_tribe_events' );

				$author->add_cap( 'edit_tribe_venue' );
				$author->add_cap( 'read_tribe_venue' );
				$author->add_cap( 'delete_tribe_venue' );
				$author->add_cap( 'delete_tribe_venues' );
				$author->add_cap( 'edit_tribe_venues' );
				$author->add_cap( 'publish_tribe_venues' );
				$author->add_cap( 'edit_published_tribe_venues' );
				$author->add_cap( 'delete_published_tribe_venues' );

				$author->add_cap( 'edit_tribe_organizer' );
				$author->add_cap( 'read_tribe_organizer' );
				$author->add_cap( 'delete_tribe_organizer' );
				$author->add_cap( 'delete_tribe_organizers' );
				$author->add_cap( 'edit_tribe_organizers' );
				$author->add_cap( 'publish_tribe_organizers' );
				$author->add_cap( 'edit_published_tribe_organizers' );
				$author->add_cap( 'delete_published_tribe_organizers' );
			}

			$contributor = get_role( 'contributor' );
			if ( $contributor ) {
				$contributor->add_cap( 'edit_tribe_event' );
				$contributor->add_cap( 'read_tribe_event' );
				$contributor->add_cap( 'delete_tribe_event' );
				$contributor->add_cap( 'delete_tribe_events' );
				$contributor->add_cap( 'edit_tribe_events' );

				$contributor->add_cap( 'edit_tribe_venue' );
				$contributor->add_cap( 'read_tribe_venue' );
				$contributor->add_cap( 'delete_tribe_venue' );
				$contributor->add_cap( 'delete_tribe_venues' );
				$contributor->add_cap( 'edit_tribe_venues' );

				$contributor->add_cap( 'edit_tribe_organizer' );
				$contributor->add_cap( 'read_tribe_organizer' );
				$contributor->add_cap( 'delete_tribe_organizer' );
				$contributor->add_cap( 'delete_tribe_organizers' );
				$contributor->add_cap( 'edit_tribe_organizers' );
			}

			$subscriber = get_role( 'subscriber' );
			if ( $subscriber ) {
				$subscriber->add_cap( 'read_tribe_event' );

				$subscriber->add_cap( 'read_tribe_organizer' );

				$subscriber->add_cap( 'read_tribe_venue' );
			}
		}

		/**
		 * Register the post types.
		 *
		 * @return void
		 */
		public function registerPostType() {
			$this->generatePostTypeLabels();
			// JHILL: I did this.
			array_push($this->postTypeArgs['taxonomies'], 'category');
			register_post_type( self::POSTTYPE, apply_filters( 'tribe_events_register_event_type_args', $this->postTypeArgs ) );
			register_post_type( self::VENUE_POST_TYPE, apply_filters( 'tribe_events_register_venue_type_args', $this->postVenueTypeArgs ) );
			register_post_type( self::ORGANIZER_POST_TYPE, apply_filters( 'tribe_events_register_organizer_type_args', $this->postOrganizerTypeArgs ) );


			if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				$this->addCapabilities();
			}
            
            /*
			register_taxonomy(
				self::TAXONOMY, self::POSTTYPE, array(
					'hierarchical'          => true,
					'update_count_callback' => '',
					'rewrite'               => array(
						'slug'         => $this->taxRewriteSlug,
						'with_front'   => false,
						'hierarchical' => true
					),
					'public'                => true,
					'show_ui'               => true,
					'labels'                => $this->taxonomyLabels,
					'capabilities'          => array(
						'manage_terms' => 'publish_tribe_events',
						'edit_terms'   => 'publish_tribe_events',
						'delete_terms' => 'publish_tribe_events',
						'assign_terms' => 'edit_tribe_events'
					)
				)
			);
			*/

			if ( $this->getOption( 'showComments', 'no' ) == 'yes' ) {
				add_post_type_support( self::POSTTYPE, 'comments' );
			}

		}

		/**
		 * Get the rewrite slug
		 *
		 * @return string
		 */
		public function getRewriteSlug() {
		    return 'events';
			// return sanitize_title( $this->getOption( 'eventsSlug', 'events' ) );
		}

		/**
		 * Get the single post rewrite slug
		 *
		 * @return string
		 */
		public function getRewriteSlugSingular() {
		    return 'event';
		}

		/**
		 * Get taxonomy rewrite slug
		 *
		 * @return mixed|void
		 */
		public function getTaxRewriteSlug() {
			$slug = $this->getRewriteSlug() . '/' . sanitize_title( __( 'category', 'tribe-events-calendar' ) );

			return apply_filters( 'tribe_events_category_rewrite_slug', $slug );
		}

		/**
		 * Get tag rewrite slug
		 *
		 * @return mixed|void
		 */
		public function getTagRewriteSlug() {
			$slug = $this->getRewriteSlug() . '/' . sanitize_title( __( 'tag', 'tribe-events-calendar' ) );

			return apply_filters( 'tribe_events_tag_rewrite_slug', $slug );
		}

		/**
		 * Get venue post type args
		 *
		 * @return array
		 */
		public function getVenuePostTypeArgs() {
			return $this->postVenueTypeArgs;
		}

		/**
		 * Get organizer post type args
		 *
		 * @return array
		 */
		public function getOrganizerPostTypeArgs() {
			return $this->postOrganizerTypeArgs;
		}

		/**
		 * Generate custom post type lables
		 */
		protected function generatePostTypeLabels() {
			$this->postTypeArgs['labels'] = array(
				'name'               => __( 'Events', 'tribe-events-calendar' ),
				'singular_name'      => __( 'Event', 'tribe-events-calendar' ),
				'add_new'            => __( 'Add New', 'tribe-events-calendar' ),
				'add_new_item'       => __( 'Add New Event', 'tribe-events-calendar' ),
				'edit_item'          => __( 'Edit Event', 'tribe-events-calendar' ),
				'new_item'           => __( 'New Event', 'tribe-events-calendar' ),
				'view_item'          => __( 'View Event', 'tribe-events-calendar' ),
				'search_items'       => __( 'Search Events', 'tribe-events-calendar' ),
				'not_found'          => __( 'No events found', 'tribe-events-calendar' ),
				'not_found_in_trash' => __( 'No events found in Trash', 'tribe-events-calendar' ),
			);

			$this->postVenueTypeArgs['labels'] = array(
				'name'               => $this->plural_venue_label,
				'singular_name'      => $this->singular_venue_label,
				'add_new'            => __( 'Add New', 'tribe-events-calendar' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'tribe-events-calendar' ), $this->singular_venue_label ),
				'edit_item'          => sprintf( __( 'Edit %s', 'tribe-events-calendar' ), $this->singular_venue_label ),
				'new_item'           => sprintf( __( 'New %s', 'tribe-events-calendar' ), $this->singular_venue_label ),
				'view_item'          => sprintf( __( 'View %s', 'tribe-events-calendar' ), $this->singular_venue_label ),
				'search_items'       => sprintf( __( 'Search %s', 'tribe-events-calendar' ), $this->plural_venue_label ),
				'not_found'          => sprintf( __( 'No %s found', 'tribe-events-calendar' ), strtolower( $this->plural_venue_label ) ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'tribe-events-calendar' ), strtolower( $this->plural_venue_label ) ),
			);

			$this->postOrganizerTypeArgs['labels'] = array(
				'name'               => $this->plural_organizer_label,
				'singular_name'      => $this->singular_organizer_label,
				'add_new'            => __( 'Add New', 'tribe-events-calendar' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				'edit_item'          => sprintf( __( 'Edit %s', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				'new_item'           => sprintf( __( 'New %s', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				'view_item'          => sprintf( __( 'View %s', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				'search_items'       => sprintf( __( 'Search %s', 'tribe-events-calendar' ), $this->plural_organizer_label ),
				'not_found'          => sprintf( __( 'No %s found', 'tribe-events-calendar' ), strtolower( $this->plural_organizer_label ) ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'tribe-events-calendar' ), strtolower( $this->plural_organizer_label ) ),
			);

			$this->taxonomyLabels = array(
				'name'              => __( 'Event Categories', 'tribe-events-calendar' ),
				'singular_name'     => __( 'Event Category', 'tribe-events-calendar' ),
				'search_items'      => __( 'Search Event Categories', 'tribe-events-calendar' ),
				'all_items'         => __( 'All Event Categories', 'tribe-events-calendar' ),
				'parent_item'       => __( 'Parent Event Category', 'tribe-events-calendar' ),
				'parent_item_colon' => __( 'Parent Event Category:', 'tribe-events-calendar' ),
				'edit_item'         => __( 'Edit Event Category', 'tribe-events-calendar' ),
				'update_item'       => __( 'Update Event Category', 'tribe-events-calendar' ),
				'add_new_item'      => __( 'Add New Event Category', 'tribe-events-calendar' ),
				'new_item_name'     => __( 'New Event Category Name', 'tribe-events-calendar' )
			);

		}

		/**
		 * Update custom post type messages
		 *
		 * @param $messages
		 *
		 * @return mixed
		 */
		public function updatePostMessage( $messages ) {
			global $post, $post_ID;

			$messages[self::POSTTYPE] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( 'Event updated. <a href="%s">View event</a>', 'tribe-events-calendar' ), esc_url( get_permalink( $post_ID ) ) ),
				2  => __( 'Custom field updated.', 'tribe-events-calendar' ),
				3  => __( 'Custom field deleted.', 'tribe-events-calendar' ),
				4  => __( 'Event updated.', 'tribe-events-calendar' ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Event restored to revision from %s', 'tribe-events-calendar' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Event published. <a href="%s">View event</a>', 'tribe-events-calendar' ), esc_url( get_permalink( $post_ID ) ) ),
				7  => __( 'Event saved.', 'tribe-events-calendar' ),
				8  => sprintf( __( 'Event submitted. <a target="_blank" href="%s">Preview event</a>', 'tribe-events-calendar' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
				9  => sprintf(
					__( 'Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview event</a>', 'tribe-events-calendar' ),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'tribe-events-calendar' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) )
				),
				10 => sprintf( __( 'Event draft updated. <a target="_blank" href="%s">Preview event</a>', 'tribe-events-calendar' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			);

			$messages[self::VENUE_POST_TYPE] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( '%s updated.', 'tribe-events-calendar' ), $this->singular_venue_label ),
				2  => __( 'Custom field updated.', 'tribe-events-calendar' ),
				3  => __( 'Custom field deleted.', 'tribe-events-calendar' ),
				4  => sprintf( __( '%s updated.', 'tribe-events-calendar' ), $this->singular_venue_label ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( '%s restored to revision from %s', 'tribe-events-calendar' ), $this->singular_venue_label, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( '%s published.', 'tribe-events-calendar' ), $this->singular_venue_label ),
				7  => sprintf( __( '%s saved.', 'tribe-events-calendar' ), $this->singular_venue_label ),
				8  => sprintf( __( '%s submitted.', 'tribe-events-calendar' ), $this->singular_venue_label ),
				9  => sprintf(
					__( '%s scheduled for: <strong>%2$s</strong>.', 'tribe-events-calendar' ), $this->singular_venue_label,
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'tribe-events-calendar' ), strtotime( $post->post_date ) )
				),
				10 => sprintf( __( '%s draft updated.', 'tribe-events-calendar' ), $this->singular_venue_label ),
			);

			$messages[self::ORGANIZER_POST_TYPE] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( '%s updated.', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				2  => __( 'Custom field updated.', 'tribe-events-calendar' ),
				3  => __( 'Custom field deleted.', 'tribe-events-calendar' ),
				4  => sprintf( __( '%s updated.', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( '%s restored to revision from %s', 'tribe-events-calendar' ), $this->singular_organizer_label, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( '%s published.', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				7  => sprintf( __( '%s saved.', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				8  => sprintf( __( '%s submitted.', 'tribe-events-calendar' ), $this->singular_organizer_label ),
				9  => sprintf(
					__( '%s scheduled for: <strong>%2$s</strong>.', 'tribe-events-calendar' ), $this->singular_organizer_label,
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'tribe-events-calendar' ), strtotime( $post->post_date ) )
				),
				10 => sprintf( __( '%s draft updated.', 'tribe-events-calendar' ), $this->singular_organizer_label ),
			);

			return $messages;
		}

		/**
		 * Adds the submenu items for editing the Venues and Organizers.
		 * Used to be a PRO only feature, but as of 3.0, it is part of Core.
		 *
		 *
		 * @return void
		 */
		public function addVenueAndOrganizerEditor() {
		    // JHILL: I turned this off
		    return;
			add_submenu_page( 'edit.php?post_type=' . TribeEvents::POSTTYPE, __( $this->plural_venue_label, 'tribe-events-calendar' ), __( $this->plural_venue_label, 'tribe-events-calendar' ), 'edit_tribe_venues', 'edit.php?post_type=' . TribeEvents::VENUE_POST_TYPE );
			add_submenu_page( 'edit.php?post_type=' . TribeEvents::POSTTYPE, __( $this->plural_organizer_label, 'tribe-events-calendar' ), __( $this->plural_organizer_label, 'tribe-events-calendar' ), 'edit_tribe_organizers', 'edit.php?post_type=' . TribeEvents::ORGANIZER_POST_TYPE );
			add_submenu_page( 'edit.php?post_type=' . TribeEvents::VENUE_POST_TYPE, __( 'Add New ' . $this->singular_venue_label, 'tribe-events-calendar' ), __( 'Add New ' . $this->singular_venue_label, 'tribe-events-calendar' ), 'edit_tribe_venues', 'post-new.php?post_type=' . TribeEvents::VENUE_POST_TYPE );
			add_submenu_page( 'edit.php?post_type=' . TribeEvents::ORGANIZER_POST_TYPE, __( 'Add New ' . $this->singular_organizer_label, 'tribe-events-calendar' ), __( 'Add New ' . $this->singular_organizer_label, 'tribe-events-calendar' ), 'edit_tribe_organizers', 'post-new.php?post_type=' . TribeEvents::ORGANIZER_POST_TYPE );
		}


		/**
		 * displays the saved venue dropdown in the event metabox
		 * Used to be a PRO only feature, but as of 3.0, it is part of Core.
		 *
		 * @param int $postId the event ID for which to create the dropdown
		 */
		public function displayEventVenueDropdown( $postId ) {
			$VenueID         = get_post_meta( $postId, '_EventVenueID', true );
			$defaultsEnabled = class_exists( 'TribeEventsPro' ) ? tribe_get_option( 'defaultValueReplace' ) : false;
			if ( ( ! $postId || get_post_status( $postId ) == 'auto-draft' ) && ! $VenueID && $defaultsEnabled && ( ( is_admin() && get_current_screen()->action == 'add' ) || ! is_admin() ) ) {
				$VenueID = tribe_get_option( 'eventsDefaultVenueID' );
			}
			$VenueID = apply_filters( 'tribe_display_event_venue_dropdown_id', $VenueID );
			?>
			<tr class="">
				<td style="width:170px"><?php _e( 'Use Saved ' . $this->singular_venue_label . ':', 'tribe-events-calendar' ); ?></td>
				<td><?php $this->saved_venues_dropdown( $VenueID ); ?></td>
			</tr>
		<?php
		}

		/**
		 * displays the saved organizer dropdown in the event metabox
		 * Used to be a PRO only feature, but as of 3.0, it is part of Core.
		 *
		 * @param int $postId the event ID for which to create the dropdown
		 *
		 * @return void
		 */
		public function displayEventOrganizerDropdown( $postId ) {
			$curOrg          = get_post_meta( $postId, '_EventOrganizerID', true );
			$defaultsEnabled = class_exists( 'TribeEventsPro' ) ? tribe_get_option( 'defaultValueReplace' ) : false;
			if ( ( ! $postId || get_post_status( $postId ) == 'auto-draft' ) && ! $curOrg && $defaultsEnabled && ( ( is_admin() && get_current_screen()->action == 'add' ) || ! is_admin() ) ) {
				$curOrg = tribe_get_option( 'eventsDefaultOrganizerID' );
			}
			$curOrg = apply_filters( 'tribe_display_event_organizer_dropdown_id', $curOrg );

			?>
			<tr class="">
				<td style="width:170px">
					<label for="saved_organizer"><?php printf( __( 'Use Saved %s:', 'tribe-events-calendar' ), $this->singular_organizer_label ); ?></label>
				</td>
				<td><?php $this->saved_organizers_dropdown( $curOrg ); ?></td>
			</tr>
		<?php
		}

		/**
		 * helper function for displaying the saved venue dropdown
		 * Used to be a PRO only feature, but as of 3.0, it is part of Core.
		 *
		 * @param mixed  $current the current saved venue
		 * @param string $name    the name value for the field
		 */
		public function saved_venues_dropdown( $current = null, $name = 'venue[VenueID]' ) {
			$my_venue_ids     = array();
			$current_user     = wp_get_current_user();
			$my_venues        = false;
			$my_venue_options = '';
			if ( 0 != $current_user->ID ) {
				$my_venues = $this->get_venue_info(
								  null, null, array(
										  'post_status' => array(
											  'publish',
											  'draft',
											  'private',
											  'pending'
										  ),
										  'author'      => $current_user->ID
									  )
				);

				if ( ! empty( $my_venues ) ) {
					foreach ( $my_venues as $my_venue ) {
						$my_venue_ids[] = $my_venue->ID;
						$venue_title    = wp_kses( get_the_title( $my_venue->ID ), array() );
						$my_venue_options .= '<option data-address="' . esc_attr( $this->fullAddressString( $my_venue->ID ) ) . '" value="' . esc_attr( $my_venue->ID ) . '"';
						$my_venue_options .= selected( $current, $my_venue->ID, false );
						$my_venue_options .= '>' . $venue_title . '</option>';
					}
				}
			}

			if ( current_user_can( 'edit_others_tribe_venues' ) ) {
				$venues = $this->get_venue_info(
							   null, null, array(
									   'post_status'  => array(
										   'publish',
										   'draft',
										   'private',
										   'pending'
									   ),
									   'post__not_in' => $my_venue_ids
								   )
				);
			} else {
				$venues = $this->get_venue_info(
							   null, null, array(
									   'post_status'  => 'publish',
									   'post__not_in' => $my_venue_ids
								   )
				);
			}
			if ( $venues || $my_venues ) {
				echo '<select class="chosen venue-dropdown" name="' . esc_attr( $name ) . '" id="saved_venue">';
				echo '<option value="0">' . sprintf( __( 'Use New %s', 'tribe-events-calendar' ), $this->singular_venue_label ) . '</option>';
				if ( $my_venues ) {
					echo $venues ? '<optgroup label="' . apply_filters( 'tribe_events_saved_venues_dropdown_my_optgroup', sprintf( __( 'My %s', 'tribe-events-calendar' ), $this->plural_venue_label ) ) . '">' : '';
					echo $my_venue_options;
					echo $venues ? '</optgroup>' : '';
				}
				if ( $venues ) {
					echo $my_venues ? '<optgroup label="' . apply_filters( 'tribe_events_saved_venues_dropdown_optgroup', sprintf( __( 'Available %s', 'tribe-events-calendar' ), $this->plural_venue_label ) ) . '">' : '';
					foreach ( $venues as $venue ) {
						$venue_title = wp_kses( get_the_title( $venue->ID ), array() );
						echo '<option data-address="' . esc_attr( $this->fullAddressString( $venue->ID ) ) . '" value="' . esc_attr( $venue->ID ) . '"';
						selected( ( $current == $venue->ID ) );
						echo '>' . $venue_title . '</option>';
					}
					echo $my_venues ? '</optgroup>' : '';
				}
				echo '</select>';
			} else {
				echo '<p class="nosaved">' . sprintf( __( 'No saved %s exists.', 'tribe-events-calendar' ), strtolower( $this->singular_venue_label ) ) . '</p>';
			}
		}

		/**
		 * helper function for displaying the saved organizer dropdown
		 * Used to be a PRO only feature, but as of 3.0, it is part of Core.
		 *
		 * @param mixed  $current the current saved venue
		 * @param string $name    the name value for the field
		 */
		public function saved_organizers_dropdown( $current = null, $name = 'organizer[OrganizerID]' ) {
			$my_organizer_ids      = array();
			$current_user          = wp_get_current_user();
			$my_organizers         = false;
			$my_organizers_options = '';
			if ( 0 != $current_user->ID ) {
				$my_organizers = $this->get_organizer_info(
									  null, null, array(
											  'post_status' => array(
												  'publish',
												  'draft',
												  'private',
												  'pending'
											  ),
											  'author'      => $current_user->ID
										  )
				);

				if ( ! empty( $my_organizers ) ) {
					foreach ( $my_organizers as $my_organizer ) {
						$my_organizer_ids[] = $my_organizer->ID;
						$organizer_title    = wp_kses( get_the_title( $my_organizer->ID ), array() );
						$my_organizers_options .= '<option value="' . esc_attr( $my_organizer->ID ) . '"';
						$my_organizers_options .= selected( $current, $my_organizer->ID, false );
						$my_organizers_options .= '>' . $organizer_title . '</option>';
					}
				}
			}


			if ( current_user_can( 'edit_others_tribe_organizers' ) ) {
				$organizers = $this->get_organizer_info(
								   null, null, array(
										   'post_status'  => array(
											   'publish',
											   'draft',
											   'private',
											   'pending'
										   ),
										   'post__not_in' => $my_organizer_ids
									   )
				);
			} else {
				$organizers = $this->get_organizer_info(
								   null, null, array(
										   'post_status'  => 'publish',
										   'post__not_in' => $my_organizer_ids
									   )
				);
			}
			if ( $organizers || $my_organizers ) {
				echo '<select class="chosen organizer-dropdown" name="' . esc_attr( $name ) . '" id="saved_organizer">';
				echo '<option value="0">' . sprintf( __( 'Use New %s', 'tribe-events-calendar' ), $this->singular_organizer_label ) . '</option>';
				if ( $my_organizers ) {
					echo $organizers ? '<optgroup label="' . apply_filters( 'tribe_events_saved_organizers_dropdown_my_optgroup', sprintf( __( 'My %s', 'tribe-events-calendar' ), $this->plural_organizer_label ) ) . '">' : '';
					echo $my_organizers_options;
					echo $organizers ? '</optgroup>' : '';
				}
				if ( $organizers ) {
					echo $my_organizers ? '<optgroup label="' . apply_filters( 'tribe_events_saved_organizers_dropdown_optgroup', sprintf( __( 'Available %s', 'tribe-events-calendar' ), $this->plural_organizer_label ) ) . '">' : '';
					foreach ( $organizers as $organizer ) {
						$organizer_title = wp_kses( get_the_title( $organizer->ID ), array() );
						echo '<option value="' . esc_attr( $organizer->ID ) . '"';
						selected( ( $current == $organizer->ID ) );
						echo '>' . $organizer_title . '</option>';
					}
					echo $my_organizers ? '</optgroup>' : '';
				}
				echo '</select>';
			} else {
				echo '<p class="nosaved">' . sprintf( __( 'No saved %s exists.', 'tribe-events-calendar' ), strtolower( $this->singular_organizer_label ) ) . '</p>';
			}
		}

		/**
		 * Update admin classes
		 *
		 * @param array $classes
		 *
		 * @return array
		 */
		public function admin_body_class( $classes ) {
			global $current_screen;
			if ( isset( $current_screen->post_type ) &&
				 ( $current_screen->post_type == self::POSTTYPE || $current_screen->id == 'settings_page_tribe-settings' )
			) {
				$classes .= ' events-cal ';
			}

			return $classes;
		}

		/**
		 * Add admin scripts and styles
		 *
		 * @return void
		 */
		public function addAdminScriptsAndStyles() {

			global $current_screen;

			// setup plugin resources & 3rd party vendor urls
			$resources_url = trailingslashit( $this->pluginUrl ) . 'resources/';
			$vendor_url    = trailingslashit( $this->pluginUrl ) . 'vendor/';

			// admin stylesheet - only load admin stylesheet when on Tribe pages
			if ( isset( $current_screen->id ) && true === strpos( $current_screen->id, 'tribe' ) ) {
				wp_enqueue_style( self::POSTTYPE . '-admin', $resources_url . 'events-admin.css', array(), apply_filters( 'tribe_events_css_version', self::VERSION ) );
			}

			// settings screen
			if ( isset( $current_screen->id ) && $current_screen->id == 'settings_page_tribe-settings' ) {

				// chosen
				Tribe_Template_Factory::asset_package( 'chosen' );

				// JS admin
				Tribe_Template_Factory::asset_package( 'admin' );

				// JS settings
				Tribe_Template_Factory::asset_package( 'settings' );

				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );

				// hook for other plugins
				do_action( 'tribe_settings_enqueue' );
			}

			if ( $current_screen->id == 'widgets' ) {
				Tribe_Template_Factory::asset_package( 'chosen' );
			}

			// events, organizer, or venue editing
			if ( ( isset( $current_screen->post_type ) && in_array(
					$current_screen->post_type, array(
						self::POSTTYPE, // events editing
						self::VENUE_POST_TYPE, // venue editing
						self::ORGANIZER_POST_TYPE // organizer editing
					)
				) )
			) {

				// chosen
				Tribe_Template_Factory::asset_package( 'chosen' );

				// select 2
				Tribe_Template_Factory::asset_package( 'select2' );

				// smoothness
				Tribe_Template_Factory::asset_package( 'smoothness' );

				// date picker
				Tribe_Template_Factory::asset_package( 'datepicker' );

				// dialog
				Tribe_Template_Factory::asset_package( 'dialog' );

				// UI admin
				Tribe_Template_Factory::asset_package( 'admin-ui' );

				// JS admin
				Tribe_Template_Factory::asset_package( 'admin' );

				// ecp placeholders
				Tribe_Template_Factory::asset_package( 'ecp-plugins' );

				switch ( $current_screen->post_type ) {
					case self::POSTTYPE :

						add_action( 'admin_footer', array( $this, 'printLocalizedAdmin' ) );

						// hook for other plugins
						do_action( 'tribe_events_enqueue' );
						break;
					case self::VENUE_POST_TYPE :

						// hook for other plugins
						do_action( 'tribe_venues_enqueue' );
						break;
					case self::ORGANIZER_POST_TYPE :

						// hook for other plugins
						do_action( 'tribe_organizers_enqueue' );
						break;
				}
			}
		}

		/**
		 * Modify the post type args to set Dashicon if we're in WP 3.8+
		 *
		 * @return array post type args
		 **/
		function setDashicon( $postTypeArgs ) {
			global $wp_version;

			if ( version_compare( $wp_version, 3.8 ) >= 0 ) {
				$postTypeArgs['menu_icon'] = 'dashicons-calendar';
			}

			return $postTypeArgs;

		}

		/**
		 * Localize admin
		 *
		 * @return array
		 */
		public function localizeAdmin() {
			$bits = array(
				'dayNames'        => $this->daysOfWeek,
				'dayNamesShort'   => $this->daysOfWeekShort,
				'dayNamesMin'     => $this->daysOfWeekMin,
				'monthNames'      => array_values( $this->monthNames() ),
				'monthNamesShort' => array_values( $this->monthNames( true ) ),
				'nextText'        => __( 'Next', 'tribe-events-calendar' ),
				'prevText'        => __( 'Prev', 'tribe-events-calendar' ),
				'currentText'     => __( 'Today', 'tribe-events-calendar' ),
				'closeText'       => __( 'Done', 'tribe-events-calendar' )
			);

			return $bits;
		}

		/**
		 * Output localized admin javascript
		 *
		 * @return void
		 */
		public function printLocalizedAdmin() {
			$object_name = 'TEC';
			$vars        = $this->localizeAdmin();

			$data = "var $object_name = {\n";
			$eol  = '';
			foreach ( $vars as $var => $val ) {

				if ( gettype( $val ) == 'array' || gettype( $val ) == 'object' ) {
					$val = json_encode( $val );
				} else {
					$val = '"' . esc_js( $val ) . '"';
				}

				$data .= "$eol\t$var: $val";
				$eol = ",\n";
			}
			$data .= "\n};\n";

			echo "<script type='text/javascript'>\n";
			echo "/* <![CDATA[ */\n";
			echo $data;
			echo "/* ]]> */\n";
			echo "</script>\n";

		}

		/**
		 * Get all options for the Events Calendar
		 *
		 * @return array of options
		 */
		public static function getOptions( $force = false ) {
			if ( ! isset( self::$options ) || $force ) {
				$options       = get_option( TribeEvents::OPTIONNAME, array() );
				self::$options = apply_filters( 'tribe_get_options', $options );
			}

			return self::$options;
		}

		/**
		 * Get value for a specific option
		 *
		 * @param string $optionName name of option
		 * @param string $default    default value
		 *
		 * @return mixed results of option query
		 */
		public static function getOption( $optionName, $default = '' ) {
			if ( ! $optionName ) {
				return null;
			}

			if ( ! isset( self::$options ) ) {
				self::getOptions();
			}

			$option = $default;
			if ( isset( self::$options[$optionName] ) ) {
				$option = self::$options[$optionName];
			} elseif ( is_multisite() && isset( self::$tribeEventsMuDefaults ) && is_array( self::$tribeEventsMuDefaults ) && in_array( $optionName, array_keys( self::$tribeEventsMuDefaults ) ) ) {
				$option = self::$tribeEventsMuDefaults[$optionName];
			}

			return apply_filters( 'tribe_get_single_option', $option, $default, $optionName );
		}

		/**
		 * Saves the options for the plugin
		 *
		 * @param array $options formatted the same as from getOptions()
		 * @param bool  $apply_filters
		 *
		 * @return void
		 */
		public function setOptions( $options, $apply_filters = true ) {
			if ( ! is_array( $options ) ) {
				return;
			}
			if ( $apply_filters == true ) {
				$options = apply_filters( 'tribe-events-save-options', $options );
			}
			// @TODO use TribeEvents::getOptions
			if ( update_option( TribeEvents::OPTIONNAME, $options ) ) {
				self::$options = apply_filters( 'tribe_get_options', $options );

				return true;
			} else {
				TribeEvents::$options = TribeEvents::getOptions();

				return false;
			}
		}

		/**
		 * Set an option
		 *
		 * @param string $name
		 * @param mixed  $value
		 *
		 * @return void
		 */
		public function setOption( $name, $value ) {
			$newOption        = array();
			$newOption[$name] = $value;
			$options          = self::getOptions();
			self::setOptions( wp_parse_args( $newOption, $options ) );
		}

		/**
		 * Get all network options for the Events Calendar
		 *
		 * @return array of options
		 * @TODO add force option, implement in setNetworkOptions
		 */
		public static function getNetworkOptions() {
			if ( ! isset( self::$networkOptions ) ) {
				$options              = get_site_option( TribeEvents::OPTIONNAMENETWORK, array() );
				self::$networkOptions = apply_filters( 'tribe_get_network_options', $options );
			}

			return self::$networkOptions;
		}

		/**
		 * Get value for a specific network option
		 *
		 * @param string $optionName name of option
		 * @param string $default    default value
		 *
		 * @return mixed results of option query
		 */
		public function getNetworkOption( $optionName, $default = '' ) {
			if ( ! $optionName ) {
				return null;
			}

			if ( ! isset( self::$networkOptions ) ) {
				self::getNetworkOptions();
			}

			if ( isset( self::$networkOptions[$optionName] ) ) {
				$option = self::$networkOptions[$optionName];
			} else {
				$option = $default;
			}

			return apply_filters( 'tribe_get_single_network_option', $option, $default );
		}

		/**
		 * Saves the network options for the plugin
		 *
		 * @param array $options formatted the same as from getOptions()
		 * @param bool  $apply_filters
		 *
		 * @return void
		 */
		public function setNetworkOptions( $options, $apply_filters = true ) {
			if ( ! is_array( $options ) ) {
				return;
			}
			if ( $apply_filters == true ) {
				$options = apply_filters( 'tribe-events-save-network-options', $options );
			}

			// @TODO use getNetworkOptions + force
			if ( update_site_option( TribeEvents::OPTIONNAMENETWORK, $options ) ) {
				self::$networkOptions = apply_filters( 'tribe_get_network_options', $options );
			} else {
				self::$networkOptions = self::getNetworkOptions();
			}
		}

		/**
		 * Add the network admin options page
		 *
		 * @return void
		 */
		public function addNetworkOptionsPage() {
			$tribe_settings = TribeSettings::instance();
			add_submenu_page(
				'settings.php', $this->pluginName, $this->pluginName, 'manage_network_options', 'tribe-events-calendar', array(
					$tribe_settings,
					'generatePage'
				)
			);
		}

		/**
		 * Render network admin options view
		 *
		 * @return void
		 */
		public function doNetworkSettingTab() {
			include_once( $this->pluginPath . 'admin-views/tribe-options-network.php' );

			new TribeSettingsTab( 'network', __( 'Network', 'tribe-events-calendar' ), $networkTab );
		}

		/**
		 * Get the post types that are associated with TEC.
		 *
		 * @return array The post types associated with this plugin
		 */
		public static function getPostTypes() {
			return apply_filters(
				'tribe_events_post_types', array(
					self::POSTTYPE,
					self::ORGANIZER_POST_TYPE,
					self::VENUE_POST_TYPE,
				)
			);
		}

		/**
		 * An event can have one or more start dates. This gives
		 * the earliest of those.
		 *
		 * @param int $post_id
		 *
		 * @return string The date string for the earliest occurrence of the event
		 */
		public static function get_series_start_date( $post_id ) {
			if ( function_exists( 'tribe_get_recurrence_start_dates' ) ) {
				$start_dates = tribe_get_recurrence_start_dates( $post_id );

				return reset( $start_dates );
			} else {
				return get_post_meta( $post_id, '_EventStartDate', true );
			}
		}

		/**
		 * Save hidden tabs
		 *
		 * @return void
		 * @TODO move somewhere else
		 */
		public function saveAllTabsHidden() {
			$all_tabs_keys = array_keys( apply_filters( 'tribe_settings_all_tabs', array() ) );

			$network_options = (array) get_site_option( self::OPTIONNAMENETWORK );

			if ( isset( $_POST['hideSettingsTabs'] ) && $_POST['hideSettingsTabs'] == $all_tabs_keys ) {
				$network_options['allSettingsTabsHidden'] = '1';
			} else {
				$network_options['allSettingsTabsHidden'] = '0';
			}

			$this->setNetworkOptions( $network_options );
		}

		/**
		 * Clean up trashed venues
		 *
		 * @param int $postId
		 *
		 * @return void
		 */
		public function cleanupPostVenues( $postId ) {
			$this->removeDeletedPostTypeAssociation( '_EventVenueID', $postId );
		}

		/**
		 * Clean up trashed organizers.
		 *
		 * @param int $postId
		 *
		 * @return void
		 */
		public function cleanupPostOrganizers( $postId ) {
			$this->removeDeletedPostTypeAssociation( '_EventOrganizerID', $postId );
		}

		/**
		 * Clean up trashed venues or organizers.
		 *
		 * @param string $key
		 * @param int    $postId
		 *
		 * @return void
		 */
		protected function removeDeletedPostTypeAssociation( $key, $postId ) {
			$the_query = new WP_Query( array(
				'meta_key'   => $key,
				'meta_value' => $postId,
				'post_type'  => TribeEvents::POSTTYPE
			) );

			while ( $the_query->have_posts() ): $the_query->the_post();
				delete_post_meta( get_the_ID(), $key );
			endwhile;

			wp_reset_postdata();
		}

		/**
		 * Truncate a given string.
		 *
		 * @param string $text           The text to truncate.
		 * @param int    $excerpt_length How long you want it to be truncated to.
		 *
		 * @return string The truncated text.
		 */
		public function truncate( $text, $excerpt_length = 44 ) {

			$text = apply_filters( 'the_content', $text );
			$text = str_replace( ']]>', ']]&gt;', $text );
			$text = strip_tags( $text );

			$words = explode( ' ', $text, $excerpt_length + 1 );
			if ( count( $words ) > $excerpt_length ) {
				array_pop( $words );
				$text = implode( ' ', $words );
				$text = rtrim( $text );
				$text .= '&hellip;';
			}

			return $text;
		}

		/**
		 * Load the text domain.
		 *
		 * @return void
		 */
		public function loadTextDomain() {
			load_plugin_textdomain( 'tribe-events-calendar', false, $this->pluginDir . 'lang/' );
			$this->constructDaysOfWeek();
			$this->initMonthNames();
		}

		/**
		 * Load asset packages.
		 *
		 * @return void
		 */
		public function loadStyle() {
			if ( tribe_is_event_query() || tribe_is_event_organizer() || tribe_is_event_venue() ) {

				// jquery-resize
				Tribe_Template_Factory::asset_package( 'jquery-resize' );

				// smoothness
				Tribe_Template_Factory::asset_package( 'smoothness' );

				// Tribe Calendar JS
				Tribe_Template_Factory::asset_package( 'calendar-script' );

				Tribe_Template_Factory::asset_package( 'events-css' );
			} else {
				if ( is_active_widget( false, false, 'tribe-events-list-widget' ) ) {

					Tribe_Template_Factory::asset_package( 'events-css' );

				}
			}
		}

		/**
		 * Set the displaying class property.
		 *
		 * @return void
		 */
		public function setDisplay() {
			if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				$this->displaying = 'admin';
			} else {
				global $wp_query;
				if ( $wp_query && $wp_query->is_main_query() && ! empty( $wp_query->tribe_is_event_query ) ) {
					$this->displaying = isset( $wp_query->query_vars['eventDisplay'] ) ? $wp_query->query_vars['eventDisplay'] : tribe_get_option( 'viewOption', 'list' );

					if ( is_single() && $this->displaying != 'all' ) {
						$this->displaying = 'single-event';
					}
				}
			}
		}

		/**
		 * Returns the default view, providing a fallback if the default is no longer availble.
		 *
		 * This can be useful is for instance a view added by another plugin (such as PRO) is
		 * stored as the default but can no longer be generated due to the plugin being deactivated.
		 *
		 * @return string
		 */
		public function default_view() {
			// Compare the stored default view option to the list of available views
			$default         = $this->getOption( 'viewOption', '' );
			$available_views = (array) apply_filters( 'tribe-events-bar-views', array(), false );

			foreach ( $available_views as $view ) {
				if ( $default === $view['displaying'] ) {
					return $default;
				}
			}

			// If the stored option is no longer available, pick the first available one instead
			$first_view = array_shift( $available_views );
			$view       = $first_view['displaying'];

			// Update the saved option
			$this->setOption( 'viewOption', $view );

			return $view;
		}

		/**
		 * Localize month names and their short names and such using $wp_locale.
		 *
		 * @return void
		 */
		protected function initMonthNames() {
			global $wp_locale;
			$this->monthsFull = array(
				'January'   => $wp_locale->get_month( '01' ),
				'February'  => $wp_locale->get_month( '02' ),
				'March'     => $wp_locale->get_month( '03' ),
				'April'     => $wp_locale->get_month( '04' ),
				'May'       => $wp_locale->get_month( '05' ),
				'June'      => $wp_locale->get_month( '06' ),
				'July'      => $wp_locale->get_month( '07' ),
				'August'    => $wp_locale->get_month( '08' ),
				'September' => $wp_locale->get_month( '09' ),
				'October'   => $wp_locale->get_month( '10' ),
				'November'  => $wp_locale->get_month( '11' ),
				'December'  => $wp_locale->get_month( '12' )
			);
			// yes, it's awkward. easier this way than changing logic elsewhere.
			$this->monthsShort = $months = array(
				'Jan' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '01' ) ),
				'Feb' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '02' ) ),
				'Mar' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '03' ) ),
				'Apr' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '04' ) ),
				'May' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '05' ) ),
				'Jun' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '06' ) ),
				'Jul' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '07' ) ),
				'Aug' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '08' ) ),
				'Sep' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '09' ) ),
				'Oct' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '10' ) ),
				'Nov' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '11' ) ),
				'Dec' => $wp_locale->get_month_abbrev( $wp_locale->get_month( '12' ) )
			);
		}

		/**
		 * Helper method to return an array of translated month names or short month names
		 *
		 * @param bool $short
		 *
		 * @return array Translated month names
		 */
		public function monthNames( $short = false ) {
			if ( $short ) {
				return $this->monthsShort;
			}

			return $this->monthsFull;
		}

		/**
		 * Flush rewrite rules to support custom links
		 *
		 * @link http://codex.wordpress.org/Custom_Queries#Permalinks_for_Custom_Archives
		 */
		public static function flushRewriteRules() {

			$tec = self::instance();

			// reregister custom post type to make sure slugs are updated
			$tec->postTypeArgs['rewrite']['slug'] = sanitize_title( $tec->getRewriteSlugSingular() );
			register_post_type( self::POSTTYPE, apply_filters( 'tribe_events_register_event_type_args', $tec->postTypeArgs ) );

			add_action( 'shutdown', 'flush_rewrite_rules' );
		}

		/**
		 * Adds the event specific query vars to WordPress
		 *
		 * @param array $qvars
		 *
		 * @link http://codex.wordpress.org/Custom_Queries#Permalinks_for_Custom_Archives
		 * @return mixed array of query variables that this plugin understands
		 */
		public function eventQueryVars( $qvars ) {
			$qvars[] = 'eventDisplay';
			$qvars[] = 'eventDate';
			$qvars[] = 'ical';
			$qvars[] = 'start_date';
			$qvars[] = 'end_date';
			$qvars[] = TribeEvents::TAXONOMY;

			return $qvars;
		}

		/**
		 * Adds Event specific rewrite rules.
		 *
		 * @param object $wp_rewrite
		 * events/                =>    /?post_type=tribe_events
		 * events/month        =>    /?post_type=tribe_events&eventDisplay=month
		 * events/week        =>  /?post_type=tribe_events&eventDisplay=week
		 * events/upcoming        =>    /?post_type=tribe_events&eventDisplay=upcoming
		 * events/past            =>    /?post_type=tribe_events&eventDisplay=past
		 * events/2008-01/#15    =>    /?post_type=tribe_events&eventDisplay=bydate&eventDate=2008-01-01
		 * events/category/some-events-category => /?post_type=tribe_events&tribe_event_cat=some-events-category
		 *
		 * @return void
		 */
		public function filterRewriteRules( $wp_rewrite ) {

			$this->rewriteSlug         = $this->getRewriteSlug();
			$this->rewriteSlugSingular = $this->getRewriteSlugSingular();
			$this->taxRewriteSlug      = $this->getTaxRewriteSlug();
			$this->tagRewriteSlug      = $this->getTagRewriteSlug();

			$base       = trailingslashit( $this->rewriteSlug );
			$singleBase = trailingslashit( $this->rewriteSlugSingular );
			$catBase    = trailingslashit( $this->taxRewriteSlug );
			$catBase    = "(.*)" . $catBase . "(?:[^/]+/)*";
			$tagBase    = trailingslashit( $this->tagRewriteSlug );
			$tagBase    = "(.*)" . $tagBase;

			$month    = $this->monthSlug;
			$list 	  = $this->listSlug;
			$today    = $this->todaySlug;
			$day      = $this->daySlug;
			$newRules = array();

			// single event
			$newRules[$singleBase . '([^/]+)/(\d{4}-\d{2}-\d{2})/?$']      = 'index.php?' . self::POSTTYPE . '=' . $wp_rewrite->preg_index( 1 ) . "&eventDate=" . $wp_rewrite->preg_index( 2 );
			$newRules[$singleBase . '([^/]+)/(\d{4}-\d{2}-\d{2})/ical/?$'] = 'index.php?ical=1&' . self::POSTTYPE . '=' . $wp_rewrite->preg_index( 1 ) . "&eventDate=" . $wp_rewrite->preg_index( 2 );
			$newRules[$singleBase . '([^/]+)/all/?$']                      = 'index.php?post_type=' . self::POSTTYPE . '&' . self::POSTTYPE . '=' . $wp_rewrite->preg_index( 1 ) . "&eventDisplay=all";

			$newRules[$base . 'page/(\d+)']                  = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=list&paged=' . $wp_rewrite->preg_index( 1 );
			$newRules[$base . 'ical']                        = 'index.php?post_type=' . self::POSTTYPE . '&ical=1';
			$newRules[$base . '(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=list&feed=' . $wp_rewrite->preg_index( 1 );
			$newRules[$base . $month]                        = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=month';
			$newRules[$base . $list . '/page/(\d+)']     	 = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=list&paged=' . $wp_rewrite->preg_index( 1 );
			$newRules[$base . $list]                     	 = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=list';
			$newRules[$base . $today]                        = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=day';
			$newRules[$base . '(\d{4}-\d{2})$']              = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=month' . '&eventDate=' . $wp_rewrite->preg_index( 1 );
			$newRules[$base . '(\d{4}-\d{2}-\d{2})/?$']      = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=day&eventDate=' . $wp_rewrite->preg_index( 1 );
			$newRules[$base . 'feed/?$']                     = 'index.php?post_type=' . self::POSTTYPE . 'eventDisplay=list&&feed=rss2';
			$newRules[$base . '?$']                          = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=default';

			// ical
			$newRules[$singleBase . '([^/]+)/ical/?$']        = 'index.php?post_type=' . self::POSTTYPE . '&name=' . $wp_rewrite->preg_index( 1 ) . '&ical=1';
			$newRules[$base . '/(\d{4}-\d{2}-\d{2})/ical/?$'] = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=day' . '&eventDate=' . $wp_rewrite->preg_index( 1 ) . '&ical=1';

			// category rules.
			$newRules[$catBase . '([^/]+)/page/(\d+)']                          = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=list&tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&paged=' . $wp_rewrite->preg_index( 3 );
			$newRules[$catBase . '([^/]+)/' . $month]                           = 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=month';
			$newRules[$catBase . '([^/]+)/' . $list . '/page/(\d+)']        	= 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list&paged=' . $wp_rewrite->preg_index( 3 );
			$newRules[$catBase . '([^/]+)/' . $list]                        	= 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list';
			$newRules[$catBase . '([^/]+)/' . $today]                           = 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=day';
			$newRules[$catBase . '([^/]+)/' . $day . '/(\d{4}-\d{2}-\d{2})/?$'] = 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=day' . '&eventDate=' . $wp_rewrite->preg_index( 3 );
			$newRules[$catBase . '([^/]+)/(\d{4}-\d{2})$']                      = 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=month' . '&eventDate=' . $wp_rewrite->preg_index( 3 );
			$newRules[$catBase . '([^/]+)/(\d{4}-\d{2}-\d{2})$']                = 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=day&eventDate=' . $wp_rewrite->preg_index( 3 );
			$newRules[$catBase . '([^/]+)/feed/?$']                             = 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&eventDisplay=list&post_type=' . self::POSTTYPE . '&feed=rss2';
			$newRules[$catBase . '([^/]+)/ical/?$']                             = 'index.php?post_type=' . self::POSTTYPE . '&eventDisplay=list&tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&ical=1';
			$newRules[$catBase . '([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$']    = 'index.php?post_type=' . self::POSTTYPE . '&tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&feed=' . $wp_rewrite->preg_index( 3 );
			$newRules[$catBase . '([^/]+)/?$']                                  = 'index.php?tribe_events_cat=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=' . $this->getOption( 'viewOption', 'month' );

			// tag rules.
			$newRules[$tagBase . '([^/]+)/page/(\d+)']                          = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list&paged=' . $wp_rewrite->preg_index( 3 );
			$newRules[$tagBase . '([^/]+)/' . $month]                           = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=month';
			$newRules[$tagBase . '([^/]+)/' . $list . '/page/(\d+)']        	= 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list&paged=' . $wp_rewrite->preg_index( 3 );
			$newRules[$tagBase . '([^/]+)/' . $list]                        	= 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list';
			$newRules[$tagBase . '([^/]+)/' . $today]                           = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=day';
			$newRules[$tagBase . '([^/]+)/(\d{4}-\d{2})$']                      = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=month' . '&eventDate=' . $wp_rewrite->preg_index( 3 );
			$newRules[$tagBase . '([^/]+)/' . $day . '/(\d{4}-\d{2}-\d{2})/?$'] = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=day' . '&eventDate=' . $wp_rewrite->preg_index( 3 );
			$newRules[$tagBase . '([^/]+)/(\d{4}-\d{2}-\d{2})$']                = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=day&eventDate=' . $wp_rewrite->preg_index( 3 );
			$newRules[$tagBase . '([^/]+)/feed/?$']                             = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list&feed=rss2';
			$newRules[$tagBase . '([^/]+)/ical/?$']                             = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list&ical=1';
			$newRules[$tagBase . '([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$']    = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&feed=' . $wp_rewrite->preg_index( 3 );
			$newRules[$tagBase . '([^/]+)/?$']                                  = 'index.php?tag=' . $wp_rewrite->preg_index( 2 ) . '&post_type=' . self::POSTTYPE . '&eventDisplay=list';

			$wp_rewrite->rules = apply_filters( 'tribe_events_rewrite_rules', $newRules + $wp_rewrite->rules, $newRules );
		}

		/**
		 * Redirect the legacy past/upcoming view URLs to list
		 */
		public function redirect_past_upcoming_view_urls() {

			if ( strpos( $_SERVER['REQUEST_URI'], $this->getRewriteSlug() . '/' . $this->pastSlug ) !== false ) {
				wp_redirect( add_query_arg( array( 'tribe_event_display' => 'past' ), str_replace( '/' . $this->pastSlug . '/', '/' . $this->listSlug . '/', $_SERVER['REQUEST_URI'] ) ) );
			} elseif ( strpos( $_SERVER['REQUEST_URI'], $this->getRewriteSlug() . '/' . $this->upcomingSlug ) !== false ) {
				wp_redirect( str_replace( '/' . $this->upcomingSlug . '/', '/' . $this->listSlug . '/', $_SERVER['REQUEST_URI'] ) );
			}

		}

		/**
		 * Returns various internal events-related URLs
		 *
		 * @param string        $type      type of link. See switch statement for types.
		 * @param string        $secondary for $type = month, pass a YYYY-MM string for a specific month's URL
		 *                                 for $type = week, pass a Week # string for a specific week's URL
		 * @param int|bool|null $term
		 *
		 * @return string The link.
		 */
		public function getLink( $type = 'home', $secondary = false, $term = null ) {
			// if permalinks are off or user doesn't want them: ugly.
			if ( '' == get_option( 'permalink_structure' ) ) {
				return esc_url_raw( $this->uglyLink( $type, $secondary ) );
			}

			// account for semi-pretty permalinks
			if ( strpos( get_option( 'permalink_structure' ), "index.php" ) !== false ) {
				$eventUrl = trailingslashit( home_url() . '/index.php/' . sanitize_title( $this->getOption( 'eventsSlug', 'events' ) ) );
			} else {
				$eventUrl = trailingslashit( home_url() . '/' . sanitize_title( $this->getOption( 'eventsSlug', 'events' ) ) );
			}

			// if we're on an Event Cat, show the cat link, except for home and days.
			if ( $type !== 'home' && is_tax( self::TAXONOMY ) && $term !== false && ! is_numeric( $term ) ) {
				$term_link = get_term_link( get_query_var( 'term' ), self::TAXONOMY );
				if ( ! is_wp_error( $term_link ) ) {
					$eventUrl = trailingslashit( $term_link );
				}
			} else {
				if ( $term ) {
					$term_link = get_term_link( (int) $term, self::TAXONOMY );
					if ( ! is_wp_error( $term_link ) ) {
						$eventUrl = trailingslashit( $term_link );
					}
				}
			}

			switch ( $type ) {
				case 'home':
					$eventUrl = trailingslashit( esc_url_raw( $eventUrl ) );
					break;
				case 'month':
					if ( $secondary ) {
						$eventUrl = trailingslashit( esc_url_raw( $eventUrl . $secondary ) );
					} else {
						$eventUrl = trailingslashit( esc_url_raw( $eventUrl . $this->monthSlug ) );
					}
					break;
				case 'list':
					$eventUrl = trailingslashit( esc_url_raw( $eventUrl . $this->listSlug ) );
					break;
				case 'upcoming':
					$eventUrl = trailingslashit( esc_url_raw( $eventUrl . $this->listSlug ) );
					break;
				case 'past':
					$eventUrl = trailingslashit( esc_url_raw( add_query_arg( 'tribe_event_display', 'past', $eventUrl . $this->listSlug ) ) );
					break;
				case 'dropdown':
					$eventUrl = esc_url_raw( $eventUrl );
					break;
				case 'single':
					global $post;
					$p        = $secondary ? $secondary : $post;
					$link     = trailingslashit( get_permalink( $p ) );
					$eventUrl = trailingslashit( esc_url_raw( $link ) );
					break;
				case 'day':
					if ( empty( $secondary ) ) {
						$secondary = $this->todaySlug;
					} else {
						$secondary = tribe_event_format_date($secondary, false, TribeDateUtils::DBDATEFORMAT);
					}
					$eventUrl  = trailingslashit( esc_url_raw( $eventUrl . $secondary ) );
					break;
				default:
					$eventUrl = esc_url_raw( $eventUrl );
					break;
			}

			return apply_filters( 'tribe_events_getLink', $eventUrl, $type, $secondary, $term );
		}

		/**
		 * If pretty perms are off, get the ugly link.
		 *
		 * @param string $type      The type of link requested.
		 * @param bool|string       $secondary Some secondary data for the link.
		 *
		 * @return string The ugly link.
		 */
		public function uglyLink( $type = 'home', $secondary = false ) {

			$eventUrl = add_query_arg( 'post_type', self::POSTTYPE, home_url() );

			// if we're on an Event Cat, show the cat link, except for home.
			if ( $type !== 'home' && is_tax( self::TAXONOMY ) ) {
				$eventUrl = add_query_arg( self::TAXONOMY, get_query_var( 'term' ), $eventUrl );
			}

			switch ( $type ) {
				case 'day':
					$eventUrl = add_query_arg( array( 'eventDisplay' => $type ), $eventUrl );
					if ( $secondary ) {
						$eventUrl = add_query_arg( array( 'eventDate' => $secondary ), $eventUrl );
					}
					break;
				case 'week':
				case 'month':
					$eventUrl = add_query_arg( array( 'eventDisplay' => $type ), $eventUrl );
					if ( is_string( $secondary ) ) {
						$eventUrl = add_query_arg( array( 'eventDate' => $secondary ), $eventUrl );
					} elseif ( is_array( $secondary ) ) {
						$eventUrl = add_query_arg( $secondary, $eventUrl );
					}
					break;
				case 'past':
				case 'upcoming':
					$eventUrl = add_query_arg( array( 'eventDisplay' => $type ), $eventUrl );
					break;
				case 'dropdown':
					$dropdown = add_query_arg( array( 'eventDisplay' => 'month', 'eventDate' => ' ' ), $eventUrl );
					$eventUrl = rtrim( $dropdown ); // tricksy
					break;
				case 'single':
					global $post;
					$p        = $secondary ? $secondary : $post;
					$eventUrl = get_permalink( $p );
					break;
				case 'home':
				default:
					break;
			}

			return apply_filters( 'tribe_events_ugly_link', $eventUrl, $type, $secondary );
		}

		/**
		 * Returns the GCal export link for a given event id.
		 *
		 * @param int $postId The post id requested.
		 *
		 * @return string The URL for the GCal export link.
		 */
		public function googleCalendarLink( $postId = null ) {
			global $post;
			$tribeEvents = TribeEvents::instance();

			if ( $postId === null || ! is_numeric( $postId ) ) {
				$postId = $post->ID;
			}
			// protecting for reccuring because the post object will have the start/end date available
			$start_date = isset( $post->EventStartDate )
				? strtotime( $post->EventStartDate )
				: strtotime( get_post_meta( $postId, '_EventStartDate', true ) );
			$end_date   = isset( $post->EventEndDate )
				? strtotime( $post->EventEndDate . ( get_post_meta( $postId, '_EventAllDay', true ) ? ' + 1 day' : '' ) )
				: strtotime( get_post_meta( $postId, '_EventEndDate', true ) . ( get_post_meta( $postId, '_EventAllDay', true ) ? ' + 1 day' : '' ) );

			$dates    = ( get_post_meta( $postId, '_EventAllDay', true ) ) ? date( 'Ymd', $start_date ) . '/' . date( 'Ymd', $end_date ) : date( 'Ymd', $start_date ) . 'T' . date( 'Hi00', $start_date ) . '/' . date( 'Ymd', $end_date ) . 'T' . date( 'Hi00', $end_date );
			$location = trim( $tribeEvents->fullAddressString( $postId ) );
			$base_url = 'http://www.google.com/calendar/event';

			$event_details = get_the_content();

			//Truncate Event Description and add permalink if greater than 996 characters
			if ( strlen( $event_details ) > 996 ) {
				//Strip tags
				$event_details = strip_tags( $event_details );

				$event_url     = get_permalink();
				$event_details = substr( $event_details, 0, 996 );

				//Only add the permalink if it's shorter than 900 characters, so we don't exceed the browser's URL limits
				if ( strlen( $event_url ) < 900 ) {
					$event_details .= ' (View Full Event Description Here: ' . $event_url . ')';
				}
			}

            $details = apply_filters( 'the_content', $event_details );
            $details = str_replace('</p>', '</p> ', $details);
            $details = urlencode(strip_tags($details));
            
			$params = array(
				'action'   => 'TEMPLATE',
				'text'     => urlencode( strip_tags( $post->post_title ) ),
				'dates'    => $dates,
				'details'  => $details,
				'location' => urlencode( $location ),
				'sprop'    => get_option( 'blogname' ),
				'trp'      => 'false',
				'sprop'    => 'website:' . home_url(),
			);
			$params = apply_filters( 'tribe_google_calendar_parameters', $params );
			$url    = add_query_arg( $params, $base_url );

			return esc_url( $url );
		}

		/**
		 * Returns a link to google maps for the given event. This link can be filtered 
		 * using the tribe_events_google_map_link hook.
		 *
		 * @param int|null $post_id
		 *
		 * @return string a fully qualified link to http://maps.google.com/ for this event
		 */
		public function googleMapLink( $post_id = null ) {
			if ( $post_id === null || ! is_numeric( $post_id ) ) {
				global $post;
				$post_id = $post->ID;
			}

			$locationMetaSuffixes = array( 'address', 'city', 'region', 'zip', 'country' );
			$to_encode = "";
			$url = '';

			foreach ( $locationMetaSuffixes as $val ) {
				$metaVal = call_user_func( 'tribe_get_' . $val, $post_id );
				if ( $metaVal ) {
					$to_encode .= $metaVal . " ";
				}
			}

			if ( $to_encode ) {
				$url = "http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=" . urlencode( trim( $to_encode ) );
			}

			return apply_filters( 'tribe_events_google_map_link', $url, $post_id );
		}

		/**
		 *  Returns the full address of an event along with HTML markup.  It
		 *  loads the full-address template to generate the HTML
		 */
		public function fullAddress( $post_id = null, $includeVenueName = false ) {
			global $post;
			if ( ! is_null( $post_id ) ) {
				$tmp_post = $post;
				$post     = get_post( $post_id );
			}
			ob_start();
			tribe_get_template_part( 'modules/address' );
			$address = ob_get_contents();
			ob_end_clean();
			if ( ! empty( $tmp_post ) ) {
				$post = $tmp_post;
			}

			return $address;
		}

		/**
		 *  Returns a string version of the full address of an event
		 *
		 * @param int|WP_Post The post object or post id.
		 *
		 * @return string The event's address.
		 */
		public function fullAddressString( $postId = null ) {
			$address = '';
			if ( tribe_get_address( $postId ) ) {
				$address .= tribe_get_address( $postId );
			}

			if ( tribe_get_city( $postId ) ) {
				if ( $address != '' ) {
					$address .= ", ";
				}
				$address .= tribe_get_city( $postId );
			}

			if ( tribe_get_region( $postId ) ) {
				if ( $address != '' ) {
					$address .= ", ";
				}
				$address .= tribe_get_region( $postId );
			}

			if ( tribe_get_zip( $postId ) ) {
				if ( $address != '' ) {
					$address .= ", ";
				}
				$address .= tribe_get_zip( $postId );
			}

			if ( tribe_get_country( $postId ) ) {
				if ( $address != '' ) {
					$address .= ", ";
				}
				$address .= tribe_get_country( $postId );
			}

			return $address;
		}

		/**
		 * This plugin does not have any deactivation functionality. Any events, categories, options and metadata are
		 * left behind.
		 *
		 * @return void
		 */
		public function on_deactivate() {
			TribeEvents::flushRewriteRules();
		}

		/**
		 * Converts a set of inputs to YYYY-MM-DD HH:MM:SS format for MySQL
		 *
		 * @param string $date     The date.
		 * @param int    $hour     The hour of the day.
		 * @param int    $minute   The minute of the hour.
		 * @param string $meridian "am" or "pm".
		 *
		 * @return string The date and time.
		 * @todo remove - unused
		 */
		public function dateToTimeStamp( $date, $hour, $minute, $meridian ) {
			if ( preg_match( '/(PM|pm)/', $meridian ) && $hour < 12 ) {
				$hour += "12";
			}
			if ( preg_match( '/(AM|am)/', $meridian ) && $hour == 12 ) {
				$hour = "00";
			}
			$date = $this->dateHelper( $date );

			return "$date $hour:$minute:00";
		}

		/**
		 * Ensures date follows proper YYYY-MM-DD format
		 * converts /, - and space chars to -
		 *
		 * @param string $date The date.
		 *
		 * @return string The cleaned-up date.
		 * @todo remove - unused
		 */
		protected function dateHelper( $date ) {

			if ( $date == '' ) {
				return date( TribeDateUtils::DBDATEFORMAT );
			}

			$date = str_replace( array( '-', '/', ' ', ':', chr( 150 ), chr( 151 ), chr( 45 ) ), '-', $date );
			// ensure no extra bits are added
			list( $year, $month, $day ) = explode( '-', $date );

			if ( ! checkdate( $month, $day, $year ) ) {
				$date = date( TribeDateUtils::DBDATEFORMAT );
			} // today's date if error
			else {
				$date = $year . '-' . $month . '-' . $day;
			}

			return $date;
		}

		/**
		 * Adds an alias for get_post_meta so we can do extra stuff to the plugin values.
		 * If you need the raw unfiltered data, use get_post_meta directly.
		 * This is mainly for templates.
		 *
		 * @param int    $id     The post id.
		 * @param string $meta   The meta key.
		 * @param bool   $single Return as string? Or array?
		 *
		 * @return mixed The meta.
		 */
		public function getEventMeta( $id, $meta, $single = true ) {
			$use_def_if_empty = class_exists( 'TribeEventsPro' ) ? tribe_get_option( 'defaultValueReplace' ) : false;
			if ( $use_def_if_empty ) {
				$cleaned_tag = str_replace( '_Event', '', $meta );
				$default     = tribe_get_option( 'eventsDefault' . $cleaned_tag );
				$default     = apply_filters( 'filter_eventsDefault' . $cleaned_tag, $default );

				return ( get_post_meta( $id, $meta, $single ) !== false ) ? get_post_meta( $id, $meta, $single ) : $default;
			} else {
				return get_post_meta( $id, $meta, $single );
			}

		}

		/**
		 * ensure only one venue or organizer is created during post preview
		 * subsequent previews will reuse that same post
		 *
		 * ensure that preview post is the one that's used when the event is published,
		 * unless we're publishing with a saved venue
		 *
		 * @param $post_type can be 'venue' or 'organizer'
		 */
		protected function manage_preview_metapost( $post_type, $event_id ) {

			if ( ! in_array( $post_type, array( 'venue', 'organizer' ) ) ) {
				return;
			}

			$posttype        = ucfirst( $post_type );
			$posttype_id     = $posttype . 'ID';
			$meta_key        = '_preview_' . $post_type . '_id';
			$valid_post_id   = "tribe_get_{$post_type}_id";
			$create          = "create$posttype";
			$preview_post_id = get_post_meta( $event_id, $meta_key, true );
			$doing_preview   = $_REQUEST['wp-preview'] == 'dopreview' ? true : false;

			if ( empty( $_POST[$posttype][$posttype_id] ) ) {
				// the event is set to use a new metapost
				if ( $doing_preview ) {
					// we're previewing
					if ( $preview_post_id && $preview_post_id == $valid_post_id( $preview_post_id ) ) {
						// a preview post has been created and is valid, update that
						wp_update_post(
							array(
								'ID'         => $preview_post_id,
								'post_title' => $_POST[$posttype][$posttype]
							)
						);
					} else {
						// a preview post has not been created yet, or is not valid - create one and save the ID
						$preview_post_id = TribeEventsAPI::$create( $_POST[$posttype], 'draft' );
						update_post_meta( $event_id, $meta_key, $preview_post_id );
					}
				}

				if ( $preview_post_id ) {
					// set the preview post id as the event metapost id in the $_POST array
					// so TribeEventsAPI::saveEventVenue() doesn't make a new post
					$_POST[$posttype][$posttype_id] = (int) $preview_post_id;
				}
			} else {
				// we're using a saved metapost, discard any preview post
				if ( $preview_post_id ) {
					wp_delete_post( $preview_post_id );
					global $wpdb;
					$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE `meta_key` = '$meta_key' AND `meta_value` = $preview_post_id" );
				}
			}
		}

		/**
		 * Adds / removes the event details as meta tags to the post.
		 *
		 * @param int     $postId
		 * @param WP_Post $post
		 *
		 * @return void
		 */
		public function addEventMeta( $postId, $post ) {

			// Remove this hook to avoid an infinite loop, because saveEventMeta calls wp_update_post when the post is set to always show in calendar
			remove_action( 'save_post_' . self::POSTTYPE, array( $this, 'addEventMeta' ), 15, 2 );

			// only continue if it's an event post
			if ( $post->post_type != self::POSTTYPE || defined( 'DOING_AJAX' ) ) {
				return;
			}
			// don't do anything on autosave or auto-draft either or massupdates
			if ( wp_is_post_autosave( $postId ) || $post->post_status == 'auto-draft' || isset( $_GET['bulk_edit'] ) || ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'inline-save' ) ) {
				return;
			}

			// don't do anything on other wp_insert_post calls
			if ( isset( $_POST['post_ID'] ) && $postId != $_POST['post_ID'] ) {
				return;
			}

			if ( ! isset( $_POST['ecp_nonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST['ecp_nonce'], TribeEvents::POSTTYPE ) ) {
				return;
			}

			if ( ! current_user_can( 'edit_tribe_events' ) ) {
				return;
			}

			$_POST['Organizer'] = isset( $_POST['organizer'] ) ? stripslashes_deep( $_POST['organizer'] ) : null;
			$_POST['Venue']     = isset( $_POST['venue'] ) ? stripslashes_deep( $_POST['venue'] ) : null;


			/**
			 * handle previewed venues and organizers
			 */
			$this->manage_preview_metapost( 'venue', $postId );
			$this->manage_preview_metapost( 'organizer', $postId );

			/**
			 * When using pro and we have a VenueID/OrganizerID, we just save the ID, because we're not
			 * editing the venue/organizer from within the event.
			 */
			if ( isset( $_POST['Venue']['VenueID'] ) && ! empty( $_POST['Venue']['VenueID'] ) ) {
				$_POST['Venue'] = array( 'VenueID' => intval( $_POST['Venue']['VenueID'] ) );
			}

			if ( isset( $_POST['Organizer']['OrganizerID'] ) && ! empty( $_POST['Organizer']['OrganizerID'] ) ) {
				$_POST['Organizer'] = array( 'OrganizerID' => intval( $_POST['Organizer']['OrganizerID'] ) );
			}


			TribeEventsAPI::saveEventMeta( $postId, $_POST, $post );

			// Add this hook back in
			add_action( 'save_post_' . self::POSTTYPE, array( $this, 'addEventMeta' ), 15, 2 );
		}

		/**
		 * Intended to run when the save_post_tribe_events action is fired.
		 *
		 * At this point we know an event is being updated or created and, if the post is going to
		 * be visible, we can set up a further action to handle updating our record of the
		 * populated date range once the post meta containing the start and end date for the post
		 * has saved.
		 */
		public function maybe_update_known_range( $post_id ) {
			// If the event isn't going to be visible (perhaps it's been trashed) rebuild dates and bail
			if ( ! in_array( get_post_status( $post_id ), array( 'publish', 'private', 'protected' ) ) ) {
				$this->rebuild_known_range();
				return;
			}

			add_action( 'added_post_meta', array( $this, 'update_known_range' ), 10, 3 );
		}

		/**
		 * Intelligently updates our record of the earliest start date/latest event date in
		 * the system. If the existing earliest/latest values have not been superseded by the new post's
		 * start/end date then no update takes place.
		 *
		 * This is deliberately hooked into save_post, rather than save_post_tribe_events, to avoid issues
		 * where the removal/restoration of hooks within addEventMeta() etc might stop this method from
		 * actually being called (relates to a core WP bug).
		 */
		public function update_known_range( $meta_id, $object_id, $meta_key  ) {
			if ( TribeEvents::POSTTYPE !== get_post_type( $object_id ) ) return;
			if ( '_EventDuration' !== $meta_key ) return;

			$current_min = tribe_events_earliest_date();
			$current_max = tribe_events_latest_date();

			$event_start = tribe_get_start_date( $object_id, false, TribeDateUtils::DBDATETIMEFORMAT );
			$event_end   = tribe_get_end_date( $object_id, false, TribeDateUtils::DBDATETIMEFORMAT );

			if ( $current_min > $event_start ) {
				tribe_update_option( 'earliest_date', $event_start );
			}
			if ( $current_max < $event_end ) {
				tribe_update_option( 'latest_date', $event_end );
			}
		}

		/**
		 * Fires on delete_post and decides whether or not to rebuild our record or
		 * earliest/latest event dates (which will be done when deleted_post fires,
		 * so that the deleted event is removed from the db before we recalculate).
		 *
		 * @param $post_id
		 */
		public function maybe_rebuild_known_range( $post_id ) {
			if ( self::POSTTYPE === get_post_type( $post_id ) ) {
				add_action( 'deleted_post', array( $this, 'rebuild_known_range' ) );
			}
		}

		/**
		 * Determine the earliest start date and latest end date currently in the database
		 * and store those values for future use.
		 */
		public function rebuild_known_range() {
			global $wpdb;
			remove_action( 'deleted_post', array( $this, 'rebuild_known_range' ) );

			$earliest = strtotime(
				$wpdb->get_var(
					 $wpdb->prepare(
						  "
				SELECT MIN(meta_value) FROM $wpdb->postmeta
				JOIN $wpdb->posts ON post_id = ID
				WHERE meta_key = '_EventStartDate'
				AND post_type = '%s'
				AND post_status IN ('publish', 'private', 'protected')
			", self::POSTTYPE
					 )
				)
			);

			$latest = strtotime(
				$wpdb->get_var(
					 $wpdb->prepare(
						  "
				SELECT MAX(meta_value) FROM $wpdb->postmeta
				JOIN $wpdb->posts ON post_id = ID
				WHERE meta_key = '_EventEndDate'
				AND post_type = '%s'
				AND post_status IN ('publish', 'private', 'protected')
			", self::POSTTYPE
					 )
				)
			);

			if ( $earliest ) {
				tribe_update_option( 'earliest_date', date( TribeDateUtils::DBDATETIMEFORMAT, $earliest ) );
			}
			if ( $latest ) {
				tribe_update_option( 'latest_date', date( TribeDateUtils::DBDATETIMEFORMAT, $latest ) );
			}
		}

		/**
		 * Adds the '_<posttype>Origin' meta field for a newly inserted events-calendar post.
		 *
		 * @param int     $postId , the post ID
		 * @param WP_Post $post   , the post object
		 *
		 * @return void
		 */
		public function addPostOrigin( $postId, $post ) {
			// Only continue of the post being added is an event, venue, or organizer.
			if ( isset( $postId ) && isset( $post->post_type ) ) {
				if ( $post->post_type == self::POSTTYPE ) {
					$post_type = '_Event';
				} elseif ( $post->post_type == self::VENUE_POST_TYPE ) {
					$post_type = '_Venue';
				} elseif ( $post->post_type == self::ORGANIZER_POST_TYPE ) {
					$post_type = '_Organizer';
				} else {
					return;
				}

				//only set origin once
				$origin = get_post_meta( $postId, $post_type . 'Origin', true );
				if ( ! $origin ) {
					add_post_meta( $postId, $post_type . 'Origin', apply_filters( 'tribe-post-origin', 'events-calendar', $postId, $post ) );
				}
			}
		}

		/**
		 * Publishes associated venue/organizer when an event is published
		 *
		 * @param int     $postID , the post ID
		 * @param WP_Post $post   , the post object
		 *
		 * @return void
		 */
		public function publishAssociatedTypes( $postID, $post ) {

			// don't need to save the venue or organizer meta when we are just publishing
			remove_action( 'save_post_' . self::VENUE_POST_TYPE, array( $this, 'save_venue_data' ), 16, 2 );
			remove_action( 'save_post_' . self::ORGANIZER_POST_TYPE, array( $this, 'save_organizer_data' ), 16, 2 );

			// save venue and organizer info on first pass
			if ( isset( $post->post_status ) && $post->post_status == 'publish' ) {

				//get venue and organizer and publish them
				$pm = get_post_custom( $post->ID );

				do_action( 'log', 'publishing an event with a venue', 'tribe-events', $post );

				// save venue on first setup
				if ( ! empty( $pm['_EventVenueID'] ) ) {
					$venue_id = is_array( $pm['_EventVenueID'] ) ? current( $pm['_EventVenueID'] ) : $pm['_EventVenueID'];
					if ( $venue_id ) {
						do_action( 'log', 'event has a venue', 'tribe-events', $venue_id );
						$venue_post = get_post( $venue_id );
						if ( ! empty( $venue_post ) && $venue_post->post_status != 'publish' ) {
							do_action( 'log', 'venue post found', 'tribe-events', $venue_post );
							$venue_post->post_status = 'publish';
							wp_update_post( $venue_post );
							$did_save = true;
						}
					}
				}

				// save organizer on first setup
				if ( ! empty( $pm['_EventOrganizerID'] ) ) {
					$org_id = is_array( $pm['_EventOrganizerID'] ) ? current( $pm['_EventOrganizerID'] ) : $pm['_EventOrganizerID'];
					if ( $org_id ) {
						$org_post = get_post( $org_id );
						if ( ! empty( $org_post ) && $org_post->post_status != 'publish' ) {
							$org_post->post_status = 'publish';
							wp_update_post( $org_post );
							$did_save = true;
						}
					}
				}

			}

			// put the actions back
			add_action( 'save_post_' . self::VENUE_POST_TYPE, array( $this, 'save_venue_data' ), 16, 2 );
			add_action( 'save_post_' . self::ORGANIZER_POST_TYPE, array( $this, 'save_organizer_data' ), 16, 2 );

		}

		/**
		 * Make sure the venue meta gets saved
		 *
		 * @param int     $postID The venue id.
		 * @param WP_Post $post   The post object.
		 *
		 * @return null|void
		 */
		public function save_venue_data( $postID = null, $post = null ) {
			// was a venue submitted from the single venue post editor?
			if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $postID || empty( $_POST['venue'] ) ) {
				return;
			}

			// is the current user allowed to edit this venue?
			if ( ! current_user_can( 'edit_tribe_venue', $postID ) ) {
				return;
			}

			$data = stripslashes_deep( $_POST['venue'] );
			TribeEventsAPI::updateVenue( $postID, $data );
		}

		/**
		 * Get venue info.
		 *
		 * @param int $p          post id
		 * @param     $deprecated (deprecated)
		 * @param     $args
		 *
		 * @return WP_Query->posts || false
		 */
		function get_venue_info( $p = null, $deprecated = null, $args = array() ) {
			$defaults = array(
				'post_type'            => self::VENUE_POST_TYPE,
				'nopaging'             => 1,
				'post_status'          => 'publish',
				'ignore_sticky_posts ' => 1,
				'orderby'              => 'title',
				'order'                => 'ASC',
				'p'                    => $p
			);

			// allow deprecated param to pass through by default
			// NOTE: setting post_status in $args will override $post_status
			if ( $deprecated != null ) {
				_deprecated_argument( __FUNCTION__, 'The Event Calendar v2.0.9', 'To use the latest code, please supply post_status in the argument array params.' );
				$defaults['post_status'] = $deprecated;
			}


			$args = wp_parse_args( $args, $defaults );
			$r    = new WP_Query( $args );
			if ( $r->have_posts() ) :
				return $r->posts;
			endif;

			return false;
		}

		/**
		 * Make sure the organizer meta gets saved
		 *
		 * @param int     $postID The organizer id.
		 * @param WP_Post $post   The post object.
		 *
		 * @return null|void
		 */
		public function save_organizer_data( $postID = null, $post = null ) {
			// was an organizer submitted from the single organizer post editor?
			if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $postID || empty( $_POST['organizer'] ) ) {
				return;
			}

			// is the current user allowed to edit this venue?
			if ( ! current_user_can( 'edit_tribe_organizer', $postID ) ) {
				return;
			}

			$data = stripslashes_deep( $_POST['organizer'] );
			TribeEventsAPI::updateOrganizer( $postID, $data );
		}

		/**
		 * Add a new Organizer
		 *
		 * @param      $data
		 * @param null $post
		 *
		 * @return int|WP_Error
		 */
		public function add_new_organizer( $data, $post = null ) {
			if ( $data['OrganizerID'] ) {
				return $data['OrganizerID'];
			}

			if ( $post->post_type == self::ORGANIZER_POST_TYPE && $post->ID ) {
				$data['OrganizerID'] = $post->ID;
			}

			//google map checkboxes
			$postdata = array(
				'post_title'  => $data['Organizer'],
				'post_type'   => self::ORGANIZER_POST_TYPE,
				'post_status' => 'publish',
				'ID'          => $data['OrganizerID']
			);

			if ( isset( $data['OrganizerID'] ) && $data['OrganizerID'] != "0" ) {
				$organizer_id = $data['OrganizerID'];
				wp_update_post( array( 'post_title' => $data['Organizer'], 'ID' => $data['OrganizerID'] ) );
			} else {
				$organizer_id = wp_insert_post( $postdata, true );
			}

			if ( ! is_wp_error( $organizer_id ) ) {
				foreach ( $data as $key => $var ) {
					update_post_meta( $organizer_id, '_Organizer' . $key, $var );
				}

				return $organizer_id;
			}
		}

		/**
		 * Get Organizer info.
		 *
		 * @param int $p          post id
		 * @param     $deprecated (deprecated)
		 * @param     $args
		 *
		 * @return WP_Query->posts || false
		 */
		function get_organizer_info( $p = null, $deprecated = null, $args = array() ) {
			$defaults = array(
				'post_type'            => self::ORGANIZER_POST_TYPE,
				'nopaging'             => 1,
				'post_status'          => 'publish',
				'ignore_sticky_posts ' => 1,
				'orderby'              => 'title',
				'order'                => 'ASC',
				'p'                    => $p
			);

			// allow deprecated param to pass through by default
			// NOTE: setting post_status in $args will override $post_status
			if ( $deprecated != null ) {
				_deprecated_argument( __FUNCTION__, 'The Event Calendar v2.0.9', 'To use the latest code, please supply post_status in the argument array params.' );
				$defaults['post_status'] = $deprecated;
			}


			$args = wp_parse_args( $args, $defaults );
			$r    = new WP_Query( $args );
			if ( $r->have_posts() ) :
				return $r->posts;
			endif;

			return false;
		}

		/**
		 * Adds a style chooser to the write post page
		 *
		 * @param WP_Post $event
		 *
		 * @return void
		 * @todo review the heck out of this method.
		 */
		public function EventsChooserBox( $event = null ) {
			$saved = false;

			if ( ! $event ) {
				global $post;

				if ( isset( $_GET['post'] ) && $_GET['post'] ) {
					$saved = true;
				}
			} else {
				$post = $event;

				if ( $post->ID ) {
					$saved = true;
				} else {
					$saved = false;
				}
			}

			$options = '';
			$style   = '';

			if ( isset( $post->ID ) ) {
				$postId = $post->ID;
			} else {
				$postId = 0;
			}

			foreach ( $this->metaTags as $tag ) {
				if ( $postId && $saved ) { //if there is a post AND the post has been saved at least once.

					// Sort the meta to make sure it is correct for recurring events

					$meta = get_post_meta( $postId, $tag );
					sort( $meta );
					if ( isset( $meta[0] ) ) {
						$$tag = $meta[0];
					}
				} else {
					$cleaned_tag = str_replace( '_Event', '', $tag );

					//allow posted data to override default data
					if ( isset( $_POST['Event' . $cleaned_tag] ) ) {
						$$tag = stripslashes_deep( $_POST['Event' . $cleaned_tag] );
					} else {
						$$tag = ( class_exists( 'TribeEventsPro' ) && $this->defaultValueReplaceEnabled() ) ? tribe_get_option( 'eventsDefault' . $cleaned_tag ) : "";
					}
				}
			}

			if ( isset( $_EventOrganizerID ) && $_EventOrganizerID ) {
				foreach ( $this->organizerTags as $tag ) {
					$$tag = get_post_meta( $_EventOrganizerID, $tag, true );
				}
			} else {
				foreach ( $this->organizerTags as $tag ) {
					$cleaned_tag = str_replace( '_Organizer', '', $tag );
					if ( isset( $_POST['organizer'][$cleaned_tag] ) ) {
						$$tag = stripslashes_deep( $_POST['organizer'][$cleaned_tag] );
					}
				}
			}

			if ( isset( $_EventVenueID ) && $_EventVenueID ) {

				foreach ( $this->venueTags as $tag ) {
					$$tag = get_post_meta( $_EventVenueID, $tag, true );
				}

			} else {

				$defaults   = $this->venueTags;
				$defaults[] = '_VenueState';
				$defaults[] = '_VenueProvince';

				foreach ( $defaults as $tag ) {

					$cleaned_tag = str_replace( '_Venue', '', $tag );

					$var_name = '_Venue' . $cleaned_tag;

					if ( $cleaned_tag != 'Cost' ) {

						$$var_name = ( class_exists( 'TribeEventsPro' ) && $this->defaultValueReplaceEnabled() ) ? tribe_get_option( 'eventsDefault' . $cleaned_tag ) : "";
					}

					if ( isset( $_POST['venue'][$cleaned_tag] ) ) {
						$$var_name = stripslashes_deep( $_POST['venue'][$cleaned_tag] );
					}

				}

				if ( isset( $_VenueState ) && ! empty( $_VenueState ) ) {
					$_VenueStateProvince = $_VenueState;
				} elseif ( isset( $_VenueProvince ) ) {
					$_VenueStateProvince = $_VenueProvince;
				} else {
					$_VenueStateProvince = null;
				}

				if ( isset( $_POST['venue']['Country'] ) ) {
					if ( $_POST['venue']['Country'] == 'United States' ) {
						$_VenueStateProvince = stripslashes_deep( $_POST['venue']['State'] );
					} else {
						$_VenueStateProvince = stripslashes_deep( $_POST['venue']['Province'] );
					}
				}

			}

			$_EventAllDay    = isset( $_EventAllDay ) ? $_EventAllDay : false;
			$_EventStartDate = ( isset( $_EventStartDate ) ) ? $_EventStartDate : null;

			if ( isset( $_EventEndDate ) ) {
				if ( $_EventAllDay && TribeDateUtils::timeOnly( $_EventEndDate ) != '23:59:59' && TribeDateUtils::timeOnly( tribe_event_end_of_day() ) != '23:59:59' ) {

					// If it's an all day event and the EOD cutoff is later than midnight
					// set the end date to be the previous day so it displays correctly in the datepicker
					// so the datepickers will match. we'll set the correct end time upon saving
					// @todo: remove this once we're allowed to have all day events without a start/end time

					$_EventEndDate = date_create( $_EventEndDate );
					$_EventEndDate->modify( '-1 day' );
					$_EventEndDate = $_EventEndDate->format( TribeDateUtils::DBDATETIMEFORMAT );

				}
			} else {
				$_EventEndDate = null;
			}
			$isEventAllDay        = ( $_EventAllDay == 'yes' || ! TribeDateUtils::dateOnly( $_EventStartDate ) ) ? 'checked="checked"' : ''; // default is all day for new posts
			$startMinuteOptions   = TribeEventsViewHelpers::getMinuteOptions( $_EventStartDate, true );
			$endMinuteOptions     = TribeEventsViewHelpers::getMinuteOptions( $_EventEndDate );
			$startHourOptions     = TribeEventsViewHelpers::getHourOptions( $_EventAllDay == 'yes' ? null : $_EventStartDate, true );
			$endHourOptions       = TribeEventsViewHelpers::getHourOptions( $_EventAllDay == 'yes' ? null : $_EventEndDate );
			$startMeridianOptions = TribeEventsViewHelpers::getMeridianOptions( $_EventStartDate, true );
			$endMeridianOptions   = TribeEventsViewHelpers::getMeridianOptions( $_EventEndDate );

			if ( $_EventStartDate ) {
				$start = TribeDateUtils::dateOnly( $_EventStartDate );
			}

			$EventStartDate = ( isset( $start ) && $start ) ? $start : date( 'Y-m-d' );

			if ( ! empty( $_REQUEST['eventDate'] ) ) {
				$EventStartDate = esc_attr( $_REQUEST['eventDate'] );
			}

			if ( $_EventEndDate ) {
				$end = TribeDateUtils::dateOnly( $_EventEndDate );
			}

			$EventEndDate = ( isset( $end ) && $end ) ? $end : date( 'Y-m-d' );
			$recStart     = isset( $_REQUEST['event_start'] ) ? esc_attr( $_REQUEST['event_start'] ) : null;
			$recPost      = isset( $_REQUEST['post'] ) ? absint( $_REQUEST['post'] ) : null;


			if ( ! empty( $_REQUEST['eventDate'] ) ) {
				$duration     = get_post_meta( $postId, '_EventDuration', true );
				$start_time   = isset( $_EventStartDate ) ? TribeDateUtils::timeOnly( $_EventStartDate ) : TribeDateUtils::timeOnly( tribe_get_start_date( $post->ID ) );
				$EventEndDate = TribeDateUtils::dateOnly( strtotime( $_REQUEST['eventDate'] . ' ' . $start_time ) + $duration, true );
			}

			$events_meta_box_template = $this->pluginPath . 'admin-views/events-meta-box.php';
			$events_meta_box_template = apply_filters( 'tribe_events_meta_box_template', $events_meta_box_template );

			include( $events_meta_box_template );
		}

		/**
		 * Adds a style chooser to the write post page
		 *
		 * @return void
		 */
		public function VenueMetaBox() {
			global $post;
			$options = '';
			$style   = '';
			$postId  = $post->ID;

			if ( $post->post_type == self::VENUE_POST_TYPE ) {

				if ( ( is_admin() && isset( $_GET['post'] ) && $_GET['post'] ) || ( ! is_admin() && isset( $postId ) ) ) {
					$saved = true;
				}

				foreach ( $this->venueTags as $tag ) {
					if ( $postId && isset( $saved ) && $saved ) { //if there is a post AND the post has been saved at least once.
						$$tag = esc_html( get_post_meta( $postId, $tag, true ) );
					} elseif ( $this->defaultValueReplaceEnabled() ) {
						$cleaned_tag = str_replace( '_Venue', '', $tag );
						$$tag        = tribe_get_option( 'eventsDefault' . $cleaned_tag );
					}
				}
			}

			?>
			<style type="text/css">
				#EventInfo {
					border: none;
				}
			</style>
			<div id='eventDetails' class="inside eventForm">
				<table cellspacing="0" cellpadding="0" id="EventInfo" class="VenueInfo">
					<?php
					$venue_meta_box_template = apply_filters( 'tribe_events_venue_meta_box_template', $this->pluginPath . 'admin-views/venue-meta-box.php' );
					if ( ! empty( $venue_meta_box_template ) ) {
						include( $venue_meta_box_template );
					}
					?>
				</table>
			</div>
		<?php
		}

		/**
		 * Adds a style chooser to the write post page
		 *
		 * @return void
		 */
		public function OrganizerMetaBox() {
			global $post;
			$options = '';
			$style   = '';
			$postId  = $post->ID;
			$saved   = false;

			if ( $post->post_type == self::ORGANIZER_POST_TYPE ) {

				if ( ( is_admin() && isset( $_GET['post'] ) && $_GET['post'] ) || ( ! is_admin() && isset( $postId ) ) ) {
					$saved = true;
				}

				foreach ( $this->organizerTags as $tag ) {
					if ( $postId && $saved ) { //if there is a post AND the post has been saved at least once.
						$$tag = get_post_meta( $postId, $tag, true );
					}
				}
			}
			?>
			<style type="text/css">
				#EventInfo {
					border: none;
				}
			</style>
			<div id='eventDetails' class="inside eventForm">
				<table cellspacing="0" cellpadding="0" id="EventInfo" class="OrganizerInfo">
					<?php
					$hide_organizer_title = true;
					$organizer_meta_box_template = apply_filters( 'tribe_events_organizer_meta_box_template', $this->pluginPath . 'admin-views/organizer-meta-box.php' );
					if ( ! empty( $organizer_meta_box_template ) ) {
						include( $organizer_meta_box_template );
					}
					?>
				</table>
			</div>
		<?php
		}

		/**
		 * Handle ajax requests from admin form
		 *
		 * @return void
		 */
		public function ajax_form_validate() {
			if ( $_REQUEST['name'] && $_REQUEST['nonce'] && wp_verify_nonce( $_REQUEST['nonce'], 'tribe-validation-nonce' ) ) {
				if ( $_REQUEST['type'] == 'venue' ) {
					echo $this->verify_unique_name( $_REQUEST['name'], 'venue' );
					exit;
				} elseif ( $_REQUEST['type'] == 'organizer' ) {
					echo $this->verify_unique_name( $_REQUEST['name'], 'organizer' );
					exit;
				}
			}
		}

		/**
		 * Allow programmatic override of defaultValueReplace setting
		 *
		 * @return boolean
		 */
		public function defaultValueReplaceEnabled() {

			if ( ! is_admin() ) {
				return false;
			}

			return tribe_get_option( 'defaultValueReplace' );

		}

		/**
		 * Verify that a venue or organizer is unique
		 *
		 * @param string $name - name of venue or organizer
		 * @param string $type - post type (venue or organizer)
		 *
		 * @return boolean
		 */
		public function verify_unique_name( $name, $type ) {
			global $wpdb;
			$name = stripslashes( $name );
			if ( '' == $name ) {
				return 1;
			}
			if ( $type == 'venue' ) {
				$post_type = self::VENUE_POST_TYPE;
			} elseif ( $type == 'organizer' ) {
				$post_type = self::ORGANIZER_POST_TYPE;
			}
			// TODO update this verification to check all post_status <> 'trash'
			$results = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->posts} WHERE post_type = %s && post_title = %s && post_status = 'publish'", $post_type, $name ) );

			return ( $results ) ? 0 : 1;
		}

		/**
		 * Given a week of the year (WW), returns the YYYY-MM-DD of the first day of the week
		 *
		 * @deprecated
		 * @TODO: remove - unused
		 *
		 * @param  string $week expects string or int 2 of 1-52 (weeks of the year)
		 *
		 * @return string $date (YYYY-MM-DD)
		 */
		public function weekToDate( $week ) {
			_deprecated_function( __FUNCTION__, '3.0' );
			// TODO get first day of the week to return in YYYY-MM-DD
			// TODO fix date return format
			$date = date( "Y-m", strtotime( $week . ' weeks' ) );

			return $date;
		}

		/**
		 * Given a date (YYYY-MM-DD), return the first day of the previous week
		 *
		 * @deprecated
		 *
		 * @param date
		 *
		 * @return date
		 * @todo remove - unused
		 */
		public function previousWeek( $date ) {
			_deprecated_function( __FUNCTION__, '3.0' );
			$dateParts = explode( '-', $date );
			if ( $dateParts[1] == 1 ) {
				$dateParts[0] --;
				$dateParts[1] = "12";
				$dateParts[2] = "01";
			} else {
				$dateParts[1] --;
				$dateParts[2] = "01";
			}
			if ( $dateParts[1] < 10 ) {
				$dateParts[1] = "0" . $dateParts[1];
			}
			$return = $dateParts[0] . '-' . $dateParts[1];

			return $return;
		}

		/**
		 * Given a date (YYYY-MM-DD), returns the first of the next month
		 * hat tip to Dan Bernadict for method cleanup
		 *
		 * @param string $date
		 *
		 * @return string Next month's date
		 * @throws OverflowException
		 */
		public function nextMonth( $date ) {
			if ( PHP_INT_SIZE <= 4 ) {
				if ( date( 'Y-m-d', strtotime( $date ) ) > '2037-11-30' ) {
					throw new OverflowException( __( 'Date out of range.', 'tribe-events-calendar' ) );
				}
			}

			// create a new date object
			$date = new DateTime( $date );

			// set date object to be the first of the month -- all months have this day!
			$date->setDate( $date->format( 'Y' ), $date->format( 'm' ), 1 );

			// add a month
			$date->modify( '+1 month' );

			// return the year-month
			return $date->format( 'Y-m' );
		}

		/**
		 * Given a date (YYYY-MM-DD), return the first of the previous month
		 * hat tip to Dan Bernadict for method cleanup
		 *
		 * @param string $date
		 *
		 * @return string Previous month's date
		 * @throws OverflowException
		 */
		public function previousMonth( $date ) {
			if ( PHP_INT_SIZE <= 4 ) {
				if ( date( 'Y-m-d', strtotime( $date ) ) < '1902-02-01' ) {
					throw new OverflowException( __( 'Date out of range.', 'tribe-events-calendar' ) );
				}
			}

			// create a new date object
			$date = new DateTime( $date );

			// set date object to be the first of the month -- all months have this day!
			$date->setDate( $date->format( 'Y' ), $date->format( 'm' ), 1 );

			// subtract a month
			$date->modify( '-1 month' );

			// return the year-month
			return $date->format( 'Y-m' );
		}

		/**
		 * Callback for adding the Meta box to the admin page
		 *
		 * @return void
		 */
		public function addEventBox() {
			add_meta_box(
				'tribe_events_event_details', $this->pluginName, array(
					$this,
					'EventsChooserBox'
				), self::POSTTYPE, 'normal', 'high'
			);
			add_meta_box(
				'tribe_events_event_options', __( 'Event Options', 'tribe-events-calendar' ), array(
					$this,
					'eventMetaBox'
				), self::POSTTYPE, 'side', 'default'
			);

			add_meta_box(
				'tribe_events_venue_details', __( $this->singular_venue_label . ' Information', 'tribe-events-calendar' ), array(
					$this,
					'VenueMetaBox'
				), self::VENUE_POST_TYPE, 'normal', 'high'
			);

			if ( ! class_exists( 'TribeEventsPro' ) ) {
				remove_meta_box( 'slugdiv', self::VENUE_POST_TYPE, 'normal' );
			}

			add_meta_box(
				'tribe_events_organizer_details', __( $this->singular_organizer_label . ' Information', 'tribe-events-calendar' ), array(
					$this,
					'OrganizerMetaBox'
				), self::ORGANIZER_POST_TYPE, 'normal', 'high'
			);
		}

		/**
		 * Include the event editor meta box.
		 *
		 * @return void
		 */
		public function eventMetaBox() {
			include( $this->pluginPath . 'admin-views/event-sidebar-options.php' );
		}

		/**
		 * Get the date string (shortened).
		 *
		 * @param string $date The date.
		 *
		 * @return string The pretty (and shortened) date.
		 */
		public function getDateStringShortened( $date ) {
			$monthNames = $this->monthNames();
			$dateParts  = explode( '-', $date );
			$timestamp  = mktime( 0, 0, 0, $dateParts[1], 1, $dateParts[0] );

			return $monthNames[date( "F", $timestamp )];
		}

		/**
		 * Return the next tab index
		 *
		 * @return void
		 */
		public function tabIndex() {
			$this->tabIndexStart ++;

			return $this->tabIndexStart - 1;
		}

		/**
		 * Check whether a post is an event.
		 *
		 * @param int|WP_Post The event/post id or object.
		 *
		 * @return bool Is it an event?
		 */
		public function isEvent( $event ) {
			if ( $event === null || ( ! is_numeric( $event ) && ! is_object( $event ) ) ) {
				global $post;
				if ( is_object( $post ) && isset( $post->ID ) ) {
					$event = $post->ID;
				}
			}
			if ( is_numeric( $event ) ) {
				if ( get_post_type( $event ) == self::POSTTYPE ) {
					return true;
				}
			} elseif ( is_object( $event ) ) {
				if ( get_post_type( $event ) == self::POSTTYPE ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check whether a post is a venue.
		 *
		 * @param int|WP_Post The venue/post id or object.
		 *
		 * @return bool Is it a venue?
		 */
		public function isVenue( $postId = null ) {
			if ( $postId === null || ! is_numeric( $postId ) ) {
				global $post;
				if ( isset( $post->ID ) ) {
					$postId = $post->ID;
				}
			}
			if ( isset( $postId ) && get_post_field( 'post_type', $postId ) == self::VENUE_POST_TYPE ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether a post is an organizer.
		 *
		 * @param int|WP_Post The organizer/post id or object.
		 *
		 * @return bool Is it an organizer?
		 */
		public function isOrganizer( $postId = null ) {
			if ( $postId === null || ! is_numeric( $postId ) ) {
				global $post;
				$postId = $post->ID;
			}
			if ( isset( $postId ) && get_post_field( 'post_type', $postId ) == self::ORGANIZER_POST_TYPE ) {
				return true;
			}

			return false;
		}

		/**
		 * Get a "previous/next post" link for events. Ordered by start date instead of ID.
		 *
		 * @param WP_Post $post The post/event.
		 * @param string  $mode Either 'next' or 'previous'.
		 * @param mixed   $anchor
		 *
		 * @return string The link (with <a> tags).
		 */
		public function get_event_link( $post, $mode = 'next', $anchor = false ) {
			global $wpdb;

			if ( $mode == 'previous' ) {
				$order = 'DESC';
				$sign  = '<';
			} else {
				$order = 'ASC';
				$sign  = '>';
			}

			$date = $post->EventStartDate;
			$id   = $post->ID;

			$eventsQuery = $wpdb->prepare(
								"
				SELECT $wpdb->posts.*, d1.meta_value as EventStartDate
				FROM $wpdb->posts
				LEFT JOIN $wpdb->postmeta as d1 ON($wpdb->posts.ID = d1.post_id)
				WHERE $wpdb->posts.post_type = '%s'
				AND d1.meta_key = '_EventStartDate'
				AND ((d1.meta_value = '%s' AND ID $sign %d) OR
					d1.meta_value $sign '%s')
				AND $wpdb->posts.post_status = 'publish'
				AND ($wpdb->posts.ID != %d OR d1.meta_value != '%s')
				ORDER BY TIMESTAMP(d1.meta_value) $order, ID $order
				LIMIT 1", self::POSTTYPE, $date, $id, $date, $id, $date
			);

			$args = array(
				'post_type'      => self::POSTTYPE,
				'post_status'    => 'publish',
				'post__not_in'   => array( $post->ID ),
				'order'          => $order,
				'orderby'        => "TIMESTAMP($wpdb->postmeta.meta_value) ID",
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'   => '_EventStartDate',
						'value' => $post->EventStartDate,
						'type'  => 'DATE'
					)
				)
			);
			// TODO: Finish rewriting this query to be WP_QUERY based

			$results = $wpdb->get_row( $eventsQuery, OBJECT );
			if ( is_object( $results ) ) {
				if ( ! $anchor ) {
					$anchor = $results->post_title;
				} elseif ( strpos( $anchor, '%title%' ) !== false ) {
					$anchor = preg_replace( '|%title%|', $results->post_title, $anchor );
				}

				return apply_filters( 'tribe_events_get_event_link', '<a href="' . tribe_get_event_link( $results ) . '">' . $anchor . '</a>' );
			}
		}

		/**
		 * Add meta links to the Plugins list page.
		 *
		 * @param array  $links The current action links.
		 * @param string $file  The plugin to see if we are on TEC.
		 *
		 * @return array The modified action links array.
		 */
		public function addMetaLinks( $links, $file ) {
			if ( $file == $this->pluginDir . 'the-events-calendar.php' ) {
				$anchor   = __( 'Support', 'tribe-events-calendar' );
				$links [] = '<a href="' . self::$dotOrgSupportUrl . '" target="_blank">' . $anchor . '</a>';

				$anchor   = __( 'View All Add-Ons', 'tribe-events-calendar' );
				$link     = add_query_arg(
					array(
						'utm_campaign' => 'in-app',
						'utm_medium'   => 'plugin-tec',
						'utm_source'   => 'plugins-manager'
					), self::$tribeUrl . self::$addOnPath
				);
				$links [] = '<a href="' . $link . '" target="_blank">' . $anchor . '</a>';
			}

			return $links;
		}

		/**
		 * Register the dashboard widget.
		 *
		 * @return void
		 */
		public function dashboardWidget() {
			wp_add_dashboard_widget(
				'tribe_dashboard_widget', __( 'News from Modern Tribe', 'tribe-events-calendar' ), array(
					$this,
					'outputDashboardWidget'
				)
			);
		}

		/**
		 * Echo the dashboard widget.
		 *
		 * @param int $items
		 *
		 * @return void
		 */
		public function outputDashboardWidget( $items = 10 ) {
			echo '<div class="rss-widget">';
			wp_widget_rss_output( self::FEED_URL, array( 'items' => $items ) );
			echo "</div>";
		}

		/**
		 * Get the localized weekday names.
		 *
		 * @return void
		 */
		protected function constructDaysOfWeek() {
			global $wp_locale;
			for ( $i = 0; $i <= 6; $i ++ ) {
				$day                       = $wp_locale->get_weekday( $i );
				$this->daysOfWeek[$i]      = $day;
				$this->daysOfWeekShort[$i] = $wp_locale->get_weekday_abbrev( $day );
				$this->daysOfWeekMin[$i]   = $wp_locale->get_weekday_initial( $day );
			}
		}

		/**
		 * Set the class property postExceptionThrown.
		 *
		 * return void
		 */
		public function setPostExceptionThrown( $thrown ) {
			$this->postExceptionThrown = $thrown;
		}

		/**
		 * Get the thrown post exception.
		 *
		 * @return mixed
		 */
		public function getPostExceptionThrown() {
			return $this->postExceptionThrown;
		}

		/**
		 * A TEC wrapper of do_action(), basically.
		 *
		 * @param string $name        The action hook name.
		 * @param int    $event_id    The event this is tied to.
		 * @param bool   $showMessage The message to show.
		 * @param mixed  $extra_args  The extra args you want.
		 *
		 * @return void
		 */
		public function do_action( $name, $event_id = null, $showMessage = false, $extra_args = null ) {
			try {
				do_action( $name, $event_id, $extra_args );
				if ( ! $this->getPostExceptionThrown() && $event_id ) {
					delete_post_meta( $event_id, TribeEvents::EVENTSERROROPT );
				}
			} catch ( TribeEventsPostException $e ) {
				$this->setPostExceptionThrown( true );
				if ( $event_id ) {
					update_post_meta( $event_id, self::EVENTSERROROPT, trim( $e->getMessage() ) );
				}

				if ( $showMessage ) {
					$e->displayMessage( $showMessage );
				}
			}
		}

		/**
		 * Echoes upsell stuff, if it should.
		 *
		 * @param int $postId
		 *
		 * @return void
		 */
		public function maybeShowMetaUpsell( $postId ) {
			?>
			<tr class="eventBritePluginPlug">
			<td colspan="2" class="tribe_sectionheader">
				<h4><?php _e( 'Additional Functionality', 'tribe-events-calendar' ); ?></h4>
			</td>
			</tr>
			<tr class="eventBritePluginPlug">
			<td colspan="2">
				<p><?php _e( 'Looking for additional functionality including recurring events, ticket sales, publicly submitted events, new views and more?', 'tribe-events-calendar' ) ?> <?php printf(
						__( 'Check out the <a href="%s">available add-ons</a>.', 'tribe-events-calendar' ),
						add_query_arg(
							array(
								'utm_campaign' => 'in-app',
								'utm_medium'   => 'plugin-tec',
								'utm_source'   => 'post-editor'
							), TribeEvents::$tribeUrl . self::$addOnPath
						)
					); ?></p>
			</td>
			</tr><?php
		}


		/**
		 * Helper function for getting Post Id. Accepts null or a post id. If no $post object exists, returns false to avoid a PHP NOTICE
		 *
		 * @param int $postId (optional)
		 *
		 * @return int post ID
		 */
		public static function postIdHelper( $postId = null ) {
			if ( $postId != null && is_numeric( $postId ) > 0 ) {
				return (int) $postId;
			} elseif ( is_object( $postId ) && ! empty( $postId->ID ) ) {
				return (int) $postId->ID;
			} else {
				global $post;
				if ( is_object( $post ) ) {
					return get_the_ID();
				} else {
					return false;
				}
			}
		}

		/**
		 * Add the buttons/dropdown to the admin toolbar
		 *
		 * @return null
		 */
		public function addToolbarItems() {
			if ( ( ! defined( 'TRIBE_DISABLE_TOOLBAR_ITEMS' ) || ! TRIBE_DISABLE_TOOLBAR_ITEMS ) && ! is_network_admin() ) {
				global $wp_admin_bar;

				$wp_admin_bar->add_menu(
							 array(
								 'id'    => 'tribe-events',
								 'title' => __( 'Events', 'tribe-events-calendar' ),
								 'href'  => $this->getLink( 'home' )
							 )
				);

				$wp_admin_bar->add_group(
							 array(
								 'id'     => 'tribe-events-group',
								 'parent' => 'tribe-events'
							 )
				);

				$wp_admin_bar->add_group(
							 array(
								 'id'     => 'tribe-events-add-ons-group',
								 'parent' => 'tribe-events'
							 )
				);

				$wp_admin_bar->add_group(
							 array(
								 'id'     => 'tribe-events-settings-group',
								 'parent' => 'tribe-events'
							 )
				);
				if ( current_user_can( 'edit_tribe_events' ) ) {
					$wp_admin_bar->add_group(
								 array(
									 'id'     => 'tribe-events-import-group',
									 'parent' => 'tribe-events-add-ons-group'
								 )
					);
				}

				$wp_admin_bar->add_menu(
							 array(
								 'id'     => 'tribe-events-view-calendar',
								 'title'  => __( 'View Calendar', 'tribe-events-calendar' ),
								 'href'   => $this->getLink( 'home' ),
								 'parent' => 'tribe-events-group'
							 )
				);

				if ( current_user_can( 'edit_tribe_events' ) ) {
					$wp_admin_bar->add_menu(
								 array(
									 'id'     => 'tribe-events-add-event',
									 'title'  => __( 'Add Event', 'tribe-events-calendar' ),
									 'href'   => trailingslashit( get_admin_url() ) . 'post-new.php?post_type=' . self::POSTTYPE,
									 'parent' => 'tribe-events-group'
								 )
					);
				}

				if ( current_user_can( 'edit_tribe_events' ) ) {
					$wp_admin_bar->add_menu(
								 array(
									 'id'     => 'tribe-events-edit-events',
									 'title'  => __( 'Edit Events', 'tribe-events-calendar' ),
									 'href'   => trailingslashit( get_admin_url() ) . 'edit.php?post_type=' . self::POSTTYPE,
									 'parent' => 'tribe-events-group'
								 )
					);
				}

				if ( current_user_can( 'publish_tribe_events' ) ) {
					$import_node = $wp_admin_bar->get_node( 'tribe-events-import' );
					if ( ! is_object( $import_node ) ) {
						$wp_admin_bar->add_menu(
									 array(
										 'id'     => 'tribe-events-import',
										 'title'  => __( 'Import', 'tribe-events-calendar' ),
										 'parent' => 'tribe-events-import-group'
									 )
						);
					}
					$wp_admin_bar->add_menu(
								 array(
									 'id'     => 'tribe-csv-import',
									 'title'  => __( 'CSV', 'tribe-events-calendar' ),
									 'href'   => add_query_arg(
										 array(
											 'post_type' => TribeEvents::POSTTYPE,
											 'page'      => 'events-importer'
										 ), admin_url( 'edit.php' )
									 ),
									 'parent' => 'tribe-events-import'
								 )
					);
				}

				if ( current_user_can( 'manage_options' ) ) {

					$hide_all_settings = TribeEvents::instance()->getNetworkOption( 'allSettingsTabsHidden', '0' );
					if ( $hide_all_settings == '0' ) {
						$wp_admin_bar->add_menu(
									 array(
										 'id'     => 'tribe-events-settings',
										 'title'  => __( 'Settings', 'tribe-events-calendar' ),
										 'href'   => trailingslashit( get_admin_url() ) . 'edit.php?post_type=' . self::POSTTYPE . '&amp;page=tribe-events-calendar',
										 'parent' => 'tribe-events-settings-group'
									 )
						);
					}

					// Only show help link if it's not blocked in network admin.
					$hidden_settings_tabs = TribeEvents::instance()->getNetworkOption( 'hideSettingsTabs', array() );
					if ( ! in_array( 'help', $hidden_settings_tabs ) ) {
						$wp_admin_bar->add_menu(
									 array(
										 'id'     => 'tribe-events-help',
										 'title'  => __( 'Help', 'tribe-events-calendar' ),
										 'href'   => trailingslashit( get_admin_url() ) . 'edit.php?post_type=' . self::POSTTYPE . '&amp;page=tribe-events-calendar&amp;tab=help',
										 'parent' => 'tribe-events-settings-group'
									 )
						);
					}
				}
			}
		}

		/**
		 * Displays activation welcome admin notice.
		 *
		 *
		 * @return void
		 */
		public function activationMessage() {
			$has_been_activated = $this->getOption( 'welcome_notice', false );
			if ( ! $has_been_activated ) {
				echo '<div class="updated tribe-notice"><p>' . sprintf(
						__( 'Welcome to The Events Calendar! Your events calendar can be found at %s. To change the events slug, visit %sEvents -> Settings%s.', 'tribe-events-calendar' ), '<a href="' . $this->getLink() . '">' . $this->getLink() . '</a>', '<i><a href="' . add_query_arg(
							array(
								'post_type' => self::POSTTYPE,
								'page'      => 'tribe-events-calendar'
							), admin_url( 'edit.php' )
						) . '">', '</i></a>'
					) . '</p></div>';
				$this->setOption( 'welcome_notice', true );
			}
		}

		/**
		 * Resets the option such that the activation message is again displayed on reactivation.
		 *
		 *
		 * @return void
		 */
		public static function resetActivationMessage() {
			$tec = TribeEvents::instance();
			$tec->setOption( 'welcome_notice', false );
		}

		/**
		 * Displays the View Calendar link at the top of the Events list in admin.
		 *
		 *
		 * @return void
		 */
		public function addViewCalendar() {
			global $current_screen;
			if ( $current_screen->id == 'edit-' . self::POSTTYPE ) {
				//Output hidden DIV with Calendar link to be displayed via javascript
				echo '<div id="view-calendar-link-div" style="display:none;"><a class="add-new-h2" href="' . $this->getLink() . '">' . __( 'View Calendar', 'tribe-events-calendar' ) . '</a></div>';
			}
		}

		/**
		 * Set the menu-edit-page to default display the events-related items.
		 *
		 *
		 * @return void
		 */
		public function setInitialMenuMetaBoxes() {
			global $current_screen;
			if ( $current_screen->id != 'nav-menus' ) {
				return;
			}
			$user_id = wp_get_current_user()->ID;
			if ( get_user_option( 'tribe_setDefaultNavMenuBoxes', $user_id ) ) {
				return;
			}

			$current_hidden_boxes = array();
			$current_hidden_boxes = get_user_option( 'metaboxhidden_nav-menus', $user_id );

			if ( $array_key = array_search( 'add-' . self::POSTTYPE, $current_hidden_boxes ) ) {
				unset( $current_hidden_boxes[$array_key] );
			}
			if ( $array_key = array_search( 'add-' . self::VENUE_POST_TYPE, $current_hidden_boxes ) ) {
				unset( $current_hidden_boxes[$array_key] );
			}
			if ( $array_key = array_search( 'add-' . self::ORGANIZER_POST_TYPE, $current_hidden_boxes ) ) {
				unset( $current_hidden_boxes[$array_key] );
			}
			if ( $array_key = array_search( 'add-' . self::TAXONOMY, $current_hidden_boxes ) ) {
				unset( $current_hidden_boxes[$array_key] );
			}

			update_user_option( $user_id, 'metaboxhidden_nav-menus', $current_hidden_boxes, true );
			update_user_option( $user_id, 'tribe_setDefaultNavMenuBoxes', true, true );
		}

		/**
		 * Add links to the plugins row
		 *
		 * @param $actions
		 *
		 * @return mixed
		 * @todo move to an admin class
		 */
		public function addLinksToPluginActions( $actions ) {
			$actions['settings']       = '<a href="' . add_query_arg(
					array(
						'post_type' => self::POSTTYPE,
						'page'      => 'tribe-events-calendar'
					), admin_url( 'edit.php' )
				) . '">' . __( 'Settings', 'tribe-events-calendar' ) . '</a>';
			$actions['tribe-calendar'] = '<a href="' . $this->getLink() . '">' . __( 'Calendar', 'tribe-events-calendar' ) . '</a>';

			return $actions;
		}

		/**
		 * Add help menu item to the admin (unless blocked via network admin settings).
		 *
		 * @todo move to an admin class
		 */
		public function addHelpAdminMenuItem() {
			$hidden_settings_tabs = TribeEvents::instance()->getNetworkOption( 'hideSettingsTabs', array() );
			if ( in_array( 'help', $hidden_settings_tabs ) ) {
				return;
			}

			$parent = 'edit.php?post_type=' . self::POSTTYPE;
			$title  = __( 'Help', 'tribe-events-calendar' );
			$slug   = add_query_arg(
				array(
					'post_type' => self::POSTTYPE,
					'page'      => 'tribe-events-calendar',
					'tab'       => 'help'
				), 'edit.php'
			);

			add_submenu_page( $parent, $title, $title, 'manage_options', $slug, '' );
		}

		/**
		 * When the edit-tags.php screen loads, setup filters
		 * to fix the tagcloud links
		 *
		 * @return void
		 */
		public function prepare_to_fix_tagcloud_links() {
			$screen = get_current_screen();
			if ( isset( $screen->post_type ) && $screen->post_type == self::POSTTYPE ) {
				add_filter( 'get_edit_term_link', array( $this, 'add_post_type_to_edit_term_link' ), 10, 4 );
			}
		}

		/**
		 * Tag clouds in the admin don't pass the post type arg
		 * when getting the edit link. If we're on the tag admin
		 * in Events post type context, make sure we add that
		 * arg to the edit tag link
		 *
		 * @param string $link
		 * @param int    $term_id
		 * @param string $taxonomy
		 * @param string $context
		 *
		 * @return string
		 */
		public function add_post_type_to_edit_term_link( $link, $term_id, $taxonomy, $context ) {
			if ( $taxonomy == 'post_tag' && empty( $context ) ) {
				$link = add_query_arg( array( 'post_type' => self::POSTTYPE ), $link );
			}

			return $link;
		}

		/**
		 * Set up the list view in the view selector in the tribe events bar.
		 *
		 * @param array $views The current views array.
		 *
		 * @return array The modified views array.
		 */
		public function setup_listview_in_bar( $views ) {
			$views[] = array(
				'displaying'     => 'list',
				'event_bar_hook' => 'tribe_events_before_template',
				'anchor'         => __( 'List', 'tribe-events-calendar' ),
				'url'            => tribe_get_listview_link()
			);

			return $views;
		}

		/**
		 * Set up the calendar view in the view selector in the tribe events bar.
		 *
		 * @param array $views The current views array.
		 *
		 * @return array The modified views array.
		 */
		public function setup_gridview_in_bar( $views ) {
			$views[] = array(
				'displaying'     => 'month',
				'event_bar_hook' => 'tribe_events_month_before_template',
				'anchor'         => __( 'Month', 'tribe-events-calendar' ),
				'url'            => tribe_get_gridview_link()
			);

			return $views;
		}

		/**
		 * Add day view to the views selector in the tribe events bar.
		 *
		 * @param array $views The current array of views registered to the tribe bar.
		 *
		 * @return array The views registered with day view added.
		 */
		public function setup_dayview_in_bar( $views ) {
			$views[] = array(
				'displaying'     => 'day',
				'anchor'         => __( 'Day', 'tribe-events-calendar' ),
				'event_bar_hook' => 'tribe_events_before_template',
				'url'            => tribe_get_day_link()
			);

			return $views;
		}

		/**
		 * Set up the keyword search in the tribe events bar.
		 *
		 * @param array $filters The current filters in the bar array.
		 *
		 * @return array The modified filters array.
		 */
		public function setup_keyword_search_in_bar( $filters ) {

			$value = "";
			if ( ! empty( $_REQUEST['tribe-bar-search'] ) ) {
				$value = esc_attr( $_REQUEST['tribe-bar-search'] );
			}

			if ( tribe_get_option( 'tribeDisableTribeBar', false ) == false ) {
				$filters['tribe-bar-search'] = array(
					'name'    => 'tribe-bar-search',
					'caption' => __( 'Search', 'tribe-events-calendar' ),
					'html'    => '<input type="text" name="tribe-bar-search" id="tribe-bar-search" value="' . $value . '" placeholder="' . __( 'Search', 'tribe-events-calendar' ) . '">'
				);

			}

			return $filters;
		}

		/**
		 * Set up the date search in the tribe events bar.
		 *
		 * @param array $filters The current filters in the bar array.
		 *
		 * @return array The modified filters array.
		 */
		public function setup_date_search_in_bar( $filters ) {

			global $wp_query;

			$value = apply_filters( 'tribe-events-bar-date-search-default-value', '' );

			if ( ! empty( $_REQUEST['tribe-bar-date'] ) ) {
				$value = $_REQUEST['tribe-bar-date'];
			}

			$caption = __( 'Date', 'tribe-events-calendar' );

			if ( tribe_is_month() ) {
				$caption = __( 'Events In', 'tribe-events-calendar' );
			} elseif ( tribe_is_list_view() ) {
				$caption = __( 'Events From', 'tribe-events-calendar' );
			} elseif ( tribe_is_day() ) {
				$caption = __( 'Day Of', 'tribe-events-calendar' );
				$value   = date( TribeDateUtils::DBDATEFORMAT, strtotime( $wp_query->query_vars['eventDate'] ) );
			}

			$caption = apply_filters( 'tribe_bar_datepicker_caption', $caption );

			$filters['tribe-bar-date'] = array(
				'name'    => 'tribe-bar-date',
				'caption' => $caption,
				'html'    => '<input type="text" name="tribe-bar-date" style="position: relative;" id="tribe-bar-date" value="' . esc_attr( $value ) . '" placeholder="' . __( 'Date', 'tribe-events-calendar' ) . '">
								<input type="hidden" name="tribe-bar-date-day" id="tribe-bar-date-day" class="tribe-no-param" value="">'
			);

			return $filters;
		}

		/**
		 * Removes views that have been deselected in the Template Settings as hidden from the view array.
		 *
		 *
		 * @param array $views The current views array.
		 * @param bool  $visible
		 *
		 * @return array The new views array.
		 */
		public function remove_hidden_views( $views, $visible = true ) {
			$enable_views_defaults = array();
			foreach ( $views as $view ) {
				$enable_views_defaults[] = $view['displaying'];
			}
			if ( $visible ) {
				$enable_views = tribe_get_option( 'tribeEnableViews', $enable_views_defaults );
				foreach ( $views as $index => $view ) {
					if ( ! in_array( $view['displaying'], $enable_views ) ) {
						unset( $views[$index] );
					}
				}
			}

			return $views;
		}

		/**
		 * Disable the canonical redirect if tribe_paged is set
		 *
		 * @param WP_Query $query The current query object.
		 *
		 * @return WP_Query The modified query object.
		 */
		function set_tribe_paged( $query ) {
			if ( ! empty( $_REQUEST['tribe_paged'] ) ) {
				add_filter( 'redirect_canonical', '__return_false' );
			}

			return $query;
		}

		/**
		 * If recurring events are imported using the WP importer, WP will flag
		 * each instance as a duplicate. Here we alter the title so that
		 * WP sees them as distinct posts.
		 *
		 * @param array $post
		 *
		 * @return array
		 * @see TribeEvents::filter_wp_import_data_after()
		 */
		public function filter_wp_import_data_before( $post ) {
			if ( $post['post_type'] == TribeEvents::POSTTYPE && ! empty( $post['post_parent'] ) ) {
				$start_date = '';
				if ( isset( $post['postmeta'] ) && is_array( $post['postmeta'] ) ) {
					foreach ( $post['postmeta'] as $meta ) {
						if ( $meta['key'] == '_EventStartDate' ) {
							$start_date = $meta['value'];
							break;
						}
					}
				}
				if ( ! empty( $start_date ) ) {
					$post['post_title'] .= '[tribe_start_date]' . $start_date . '[/tribe_start_date]';
				}
			}

			return $post;
		}


		/**
		 * Event titles have been modified by filter_wp_import_data_before().
		 * This puts them back how they belong.
		 *
		 * @param array $post
		 *
		 * @return array
		 * @see TribeEvents::filter_wp_import_data_before()
		 */
		public function filter_wp_import_data_after( $post ) {
			if ( $post['post_type'] == TribeEvents::POSTTYPE ) {
				$post['post_title'] = preg_replace( '#\[tribe_start_date\].*?\[\/tribe_start_date\]#', '', $post['post_title'] );
			}

			return $post;
		}

		/**
		 * Insert an array after a specified key within another array.
		 *
		 * @param $key
		 * @param $source_array
		 * @param $insert_array
		 *
		 * @return array
		 *
		 */
		public static function array_insert_after_key( $key, $source_array, $insert_array ) {
			if ( array_key_exists( $key, $source_array ) ) {
				$position     = array_search( $key, array_keys( $source_array ) ) + 1;
				$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
			} else {
				// If no key is found, then add it to the end of the array.
				$source_array += $insert_array;
			}

			return $source_array;
		}

		/**
		 * Checks to see if any registered TEC-related plugins have been updated just now
		 * and runs an action if so, so that any upgrade-specific functionality can
		 * be run.
		 *
		 * @return void
		 */
		public function checkSuiteIfJustUpdated() {
			$plugins             = apply_filters(
				'tribe_tec_addons', array(
					'TribeEventsCalendar' => array(
						'plugin_name'      => 'The Events Calendar',
						'required_version' => self::VERSION,
						'current_version'  => self::VERSION,
						'plugin_dir_file'  => basename( dirname( __FILE__ ) ) . '/the-events-calendar.php'
					)
				)
			);
			$plugin_versions     = get_option( 'tribe_events_suite_versions', array() );
			$new_plugin_versions = $plugin_versions;

			foreach ( $plugins as $slug => $plugin ) {
				if ( ! isset( $plugin_versions[$slug] ) || version_compare( $plugin_versions[$slug], $plugin['current_version'], '!=' ) ) {
					$old_version = isset( $plugin_versions[$slug] ) ? $plugin_versions[$slug] : null;

					// Hook into this filter to execute upgrade items.
					if ( apply_filters( 'tribe_events_suite_upgrade', true, $slug, $plugin['plugin_name'], $plugin['current_version'], $old_version ) ) {
						$new_plugin_versions[$slug] = $plugin['current_version'];
					}
				}
			}

			if ( $new_plugin_versions != $plugin_versions ) {
				update_option( 'tribe_events_suite_versions', $new_plugin_versions );
			}
		}

		/**
		 * Helper used to test if PRO is present and activated.
		 *
		 * This method should no longer be used, but is being retained to avoid potential
		 * for fatal errors where core is updated before an addon plugin - such as Community
		 * Events 3.4 or earlier - which might otherwise occur were it removed completely.
		 *
		 * @deprecated as of 3.7, remove in 4.0
		 *
		 * @param string $version
		 *
		 * @return bool
		 */
		public static function ecpActive( $version = '2.0.7' ) {
			return class_exists( 'TribeEventsPro' ) && defined( 'TribeEventsPro::VERSION' ) && version_compare( TribeEventsPro::VERSION, $version, '>=' );
		}

	} // end TribeEvents class

} // end if !class_exists TribeEvents
