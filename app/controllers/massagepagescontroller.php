<?php

class MassagepagesController extends Controller {

    function about() {
    }
    function midnav($queryString) {
        $this->doNotRenderHeader = true;
        $this->set('currentPage', $queryString);
    }
}
