<?php
defined('MOODLE_INTERNAL') || die();

interface mod_vpl_observer
{
    public function subscribe();
    public function unsubscribe();
    public function update($desc);
}


?>