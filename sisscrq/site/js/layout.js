var outerLayout;

$(document).on("ready", function(){
	outerLayout = $('body').layout({
		center__paneSelector:	".outer-center",
		west__paneSelector:		".outer-west",
		west__showOverflowOnHover: true,
		west__size:				215,
		spacing_open:			4,
		spacing_closed:			6,
		north__spacing_open:	0,
		south__spacing_open:	0,
		center__onresize:		"middleLayout.resizeAll"
	});

	$("header").hover(function(){
		outerLayout.allowOverflow('north');
	}, function(){
		outerLayout.resetOverflow(this);
	});
});