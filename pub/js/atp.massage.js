this.Atp.Massage = this.Atp.Massage || function() {

    // Globals, bah!
    var root = "http://athousandpetals.com", ds = "/";

    // Elements
    var contentWPElem = function() {return Ydom.get('contentWP');},
    midnavElem = function() {return Ydom.get('midnav');};

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

    var handleMidnavFailure = function(o){
        if(o.responseText !== undefined){
            midnavElem().innerHTML = "request failure: " + o.responseText + midnavElem().innerHTML;
        }
    };

    var handleMidnavSuccess = function(o) {
        if(o.responseText !== undefined){
            midnavElem().innerHTML = o.responseText;
        }
    };
    
    var callback ={
        method:"GET",
        success: handleSuccess,
        failure: handleFailure
    };

    var catRequest = function(pageStr){
        var requestStr = root+ds+'Massage_Therapy'+pageStr;
        var request = AjaxR(requestStr, callback);
    };

    var midnavCallback ={
        method:"GET",
        success: handleMidnavSuccess,
        failure: handleMidnavFailure
    };
    
    var midnavRequest = function(currentPage){
        var requestStr = root+ds+'Massage_Therapy/midnav';
        var request = AjaxR(requestStr, midnavCallback);
    };    
    
    
    
    return {

        Load: function(pageArray){
            // initial load
            if (pageArray) {
                var pageStr='', i;
                for(i=pageArray.length;i;i--){
                    pageStr+=ds+pageArray[i];
                }
            } else {
                pageStr = ds+'index';
            }
            catRequest(pageStr);
        },
        LoadMidNav: function(currentPage){
            midnavRequest(currentPage);
        }
    };

}();