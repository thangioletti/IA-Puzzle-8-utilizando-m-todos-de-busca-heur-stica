<?php
	
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	include 'funcoes.php';
	include 'Arvore.php';

	$aMatrizOriginal = array(
			array(1,2,3)
	 	,	array(4,5,6)
		,	array(7,x,8)
	);

	$aMatrizDestino = array(
			array(5,1,3)
	 	,	array(x,2,6)
		,	array(4,7,8)
	);

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
        
	}			
    
	printr(count($aArvoresDerivadas));
	$aCaminhos = fMostraCaminho($aArvores[toString($aMatrizDestino)], array());
	$aCaminhos = array_reverse($aCaminhos);
	printr($aCaminhos);
	printar($aMatrizOriginal);	
	printar($aMatrizDestino);

	

	function fMostraCaminho($oArvore, $aCaminho) {
		if ($oArvore->getOPai()) {			
			$aCaminho[] = $oArvore->getSOperacao();			
			return fMostraCaminho($oArvore->getOPai(), $aCaminho);						
		} else {
			return $aCaminho;
		}
	}