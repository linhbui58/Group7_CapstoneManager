<?php
$str = "Báº¡n Ä‘Ã£ Ä‘Äƒng kÃ½ Ä‘á»  tÃ i trong há» c ká»³ nÃ y rá»“i";
echo mb_convert_encoding($str, 'Windows-1252', 'UTF-8');
