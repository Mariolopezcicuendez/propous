$(document).ready(function() 
{
	$("#header_button_cms").on("click", function()
	{
    window.location = baseurl + "/es/cms";
	});

	$("#header_button_analytics").on("click", function()
	{
    window.location = baseurl + "/es/analytics";
	});
});