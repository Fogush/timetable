/**
 * @package		jTweet
 * @subpackage	jTweet
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		2.0.5
 */

jQuery(document).ready(function() {
	// Param where we store the information
	current = "#jform_params_useditems";
	params = "#jform_params_layout";

	// Sortable Lists
	usedList = "#sortable";
	unusedList = "#sortable2";

	jQuery(this).availableTags();

	// Initialises sortables and checks if values are assigned correctly.
	jQuery(this).initSortables();


	// Hide or show twitter fields based on latout type
	switch (jQuery('#jform_params_source :selected').text()) {
		case 'User tweets':
			jQuery("#jform_params_username,#jform_params_username-lbl,#jform_params_tweetList,#jform_params_tweetList-lbl,.twitterlist").parent().parent().show();
			jQuery("#jform_params_query,#jform_params_query-lbl").parent().parent().hide();
			break;

		case 'Twitter search':
			jQuery("#jform_params_query,#jform_params_query-lbl").parent().parent().show();
			jQuery("#jform_params_username,#jform_params_username-lbl,#jform_params_tweetList,#jform_params_tweetList-lbl,.twitterlist").parent().parent().hide();
			break;
	}

	// Hide or show the scroller height option
	switch (jQuery('#jform_params_tweetLayout :selected').text()) {
		case 'Scroller':
			jQuery("#jform_params_height,#jform_params_height-lbl,.moduleheight").parent().parent().show();
			break;

		case 'Default':
			jQuery("#jform_params_height,#jform_params_height-lbl,.moduleheight").parent().parent().hide();
			break;

		case 'Pagination':
			jQuery("#jform_params_height,#jform_params_height-lbl,.moduleheight").parent().parent().hide();
			break;
	}


	// Hides options based on whether the avatar tag is used in the display
	if(jQuery("#sortable li.avatar").length == 1)  {
		jQuery("#jform_params_avatarSize,.avatardescription,.Avatar h2,#jform_params_avatarSize-lbl").parent().parent().show();
	}
	else {
		jQuery("#jform_params_avatarSize,.avatardescription,.Avatar h2,#jform_params_avatarSize-lbl").parent().parent().hide();
	}

	// Hides options based on whether the icona tag is used in the display
	if(jQuery("#sortable li.icon").length == 1)  {
		jQuery("#jform_params_twitterBird-lbl,#jform_params_twitterBird,.icondescription").parent().parent().show();
	}
	else {
		jQuery("#jform_params_twitterBird-lbl,#jform_params_twitterBird,.icondescription").parent().parent().hide();
	}


	// Hides options based on whether the tag is used int he display
	if(jQuery("#sortable li.column1").length == 1)  {
		col1 = 1;
		jQuery("#jform_params_col1Width,#jform_params_col1Width-lbl").parent().parent().show();
	}
	else {
	col1 = 0;
		jQuery("#jform_params_col1Width,#jform_params_col1Width-lbl").parent().parent().hide();
	}

	// Hides options based on whether the tag is used int he display
	if(jQuery("#sortable li.column2").length == 1)  {
		col2 = 1;
		jQuery("#jform_params_col2Width,#jform_params_col2Width-lbl").parent().parent().show();
	}
	else {
	col2 = 0;
		jQuery("#jform_params_col2Width,#jform_params_col2Width-lbl").parent().parent().hide();
	}

	// Hides options based on whether the tag is used int he display
	if(jQuery("#sortable li.column3").length == 1)  {
		col3 = 1;
		jQuery("#jform_params_col3Width,#jform_params_col3Width-lbl").parent().parent().show();
	}
	else {
	col3 = 0;
		jQuery("#jform_params_col3Width,#jform_params_col3Width-lbl").parent().parent().hide();
	}


	if((col1 + col2 + col3) > 0) {
		jQuery(".moduleheader.Column.Widths,.columnwidths,.Columns").parent().parent().show();
	}
	else {
		jQuery(".moduleheader.Column.Widths,.columnwidths,.Columns").parent().parent().hide();
	}



	// The following is the basic sort and order script
	//
	//
	//
	// set the list selector
	var setSelector = "#sortable";
	var setSelector2 = "#sortable2";



	// function that writes the list order to a cookie
	function getOrder() {
		// save custom order to cookie
		jQuery("#jform_params_useditems").val(jQuery(usedList).sortable("toArray"));
		jQuery("#jform_params_unuseditems").val(jQuery(unusedList).sortable("toArray"));
	}


	// function that restores the list order from a cookie
	function restoreOrder() {
		var list = jQuery(setSelector);
		if (list == null) return

		// fetch the cookie value (saved order)
		var useditems = jQuery("#jform_params_useditems").val();
		var unuseditems = jQuery("#jform_params_unuseditems").val();
			if (!useditems) return;

		// make array from saved order
		var IDs = useditems.split(",");
		var unusedIDs = unuseditems.split(",");


		// fetch current order
		var items = list.sortable("toArray");

		// make array from current order
		var rebuild = new Array();
		for ( var v=0, len=IDs.length; v<len; v++ ){
			rebuild[IDs[v]] = IDs[v];
		}

		for (var i = 0, n = IDs.length; i < n; i++) {

			// item id from saved order
			var itemID = IDs[i];

			if (itemID in rebuild) {

				// select item id from current order
				var item = rebuild[itemID];

				// select the item according to current order
				var child = jQuery("ui-sortable").children("#" + item);

				// select the item according to the saved order
				var savedOrd = jQuery("ui-sortable").children("#" + itemID);

				// remove all the items
				child.remove();

				// add the items in turn according to saved order
				// we need to filter here since the "ui-sortable"
				// class is applied to all ul elements and we
				// only want the very first!  You can modify this
				// to support multiple lists - not tested!
				jQuery("ul.ui-sortable").filter(":first").append(savedOrd);


			}
		}
	}


	// code executed when the document loads
	jQuery(function() {

		setSelector="#sortable";
		setSelector2="#sortable2";
		// here, we allow the user to sort the items
		jQuery(setSelector).sortable({

				connectWith: ".connectedLists",
				cursor: "move",
				placeholder: "ui-state-highlight",
				scope: "tags",
				opacity: 0.8,
				dropOnEmpty: true,
				items: "li:not(.disabled)",
				revert: 200,

				update: function(event,ui) {

					// Hides options based on whether the tag is used int he display
					if(jQuery("#sortable li.avatar").length == 1)  {
						jQuery("#jform_params_avatarSize,.avatardescription,.Avatar h2,#jform_params_avatarSize-lbl").parent().parent().show();
					}
					else {
						jQuery("#jform_params_avatarSize,.avatardescription,.Avatar h2,#jform_params_avatarSize-lbl").parent().parent().hide();
					}


					// Hides options based on whether the icona tag is used in the display
					if(jQuery("#sortable li.icon").length == 1)  {
						jQuery("#jform_params_twitterBird-lbl,#jform_params_twitterBird,.icondescription").parent().parent().show();
					}
					else {
						jQuery("#jform_params_twitterBird-lbl,#jform_params_twitterBird,.icondescription").parent().parent().hide();
					}


					// Hides options based on whether the tag is used int he display
					if(jQuery("#sortable li.column1").length == 1)  {
						col1 = 1;
						jQuery("#jform_params_col1Width,#jform_params_col1Width-lbl").parent().parent().show();
					}
					else {
					col1 = 0;
						jQuery("#jform_params_col1Width,#jform_params_col1Width-lbl").parent().parent().hide();
					}

					// Hides options based on whether the tag is used int he display
					if(jQuery("#sortable li.column2").length == 1)  {
						col2 = 1;
						jQuery("#jform_params_col2Width,#jform_params_col2Width-lbl").parent().parent().show();
					}
					else {
					col2 = 0;
						jQuery("#jform_params_col2Width,#jform_params_col2Width-lbl").parent().parent().hide();
					}

					// Hides options based on whether the tag is used int he display
					if(jQuery("#sortable li.column3").length == 1)  {
						col3 = 1;
						jQuery("#jform_params_col3Width,#jform_params_col3Width-lbl").parent().parent().show();
					}
					else {
					col3 = 0;
						jQuery("#jform_params_col3Width,#jform_params_col3Width-lbl").parent().parent().hide();
					}


					if((col1 + col2 + col3) > 0) {
						jQuery(".moduleheader.Column.Widths,.columnwidths,.Columns").parent().parent().show();
					}
					else {
						jQuery(".moduleheader.Column.Widths,.columnwidths,.Columns").parent().parent().hide();
					}


					getOrder();

					jQuery("li#empty").remove();

					var itemOrder = jQuery("#jform_params_useditems").val();

				}
		});

		jQuery(setSelector2).sortable({
				cursor: "move",
				placeholder: "ui-state-highlight",
				scope: "tags",
				opacity: 0.8,
				dropOnEmpty: true,
				items: "li:not(.disabled)",
				revert: 200
		});


		jQuery("#sortable2,#sortable").sortable({
		    connectWith: jQuery("#sortable,#sortable2")
		});

		// here, we reload the saved order
		restoreOrder();
	});


	jQuery(".controls select").chosen().change(function () {

		// Hide or show twitter fields based on latout type
		switch (jQuery('#jform_params_source :selected').text()) {
			case 'User tweets':
				jQuery("#jform_params_username,#jform_params_username-lbl,#jform_params_tweetList,#jform_params_tweetList-lbl,.twitterlist").parent().parent().show();
				jQuery("#jform_params_query,#jform_params_query-lbl").parent().parent().hide();
				break;

			case 'Twitter search':
				jQuery("#jform_params_query,#jform_params_query-lbl").parent().parent().show();
				jQuery("#jform_params_username,#jform_params_username-lbl,#jform_params_tweetList,#jform_params_tweetList-lbl,.twitterlist").parent().parent().hide();
				break;
		}

		// Hide or show the scroller height option
		switch (jQuery('#jform_params_tweetLayout :selected').text()) {
			case 'Scroller':
				jQuery("#jform_params_height,#jform_params_height-lbl,.moduleheight").parent().parent().show();
				break;

			case 'Default':
				jQuery("#jform_params_height,#jform_params_height-lbl,.moduleheight").parent().parent().hide();
				break;

			case 'Pagination':
				jQuery("#jform_params_height,#jform_params_height-lbl,.moduleheight").parent().parent().hide();
				break;
		}
	});

});
