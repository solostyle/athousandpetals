<?php

class MassagepagesController extends Controller {

    function about() {
    }
    function benefits() {
    }
    function therapies() {
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
    function midnav($queryArray) {
        $this->doNotRenderHeader = true;
        $this->set('currentPage', $queryArray[0]);
    }
}
