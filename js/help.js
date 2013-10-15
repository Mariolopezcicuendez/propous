$(document).ready(function() 
{
	$('.page_info_faq_question').on("click", function()
  {
  	var id_faq = $(this).attr("faq");

  	if ($('.page_info_paragraph[faq='+id_faq+']').hasClass("hidden"))
  	{
  		$('.page_info_paragraph[faq='+id_faq+']').removeClass('hidden');
  	}
  	else
  	{
  		$('.page_info_paragraph[faq='+id_faq+']').addClass('hidden');
  	}
  });
});