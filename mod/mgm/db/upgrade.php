<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * This file keeps track of upgrades to the mgm module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installtion to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in
 * lib/ddllib.php
 *
 * @package   mod_mgm
 * @copyright 2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * xmldb_mgm_upgrade
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_mgm_upgrade($oldversion=0) {
	global $CFG, $DB, $OUTPUT;
	
	$dbman = $DB->get_manager(); // loads ddl manager and xmldb classes
	$result = true;
	
	// Moodle v2.2.0 release upgrade line
	// Put any upgrade step following this
	
	// Moodle v2.3.0 release upgrade line
	// Put any upgrade step following this
	
	
	// Moodle v2.4.0 release upgrade line
	// Put any upgrade step following this
	
	// Forcefully assign mod/forum:allowforcesubscribe to frontpage role, as we missed that when
	// capability was introduced.
	
	
// 	if ($oldversion < 2012112901) {
// 		// If capability mod/forum:allowforcesubscribe is defined then set it for frontpage role.
// 		if (get_capability_info('mod/forum:allowforcesubscribe')) {
// 			assign_legacy_capabilities('mod/forum:allowforcesubscribe', array('frontpage' => CAP_ALLOW));
// 		}
// 		// Forum savepoint reached.
// 		upgrade_mod_savepoint(true, 2012112901, 'forum');
// 	}
	if ($oldversion < 2013081400) {
	
		// Define field to be added to assign.
		$table = new xmldb_table('edicion');		                         
		$field1 = new xmldb_field('numberc', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, null, '3', 'paid');
		$field2 = new xmldb_field('methodenrol', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, null, '1', 'numberc');
	
		// Conditionally launch add field1.
		if (!$dbman->field_exists($table, $field1)) {
			$dbman->add_field($table, $field1);
		}
		// Conditionally launch add field2.
		if (!$dbman->field_exists($table, $field2)) {
			$dbman->add_field($table, $field2);
		}
	
		// Assign savepoint reached.
		upgrade_mod_savepoint(true, 2013081400, 'assign');
	}
		
    return $result;
}
