
<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8"></meta>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Terminal Remoto</title>
        <!--CSS-->
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css"></link>
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap-theme.css"></link>
        <!--JS-->
        <script src="assets/plugins/bootstrap/js/jquery.min.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.PutCursosAtEnd.js"></script>

        <style>

            body {overflow-y:scroll;}

            html, body {
                height:100%;
            }

            .console-result
            {
                background-color:black!important;
            }


            .console-response
            {

                margin-left: 10px;
                padding-left: 10px;
                border-left: 1px solid red;

            }

        </style>

    </head>
    <body class="console-result">

        <nav class="navbar-fixed-top" style="height:10px !important;">
            <span class="text-danger" style="background-color:black;">Pasta atual:</span>
            <span class="text-primary" style="background-color:black;" id="pasta-atual">Inicializando...</span>
        </nav>

        <div class="container" style="padding-bottom: 40px;">


            <div id="console-result">

            </div>


        </div>

        <div class="footer navbar-fixed-bottom">
            <input class="form-control" type="text" name="command"/>
        </div>

    </body>
</html>

<script>
    //variavel global;

    AutoComplete = [];

    historicoComandos = [];

    historicoComandosAtual = 0;

    $(document).ready(function(){



        $(document).keydown(function(event){
            //ctrl = 17
            //L = 76
            //C = 67

            //CTRL + C => LIMPA INPUT
            if(
                event.ctrlKey && event.keyCode == 67
                &&
                (
                    window.getSelection() == ""
                    ||
                    window.getSelection().focusNode.firstElementChild == undefined
                    ||
                    window.getSelection().focusNode.firstElementChild.name == undefined
                    ||
                    window.getSelection().focusNode.firstElementChild.name != 'command'

                )
            )
            {
                historicoComandosAtual = 0;

                $("input[name='command']").val("");
            }
            //CTRL + l => LIMPA TELA
            else if(event.ctrlKey && event.keyCode == 76)
            {
                $("#console-result").html("");
                event.preventDefault();
            }

        });

        $("#pasta-atual").html(run('getcwd')['dir']);


        $("input[name='command']").keydown(function(event){


            switch (event.keyCode) {
                //ENTER
                case 13:


                    if($(this).val() != "")
                        historicoComandos.push($(this).val());

                    historicoComandosAtual = 0;

                    if($(this).val() == "cls" || $(this).val() == "clear")
                    {
                        $(this).val("");
                        $("#console-result").html("");
                        return false;
                    }


                    $("#console-result").html($("#console-result").html() + "<br><span class='console-command text-success' onclick=\"toCommand(this.innerHTML);\">" + $(this).val()  + "</span>");

                    $('html, body').animate({scrollTop:$(document).height()}, 'slow');

                    $("#console-result").html($("#console-result").html() + "<br><div class='console-response text-danger'>" + run($(this).val())['command-response'] + "</div>");

                    $('html, body').animate({scrollTop:$(document).height()}, 'slow');

                    $(this).val("");

                    break;

                //TAB
                case 9:

                    var lastWord = $(this).val().split(" ").slice(-1)[0];

                    if(lastWord)
                    {
                        for(word in AutoComplete)
                        {
                            if(AutoComplete[word].toLowerCase().indexOf(lastWord.toLowerCase()) === 0)
                            {

                                $(this).val($(this).val().substr(0,$(this).val().length - lastWord.length) + AutoComplete[word]);

                            }

                        }
                    }

                    event.preventDefault();

                    break;

                //UP
                case 38:

                    if(historicoComandosAtual < historicoComandos.length)
                    {

                        var aux = historicoComandos.length - (++historicoComandosAtual);

                        $(this).val(historicoComandos[aux]);
                        $(this).putCursorAtEnd();


                        event.preventDefault();

                    }

                    break;

                //DOWN
                case 40:

                    if(historicoComandosAtual > 1)
                    {

                        var aux = historicoComandos.length - (--historicoComandosAtual);

                        $(this).val(historicoComandos[aux]);
                        $(this).putCursorAtEnd();

                        event.preventDefault();

                    }

                    break;





            }



        });

    });

    function toCommand(text)
    {
        $("input[name='command']").val(text);
    }

    function run(Ccommand)
    {

        if(Ccommand == "")
            return hArray['command-response'] = '';


        resultado = "";

        $.ajax({
            type: "POST",
            url: 'ajax/send.commands.php',
            data: {
                command: Ccommand,
                dir: $("#pasta-atual").html()
            },
            async: false,
            success: function(data){
                resultado = data;
            }
        });

        resultado = JSON.parse(resultado)

        resultado['command-response'] = atob(resultado['command-response']);

        AutoComplete = resultado['contents'];

        $("#pasta-atual").html(resultado['dir']);

        return resultado;
    }

</script>
