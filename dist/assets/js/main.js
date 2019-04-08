$(function(){
	$('ul.nav a').filter(function(){
		return this.href==location.href
		}).parent().addClass('active').siblings().removeClass('active')
	$('ul.nav a').click(function(){
		$(this).parent().addClass('active').siblings().removeClass('active')	
	})
});

