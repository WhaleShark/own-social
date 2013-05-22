(function($){

var Sociable = {
    
    defaults: {
    },

    init: function(elem, options) {

        //Mix in the passed in options with the default options
        this.options = $.extend({}, this.defaults, options);

        //Save the element reference, both as a jQuery reference and a normal reference
        this.elem  = elem;
        this.$elem = $(elem);
        this.parent = this.$elem.parent();

        //Setup reference to buttons
        this.facebookLikeBtn = this.$elem.find('.btn-fb');
        this.facebookLikeIFrame = this.$elem.find('.iframe-fb');
        this.facebookLikeUrl = this.facebookLikeIFrame.data('path');

        this.twitterTweetBtn = this.$elem.find('.btn-tw');
        this.twitterTweetUrl = this.twitterTweetBtn.attr('href');
        
        this.googlePlusBtn = this.$elem.find('.btn-gp');
        this.googlePlusUrl = this.googlePlusBtn.attr('href');
        
        //Setup events
        this.events();

        //Return object for JQuery chaining
        return this;
    },
    
    events: function() {
	    
	    //Get reference to mainObj
	    var mainObj = this;
	    
	    //When someone rolls over the sociable block
	    this.parent.on("mouseover.sociable", function(e) { 
		    
		    //Load the iFrame
		    mainObj.loadFBIframe();

		    //Turn off the rollover event
		    mainObj.parent.off("mouseover.sociable");

	    });
	    
	    //When the iFrame loads, hide the image
	    this.facebookLikeIFrame.on("load",function() {
	    
	    	mainObj.facebookLikeBtn.remove();	
	    
	    });
	    
	    //Setup click handlers for buttons
		this.twitterTweetBtn.on("click.sociable", function(e) { mainObj.action(e, mainObj.actionTwitterTweet, mainObj.twitterTweetUrl) });
	    this.googlePlusBtn.on("click.sociable", function(e) { mainObj.action(e, mainObj.actionGooglePlusOne, mainObj.googlePlusUrl) });
	    
    },
    
    loadFBIframe: function() {
	   
	   	//The main object
	   	var mainObj = this;
	   
	   	//Load the content from FB
		this.facebookLikeIFrame.attr('src', this.facebookLikeUrl)
		this.facebookLikeIFrame.show();

    },
    
    action: function(e, actionMethod, link) {
	   
	   //Prevent default
	   e.preventDefault();
	  
	   //Trigger the appropriate action method
	   actionMethod(this, link); 
	   
    },
    
    actionTwitterTweet: function(mainObj, link) {
	    
	    //Broadcast event
	    $(document).trigger('twitter-tweet.sociable');	    
	    
		//Generate popup
	    mainObj.generatePopup('twitter', link, 450, 470);
    
	},
    
    actionGooglePlusOne: function(mainObj, link) {
	    
	    //Broadcast event
	    $(document).trigger('google-plusone.sociable');	    
	    
	    //Generate popup
	    mainObj.generatePopup('google', link, 490, 470);
	    
    },
    
    generatePopup: function(type, link, w, h) {
	    
		wLeft = window.screenLeft ? window.screenLeft : window.screenX;
	    wTop = window.screenTop ? window.screenTop : window.screenY;
	
	    var left = wLeft + (window.innerWidth / 2) - (w / 2);
	    var top = wTop + (window.innerHeight / 2) - (h / 2);
	    return window.open(link, type, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    }
    
};

//Register this plugin as a jquery plugin
$.plugin('sociable', Sociable);

//Use of plugin
$('.sociable').sociable();

//Return the Constructor for the love
return Sociable;

})(jQuery);