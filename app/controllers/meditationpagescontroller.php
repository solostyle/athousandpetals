<?php

class MeditationpagesController extends Controller {

    function index($queryArray) {
        $this->set('pageArray', $queryArray);
    }
}
