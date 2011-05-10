<?php

class YogapagesController extends Controller {

    function index($queryArray) {
        $this->set('pageArray', $queryArray);
    }
}
