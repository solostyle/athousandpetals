this.Atp.Massage = this.Atp.Massage || function() {

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
        var requestStr = root+ds+'Massage_Therapy'+pageStr;
        var request = AjaxR(requestStr, callback);
    };

    
    return {

        Load: function(){
		
			// set event handler for clicks in the web part
			Listen("click", handleClick, 'left');
        }
    };

}();