function SubmitKWDeleter(kwid,kw){
   if(kwid > 0){
				   document.getElementById('delete_keyword_id').value = kwid;
					 document.getElementById('delete_keyword').value = kw;
					 document.DeleteKeywordForm.submit();
	 }else{
				   alert('Error with Keyword ID');
	 }
}///end function