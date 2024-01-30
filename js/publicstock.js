$(function() {

	// Show categories in tabs if the feature is enabled
	const categoriesDiv = $('#ps_categories');
	if (categoriesDiv.hasClass('ps_withCategories')) {
       categoriesDiv.tabs(); 
	}
});
