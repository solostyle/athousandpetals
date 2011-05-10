this.Atp.Shell = this.Atp.Shell || function() {

    var handleDomReady = function(obj) {
        //onDOMReady uses the Custom Event signature, with the object
        //passed in as the third argument:
        //type <string>, args <array>, customobject <object>
        //"DOMReady", [], obj

        // load blog entries web part
        Atp.Blog.Load(); // actually loads blog entries and then may listen for clicks

        // load archive navigation web part
        //Atp.Archmenu.Load();
        
        // load admin web part
        Atp.Admin.Load(); // doesn't load anything (header.php does it); just listens for clicks
    };

    return {
        LoadWebParts: function() {
            Yevent.onDOMReady(handleDomReady);
        }
    };

}();