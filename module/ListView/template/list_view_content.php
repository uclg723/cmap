<?php
 // we have $form
  global $list, $css;
?>
<div class='edit_view_content'>
<style type='text/css'>
<?php foreach ($css as $cur_css){ 
		readfile($cur_css, true);		
	} // foreach ?>
</style>
<?php 
	function renderCurNode($node){
		echo "<{$node['elementType']} ";
		foreach ($node['attribute'] as $key => $value){
			echo " {$key}='{$value}' ";
		} //foreach
		echo ">";
		if (!empty($node['textNode'])){
			echo $node['textNode'];
		}
		foreach ($node['childNode'] as $curNode){
			// recursive
			renderCurNode($curNode);
		}
		echo "</{$node['elementType']}>";
	}
	
	renderCurNode($list);

?>
</div><?php // edit_view_content ?>