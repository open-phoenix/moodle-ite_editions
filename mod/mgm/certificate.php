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
 * Certification módule
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once(dirname(__FILE__).'/certificate_form.php');

require_login();

$id = optional_param('id', 0, PARAM_INT);
$date = optional_param('date', 0, PARAM_INT);
$draft = optional_param('draft', 0, PARAM_INT);
$dodraft = optional_param('dodraft', 0, PARAM_INT);
$validate = optional_param('validate', 0, PARAM_INT);
$dovalidate = optional_param('dovalidate', 0, PARAM_INT);
$fechaemision = optional_param('fecha_emision', array(), PARAM_RAW);

if (!$id) {
    error('No id provided');
}

admin_externalpage_setup('edicionesmgmt', mgm_update_edition_button());
admin_externalpage_print_header();
print_heading(get_string('certdrafttitle', 'mgm'));

if (!$edition = get_record('edicion', 'id', $id)) {
    error('Edition not known!');
}

if ($draft) {
    $edition = get_record('edicion', 'id', $id);
    if ($edition->certified == MGM_CERTIFICATE_VALIDATED) {
        error('Edition already validated!');
    }
        
    notice_yesno(get_string('certvalidatesure', 'mgm'),
                 'certificate.php?id='.$id.'&amp;dovalidate=1',
                 'index.php');
    
    admin_externalpage_print_footer();
    die();
}

if ($dodraft && !$dovalidate) {
    if (mgm_set_edition_certification_on_draft($edition)) {
        update_record('edicion', $edition);
        notice(get_string('certondraft', 'mgm'), 'index.php');
        admin_externalpage_print_footer();
        die();
    } else {
        error('Edition can not be draft!');
    }
}

if ($dovalidate && !$dodraft) {
    if (mgm_set_edition_certification_on_validate($edition)) {
        update_record('edicion', $edition);
        mgm_certificate_edition($edition);
        notice(get_string('certonvalidate', 'mgm'), 'index.php');
        admin_externalpage_print_footer();
        die();
    } else {
        error('Edition can not be validated!');
    }
}

if ($date) {    
    if (!empty($fechaemision)) {
        $edition->fechaemision = $fechaemision;
        update_record('edicion', $edition);        
        
        notice_yesno(get_string('certdraftsure', 'mgm'),
                    'certificate.php?id='.$id.'&amp;dodraft=1',
                    'index.php');    
        admin_externalpage_print_footer();
        die();        
    } else {
        error(get_string('nofecha', 'mgm'));
    }
    
}

$edition = get_record('edicion', 'id', $id);
if ($edition->certified == MGM_CERTIFICATE_DRAFT) {
    error('Edition already draft!');
}    

$edition = get_record('edicion', 'id', $id);
$mform = new mod_mgm_certification_form('certificate.php?id='.$id.'&date=1', $edition);
$mform->set_data($edition);

$mform->display();

admin_externalpage_print_footer();
