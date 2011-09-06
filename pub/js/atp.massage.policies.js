this.Atp.Massage.Policies = this.Atp.Massage.Policies || function() {

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

	var handleClick = function(e) {
		var targetId = e.target.getAttribute('href'),
		// separate the href, before and after the hash
		location = (targetId)?targetId.split('#', 2)[0]:null;
		hash = (targetId)?targetId.split('#', 2)[1]:null;
		switch (hash) {
			case "Cancellation_and_Missed_Appointments":
				$(window).scrollTo(Ydom.get('cancellation'), {duration:500,queue:false});
				break;
			case "Tardiness":
				$(window).scrollTo(Ydom.get('tardiness'), {duration:500,queue:false});
				break;
			case "Conduct_and_Behavior":
				$(window).scrollTo(Ydom.get('conduct'), {duration:500,queue:false});
				break;
			case "Referrals":
				$(window).scrollTo(Ydom.get('referrals'), {duration:500,queue:false});
				break;
			case "Background_Music_and_Environment":
				$(window).scrollTo(Ydom.get('background'), {duration:500,queue:false});
				break;
			case "Payment":
				$(window).scrollTo(Ydom.get('payment'), {duration:500,queue:false});
				break;
			case "Health_History_and_Informed_Consent":
				$(window).scrollTo(Ydom.get('health'), {duration:500,queue:false});
				break;
			case "Privacy":
				$(window).scrollTo(Ydom.get('privacy'), {duration:500,queue:false});
				break;
			case "Liability_and_Disclaimer":
				$(window).scrollTo(Ydom.get('liability'), {duration:500,queue:false});
				break;
			default:
				break;
		};
	};
    
    return {

        Load: function(){
		
			// set event handler for clicks in the web part
			Listen("click", handleClick, 'left');
        }
    };

}();