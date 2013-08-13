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
 * Adds new instance of enrol_paypal to specified course
 * or edits current instance.
 *
 * @package    enrol
 * @subpackage paypal
 * @copyright  2013 Jesus Jaen  {jesus.jaen@open-phoenix.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once('edit_form.php');

$courseid   = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT); // instanceid

$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/mgm:config', $context);

$PAGE->set_url('/enrol/mgm/edit.php', array('courseid'=>$course->id, 'id'=>$instanceid));
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/enrol/instances.php', array('id'=>$course->id));
if (!enrol_is_enabled('mgm')) {
    redirect($return);
}

$plugin = enrol_get_plugin('mgm');

if ($instanceid) {
    $instance = $DB->get_record('enrol', array('courseid'=>$course->id, 'enrol'=>'mgm', 'id'=>$instanceid), '*', MUST_EXIST);
    
} else {
	require_capability('moodle/course:enrolconfig', $context);
	// No instance yet, we have to add new instance.
	navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));
	$instance = new stdClass();
	$instance->id              = null;
	$instance->courseid        = $course->id;
	$instance->expirynotify    = $plugin->get_config('expirynotify');
	$instance->expirythreshold = $plugin->get_config('expirythreshold');
}

$mform = new enrol_mgm_edit_form(null, array($instance, $plugin, $context));

if ($mform->is_cancelled()) {
	redirect($return);

} else if ($data = $mform->get_data()) {
	if ($data->expirynotify == 2) {
		$data->expirynotify = 1;
		$data->notifyall = 1;
	} else {
		$data->notifyall = 0;
	}
	if (!$data->expirynotify) {
		// Keep previous/default value of disabled expirythreshold option.
		$data->expirythreshold = $instance->expirythreshold;
	}
	if ($instance->id) {
		$instance->roleid          = $data->roleid;
		$instance->enrolperiod     = $data->enrolperiod;
		$instance->expirynotify    = $data->expirynotify;
		$instance->notifyall       = $data->notifyall;
		$instance->expirythreshold = $data->expirythreshold;
		$instance->timemodified    = time();

		$DB->update_record('enrol', $instance);

		// Use standard API to update instance status.
		if ($instance->status != $data->status) {
			$instance = $DB->get_record('enrol', array('id'=>$instance->id));
			$plugin->update_status($instance, $data->status);
			$context->mark_dirty();
		}

	} else {
		$fields = array(
				'status'          => $data->status,
				'roleid'          => $data->roleid,
				'enrolperiod'     => $data->enrolperiod,
				'expirynotify'    => $data->expirynotify,
				'notifyall'       => $data->notifyall,
				'expirythreshold' => $data->expirythreshold);
		$plugin->add_instance($course, $fields);
	}

	redirect($return);
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_mgm'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_mgm'));
$mform->display();
echo $OUTPUT->footer();
