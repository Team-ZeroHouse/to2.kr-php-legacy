function showShortUrl(shortedUrl)
{
	$('#shorted-url').text(shortedUrl);
	$('#result').css('display', 'inline-block');
}

function hideShortUrl()
{
	$('#shorted-url').empty();
	$('#result').hide();
}

function enableForm(enable)
{
	$('short-form input').prop('disabled', enable);
}

$(function()
{
	var available = true;
	
	$('#short-form').submit(function(e)
  {
		e.preventDefault();
		if (this.url.value.trim() == 0)
    {
			alert("주소를 입력하세요.");
			return false;
		}
		var recaptcha = grecaptcha.getResponse();
		if (!recaptcha)
		{
			alert("[로봇이 아닙니다]를 체크하세요.");
			return false;
		}
		if (!available) {
			alert("이미 처리중 입니다.");
			return false;
		}
		
		available = false;
		enableForm(false);
		
		hideShortUrl();
		$.ajax('/shorten',
    {
			method: 'POST',
			data: { url: this.url.value, recaptcha: recaptcha },
			dataType: 'text',
			success: function(data)
      {
				alert("주소 줄이기 성공!");
				showShortUrl(data);
				available = true;
				enableForm(true);
				grecaptcha.reset();
			},
			error: function(xhr)
      {
				alert("["+xhr.responseText+"] 에러 발생");
				hideShortUrl();
				available = true;
				enableForm(true);
			}
		});
	});
});
