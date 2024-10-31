//setup buttons
!function(d,s,id){
    var js,fjs=d.getElementsByTagName(s)[0];
    if(!d.getElementById(id)){
        js=d.createElement(s);
        js.id=id;
        js.src="//platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js,fjs);
    }
}(document,"script","twitter-wjs");

(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    js.async = true;
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

(function() {
    var po = document.createElement('script');
    po.type = 'text/javascript';
    po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
})();

(function() {
    var li = document.createElement('script');
    li.type = 'text/javascript';
    li.async = true;
    li.src = '//platform.linkedin.com/in.js';
    var lis = document.getElementsByTagName('script')[0];
    lis.parentNode.insertBefore(li, lis);
})();


// analytics
jQuery(window).load(function (){
    addProfessionalShareAnalytics();
});


function addProfessionalShareAnalytics() {
    // check for google analytics
    if (typeof _gaq === 'undefined') return;    

    try {
        if (FB && FB.Event && FB.Event.subscribe) {
            FB.Event.subscribe('edge.create', function(targetUrl) {
                _gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
            });
            FB.Event.subscribe('edge.remove', function(targetUrl) {
                _gaq.push(['_trackSocial', 'facebook', 'unlike', targetUrl]);
            });
            FB.Event.subscribe('message.send', function(targetUrl) {
                _gaq.push(['_trackSocial', 'facebook', 'send', targetUrl]);
            });
        }
    }catch(e) {}

    
    function extractParamFromUri(uri, paramName) {
        if (!uri) {
            return;
        }
        var regex = new RegExp('[\\?&#]' + paramName + '=([^&#]*)');
        var params = regex.exec(uri);
        if (params != null) {
            return unescape(params[1]);
        }
        return;
    }
 

    function trackTwitter(intent_event) {
        if (intent_event) {
            var opt_target;
            var opt_pagePath;
            if (intent_event.target && intent_event.target.nodeName == 'IFRAME') {
                opt_target = extractParamFromUri(intent_event.target.src, 'url');
            }
            _gaq.push(['_trackSocial', 'twitter', 'tweet', opt_target, opt_pagePath]);
                
        }
    }

    //Wrap event bindings - Wait for async js to load
    twttr.ready(function (twttr) {
        //event bindings
        twttr.events.bind('tweet', trackTwitter);
    });
              
    
    
}
    
function LinkedInShare(link){                      
    // check for google analytics
     if (typeof _gaq === 'undefined') return;  
     _gaq.push(['_trackSocial', 'linkedin', 'share',link]); 
}   
    