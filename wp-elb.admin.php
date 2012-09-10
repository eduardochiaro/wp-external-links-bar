<?php
function elb_insert() {
	$wpelb_options = array(
						"posts_active"=>"on",
						"comments_active"=>"on",
						"comments_active"=>"",
						"linkpath"=>"external",
						"template"=>"default",
						"backgroundcolor"=>"#333333",
						"bordercolor"=>"#e7e5dd",
						"fontcolor"=>"#e7e5dd",
						"linkcolor"=>"#e7e5dd",
						"backgroundimage"=>"",
						"backgroundimageposition"=>"",
						"backgroundimagerepeat"=>"",
						"logo"=>"",
						"is_internal"=>"",
						"no_apply"=>"",
						"on_bottom"=>"",
					);
	add_option('wpelb_options',$wpelb_options);
}
function elb_update() {
	$wpelb_options = array(
						"posts_active"=>"",
						"comments_active"=>"",
						"author_link_active"=>"",
						"linkpath"=>"",
						"template"=>"",
						"backgroundcolor"=>"#333333",
						"bordercolor"=>"#e7e5dd",
						"fontcolor"=>"#e7e5dd",
						"linkcolor"=>"#e7e5dd",
						"backgroundimage"=>"",
						"backgroundimageposition"=>"",
						"backgroundimagerepeat"=>"",
						"logo"=>"",
						"is_internal"=>"",
						"no_apply"=>"",
						"on_bottom"=>"",
					);
		
	foreach($wpelb_options as $key => $value){
		if($_POST[$key]){
			$wpelb_options[$key] = $_POST[$key];
		}
	}
	
	if(!get_option('wpelb_options')){
		add_option('wpelb_options',$wpelb_options);
	}else{
		update_option('wpelb_options',$wpelb_options);
	}

}

function elb_config_head(){
?>
<script type="text/javascript" src="../wp-includes/js/colorpicker.js"></script>
<script type="text/javascript">
// <![CDATA[
	function pickColor(color) {
		ColorPicker_targetInput.value = color;
	}
	function PopupWindow_populate(contents) {
		contents += '<br /><p style="text-align:center;margin-top:0px;"><input type="button" class="button-secondary" value="<?php echo __('Close Color Picker'); ?>" onclick="cp.hidePopup(\'prettyplease\')"></input></p>';
		this.contents = contents;
		this.populated = false;
	}
	function PopupWindow_hidePopup(magicword) {
		if ( magicword != 'prettyplease' )
			return false;
		if (this.divName != null) {
			if (this.use_gebi) {
				document.getElementById(this.divName).style.visibility = "hidden";
			}
			else if (this.use_css) {
				document.all[this.divName].style.visibility = "hidden";
			}
			else if (this.use_layers) {
				document.layers[this.divName].visibility = "hidden";
			}
		}
		else {
			if (this.popupWindow && !this.popupWindow.closed) {
				this.popupWindow.close();
				this.popupWindow = null;
			}
		}
		return false;
	}
	function colorSelect(t,p) {
		if ( cp.p == p && document.getElementById(cp.divName).style.visibility != "hidden" )
			cp.hidePopup('prettyplease');
		else {
			cp.p = p;
			cp.select(t,p);
		}
		
	}
	function PopupWindow_setSize(width,height) {
		this.width = 162;
		this.height = 210;
	}

	var cp = new ColorPicker();
	function advUpdate(val, obj) {
		document.getElementById(obj).value = val;
		kUpdate(obj);
	}
// ]]>
</script>

<?php
}

function elb_config(){
	
	if(isset($_POST['action'])){
		switch($_POST['action']){
			case "elb_update":
				elb_update();
				break;
		}
	}
	
	$wpelb_options = get_option('wpelb_options');
	if(!$wpelb_options){
		elb_insert();
		$wpelb_options = get_option('wpelb_options');
	}
	
	$class = new ExternalLinks();
?>
<div class="wrap">
	<h2>Wordpress External Links Bar Configuration</h2>
	<form name="dofollow" action="" method="post">
    <div id="poststuff">
	    <div class="postbox">
	<h3>system</h3>
    <div class="inside">
    

<table class="form-table">
<tr valign="bottom">

<th scope="row">
<?php echo __('activate on posts content')?>
</th>
<td>
<input type="checkbox" name="posts_active" <?php if ($wpelb_options['posts_active']) echo "checked=\"1\""; ?>/>
</td>
</tr>
<tr valign="bottom">

<th scope="row">
<?php echo __('activate on comments text')?>
</th>
<td>
<input type="checkbox" name="comments_active" <?php if ($wpelb_options['comments_active']) echo "checked=\"1\""; ?>/>
</td>
</tr>

<th scope="row">
<?php echo __('activate on comment\'s author links')?>
</th>
<td>
<input type="checkbox" name="author_link_active" <?php if ($wpelb_options['author_link_active']) echo "checked=\"1\""; ?>/>
</td>
</tr>
<tr valign="bottom">
<th scope="row">
<?php echo __('link')?>
</th>
<td>
<?php echo get_option('home')?>/<input type="input" name="linkpath" value="<?php echo $wpelb_options['linkpath']?>" />/
</td>
</tr>
<tr valign="bottom">
<th scope="row">
<?php echo __('not apply on internal links')?>
</th>
<td>
<input type="checkbox" name="is_internal" <?php if ($wpelb_options['is_internal']) echo "checked=\"1\""; ?>/>
<?php printf(__('not apply if links begin with %s'), '<code>'.get_option('home').'</code>'); ?>
</td>
</tr>

<tr valign="bottom">
<th scope="row">
<?php echo __('not apply on follow links')?>
</th>
<td>
<textarea name="no_apply" rows="5" cols="55"><?php echo $wpelb_options['no_apply']?></textarea>
<?php __('one for line'); ?>
</td>
</tr>


<tr valign="bottom" style="display:none;"> 
	<th scope="row">Template</th>
    <td>
	    <select name="template">
	    <?php
	    $d = dir(dirname(__FILE__)."/templates");
		while($entry=$d->read()) {
			if($entry!="." && $entry!=".." && $entry!=".svn" && $entry!=".DS_Store"  && is_dir(dirname(__FILE__)."/templates/".$entry)){
				if($entry == $template){
					echo '<option value="'.$entry.'" selected="selected">'.$entry."</option>\n";
				}else{
					echo '<option value="'.$entry.'">'.$entry."</option>\n";
				}
				
			}
		}
		$d->close();
	    ?>
	    </select>
    </td>                      
</tr>

</table>
</div>
</div>
<p class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php echo __('Update Options')?> &raquo;" /> 
</p>
	    <div class="postbox">
	<h3>preview</h3>
    <div class="inside">
				<style type="text/css">
				<?php require_once($class->filepath."/asset/style.css")?>
				
				<?php require_once($class->filepath."/templates/".$class->usetemplate."/style.css")?>
				</style>
    				<style type="text/css">
					#wpbar{
						width: 100%;
						height: auto;
						<?php if($wpelb_options['backgroundcolor']){?>background-color: <?php echo $wpelb_options['backgroundcolor']?> !important;<?php }?>
						<?php if($wpelb_options['backgroundimage']){?>background-image: url(<?php echo $wpelb_options['backgroundimage']?>) !important;<?php }?>
						<?php if($wpelb_options['backgroundimage'] && $wpelb_options['backgroundimagerepeat']){?>background-repeat: url(<?php echo $wpelb_options['backgroundimagerepeat']?>) !important;<?php }?>
						<?php if($wpelb_options['backgroundimage'] && $wpelb_options['backgroundimageposition']){?>background-position: url(<?php echo $wpelb_options['backgroundimageposition']?>) !important;<?php }?>

						<?php if($wpelb_options['fontcolor']){?>color: <?php echo $wpelb_options['fontcolor']?> !important;<?php }?>
					}
					#wpbar .intbar{
						<?php if($wpelb_options['bordercolor']){?>border-bottom-color: <?php echo $wpelb_options['bordercolor']?> !important;<?php }?>
						<?php if($wpelb_options['logo']){?>background: transparent url(<?php echo $wpelb_options['logo']?>) 10px 5px no-repeat !important;<?php }?>
					}
					#wpbar .intbar h1{
						<?php if(!$wpelb_options['logo']){?>margin: 0;<?php }?>
						<?php if($wpelb_options['fontcolor']){?>color: <?php echo $wpelb_options['fontcolor']?> !important;<?php }?>
					}
					#wpbar .intbar h2{
						margin: 0;
						padding: 0;
						<?php if(!$wpelb_options['logo']){?>margin: 0;<?php }?>
						<?php if($wpelb_options['fontcolor']){?>color: <?php echo $wpelb_options['fontcolor']?> !important;<?php }?>
						
					}
					#wpbar .intbar a{
						<?php if($wpelb_options['linkcolor']){?>color: <?php echo $wpelb_options['linkcolor']?> !important;<?php }?>
						<?php if($wpelb_options['bordercolor']){?>border-color: <?php echo $wpelb_options['bordercolor']?> !important;<?php }?>
						<?php if($wpelb_options['backgroundcolor']){?>background-color: <?php echo $wpelb_options['backgroundcolor']?> !important;<?php }?>
					}
					#wpbar .intbar .sharelist{
						<?php if($wpelb_options['bordercolor']){?>border-color: <?php echo $wpelb_options['bordercolor']?> !important;<?php }?>
						<?php if($wpelb_options['backgroundcolor']){?>background-color: <?php echo $wpelb_options['backgroundcolor']?> !important;<?php }?>
					}
					</style>
    <div>
    	<div id="wpbar" class="bar">
			<div class="intbar">
				<h1>Blog Name</h1>
				<h2>title of link</h2>
				<a href="#" class="share" id="share">share</a>
				<a href="#" class="close">close</a>
			</div>
		</div>
    </div>
</div>
</div>
	    <div class="postbox">
	<h3>template</h3>
    <div class="inside">    
	<div id="colorPickerDiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;visibility:hidden;"> </div>
<table class="form-table">


<th scope="row">
<?php echo __('position on bottom')?>
</th>
<td>
<input type="checkbox" name="on_bottom" <?php if ($wpelb_options['on_bottom']) echo "checked=\"1\""; ?>/>
<?php echo __('normal the bar appear on top')?>
</td>
</tr>

<tr valign="bottom">
<th scope="row">
<?php echo __('background color')?>
</th>
<td>
<input type="input" name="backgroundcolor" id="backgroundcolor" value="<?php echo $wpelb_options['backgroundcolor']?>" /><input type="button"  class="button-secondary" onclick="tgt=document.getElementById('backgroundcolor');colorSelect(tgt,'pick1');return false;" name="pick1" id="pick1" value="<?php esc_attr_e('picker'); ?>"> <?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?>
</td>
</tr>
<tr valign="bottom">
<th scope="row">
<?php echo __('border color')?>
</th>
<td>
<input type="input" name="bordercolor" id="bordercolor" value="<?php echo $wpelb_options['bordercolor']?>" /><input type="button"  class="button-secondary" onclick="tgt=document.getElementById('bordercolor');colorSelect(tgt,'pick1');return false;" name="pick1" id="pick1" value="<?php esc_attr_e('picker'); ?>"> <?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?>
</td>
</tr>

<tr valign="bottom">
<th scope="row">
<?php echo __('font color')?>
</th>
<td>
<input type="input" name="fontcolor" id="fontcolor" value="<?php echo $wpelb_options['fontcolor']?>" /><input type="button"  class="button-secondary" onclick="tgt=document.getElementById('fontcolor');colorSelect(tgt,'pick1');return false;" name="pick1" id="pick1" value="<?php esc_attr_e('picker'); ?>"> <?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?>
</td>
</tr>

<tr valign="bottom">
<th scope="row">
<?php echo __('link color')?>
</th>
<td>
<input type="input" name="linkcolor" id="linkcolor" value="<?php echo $wpelb_options['linkcolor']?>" /><input type="button"  class="button-secondary" onclick="tgt=document.getElementById('linkcolor');colorSelect(tgt,'pick1');return false;" name="pick1" id="pick1" value="<?php esc_attr_e('picker'); ?>"> <?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?>
</td>
</tr>



<tr valign="bottom">
<th scope="row">
<?php echo __('your logo')?>
</th>
<td>
<input type="input" name="logo" id="logo" class="regular-text" value="<?php echo $wpelb_options['logo']?>" /> <?php printf(__('best size %s'),"<code>27px X 27px</code>"); ?>
</td>
</tr>

</table>
</div>
</div>
	    <div class="postbox">
	<h3>background image</h3>
    <div class="inside">
<table class="form-table">

<tr valign="bottom">
<th scope="row">
<?php echo __('background image')?>
</th>
<td>
<input type="input" name="backgroundimage" id="backgroundimage" class="regular-text" value="<?php echo $wpelb_options['backgroundimage']?>" /> <?php printf(__('With <code>http://</code>')); ?>
</td>
</tr>

<tr valign="bottom">
<th scope="row">
<?php echo __('background image repeat')?>
</th>
<td>
<?php $backgroundimagerepeat=array("no-repeat","repeat","repeat-x","repeat-y");?>
<select name="backgroundimagerepeat" id="backgroundimagerepeat">
<?php
foreach($backgroundimagerepeat as $option){
?>
	<option value="<?php echo $option?>"<?php if($wpelb_options['backgroundimagerepeat']==$option){ echo ' selected="selected"';}?>><?php echo $option?></option>
<?php }?>
</select>
</td>
</tr>

<tr valign="bottom">
<th scope="row">
<?php echo __('background image position')?>
</th>
<td>
<input type="input" name="backgroundimageposition" id="backgroundimageposition" value="<?php echo $wpelb_options['backgroundimageposition']?>" />
<?php printf(__('use position, percent o pixel (%s or %s or %s)'), '<code>top left</code>', '<code>50% 10%</code>', '<code>10px 0px</code>'); ?>
</td>
</tr>

</table>
</div>
</div>
<p class="submit">
	
<input type="hidden" name="action" value="elb_update" /> 
<input type="hidden" name="page_options" value="wp-elb-config" /> 
<input type="submit" class="button-primary" name="Submit" value="<?php echo __('Update Options')?> &raquo;" /> 
</p>
</div>
</form>

</div>
<?php
}
?>