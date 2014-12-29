<?php
/**
 * groups-emails.php
 *
 * Copyright (c) 2014 Antonio Blanco http://www.eggemplo.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author eggemplo	
 * @package GroupsEmails
 * @since GroupsEmails 1.0.0
 *
 * Plugin Name: Groups Emails
 * Plugin URI: http://www.eggemplo.com
 * Description: Sends emails to users when they are added and removed to/from a group.
 * Version: 1.0
 * Author: eggemplo
 * Author URI: http://www.eggemplo.com
 * License: GPLv3
 */

class GroupsEmails_Plugin {
	public static function init() {
		add_action( 'init', array( __CLASS__, 'wp_init' ) );
	}

	public static function wp_init() {
		// users
		add_action( 'groups_created_user_group', array( __CLASS__, 'groups_created_user_group' ), 10, 2 );
		add_action( 'groups_deleted_user_group', array( __CLASS__, 'groups_deleted_user_group' ), 10, 2 );
	}
	
	/**
	 * Added an user to a group.
	 * @param int $user_id
	 * @param int $group_id
	 */
	public static function groups_created_user_group ( $user_id, $group_id ) {
		$group = new Groups_Group ( $group_id );
		$user_info = get_userdata( $user_id );

		if ( $group && $user_info ) {
			$group_name = $group->name;
			$user_email = $user_info->user_email;
			$user_name = $user_info->user_firstname;
			
			$headers = 'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) .'>' . "\r\n";
			$to = $user_email;
			$subject = 'Welcome to ' . $group_name;
			$message = 'Hi ' . $user_name . '. You have been added to ' . $group_name . ' group.';
			@wp_mail( $to, $subject, $message, $headers );
		}
	}
	
	/**
	 * Deleted an user from a group
	 * @param int $user_id
	 * @param int $group_id
	 */
	public static function groups_deleted_user_group ( $user_id, $group_id ) {
		$group = new Groups_Group ( $group_id );
		$user_info = get_userdata( $user_id );

		if ( $group && $user_info ) {
			$group_name = $group->name;
			$user_email = $user_info->user_email;
			$user_name = $user_info->user_firstname;
				
			$headers = 'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) .'>' . "\r\n";
			$to = $user_email;
			$subject = 'Leaving ' . $group_name;
			$message = 'Hi ' . $user_name . '. You have been removed from ' . $group_name . ' group.';
			@wp_mail( $to, $subject, $message, $headers );
		}
	}
}
GroupsEmails_Plugin::init();

