<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/vpl_observer.interface.php');

class mod_vpl_subscribee_code implements mod_vpl_observer
{
    public $id;
    public $observer;
    public $desc;
    public function __construct($Observer,$id)
    {
        $this->observer=$Observer;
        $this->id=$id;
    }

    public function subscribe()
    {
        $this->observer->subscribeObserver($this);
    }
    public function unsubscribe()
    {
        $this->observer->unSubscribeObserver($this);
    }
    public function update($desc)
    {
        $this->desc=$desc;
        $this->display();
    }
    private function display()
    {
        echo 'new massage from moodle with description '.$this->desc;
    }
}


?>