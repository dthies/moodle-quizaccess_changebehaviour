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
 * Strings for the quizaccess_changebehaviour plugin.
 *
 * @package   quizaccess_changebehaviour
 * @copyright 2016 Daniel Thies <dthies@ccal.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Change question behaviour';
$string['behaviourtime'] = 'Extend the close time';
$string['behaviourtime_help'] = 'Set a new close time or an amount of time to extend the quiz and a new behaviour to apply to attempts started after the original close. This is an opportunity to change the conditions for students who wait too late to attempt the quiz.';
$string['changebehaviournotice'] = 'The original due date for this quiz was {$a->time}. It has been extended, but the conditions may have changed.';
$string['newbehaviour'] = 'Behaviour during extension';
$string['privacy:metadata'] = 'The Change question behaviour access rule plugin does not store any personal data.';
