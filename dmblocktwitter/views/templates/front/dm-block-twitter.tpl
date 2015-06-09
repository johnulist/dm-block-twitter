<!-- Simple Twitter Block Module -->
<div class="clearfix"></div>
<section id="dm-block-twitter" class="footer-block col-xs-12 hidden-xs hidden-sm">	

	<h4>{l s="Latest Tweets" mod="dmblocktwitter"}</h4>

	<div class="dm-block-twitter-content toggle-footer" data-widgetid="{$widget_id}" data-limit="{$tweets_limit}">

	    {if $follow_btn}
	    <a href="https://twitter.com/{$user}" class="twitter-follow-button" data-dnt="true" data-show-count="true">
	    	{l s="Follow" mod="dmblocktwitter"} @{$user}
	    </a>
	    <script>!function(d,s,id){ var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){ js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs); } }(document,"script","twitter-wjs");</script>
	    {/if}

	    <div id="twitter-dyn" class="block_content"></div>

	</div>

</section>
<!-- Simple Twitter Block Module -->