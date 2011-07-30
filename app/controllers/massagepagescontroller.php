<?php

class MassagepagesController extends Controller {

    function about() {
    }
    function benefits() {
    }
    function therapies($queryArray) {
		$this->set('leftList',array('Swedish Relaxation and Circulatory', 'Clinical Rehabilitative', 'Sports and Event', 'Myofascial Structural', 'Deep Tissue'));
		$this->set('cat', 'Massage_Therapy');
		$this->set('subCat', 'therapies');
    }

    function blog() {
    }
    function contact() {
    }
    function forms() {
    }
    function policies() {
    }
    function reviews() {
    }	
}
