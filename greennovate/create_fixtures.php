<?php
$im = imagecreatetruecolor(10, 10);
imagejpeg($im, 'tests/Browser/fixtures/dummy_foto.jpg');
imagedestroy($im);
file_put_contents('tests/Browser/fixtures/dummy_doc.pdf', 'PDF-1.4');
