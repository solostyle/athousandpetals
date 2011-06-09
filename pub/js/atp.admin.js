this.Atp.Admin = this.Atp.Admin || function() {

    // Globals, bah!
    var root = "http://athousandpetals.com", ds = "/";
    
    // Elements
    var divElem = function() {return Ydom.get('midnav');};    
    
    // Success and failure functions for different requests
    var handleFailure = function(o){
        if(o.responseText !== undefined){
            divElem().innerHTML = "request failure: " + o.responseText + divElem().innerHTML;
        }
    };

    var handleSuccess = function(o) {
        if(o.responseText !== undefined){
            divElem().innerHTML = o.responseText;
        }
    };
    
    var callback ={
        method:"GET",
        success: handleSuccess,
        failure: handleFailure
    };
    
    var request = function(currentPage){
        var requestStr = root+ds+currentPage;
        var request = AjaxR(requestStr, vallback);
    };  
    
    return {

        Load: function(currentPage){
            request(currentPage);
        }
    };

}();