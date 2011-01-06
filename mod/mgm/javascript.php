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
 * This is a one-line short description of the file
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
?>

function mgm_opciones(primaria) {
    opt1 = document.getElementById('id_opcion1');
    opt2 = document.getElementById('id_opcion2');

    if (opt1.value == opt2.value) {
        if (opt1.value == 'ninguna' || opt2.value == 'ninguna') {
            return;
        }
        if (opt2.value == 'centros') {
            if (primaria) {
                opt2.value = 'especialidades';
            } else {
                opt1.value = 'especialidades';
            }
        } else {
            if (primaria) {
                opt2.value = 'centros';
            } else {
                opt1.value = 'centros';
            }
        }
    }
}