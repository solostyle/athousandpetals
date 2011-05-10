<?php

class MassagepagesController extends Controller {

    function index($queryArray) {
        $this->set('pageArray', $queryArray);
    }
}
