<?php
/**
 * File autoloader functionality
 *
 * @package     N8FLessonPopup\Support
 * @since       1.0.0
 * @author      Nate Finch
 * @link        https://n8finch.com
 * @license     GNU General Public License 2.0+
 */
namespace N8FLessonPopup\Support;

/**
 * Load all of the plugin's files.
 *
 * @since 1.0.0
 *
 * @param string $src_root_dir Root directory for the source files
 *
 * @return void
 */
function n8f_popup_autoload_files( $src_root_dir ) {



		$filenames = array(
			 'custom/dialog-form-view',
			 'custom/n8f-functionality',
			 'custom/cb-change-mail-sender',
			 'custom/n8f-rss-feed-agg',
			 'custom/n8f-popup-login',
		);

		foreach( $filenames as $filename ) {
			include_once( $src_root_dir . $filename . '.php' );
		}

}
