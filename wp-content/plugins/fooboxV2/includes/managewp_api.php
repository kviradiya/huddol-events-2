<?php
/**
 * Manage WP API integration
 * Date: 2013/11/10
 */
if ( !class_exists( 'foobox_managewp_api' ) ) {

	class foobox_managewp_api {

		private $_plugin_slug;
		private $_plugin_file;
		private $_plugin_version;
		private $_update_checker;

		/**
		 * @param $update_checker foolic_update_checker_v1_5
		 */
		function __construct($plugin_file, $plugin_slug, $plugin_version, $update_checker) {
			$this->_plugin_file    = $plugin_file;
			$this->_plugin_slug    = $plugin_slug;
			$this->_update_checker = $update_checker;
			$this->_plugin_version = $plugin_version;

			if ( !is_admin() ) {
				// ManageWP premium update filters
				add_filter( 'mwp_premium_update_notification', array($this, 'update_notification') );
				add_filter( 'mwp_premium_perform_update', array($this, 'perform_update') );
			}
		}

		function get_plugin_info() {

			$info = get_transient( 'foobox_plugin_version' );

			if ( !$info ) {

				$update_response_raw = $this->_update_checker->send_update_check_request( $this->_plugin_version );

				if (!is_wp_error($update_response_raw) && wp_remote_retrieve_response_code($update_response_raw) == 200) {
					$update_response = @unserialize(stripslashes($update_response_raw['body']));

					if ($update_response !== false) {

						$info = array(
							'valid'           => ($update_response->slug === $this->_plugin_slug && !isset($update_response->new_version)) ||
								(isset($update_response->new_version) && isset($update_response->package)),
							'requires_update' => isset($update_response->new_version),
							'new_version'     => isset($update_response->new_version) ? $update_response->new_version : $this->_plugin_version
						);

					}
				}

				if ( !$info ) {
					//we had problems!
					$info = array(
						'valid' => false
					);
				}

				//cache responses for 24 hours
				set_transient( "foobox_plugin_version", $info, 60 * 60 * 24 );
			}

			return $info;
		}

		function get_update_info() {

			$info = get_transient( 'foobox_update_info' );

			if ( !$info ) {

				$infoClass = $this->_update_checker->get_plugin_info();

				if ( !is_wp_error( $infoClass ) ) {

					$info = array(
						'valid'        => !empty($infoClass->download_link),
						'last_updated' => $infoClass->last_updated,
						'url'          => $infoClass->download_link
					);

				} else {
					//we had problems!
					$info = array(
						'valid' => false
					);
				}

				//cache responses for 24 hours
				set_transient( "foobox_update_info2", $info, 60 * 60 * 24 );
			}

			return $info;
		}

		public function update_notification($premium_update) {

			if ( !function_exists( 'get_plugin_data' ) ) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$info = $this->get_plugin_info();

			if ( $info['valid'] === true && $info['requires_update'] === true ) {
				$plugin_data                = get_plugin_data( $this->_plugin_file );
				$plugin_data['type']        = 'plugin';
				$plugin_data['slug']        = plugin_basename( $this->_plugin_file );
				$plugin_data['new_version'] = isset($info['new_version']) ? $info['new_version'] : false;
				$premium_update[]           = $plugin_data;
			}

			return $premium_update;
		}

		public function perform_update($premium_update) {

			if ( !function_exists( 'get_plugin_data' ) ) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$info = $this->get_plugin_info();

			if ( $info['valid'] === true && $info['requires_update'] === true ) {

				$update_info = $this->get_update_info();

				$plugin_data         = get_plugin_data( $this->_plugin_file );
				$plugin_data['slug'] = plugin_basename( $this->_plugin_file );
				$plugin_data['type'] = 'plugin';
				$plugin_data['url']  = isset($update_info['url']) ? $update_info['url'] : false;
				$premium_update[] 	 = $plugin_data;
			}

			return $premium_update;
		}
	}
}