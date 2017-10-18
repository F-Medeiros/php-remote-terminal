<?php

function run($command)
{
    return nl2br(_replace(shell_exec($command)));
}


function _replace($string)
{

    $string = utf8_encode($string);


    $hArray = [

        'ÃÄ'    => '|-',
        'Ä'     => '-',
        '³'     => '|&nbsp;&nbsp;&nbsp;',
        'À'     => '|',
    ];

    $string =  strtr(
        $string,
        $hArray
    );

    $string = utf8_decode($string);



    return $string;
}
