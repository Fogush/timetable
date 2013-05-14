<?php
/**
 * @package		jTweet
 * @subpackage	jTweet
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		2.0.5
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

$template = str_replace(",", "",$params->get( 'useditems'));
$template = str_replace("break", "zenbreak",$template);
$source = $params->get( 'source');
$layout = $params->get( 'tweetLayout');

if ($layout == "scroller")
{
	$height = $params->get( 'height');
}
else
{
	$height = "auto";
}


// Load css and script references into the head
if($scripts) {
	if($cache) { ?>
		<link rel="stylesheet" href="<?php echo $modbase ?>css/jTweet.css" type="text/css" />
		<script type="text/javascript" src="<?php echo $modbase?>js/jquery.tweet.js"></script>
	<?php }
 } ?>


<div class="jTweet jTweet<?php echo $moduleID ?> <?php echo $params->get( 'theme');  ?>" style="height: <?php echo $height; ?>"></div>

	<div class="jTweetClear"></div>

	<script type='text/javascript'>
		jQuery(function($){

			<?php if($layout == "pagination") { ?>
				// Start of the option variable in pagination mode
				options = {
			<?php }
			else { ?>
				// Non Pagination layout reference
				$(".jTweet<?php echo $moduleID ?>").tweet({

					<?php }
					if ($source =="query") { ?>
						query: '<?php echo $params->get( 'query');  ?>',

					<?php } else { ?>
						username: <?php echo $username; ?>,
						list: '<?php echo $params->get( 'tweetList');  ?>',
					<?php } ?>

					<?php if (!$params->get( 'suppressreply')) { ?>
					filter: function(t){ return ! /^@\w+/.test(t.tweet_raw_text); },
					<?php } ?>

					<?php // Display Favourites
					if($params->get( 'favourite')) { ?>
					   favorites: true,
					<?php } else { ?>
						favorites: false,
					<?php } ?>


					<?php // Display retweets
					if($params->get( 'retweet')) { ?>
					   retweets: true,
					<?php } else { ?>
						retweets: false,
					<?php } ?>


					// General Options
					avatar_size: <?php echo $params->get( 'avatarSize'); ?>,
					count: <?php echo $params->get( 'count'); ?>,
					fetch: <?php echo $params->get( 'count') + 20 ?>,
					page: 1,
					join_text:  'auto',
					loading_text: "<?php echo $params->get( 'loadingText'); ?>",
					refresh_interval: <?php echo $params->get( 'refreshTime');?>,
					template: "<?php echo $template; ?>",

					<?php // Close the variable for pagination mode
					if($layout == "pagination") { ?>
						}
					<?php } ?>

					<?php // Callback for scroller
					if($layout == "scroller") { ?>
					}).bind("loaded", function() {
					  var ul = $(this).find(".tweet_list");
					  var ticker = function() {
						setTimeout(function() {
						  ul.find('li:first').animate( {marginTop: '-3.5em'}, 500, function() {
							$(this).detach().appendTo(ul).removeAttr('style');
						  });
						  ticker();
						}, 5000);
					  };
					  ticker();
					<?php } ?>

					<?php // call back for open in new window
					if($params->get( 'targetBlank')) { ?>
						}).bind("loaded",function(){$(this).find("a").attr("target","_blank");
					<?php } ?>

					});

					<?php // Close the function for non pagination mode
						if($layout !== "pagination") { ?>
					});
					<?php } ?>

					<?php // Pagination Mode
					if($layout == "pagination") { ?>
					jQuery(function($){


					var widget = $("#paging .widget"),
					  next = $("#paging .next"),
					  prev = $("#paging .prev");

					var enable = function(el, yes) {
					  yes ? $(el).removeAttr('disabled') :
							$(el).attr('disabled', true);
					};

					var stepClick = function(incr) {
					  return function() {
						options.page = options.page + incr;
						enable(this, false);
						widget.tweet(options);
					  };
					};

					next.bind("checkstate", function() {
					  enable(this, widget.find("li").length == options.count)
					}).click(stepClick(1));

					prev.bind("checkstate", function() {
					  enable(this, options.page > 1)
					}).click(stepClick(-1));

					widget.tweet(options).bind("loaded", function() { next.add(prev).trigger("checkstate"); });
				  });
				 <?php } ?>


	</script>
	<!-- End Joomla Bamboo jTweet -->

	<?php if($layout == "pagination") { ?>
	<div id="paging">
		<div class="widget query"></div>
		<div class="controls">
			<button class="prev" type="button" disabled>&larr;</button>
			<span class="pagenum"></span>
			<button class="next" type="button" disabled>&rarr;</button>
		</div>
	</div>
	<?php }

	 if($params->get( 'follow') && !($source =="query") && $singleuser) { ?>
		<div class="jTweetfollowtext">
			<span>
				<a href="https://twitter.com/<?php echo $params->get( 'username');  ?>">
					<?php echo $params->get( 'followText');  ?>
					</a>
			</span>
		</div>
	 <?php } ?>
