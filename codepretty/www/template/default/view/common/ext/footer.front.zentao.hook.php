<?php
	css::import($jsRoot    . 'prettify/prettify.css');
	js::import($jsRoot . 'prettify/prettify.js');
?>
<script>
$(function(){
	$('pre.prettyprint').addClass('linenums')
	prettyPrint();

})
</script>
