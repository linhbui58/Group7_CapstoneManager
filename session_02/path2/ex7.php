<?php
$arr = [1, 2, 3];
$reversed = [];

for ($i = count($arr) - 1; $i >= 0; $i--) {
    $reversed[] = $arr[$i];
}

print_r($reversed);
?>