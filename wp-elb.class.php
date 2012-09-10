<?php
class ExternalLinks {
	
	
	var $filepath="";
	var $plugindir="";
	var $basedir="";
	var $usetemplate="";
	var $totallink="";
	var $actuallink="";
	
	var $options = array();
	var $config = array();
	
	function ExternalLinks(){
	
		$this->config =  get_option('wpelb_options');
		
		if ( !$this->filepath )
	    	$this->filepath = dirname(__FILE__);
		
		if ( !$this->plugindir )
	    	$this->plugindir = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));

		if ( !$this->basedir )
			$this->basedir = get_option('home')."/".$this->config['linkpath']."/";
		
		if ( !$this->usetemplate )
			$this->usetemplate = $this->config['template'];
			
		if ( !$this->totallink )
			$this->totallink = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		
		if ( !$this->actuallink ){
			$url=str_replace($this->basedir,'',$this->totallink);
			$this->actuallink = $this->cleanUrl($url);
		}
	
	}
	
	function load(){
		$this->options = array(
							"{BLOG_NAME}" => get_option('blogname'),
							"{LINK_TITLE}" => $this->getAttributeFromLink("title"),
							"{SHARE}" => '#" onclick="share()',
							"{URL}" => $this->actuallink,
						);
	}
	
	function searchLinks($content = ''){

		preg_match_all("/<a\s*[^>]*>(.*)<\/a>/siU", $content, $matches);

		$foundLinks = $matches[0];
		
		foreach ($foundLinks as $theLink) {
			$uri = $this->cleanUrl($this->getAttribute('href',$theLink));
			if($this->isntInternal($uri) && $this->noApply($uri)){
				$content=str_replace("href=\"".$uri."\"","title='original link: ".$uri."' href=\"".$this->basedir.$uri."\"",$content);
			}
		}	
		
		return $content;
	}
	
	function onlyLinks($link = ''){

		if($link){
			$link=$this->basedir.$link;
		}	
		
		return $link;
	}
	function cleanUrl($url) {
		if ('' == $url) return $url;
		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%]|i', '', $url);
		$strip = array('%0d', '%0a');
		$url = str_replace($strip, '', $url);
		$url = str_replace(';//', '://', $url);
		$url = (!strstr($url, '://')) ? 'http://'.$url : $url;
		$url = preg_replace('/&([^#])(?![a-z]{2,8};)/', '&#038;$1', $url);
	
		return $url;
	}
	function isntInternal($url){
		$return = true;
		if(strpos($url, $this->cleanUrl(get_option("home"))) === 0 && $this->config['is_internal']){
			$return = false;
		}
		return $return;
	}
	
	function noApply($url){
		$return = true;
		$noapply = explode("\n",$this->config['no_apply']);
		foreach($noapply as $link){
			if($link){
				if(strpos($url, $this->cleanUrl($link)) === 0){
					$return = false;
				}
			}
		}
		return $return;
	}
	
	function getAttribute($attrib, $tag){
		//get attribute from html tag
		$re = '/' . preg_quote($attrib) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
		if (preg_match($re, $tag, $match)) {
		 return $match[2];
		}
		return false;
	}
	
	function getAttributeFromLink($attrib){
		if($tag=@file_get_contents($this->actuallink)){
			$re = "/<" . preg_quote($attrib) . "\s*[^>]*>(.*)<\/" . preg_quote($attrib) . ">/siU";
			if (preg_match($re, $tag, $match)) {
				return $match[1];
			}
		}else{
			return "not found";
		}
		return false;
	}

	function isValid(){
		if( strtolower( substr( $this->totallink, 0, strlen($this->basedir) ) ) == $this->basedir && ($this->config['posts_active'] || $this->config['comments_active'])){
			return true;
		}else{
			return false;
		}
	}
	
	function getTemplate(){
		$this->load();
		$html=file_get_contents(dirname(__FILE__) ."/". "templates"."/". $this->usetemplate ."/"."index.html");
		foreach($this->options as $key => $value){
			$html=str_replace($key,$value,$html);
		}
		return $html;
	}	
	
	function makeBar(){
		header("HTTP/1.1 301 Moved Permanently");
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title><?php echo get_option('blogname')?> | <?php echo $this->getAttributeFromLink("title")?></title>
				<link href="<?php echo $this->plugindir?>/asset/style.css" rel="stylesheet" type="text/css" media="screen" />
				<link href="<?php echo $this->plugindir?>/templates/<?php echo $this->usetemplate?>/style.css" rel="stylesheet" type="text/css" media="screen" />
				<link rel="canonical" href="<?php echo  $this->basedir?>" />
				<script type="text/javascript" src="<?php echo $this->plugindir?>/asset/mootools-core.js"></script>
				<script type="text/javascript" src="<?php echo $this->plugindir?>/asset/functions.js"></script>
				<style type="text/css">
					#wpbar {
						<?php if($this->config['backgroundcolor']){?>background-color: <?php echo $this->config['backgroundcolor']?> !important;<?php }?>
						<?php if($this->config['backgroundimage']){?>background-image: url(<?php echo $this->config['backgroundimage']?>) !important;<?php }?>
						<?php if($this->config['backgroundimage'] && $this->config['backgroundimagerepeat']){?>background-repeat: url(<?php echo $this->config['backgroundimagerepeat']?>) !important;<?php }?>
						<?php if($this->config['backgroundimage'] && $this->config['backgroundimageposition']){?>background-position: url(<?php echo $this->config['backgroundimageposition']?>) !important;<?php }?>

						<?php if($this->config['fontcolor']){?>color: <?php echo $this->config['fontcolor']?> !important;<?php }?>
					}
					#wpbar .intbar{
						<?php if($this->config['bordercolor'] && !$this->config['on_bottom']){?>border-bottom-color: <?php echo $this->config['bordercolor']?> !important;<?php }?>
						<?php if($this->config['bordercolor'] && $this->config['on_bottom']){?>border-top-color: <?php echo $this->config['bordercolor']?> !important;<?php }?>
						<?php if($this->config['logo']){?>background: transparent url(<?php echo $this->config['logo']?>) 10px 5px no-repeat !important;<?php }?>
					}
					#wpbar .intbar h1{
						<?php if(!$this->config['logo']){?>margin: 0 !important;<?php }?>
						<?php if($wpelb_options['fontcolor']){?>color: <?php echo $wpelb_options['fontcolor']?> !important;<?php }?>
					}
					#wpbar .intbar h2{
						<?php if(!$this->config['logo']){?>margin: 0 !important;<?php }?>
						<?php if($wpelb_options['fontcolor']){?>color: <?php echo $wpelb_options['fontcolor']?> !important;<?php }?>
					}
					#wpbar .intbar a{
						<?php if($this->config['linkcolor']){?>color: <?php echo $this->config['linkcolor']?> !important;<?php }?>
						<?php if($this->config['bordercolor']){?>border-color: <?php echo $this->config['bordercolor']?> !important;<?php }?>
						<?php if($this->config['backgroundcolor']){?>background-color: <?php echo $this->config['backgroundcolor']?> !important;<?php }?>
					}
					#wpbar .intbar .sharelist{
						<?php if($this->config['bordercolor']){?>border-color: <?php echo $this->config['bordercolor']?> !important;<?php }?>
						<?php if($this->config['backgroundcolor']){?>background-color: <?php echo $this->config['backgroundcolor']?> !important;<?php }?>
					}
				</style>
			</head>
			<body>
			<?php if(!$this->config['on_bottom']){?>
			<div id="wpbar" class="bar">
				<div class="intbar">
					<?php echo $this->getTemplate() ?>
				</div>
			</div>
			<?php }?>
	    <iframe src="<?php echo rawurldecode($this->actuallink)?>" frameborder="0" id="wpframe" class="url" noresize="noresize"></iframe>
			<?php if($this->config['on_bottom']){?>
			<div id="wpbar" class="bar bottom">
				<div class="intbar">
					<?php echo $this->getTemplate() ?>
				</div>
			</div>
			<?php }?>
			</body>
		</html>
		
		<?php
		exit();
	}
}
?>