this.Atp.Meditation = this.Atp.Meditation || function() {

    // Globals, bah!
    var root = "http://athousandpetals.com", ds = "/";

    // Elements
    var contentWPElem = function() {return Ydom.get('contentWP');};

    // Success and failure functions for different requests
    var handleFailure = function(o){
        if(o.responseText !== undefined){
            blogWPElem().innerHTML = "request failure: " + o.responseText + blogWPElem().innerHTML;
        }
    };

    var handleSuccess = function(o) {
        if(o.responseText !== undefined){
            blogWPElem().innerHTML = o.responseText;
        }
    };

    var callback ={
        method:"GET",
        success: handleSuccess,
        failure: handleFailure
    };

    var catRequest = function(pageStr){
        cat = cat.replace(/[_]/gi, " ");
        var requestStr = root+ds+'categories/index/Meditation_Classes'+pageStr;
        var request = AjaxR(requestStr, callback);
    };

    return {

        Load: function(pageArray){
            // initial load
            if (pageArray) {
                var pageStr='', i;
                for(i=pageArray.length;i;i--){
                    pageStr+='/'+pageArray[i];
                }
            } else {
                pageStr = 'index';
            }
            catRequest(pageStr);
        }
    };

}();