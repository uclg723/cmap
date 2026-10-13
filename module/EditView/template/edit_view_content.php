<?php
 // we have $form
  global $form, $error_queue;
?>
<div class='edit_view_error'>
<?php 
	foreach ($error_queue as $msg){
		echo "<span>{$msg}</span>";
	} 
?>
</div>
<div class='edit_view_content'>
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
	
	renderCurNode($form);

?>
</div>