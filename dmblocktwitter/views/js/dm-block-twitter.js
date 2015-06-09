$(document).ready(function() {
    
    var block = $('.dm-block-twitter-content');

    if( block.length != 0 ){

        var widget_id = block.attr('data-widgetid');
        var limit = block.data('limit');
        
        function handleTweets(tweets){

            var output = '<ul>';
            $.each(tweets, function(index, tweet) {
                output += '<li>' + tweet + '</li>';
            });
            output += '</ul>';

            block.children('.block_content').append(output);

            // Thanks CSS-Tricks
            // https://css-tricks.com/snippets/jquery/cycle-through-a-list/                
            var j = 0;
            var delay = 4000;
             function cycle(){
                     var jmax = $("div#twitter-dyn ul li").length -1;
                     $("div#twitter-dyn ul li:eq(" + j + ")")
                            .animate({
                                "opacity"   :   "1",
                                "zIndex"    :   "1000"
                            }, 300)
                            .animate({
                                "opacity"   :   "1",
                                "zIndex"    :   "1000"
                            }, delay)
                            .animate({
                                "opacity"   :   "0",
                                "zIndex"    :   "0"
                            }, 300, function(){
                                (j == jmax) ? j=0 : j++;
                                    cycle();
                             });
                     };

             cycle();
        }
        var config = {
          "id":  widget_id,
          "domId": 'dm-block-twitter',
          "maxTweets": limit,
          "enableLinks": true,
          "showUser": true,
          "showTime": false,
          "lang": 'es',
          "showRetweet": false,
          "showImages": false,
          "customCallback": handleTweets,
          "showInteraction": false
        };
        twitterFetcher.fetch(config);
    }
});