<?php

$maze = <<<EOF
wwwwwwwwwwwwwwwwwwwwwwwww
w...ss..................w
w.......................w
w...wwwwww.....wwwwww...w
w.......................w
w.....ww.........ww.....w
w....w..........w.......w
w....w..........w.......w
w.....ww....w....ww.....w
w..........ww...........w
w.........ww............w
w........ww.............w
w.........wwww..........w
w.......................w
w....ww...........ww....w
w...ww....wwwww....ww...w
w....wwwwwwwwwwwwwww....w
w......wwww...wwww......w
w.......................w
w.......w.......w.......w
w........wwwwwww........w
w..........www..........w
w...........w...........w
w.......................w
wwwwwwwwwwwwwwwwwwwwwwwww
EOF;
;

$rows = explode("\n", $maze);
foreach ($rows as &$row) {
    $row = str_replace('.', 'v', $row);
    $row = str_split($row);
}

return $rows;
