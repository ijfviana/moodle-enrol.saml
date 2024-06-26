<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/weblib.php');

/**
 * Class output tracker.
 *
 * @package    tool_uploadcourse
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class uploadmapping_tracker {

    /**
     * Constant to output HTML.
     */
    const OUTPUT_HTML = 1;

    /**
     * @var array columns to display.
     */
    protected $columns = array('line', 'result', 'lms_course_id', 'saml_course_id');

    /**
     * @var int row number.
     */
    protected $rownb = 0;

    /**
     * @var int chosen output mode.
     */
    protected $outputmode;

    /**
     * @var object output buffer.
     */
    protected $buffer;

    /**
     * Constructor.
     *
     * @param int $outputmode desired output mode.
     */
    public function __construct() {
        $this->outputmode = self::OUTPUT_HTML;
    }

    /**
     * Finish the output.
     *
     * @return void
     */
    public function finish() {

        echo html_writer::end_tag('table');
    }

    /**
     * Output the results.
     *
     * @param int $total total courses.
     * @param int $created count of courses created.
     * @param int $updated count of courses updated.
     * @param int $errors count of errors.
     * @return void
     */
    public function results($total, $created, $updated, $ignored, $errors) {

        $message = array(
            get_string('coursestotal', 'enrol_saml', $total),
            get_string('coursescreated', 'enrol_saml', $created),
            get_string('coursesupdated', 'enrol_saml', $updated),
            get_string('coursesignored', 'enrol_saml', $ignored),
            get_string('courseserrors', 'enrol_saml', $errors)
        );


        $buffer = new progress_trace_buffer(new html_list_progress_trace());
        foreach ($message as $msg) {
            $buffer->output($msg);
        }
        $buffer->finished();
    }

    /**
     * Output one more line.
     *
     * @param int $line line number.
     * @param bool $outcome success or not?
     * @param array $data extra data to display.
     * @global moodle_database $DB
     * @global moodle_page $PAGE
     * @global core_renderer $OUTPUT
     * @return void
     */
    public function output($line, $outcome, $data) {
        global $OUTPUT;


        $ci = 0;
        $this->rownb++;
        if ($outcome) {
            $outcome = $OUTPUT->pix_icon('i/valid', '');
        } else {
            $outcome = $OUTPUT->pix_icon('i/invalid', '');
        }
        echo html_writer::start_tag('tr', array('class' => 'r' . $this->rownb % 2));
        echo html_writer::tag('td', $line, array('class' => 'c' . $ci++));
        echo html_writer::tag('td', $outcome, array('class' => 'c' . $ci++));
        echo html_writer::tag('td', isset($data['lms_course_id']) ? $data['saml_course_id'] . ' ' : '', array('class' => 'c' . $ci++));
        echo html_writer::tag('td', isset($data['saml_course_id']) ? $data['saml_course_id'] . ' ' : '', array('class' => 'c' . $ci++));
        echo html_writer::end_tag('tr');
        
    }

    /**
     * Start the output.
     *
     * @return void
     */
    public function start() {

        $ci = 0;
        echo html_writer::start_tag('table', array('class' => 'generaltable boxaligncenter flexible-wrap',
            'summary' => get_string('importmappingreview', 'enrol_saml')));
        echo html_writer::start_tag('tr', array('class' => 'heading r' . $this->rownb));
        echo html_writer::tag('th', get_string('csvline', 'tool_uploadcourse'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::tag('th', get_string('result', 'tool_uploadcourse'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::tag('th', get_string('lms_course_id', 'enrol_saml'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::tag('th', get_string('saml_course_id', 'enrol_saml'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::end_tag('tr');
    }

}

class old_mapping_tracker {

    /**
     * Constant to output HTML.
     */
    const OUTPUT_HTML = 1;

    /**
     * @var array columns to display.
     */
    protected $columns = array('line', 'result', 'lms_course_id', 'saml_course_id');

    /**
     * @var int row number.
     */
    protected $rownb = 0;

    /**
     * @var int chosen output mode.
     */
    protected $outputmode;

    /**
     * @var object output buffer.
     */
    protected $buffer;

    /**
     * Constructor.
     *
     * @param int $outputmode desired output mode.
     */
    public function __construct() {
        $this->outputmode = self::OUTPUT_HTML;
    }

    /**
     * Finish the output.
     *
     * @return void
     */
    public function finish() {

        echo html_writer::end_tag('table');
    }

    /**
     * Output the results.
     *
     * @param int $total total courses.
     * @param int $created count of courses created.
     * @param int $updated count of courses updated.
     * @param int $errors count of errors.
     * @return void
     */
    public function results($total, $created, $ignored, $errors) {

        $message = array(
            get_string('coursestotal', 'enrol_saml', $total),
            get_string('coursescreated', 'enrol_saml', $created),
            get_string('coursesignored', 'enrol_saml', $ignored),
            get_string('courseserrors', 'enrol_saml', $errors)
        );


        $buffer = new progress_trace_buffer(new html_list_progress_trace());
        foreach ($message as $msg) {
            $buffer->output($msg);
        }
        $buffer->finished();
    }

    /**
     * Output one more line.
     *
     * @param int $line line number.
     * @param bool $outcome success or not?
     * @param array $data extra data to display.
     * @global moodle_database $DB
     * @global moodle_page $PAGE
     * @global core_renderer $OUTPUT
     * @return void
     */
    public function output($line, $outcome, $data) {
        global $OUTPUT;


        $ci = 0;
        $this->rownb++;
        if ($outcome) {
            $outcome = $OUTPUT->pix_icon('i/valid', '');
        } else {
            $outcome = $OUTPUT->pix_icon('i/invalid', '');
        }
        echo html_writer::start_tag('tr', array('class' => 'r' . $this->rownb % 2));
        echo html_writer::tag('td', $line, array('class' => 'c' . $ci++));
        echo html_writer::tag('td', $outcome, array('class' => 'c' . $ci++));
        echo html_writer::tag('td', isset($data->lms_course_id) ? $data->lms_course_id . ' ' : '', array('class' => 'c' . $ci++));
        echo html_writer::tag('td', isset($data->saml_course_id) ? $data->saml_course_id . ' ' : '', array('class' => 'c' . $ci++));
        echo html_writer::end_tag('tr');
        
    }

    /**
     * Start the output.
     *
     * @return void
     */
    public function start() {

        $ci = 0;
        echo html_writer::start_tag('table', array('class' => 'generaltable boxaligncenter flexible-wrap',
            'summary' => get_string('importmappingreview', 'enrol_saml')));
        echo html_writer::start_tag('tr', array('class' => 'heading r' . $this->rownb));
        echo html_writer::tag('th', get_string('csvline', 'tool_uploadcourse'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::tag('th', get_string('result', 'tool_uploadcourse'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::tag('th', get_string('lms_course_id', 'enrol_saml'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::tag('th', get_string('saml_course_id', 'enrol_saml'), array('class' => 'c' . $ci++, 'scope' => 'col'));
        echo html_writer::end_tag('tr');
    }

}
