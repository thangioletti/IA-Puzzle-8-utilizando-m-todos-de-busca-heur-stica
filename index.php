<?php
$aMatrizOriginal = array(
    array(7, 1, 'x')
    , array(2, 4, 3)
    , array(8, 6, 5)
);

$aMatrizDestino = array(
    array(1, 2, 3)
    , array(4, 5, 6)
    , array(7, 8, 'x')
);

session_start();
if (!empty($_POST['destino'])) {
    $_SESSION['origem'] = $_POST['origem'];
    $_SESSION['destino'] = $_POST['destino'];
    $_SESSION['heuristica'] = $_POST['heuristica'];
    $_SESSION['tabuleiro'] = $_POST['tabuleiro'];
} elseif (empty($_SESSION['destino'])) {
    $_SESSION['origem'] = $aMatrizOriginal;
    $_SESSION['destino'] = $aMatrizDestino;
    $_SESSION['heuristica'] = 'quadrados';
    $_SESSION['tabuleiro'] = 'baby';
}

$aMatrizOriginal = $_SESSION['origem'];
$aMatrizDestino = $_SESSION['destino'];
$sHeuristica = $_SESSION['heuristica'];
$sTabuleiro = $_SESSION['tabuleiro'];

//MONTA DESENHO CORRETO
$aBg = array();

$i = 1;
foreach ($aMatrizDestino as $iRow => $aColumn) {
    foreach ($aColumn as $iColumn => $nElement) {
        $aBg[$nElement] = $i;
        $i++;
    }
}
?>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">        
        <link rel="stylesheet" type="text/css" href="sweet_alert/sweetalert.css">
        <link rel="stylesheet" type="text/css" href="estilo.css">
    </head>
    <body>

        <audio id="terminei">
            <source src="terminei.mpeg" type="audio/mp3" />
        </audio>
        <audio id="play">
            <source src="play.mpeg" type="audio/mp3" />
        </audio>
        <audio id="pensando">
            <source src="pensando.mpeg" type="audio/mp3" />
        </audio>
        
        <div id="loader">
            <div class="spinner">
                <div class="cube1"></div>
                <div class="cube2"></div>
            </div>
        </div>

        <div class="row top-bar">
            <div class="col-md-12">
                <h2> Puzzle 8 peças </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 text-left" id="lateral">
                <h4> Configurar </h4>
                <hr>
                <form action="?<?= $_SERVER['QUERY_STRING'] ?>" method="POST">                                  
                    <h4>Tabuleiro</h4>
                    <div class="row">         
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="baby">
                                    <input type="radio" name="tabuleiro" value="baby" id="baby" <?= $sTabuleiro == 'baby' ? 'checked' : '' ?>>
                                    <img src="images/baby.jpg">
                                </label>                        
                            </div>
                        </div>
                        <div class="col-md-3">

                            <div class="input-group">
                                <label for="homer">
                                    <input type="radio" name="tabuleiro" value="homer" id="homer" <?= $sTabuleiro == 'homer' ? 'checked' : '' ?>>                                
                                    <img src="images/homer.jpg">
                                </label>                        
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="cube">
                                    <input type="radio" name="tabuleiro" value="cube" id="cube" <?= $sTabuleiro == 'cube' ? 'checked' : '' ?>>
                                    <img src="images/cube.jpg">
                                </label>                        
                            </div>
                        </div>
                    </div> 
                    <br>
                    <h4>Matrizes</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Origem</h5>
                            <div>
                                <?php
                                $sTagShow = '<input type="text" name="origem[%s][%s]" value="%s">';
                                foreach ($aMatrizOriginal as $iLinha => $aLinha) {
                                    foreach ($aLinha as $iColuna => $sElemento) {
                                        printf(
                                                $sTagShow
                                                , $iLinha
                                                , $iColuna
                                                , $sElemento
                                        );
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Destino</h5>
                            <div>
                                <?php
                                $sTagShow = '<input type="text" name="destino[%s][%s]" value="%s">';
                                foreach ($aMatrizDestino as $iLinha => $aLinha) {
                                    foreach ($aLinha as $iColuna => $sElemento) {
                                        printf(
                                                $sTagShow
                                                , $iLinha
                                                , $iColuna
                                                , $sElemento
                                        );
                                    }
                                }
                                ?>
                            </div>                            
                        </div>
                    </div>
                    <br>
                    <h4>Heurística</h4>                
                    <div class="input-group">
                        <label for="heuristica4">
                            <input type="radio" id="heuristica4" name="heuristica" value="horizontal" <?= $sHeuristica == 'horizontal' ? 'checked' : '' ?>>
                            Busca Horizontal
                        </label>                        
                    </div>
                    <div class="input-group">
                        <label for="heuristica">
                            <input type="radio" id="heuristica" name="heuristica" value="quadrados" <?= $sHeuristica == 'quadrados' ? 'checked' : '' ?>>
                            Quadrados fora do lugar
                        </label>                        
                    </div>
                    <div class="input-group">
                        <label for="heuristica2">
                            <input type="radio" id="heuristica2" name="heuristica" value="manhattan" <?= $sHeuristica == 'manhattan' ? 'checked' : '' ?>>
                            Caminhos de Manhattan
                        </label>                        
                    </div>
                    <div class="input-group">
                        <label for="heuristica3">
                            <input type="radio" id="heuristica3" name="heuristica" value="aestrela" <?= $sHeuristica == 'aestrela' ? 'checked' : '' ?>>
                            A*
                        </label>                        
                    </div>
                    <hr>
                    <div class="text-center">         
                        <button class="btn btn-primary btn-lg" type="submit">Configurar</button>
                    </div>
                </form>
            </div>

            <div class="col-md-4">                     
                <div class="col-md-12 card">
                    <h4>Movimentos</h4>
                    <hr>
                    <div class="col-md-12" id="setas"></div>
                </div>            
                <div class="col-md-12 card">
                    <h4>Console</h4>
                    <hr>
                    <div class="col-md-12" id="console"></div>
                </div>
            </div>
            
            <div class="col-md-4 card">
                <h4>Puzzle</h4>
                <hr>
                <div class="game">
                    <?php
                    $nTop = 0;
                    foreach ($aMatrizOriginal as $iLinha => $aLinha) {
                        $nLeft = 0;
                        foreach ($aLinha as $iColuna => $sElemento) {
                            if ($sElemento != 'x') {
                                printf(
                                        '
                                            <div class="block" id="b%s" style="top: %spx; left: %spx; background-image: url(images/%s/%s.jpg);"></div>
                                            '
                                        , $sElemento
                                        , $nTop
                                        , $nLeft
                                        , $sTabuleiro
                                        , $aBg[$sElemento]
                                );
                            } else {
                                printf('<div id="espaco"  style="top: %spx; left: %spx"></div>', $nTop, $nLeft);
                            }
                            $nLeft += 105;
                        }
                        $nTop += 105;
                    }
                    ?>           
                </div>
                <br>
                <hr>
                <button id="rodar" class="btn btn-success btn-lg" >Rodar</button>
                <button id="reload" class="btn btn-default btn-lg">Organizar peças</button>
            </div>  
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="sweet_alert/sweetalert.min.js"></script>
        <script>

            function play(sId){
                audio = document.getElementById(sId);
                audio.play();
            }


            $(function () {                                

                $('#reload').on('click', function () {
                    location.reload();
                });
                
                $('[name=heuristica], [name^=destino], [name^=origem], [name=tabuleiro]').on('change click', function() {
                    $('#rodar').attr('disabled', 'disabled');
                });
                
                $('#rodar').on('click', function () {                    
                    $('#loader').fadeIn();
                    $(this).attr('disabled', 'disabled');
                    play('pensando');

                    $.ajax({
                        url: 'processar.php',
                        dataType: 'JSON',
                        method: 'POST',
                        data: {
                            'matriz-original': <?= json_encode($aMatrizOriginal) ?>,
                            'matriz-destino': <?= json_encode($aMatrizDestino) ?>,
                            'heuristica': $('[name=heuristica]:checked').val()
                        },
                        success: function (oRetorno) {
                            setTimeout(function() {
                                $('#loader').fadeOut();
                                play('play');
                                if (oRetorno.msg == 'erro') {
                                    swal({
                                        title: "Erro!",
                                        text: "Muito complexo para processar.",
                                        type: "error",
                                        confirmButtonText: "Ok"
                                    });
                                    return true;
                                }
                                $('#console').html(oRetorno.console);
                                jMontaPuzzle(oRetorno.caminhos, 0);
                            }, 1500);
                        }
                    });
                });
            });

            function jGetIdMover(iTop, iLeft) {
                return $('.block').filter(function () {
                    var nTop = parseInt($(this).css('top'));
                    var nLeft = parseInt($(this).css('left'));
                    return nTop === iTop && nLeft == iLeft;
                });
            }

            function jMontaPuzzle(aCaminhos, i) {
                if (i == aCaminhos.length) {
                    play('terminei');
                    return;                    
                }

                var sCaminho = aCaminhos[i];
                var nTop = parseInt($('#espaco').css('top'));
                var nLeft = parseInt($('#espaco').css('left'));
                var sClass = '';

                if (sCaminho == 'direita') {
                    nLeft -= 105;
                    sId = jGetIdMover(nTop, nLeft).attr('id');
                    jDireita(sId);
                    sClass = 'glyphicon-arrow-right';
                } else if (sCaminho == 'esquerda') {
                    nLeft += 105;
                    sId = jGetIdMover(nTop, nLeft).attr('id');
                    jEsquerda(sId);
                    sClass = 'glyphicon-arrow-left';
                } else if (sCaminho == 'bottom') {
                    nTop -= 105;
                    sId = jGetIdMover(nTop, nLeft).attr('id');
                    jBottom(sId);
                    sClass = 'glyphicon-arrow-down';
                } else if (sCaminho == 'top') {
                    nTop += 105;
                    sId = jGetIdMover(nTop, nLeft).attr('id');
                    jTop(sId);
                    sClass = 'glyphicon-arrow-up';
                }

                $('#espaco').css({'top': nTop, 'left': nLeft});
                $('<span/>').addClass('glyphicon').addClass(sClass).appendTo('#setas');

                setTimeout(function () {
                    i++;
                    jMontaPuzzle(aCaminhos, i);
                }, 200);
            }

            function jDireita(sId) {
                var nLeft = parseInt($('#' + sId).css('left'));

                if (nLeft == 210) {
                    return;
                }

                $('#' + sId).animate({
                    left: '+=105'
                }, 50);
            }

            function jEsquerda(sId) {
                var nLeft = parseInt($('#' + sId).css('left'));

                if (nLeft == 0) {
                    return;
                }

                $('#' + sId).animate({
                    left: '-=105'
                }, 50);
            }

            function jTop(sId) {
                var nTop = parseInt($('#' + sId).css('top'));

                if (nTop == 0) {
                    return;
                }

                $('#' + sId).animate({
                    top: '-=105'
                }, 50);
            }

            function jBottom(sId) {
                var nTop = parseInt($('#' + sId).css('top'));

                if (nTop == 210) {
                    return;
                }

                $('#' + sId).animate({
                    top: '+=105'
                }, 50);
            }
        </script>
    </body>
</html>