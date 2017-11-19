<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

include 'funcoes.php';
include 'Arvore.php';

list($usec, $sec) = explode(' ', microtime());
$script_start = (float) $sec + (float) $usec;

$aMatrizOriginal = $_POST['matriz-original'];
$aMatrizDestino = $_POST['matriz-destino'];


$sDestino = toString($aMatrizDestino);

$oArvorePai = new Arvore();
$oArvorePai->setAMatriz($aMatrizOriginal);

//Aqui guarda as arvores
$aArvores = array();
$aArvores[toString($oArvorePai->getAMatriz())] = $oArvorePai;

//Aqui guarda as arvores que já foram derivadas
$aArvoresDerivadas = array();

//Aqui guarda as arvores que precisam ser derivadas
$aArvoresDerivar = array();
$aArvoresDerivar[] = toString($oArvorePai->getAMatriz());
$bParar = false;

while (!$bParar) {

    //Sempre pega a arvore do topo para derivar
    $iKey = key($aArvoresDerivar);
    $sArvore = $aArvoresDerivar[$iKey];
    $oArvore = $aArvores[$sArvore];
    
    //Deriva a arvore
    $aDerivaveis = derivar($oArvore, $aArvoresDerivadas);
    $aArvoresDerivadas[] = toString($oArvore->getAMatriz());
    unset($aArvoresDerivar[$iKey]); //Remove do array das que precisam ser derivadas ainda

    foreach ($aDerivaveis as $oArvore) {

        //Garante que vai parar se encontrou a correta
        if ($bParar) {
            continue;
        }

        $sToString = toString($oArvore->getAMatriz());

        //Verifica se é hora de parar
        if ($sToString == toString($aMatrizDestino)) {
            $bParar = true;
        }

        //Caso a possivel arvore já não foi derivada adiciona a fila de derivação
        if (!in_array($sToString, $aArvoresDerivar)) {
            $aArvoresDerivar[] = $sToString;
            $aArvores[$sToString] = $oArvore;
        }
    }

    if ($_POST['heuristica'] != 'horizontal') {
        //Aplicando Heuristica
        if ($_POST['heuristica'] == 'quadrados') {

            $aArvoresOrdenar = array();
            foreach ($aArvoresDerivar as $sToStringHeuristica) {
                $aArvoresOrdenar[$sToStringHeuristica] = 9 - fPosicoesIguais($aArvores[$sToStringHeuristica]->getAMatriz(), $aMatrizDestino);
            }
        } elseif ($_POST['heuristica'] == 'manhattan') {

            $aArvoresOrdenar = array();
            foreach ($aArvoresDerivar as $sToStringHeuristica) {
                $aArvoresOrdenar[$sToStringHeuristica] = fCaminhosManhattan($aArvores[$sToStringHeuristica]->getAMatriz(), $aMatrizDestino);
            }
        } elseif ($_POST['heuristica'] == 'aestrela') {

            $aArvoresOrdenar = array();
            foreach ($aArvoresDerivar as $sToStringHeuristica) {
                $aArvoresOrdenar[$sToStringHeuristica] = fTamanhoCaminho($aArvores[$sToStringHeuristica]) + fAEstrela($aArvores[$sToStringHeuristica]->getAMatriz(), $aMatrizDestino);
            }
        }

        //Ordena
        asort($aArvoresOrdenar);
        //Recria com a ordenação heuristica                
        $aArvoresDerivar = array();
        foreach ($aArvoresOrdenar as $sToString => $nValorHeuristico) {
            $aArvoresDerivar[] = $sToString;
        }
    }
    
    if (count($aArvores) > 7000) {
        $bParar = true;
        $sMsg = 'erro';
    }
}   

if ($sMsg == 'erro'){
    $aRetorno['msg'] = $sMsg;
} else {
    
    list($usec, $sec) = explode(' ', microtime());
    $script_end = (float) $sec + (float) $usec;
    $elapsed_time = round($script_end - $script_start, 5);
    
    $aCaminhos = fMostraCaminho($aArvores[toString($aMatrizDestino)], array());
    $aCaminhos = array_reverse($aCaminhos);

    $aRetorno = array();

    $aRetorno['console'] = 'Nós encontrados: %s <br> Nós expandidos: %s <br> Tamanho do caminho: %s <br> Tempo: %s s<br> Memória: %s Mb';

    $aRetorno['console'] = sprintf(
            $aRetorno['console']
            , count($aArvores)
            , count($aArvoresDerivadas)
            , count($aCaminhos)
            , $elapsed_time
            , round(((memory_get_peak_usage(true) / 1024) / 1024), 2)
    );

    $aRetorno['caminhos'] = $aCaminhos;
}
echo json_encode($aRetorno);
exit;
