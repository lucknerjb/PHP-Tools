<?php
$new_text = '';
if (isset($_POST['submit'])){
   $text = $_POST['text'];

   $new_text = urldecode($text);;
}

function myUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}
?>

<form action="./urlencode.php" method="post">
   <input type="text" id="text" name="text" size="150" />
   <input type="submit" name="submit" value="submit" />
</form>


<textarea cols="150"><?php echo $new_text; ?></textarea>
