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
	current = "#paramsuseditems";
	params = "#paramslayout";

	// Sortable Lists
	usedList = "#sortable";
	unusedList = "#sortable2";

	jQuery(this).availableTags();

	// Initialises sortables and checks if values are assigned correctly.
	jQuery(this).initSortables();


	// Hide or show twitter fields based on latout type
	switch (jQuery('#paramssource :selected').text()) {
		case 'User tweets':
			jQuery("#paramsusername,#paramsusername-lbl,#paramstweetList,#paramstweetList-lbl,.twitterlist").show();
			jQuery("#paramsquery,#paramsquery-lbl").hide();
			break;

		case 'Twitter search':
			jQuery("#paramsquery,#paramsquery-lbl").show();
			jQuery("#paramsusername,#paramsusername-lbl,#paramstweetList,#paramstweetList-lbl,.twitterlist").hide();
			break;

	}

	// Hide or show the scroller height option
	switch (jQuery('#paramstweetLayout :selected').text()) {
		case 'Scroller':
			jQuery("#paramsheight,#paramsheight-lbl,.moduleheight").show();
			break;

		case 'Default':
			jQuery("#paramsheight,#paramsheight-lbl,.moduleheight").hide();
			break;

		case 'Pagination':
			jQuery("#paramsheight,#paramsheight-lbl,.moduleheight").hide();
			break;

	}


	// Hides options based on whether the tag is used int he display
	if(jQuery("#sortable li.avatar").length == 1)  {
		jQuery("#paramsavatarSize,.avatardescription,.AVATAR h2,#paramsavatarSize-lbl").show();
	}
	else {
		jQuery("#paramsavatarSize,.avatardescription,.AVATAR h2,#paramsavatarSize-lbl").hide();
	}


		// Hides options based on whether the icona tag is used in the display
	if(jQuery("#sortable li.icon").length == 1)  {
		jQuery("#paramstwitterBird-lbl,#paramstwitterBird,.icondescription,.Module.Icon").show();
	}
	else {
		jQuery("#paramstwitterBird-lbl,#paramstwitterBird,.icondescription,.Module.Icon").hide();
	}



	// Hides options based on whether the tag is used int he display
	if(jQuery("#sortable li.column1").length == 1)  {
		col1 = 1;
		jQuery("#paramscol1Width,#paramscol1Width-lbl").show();
	}
	else {
	col1 = 0;
		jQuery("#paramscol1Width,#paramscol1Width-lbl").hide();
	}

	// Hides options based on whether the tag is used int he display
	if(jQuery("#sortable li.column2").length == 1)  {
		col2 = 1;
		jQuery("#paramscol2Width,#paramscol2Width-lbl").show();
	}
	else {
	col2 = 0;
		jQuery("#paramscol2Width,#paramscol2Width-lbl").hide();
	}

	// Hides options based on whether the tag is used int he display
	if(jQuery("#sortable li.column3").length == 1)  {
		col3 = 1;
		jQuery("#paramscol3Width,#paramscol3Width-lbl").show();
	}
	else {
	col3 = 0;
		jQuery("#paramscol3Width,#paramscol3Width-lbl").hide();
	}


	if((col1 + col2 + col3) > 0) {
		jQuery(".moduleheader.Column.Widths,.COLUMN_WIDTHS").show();
	}
	else {
		jQuery(".moduleheader.Column.Widths,.COLUMN_WIDTHS").hide();
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
		jQuery("#paramsuseditems").val(jQuery(usedList).sortable("toArray"));
		jQuery("#paramsunuseditems").val(jQuery(unusedList).sortable("toArray"));
	}


	// function that restores the list order from a cookie
	function restoreOrder() {
		var list = jQuery(setSelector);
		if (list == null) return

		// fetch the cookie value (saved order)
		var useditems = jQuery("#paramsuseditems").val();
		var unuseditems = jQuery("#paramsunuseditems").val();
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
					jQuery("#paramsavatarSize,.avatardescription,.Avatar h2,#paramsavatarSize-lbl").show();
				}
				else {
					jQuery("#paramsavatarSize,.avatardescription,.Avatar h2,#paramsavatarSize-lbl").hide();
				}


					// Hides options based on whether the icona tag is used in the display
				if(jQuery("#sortable li.icon").length == 1)  {
					jQuery("#paramstwitterBird-lbl,#paramstwitterBird,.icondescription,.Module.Icon").show();
				}
				else {
					jQuery("#paramstwitterBird-lbl,#paramstwitterBird,.icondescription,.Module.Icon").hide();
				}


				// Hides options based on whether the tag is used int he display
				if(jQuery("#sortable li.column1").length == 1)  {
					col1 = 1;
					jQuery("#paramscol1Width,#paramscol1Width-lbl").show();
				}
				else {
				col1 = 0;
					jQuery("#paramscol1Width,#paramscol1Width-lbl").hide();
				}

				// Hides options based on whether the tag is used int he display
				if(jQuery("#sortable li.column2").length == 1)  {
					col2 = 1;
					jQuery("#paramscol2Width,#paramscol2Width-lbl").show();
				}
				else {
				col2 = 0;
					jQuery("#paramscol2Width,#paramscol2Width-lbl").hide();
				}

				// Hides options based on whether the tag is used int he display
				if(jQuery("#sortable li.column3").length == 1)  {
					col3 = 1;
					jQuery("#paramscol3Width,#paramscol3Width-lbl").show();
				}
				else {
				col3 = 0;
					jQuery("#paramscol3Width,#paramscol3Width-lbl").hide();
				}


				if((col1 + col2 + col3) > 0) {
					jQuery(".moduleheader.Column.Widths,.COLUMN_WIDTHS").show();
				}
				else {
					jQuery(".moduleheader.Column.Widths,.COLUMN_WIDTHS").hide();
				}



				getOrder();

				jQuery("li#empty").remove();

				var itemOrder = jQuery("#paramsuseditems").val();

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


	jQuery("td.paramlist_value select").change(function () {

		// Hide or show twitter fields based on latout type
		switch (jQuery('#paramssource :selected').text()) {
			case 'User tweets':
				jQuery("#paramsusername,#paramsusername-lbl,#paramstweetList,#paramstweetList-lbl,.twitterlist").show();
				jQuery("#paramsquery,#paramsquery-lbl").hide();
				break;

			case 'Twitter search':
				jQuery("#paramsquery,#paramsquery-lbl").show();
				jQuery("#paramsusername,#paramsusername-lbl,#paramstweetList,#paramstweetList-lbl,.twitterlist").hide();
				break;

		}

		// Hide or show the scroller height option
		switch (jQuery('#paramstweetLayout :selected').text()) {
			case 'Scroller':
				jQuery("#paramsheight,#paramsheight-lbl,.moduleheight").show();
				break;

			case 'Default':
				jQuery("#paramsheight,#paramsheight-lbl,.moduleheight").hide();
				break;

			case 'Pagination':
				jQuery("#paramsheight,#paramsheight-lbl,.moduleheight").hide();
				break;

		}
	});



});
