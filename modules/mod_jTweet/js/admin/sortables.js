/**
 * @package		jTweet
 * @subpackage	jTweet
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		2.0.5
 */

// Function to use when setting the contents of the sortable lists

(function( $ ) {

	var available = [];

	$.fn.availableTags = function() {

		available = new Array();
		available = ['{source}','{text}','{user}','{avatar}','{time}','{icon}','{favorite}','{retweet}','{reply}','{break}','{break}','{break}','{break}','{break}','{break}','{column1}','{column2}','{column3}'];
	}

	$.fn.initSortables = function() {

		// Retrieve the cookie contents for this instance of the module
		setItems = ($(current).val()).split(",");

		//if the current contents is empty initialise the array
		if(!setItems) {
			setItems = ['{avatar}','{text}','{break}','{user}','{time}','{source}',];
		}

		$(this).setSortables();
	}

	$.fn.setSortables = function() {

		$(this).availableTags();

		// Empty the sortable lists in case the default button clicked
		$(usedList).empty();
		$(unusedList).empty();

		// Reinstate the instructions
		$(usedList).prepend("<li class='disabled'>Drag items here to use</li>");
		$(unusedList).prepend("<li class='disabled'>Available Items</li>");

		// Array to store the unused items
		var unusedItems = Array();


		// Look through the array of possible tags and filter out the ones that are actually being used.
		$.each(available, function(i, val){
			 if($.inArray(val, setItems) < 0)
				 unusedItems.push(val);
		});

		// Store the used items so they will be retrieved on page load.
		$("#paramsuseditems").val(setItems);


		// Create two strings out of the arrays
		var useditems = setItems.join(',');
		var unuseditems = unusedItems.join(',')

		// Then split them again
		var used = useditems.split(",");
		var unused = unuseditems.split(",");



		// Populate the list of sortable items
		if($(current).val().length > 0)  {
			// Repopulates the list of elements. We check for the length of the textbox and if its not empty populate it.
			jQuery.each(used, function(i)
			{
				var li = $('<li/>').attr('id', used[i]).addClass(used[i].replace('{', '').replace('}', '')).text(used[i].replace('{', '').replace('}', '')).appendTo(usedList);
			});
		}

		// Sets the list of available items
		jQuery.each(unused, function(i)
		{
			var li = $('<li/>').attr('id', unused[i]).addClass(unused[i].replace('{', '').replace('}', '')).text(unused[i].replace('{', '').replace('}', '')).appendTo(unusedList);
		});
	};

})( jQuery );
