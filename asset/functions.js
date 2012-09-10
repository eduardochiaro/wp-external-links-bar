var borderbottom = 0;
var shareopen = 0;

function UpdateShare(){

	originalheight = $("sharelist").getStyle("height").toInt();
	borderbottom = $("sharelist").getStyle("border-bottom");
	bordertop = $("sharelist").getStyle("border-top");
	
	$("sharelist").setStyles({
								"display" : "none"
							});
}

function CloseShare(){
	if($$(".bottom").length){
		$("share").setStyle("border-top",bordertop);	
	}else{
		$("share").setStyle("border-bottom",borderbottom);
	}
	
	$("sharelist").setStyles({
								"display" : "none"
							});
}

function OpenShare(){

	if($$(".bottom").length){
		$("share").setStyle("border-top","none");	
	}else{
		$("share").setStyle("border-bottom","none");
	}

	$("sharelist").setStyles({
								"display" : "block",
							});
}

function share(){
	if(shareopen == 0){
		OpenShare();
		shareopen = 1;
	}else{
		CloseShare()
		shareopen = 0;
	}
}

function UpdateLayer(){

	hbar = $("wpbar").getStyle("height").toInt();
	
	size=window.getSize();
	$("wpframe").setStyle("height",((size.y)-hbar));
}
/*
if (Browser.Engine.trident){
	var replacement = new Element('span', {
		id:(el.id)?el.id:'',
		'class':(el.className)?el.className:'',
		title:(el.title)?el.title:(el.alt)?el.alt:'',
		styles: {
			display: vis?'inline-block':'none',
			width: dim.x,
			height: dim.y,
			filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader (src='" 
				+ el.src + "', sizingMethod='scale');"
		},
		src: el.src
	});
	
	this.style.filter = (opacity == 1) ? '' : 'alpha(opacity=' + opacity * 100 + ')';
}
*/

window.addEvent('domready',function(){
	UpdateLayer();
	UpdateShare();
});
window.addEvent('resize',function(){
	UpdateLayer();
});