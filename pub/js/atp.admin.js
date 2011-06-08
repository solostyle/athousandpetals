this.Atp.Admin = this.Atp.Admin || function() {

    // Globals, bah!
    var root = "http://athousandpetals.com", ds = "/";
    
    // Elements
    var midnavElem = function() {return Ydom.get('midnav');};    
    
    // Success and failure functions for different requests
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
    
    var midnavCallback ={
        method:"GET",
        success: handleMidnavSuccess,
        failure: handleMidnavFailure
    };
    
    var midnavRequest = function(currentPage){
        var requestStr = root+ds+'Massage_Therapy'+ds+'midnav'+ds+currentPage;
        var request = AjaxR(requestStr, midnavCallback);
    };  
    
    return {

        LoadMidNav: function(currentPage){
            midnavRequest(currentPage);
        }
    };

}();