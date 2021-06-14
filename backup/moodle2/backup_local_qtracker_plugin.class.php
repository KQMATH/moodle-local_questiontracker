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
 * Defines backup_local_qtracker_plugin class
 *
 * @package     local_qtracker
 * @author      David Rise Knotten <david_knotten@hotmail.no>
 * @copyright   2021 Norwegian University of Science and Technology (NTNU)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Local qtracker backup
 *
 * @package     local_qtracker
 * @author      David Rise Knotten <david_knotten@hotmail.no>
 * @copyright   2021 Norwegian University of Science and Technology (NTNU)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_local_qtracker_plugin extends backup_local_plugin {

    /**
     * Not used
     */
    protected function define_my_settings() {
    }

    /**
     * Define qtracker structure from question entrypoint
     *
     * @return backup_plugin_element
     * @throws base_element_struct_exception
     */
    protected function define_question_plugin_structure() {
        // Define backup elements
        $plugin = $this->get_plugin_element();
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());
        $qtrackerissue = new backup_nested_element('issue', ['id'], [
            'title', 'description', 'questionusageid', 'slot',
            'state', 'userid', 'contextid', 'timecreated'
        ]);
        $qtrackercomment = new backup_nested_element('comment', ['id'], [
            'description', 'userid', 'timecreated'
        ]);

        // Build the backup tree
        $qtrackerissue->add_child($qtrackercomment);
        $pluginwrapper->add_child($qtrackerissue);
        $plugin->add_child($pluginwrapper);

        // Define sources
        $qtrackerissue->set_source_table('qtracker_issue', ['questionid' => backup::VAR_PARENTID]);
        $qtrackercomment->set_source_table('qtracker_comment', ['issueid' => backup::VAR_PARENTID]);

        // Define annotations
        // TODO: Make these work
        $qtrackerissue->annotate_ids('user','userid');
        $qtrackerissue->annotate_ids('questionusage','questionusageid');
        $qtrackerissue->annotate_ids('context','contextid');

        return $plugin;
    }
}