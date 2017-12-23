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
 * Implementaton of the quizaccess_changebehaviour plugin.
 *
 * @package    quizaccess
 * @subpackage changebehaviour
 * @copyright  2016 Daniel Thies <dthies@ccal.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');


/**
 * A rule representing to change the question behaviour setting after a time.
 *
 * @copyright  2016 Daniel Thies <dthies@ccal.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_changebehaviour extends quiz_access_rule_base {
    protected $timeremaining;

    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {

        $newclose = $quizobj->get_quiz()->timeclose;

        if (!empty($quizobj->get_quiz()->behaviourtime)) {
            $newclose = $quizobj->get_quiz()->behaviourtime;
        }

        if (!empty($quizobj->get_quiz()->behaviourduration)) {
            $newclose = max($newclose, $quizobj->get_quiz()->timeclose + $quizobj->get_quiz()->behaviourduration);
        }

        if ($newclose <= $quizobj->get_quiz()->timeclose ||
                $newclose < $timenow ||
                $quizobj->get_quiz()->timeclose > $timenow) {
            return null;
        }

        $quizobj->get_quiz()->originalclose = $quizobj->get_quiz()->timeclose;
        $quizobj->get_quiz()->timeclose = $newclose;
        $quizobj->get_quiz()->preferredbehaviour = $quizobj->get_quiz()->newbehaviour;

        return new self($quizobj, $timenow);
    }

    public function description() {
        if (!empty($this->quiz->originalclose)) {
            return get_string('changebehaviournotice', 'quizaccess_changebehaviour', array('time' => userdate($this->quiz->originalclose)));
        }
        return '';
    }

    public static function add_settings_form_fields(
            mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {
        $mform->addElement('date_time_selector', 'behaviourtime', get_string('behaviourtime', 'quizaccess_changebehaviour'), array('optional' => true, 'step' => 1));
        $mform->disabledIf('behaviourtime', 'timeclose[enabled]');
        $mform->addHelpButton('behaviourtime', 'behaviourtime', 'quizaccess_changebehaviour');
        $mform->addElement('duration', 'behaviourduration', '',
                array('optional' => true));
        $mform->disabledIf('behaviourduration', 'timeclose[enabled]');

        $behaviours = question_engine::get_behaviour_options(null);
        $mform->addElement('select', 'newbehaviour',
                get_string('newbehaviour', 'quizaccess_changebehaviour'), $behaviours);
        $mform->disabledIf('newbehaviour', 'timeclose[enabled]');
    }

    public static function save_settings($quiz) {
        global $DB;

        $DB->delete_records('quizaccess_changebehaviour', array('quizid' => $quiz->id));
        if (!empty($quiz->behaviourtime) || !empty($quiz->behaviourduration)) {
            $record = new stdClass();
            $record->quizid = $quiz->id;
            $record->behaviourtime = $quiz->behaviourtime;
            $record->behaviourduration = $quiz->behaviourduration;
            $record->newbehaviour = $quiz->newbehaviour;
            $DB->insert_record('quizaccess_changebehaviour', $record);
        }
    }

    public static function delete_settings($quiz) {
        global $DB;
        $DB->delete_records('quizaccess_changebehaviour', array('quizid' => $quiz->id));
    }

    public static function get_settings_sql($quizid) {
        return array(
            'behaviourduration, behaviourtime, newbehaviour',
            'LEFT JOIN {quizaccess_changebehaviour} changebehaviour ON changebehaviour.quizid = quiz.id',
            array());
    }
}
