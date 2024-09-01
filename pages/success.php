<!-- made by monarch60 -->
<?php
function get_config( $int1) {
    $configContent = file_get_contents("/var/www/html/auth.config");
    if ($configContent === false) {
        return null;
    }
    $list1 = explode("\n", trim($configContent));
    if (count($list1) < $int1) {
        return null;
    }
    $substring = $list1[$int1 - 1];
    $array2 = explode("\t", $substring);
    if (empty($array2)) {
        return null;
    }
    
    return array_pop($array2);
}
$target = get_config(4);
header("Location: $target");
exit;
?>
