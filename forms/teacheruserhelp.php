<?php
// This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
//
// VPL for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// VPL for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with VPL for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * View a submission
 *
 * @package mod_vpl
 * @copyright 2012 Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>
 */

require_once(__DIR__ . '/../../../config.php');
require_once(dirname(__FILE__).'/../vpl.class.php');
global $CFG, $USER;

require_login();
$id = required_param( 'id', PARAM_INT );
$userid = optional_param( 'userid', false, PARAM_INT );
$vpl = new mod_vpl( $id );

$PAGE->requires->js('/mod/vpl/jscript/jquery-3.3.1.min.js',true);

// Print header.
$vpl->print_header( get_string( 'submissionview', VPL ) );
$vpl->print_view_tabs( basename( __FILE__ ) );

echo ' 
<!-- start FAQ intro -->
<div>
    <section class="faq text-center">
        <div class="container">
            <h1>User Help</h1>
            <hr>
            <p class="lead">This page to help you so you can understand and use this plugin well.</p>
        </div>
    </section>
<!-- end FAQ intro -->
        
    <!-- strart faq-accordion -->
    <section class="faq-questions">
        <div class="container">
            <div class="panel-group" id="accordion" role="tablist">

                <!-- start question 1 -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-one">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-one" aria-expanded="true" aria-controls="collapse-one">
                                #01 How to see all submissions?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-one" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">You can click on <b>Submission List tab</b></li>
                                <li class="list-group-item">You can filter submissions to show all of them, graded or non-graded submissions</li>
                                <li class="list-group-item">You can choose to evaluate non-executed or non-graded files</li>
                                <li class="list-group-item">You can see all of the submissions in this page</li>
                                <li class="list-group-item">You can view assessments by clicking on assessment report button at the bottom of the page</li>
                                <li class="list-group-item">You can also download current submission by clicking on Download Submissions or download all submissions by clicking on Download All Submissions.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 1 -->
                
                <!-- start question 2 -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-one">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-two" aria-expanded="true" aria-controls="collapse-one">
                                #02 How to edit a submission?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-two" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">First you can click on <b>Edit tab</b></li>
                                <li class="list-group-item">You will see your last submission file if not it will ask you to create a new file so you can write and edit it</li>
                                <li class="list-group-item">In the Edit tab you can see an editor to edit your code</li>
                                <li class="list-group-item">You can also see your code description at the right side</li>
                                <li class="list-group-item">You can edit the code title, description and change code status</li>
                                <li class="list-group-item">You can edit the code and save the changes by clicking Ctrl + s shortcut or click the save icon at the top of the editor</li>
                                <li class="list-group-item">You can also view submission comment by clicking on the little comment icon next to the save icon at the top of the editor</li>
                                <li class="list-group-item">You can also run the code by clicking on the run code icon at the top of the editor</li>
                                <li class="list-group-item">You can also debug, evaluate or open the console from their icons at the top of the editor</li>
                                <li class="list-group-item">You can see how much time left to submit your final code when you press on time left icon at the right corner of the editor</li>
                                <li class="list-group-item">There\'re more than this, you can expand the control icons at the top of the editor to see more icons with more functionalities and to know what they can do you can click on the about icon at the top of the editor it will show you a description for the editor and Keyboard shortcuts</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 2 -->
                
                <!-- start question 3 -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-one">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-three" aria-expanded="true" aria-controls="collapse-one">
                                #03 How to scan for similarity?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-three" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">First you can click on <b>Similarity tab</b></li>
                                <li class="list-group-item">You can set the maximum output files by similarity and you can also set other sources</li>
                                <li class="list-group-item">Click on search button to search for similarities between submissions</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 3 -->
                
                <!-- start question 4 -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-one">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-four" aria-expanded="true" aria-controls="collapse-one">
                                #04 How to set the grade for the assignment?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-four" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">First click on <b>Test activity tab</b></li>
                                <li class="list-group-item">Then click on <b>Grade tab</b></li>
                                <li class="list-group-item">Now you can set the grade in the grade field</li>
                                <li class="list-group-item">If you need to remove the grade click on the <b>Remove grade</b> button</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 4  -->
                
                <!-- start question 5 -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-one">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-five" aria-expanded="true" aria-controls="collapse-one">
                                #05 How to show the previous submissions list?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-five" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">First click on <b>Test activity tab</b></li>
                                <li class="list-group-item">Then click on <b>Previous submissions list tab</b></li>
                                <li class="list-group-item">You will see two graphs:</li>
                                <li class="list-group-item">One graph shows submissions as x-axis versus bytes (size of the file) as y-axis</li>
                                <li class="list-group-item">The other one shows the working periods as x-axis versus hours as y- axisn</li>
                                <li class="list-group-item">And also you can see the list of submitted codes as a table at the bottom of the page</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 5  -->
        </div>
    </section>
</div>
';

$vpl->print_footer();
