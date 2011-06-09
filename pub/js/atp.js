// Global Libraries and functions
this.Ydom = this.Ydom || YAHOO.util.Dom;
this.Ycnxn = this.Ycnxn || YAHOO.util.Connect;
this.AjaxR = this.AjaxR || function (url, callback) {
	Ycnxn.asyncRequest(callback.method, url, callback, callback.data);
};
this.Yevent = this.Yevent || YAHOO.util.Event;
this.Listen = this.Listen || function (event, fn, elid) {
	Yevent.addListener(Ydom.get(elid), event, fn);
};

// Now define local website namespace
this.Atp = this.Atp || function() {
    var toggleTopNavClass = function () {
        if (document.URL.match(/.*athousandpetals.com\/?$/gi)) {
            Ydom.addClass('topnav', 'index');
            Ydom.addClass('midnav', 'index');
            Ydom.addClass('pagetitle', 'index');
            Ydom.addClass('pagesubtitle', 'index');
        } else {
            Ydom.removeClass('topnav', 'index');
            Ydom.removeClass('midnav', 'index');
            Ydom.removeClass('pagetitle', 'index');
            Ydom.removeClass('pagesubtitle', 'index');
        }
    };

    return {
        toggleTopNavClass: toggleTopNavClass
    };
}();
