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
 * Exports to a csv file all course mappings
 *
 * @package    enrol
 * @subpackage saml
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We load all moodle config and libs.
require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once($CFG->dirroot . '/enrol/saml/locallib.php');

// Validate that the user has admin rights.
if (!is_siteadmin()) {
    die('Only admins can execute this action.');
}


$coursemapping = get_all_course_mapping_custom();

$filename = "course_saml_mappings_" . date('Y-m-d') . ".csv";
// Create a file pointer.
$f = fopen('php://memory', 'w');
$delimiter = ";";

// Set column headers.
$coursefields = ['id', 'lms_course_id', 'saml_course_id', 'source', 'creation', 'modified'];

fputcsv($f, $coursefields, $delimiter);


$row = [];
foreach ($coursemapping as $key => $values) {
    foreach ($values as $value) {

        
        $row[] = trim($value);
    }
    
    fputcsv($f, $row, $delimiter);
    $row = [];
}

// Move back to beginning of file.
fseek($f, 0);

// Set headers to download file rather than displayed.
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

// Output all remaining data on a file pointer.
fpassthru($f);
exit;
