<?php
defined('MOODLE_INTERNAL') || die();



interface mod_vpl_subject
{
    public function unSubscribeObserver($observer);
    public function subscribeObserver($observer);
    public function setDesc($desc);
    public function notifyOpservers();
}





?>