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
            <p class="lead">This page help you to understand and use this plugin well.</p>
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
                                #01 How to submit a file?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-one" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">First you can click on Submission tab</li>
                                <li class="list-group-item">Then you can enter title, description and choose a status (public or private)</li>
                                <li class="list-group-item">Note: you can choose public if you want your code to appear to other student else choose private</li>
                                <li class="list-group-item">Then you can choose a file to upload with maximum size allowed written next to button</li>
                                <li class="list-group-item">Finally you can click submit to save or you can cancel the submission</li>
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
                                #02 How to edit your submission?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-two" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">First you can click on Edit tab</li>
                                <li class="list-group-item">You will see your last submission file if not it will ask you to create a new file so you can write and edit it</li>
                                <li class="list-group-item">In the Edit tab you can see an editor to edit your code</li>
                                <li class="list-group-item">You can also see your code description at the right side</li>
                                <li class="list-group-item">You can edit the code title, description and change code status</li>
                                <li class="list-group-item">You can edit the code and save the changes by clicking Ctrl + s shortcut or click the save icon at the top of the editor</li>
                                <li class="list-group-item">You can also view submission comment by clicking on the little comment icon next to the save icon at the top of the editor</li>
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
                                #03 How to view your grades?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-three" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">Click on <b>Submission view tab</b></li>
                                <li class="list-group-item">Yo can see your grade, when it was reviewed and by whom if it is already graded.</li>
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
                                #04 How to download your submitted code?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-four" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">Click on <b>Submission view tab</b></li>
                                <li class="list-group-item">You can click on Download hyperlink to download</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 4 -->
                
                <!-- start question 5 -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-one">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-five" aria-expanded="true" aria-controls="collapse-one">
                                #05 How to view your title, description, status, and code for your last submitted code?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-five" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">Click on <b>Submission view tab</b></li>
                                <li class="list-group-item">Now you can see your last submission title, description, status, and code</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 5 -->
                
                <!-- start question 6 -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-one">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-six" aria-expanded="true" aria-controls="collapse-one">
                                #06 How to view Other Students public code?
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-six" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-one">
                        <div class="panel-body">
                            <ul style="margin-left: 50px" class="list-group">
                                <li class="list-group-item">First click on <b>Other Students Public Code tab</b></li>
                                <li class="list-group-item">You can now see other students public code with the title and time submitted</li>
                                <li class="list-group-item">If you need to search for specific code you can use the search field</li>
                                <li class="list-group-item">Click on view icon in the action column to view your code and its description</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End question 6 -->
        </div>
    </section>
</div>
';

$vpl->print_footer();
