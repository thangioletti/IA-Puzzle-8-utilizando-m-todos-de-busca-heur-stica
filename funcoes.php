<?php

	function printar($aArray) {
		echo '<div style="font-size: 18px; width: 300px; float: left;">';		
		foreach ($aArray as $aLinha){
			foreach($aLinha as $iColuna){
				printf('%s ', $iColuna);
			}
			echo '<br>';
		}	
		echo '</div>';			
	}

	function fVerificaDireita($aArray) {
		list($i, $j) = fProcuraPosicao('x', $aArray);				
		return $j > 0;
	}

	function fVerificaEsquerda($aArray) {
		list($i, $j) = fProcuraPosicao('x', $aArray);				
		$iSize = count($aArray)-1;
		return $j < $iSize;
	}

	function fVerificaTopo($aArray) {
		list($i, $j) = fProcuraPosicao('x', $aArray);				
		$iSize = count($aArray)-1;
		return $i < $iSize;
	}

	function fVerificaBaixo($aArray) {
		list($i, $j) = fProcuraPosicao('x', $aArray);
		$iSize = count($aArray)-1;
		return $i > 0;
	}

	function fMoveDireita($aArray){
		
		if (!fVerificaDireita($aArray)) {
			return false;
		}
		
		list($i, $j) = fProcuraPosicao('x', $aArray);

		$k = $j-1;	
		$aArray[$i][$j] = $aArray[$i][$k];
		$aArray[$i][$k] = 'x';
		
		return $aArray;

	}

	function fMoveEsquerda($aArray){
		
		if (!fVerificaEsquerda($aArray)) {
			return false;
		}
		
		list($i, $j) = fProcuraPosicao('x', $aArray);

		$k = $j+1;	
		$aArray[$i][$j] = $aArray[$i][$k];
		$aArray[$i][$k] = 'x';
		
		return $aArray;
				
	}

	function fMoveTopo($aArray){
		
		if (!fVerificaTopo($aArray)) {
			return false;
		}
		
		list($i, $j) = fProcuraPosicao('x', $aArray);

		$k = $i+1;		
		$aArray[$i][$j] = $aArray[$k][$j];
		$aArray[$k][$j] = 'x';
		
		return $aArray;
		
	}

	function fMoveBaixo($aArray){
	
		if (!fVerificaBaixo($aArray)) {
			return false;
		}
		
		list($i, $j) = fProcuraPosicao('x', $aArray);

		$k = $i-1;		
		$aArray[$i][$j] = $aArray[$k][$j];
		$aArray[$k][$j] = 'x';
		
		return $aArray;
			
	}

	function fProcuraPosicao($sProcurar, $aArray) {
		foreach ($aArray as $iKey => $aLinha) {								
			$iKey2 = array_search($sProcurar, $aLinha);
			if (fNotEmpty($iKey2)) {
				return [$iKey, $iKey2];
			}
		}
	}


	function fNotEmpty($var) {
    	return ($var==="0"||$var===0||$var);
	}

	function toString($aArray){		
		foreach($aArray as $iKey => $aLinha) {
			$aArray[$iKey] = implode($aLinha); 
		}		
		return implode($aArray);
	}

	function printr($aArray) {
		echo '<pre>';
		print_r($aArray);
		echo '</pre>';
	}

	function derivar($oArvorePai, $aArvoresDerivadas) {
		
		$aMatriz = $oArvorePai->getAMatriz();
		$aCaminhos = array();		

		//Verifica se pode mover a direita
		if (($aDireita = fMoveDireita($aMatriz)) && !in_array(toString($aDireita), $aArvoresDerivadas)) {					
			$oNovaArvore = new Arvore();
			$oNovaArvore->setAMatriz($aDireita);
			$oNovaArvore->setSToString(toString($aDireita));
			$oNovaArvore->setOPai($oArvorePai);
			$oNovaArvore->setSOperacao('direita');						
			$aCaminhos[] = $oNovaArvore;				
		}

		//Verifica se pode mover a esquerda
		if (($aEsquerda = fMoveEsquerda($aMatriz)) && !in_array(toString($aEsquerda), $aArvoresDerivadas)) {					
			$oNovaArvore = new Arvore();
			$oNovaArvore->setAMatriz($aEsquerda);
			$oNovaArvore->setSToString(toString($aEsquerda));
			$oNovaArvore->setOPai($oArvorePai);	
			$oNovaArvore->setSOperacao('esquerda');		
			$aCaminhos[] = $oNovaArvore;		
		}

		//Verifica se pode mover para cima
		if (($aTopo = fMoveTopo($aMatriz)) && !in_array(toString($aTopo), $aArvoresDerivadas)) {					
			$oNovaArvore = new Arvore();
			$oNovaArvore->setAMatriz($aTopo);
			$oNovaArvore->setSToString(toString($aTopo));
			$oNovaArvore->setOPai($oArvorePai);	
			$oNovaArvore->setSOperacao('top');	
			$aCaminhos[] = $oNovaArvore;		
		}

		//Verifica se pode mover para baixo
		if (($aBaixo = fMoveBaixo($aMatriz)) && !in_array(toString($aBaixo), $aArvoresDerivadas)) {					
			$oNovaArvore = new Arvore();
			$oNovaArvore->setAMatriz($aBaixo);
			$oNovaArvore->setSToString(toString($aBaixo));
			$oNovaArvore->setOPai($oArvorePai);
			$oNovaArvore->setSOperacao('bottom');		
			$aCaminhos[] = $oNovaArvore;		
		}		

		return $aCaminhos;
	}

	function fPosicoesIguais($aMatrizAtual, $aMatrizFinal) {
		$iValorFinal = 0;
		foreach ($aMatrizAtual as $iLinha => $aLinha) {
			foreach ($aLinha as $iColuna => $nValor) {
				if ($nValor == $aMatrizFinal[$iLinha][$iColuna]) {
					$iValorFinal++;
				}
			}
		}
		return $iValorFinal;
	}

	function fCaminhosManhattan($aMatrizAtual, $aMatrizFinal) {
		$iValorFinal = 0;
		foreach ($aMatrizAtual as $iLinha => $aLinha) {
			foreach ($aLinha as $iColuna => $nValor) {				
				list($i, $j) = fProcuraPosicao($nValor, $aMatrizFinal);
				$iValorFinal += positivo($i - $iLinha)+positivo($j - $iColuna);
			}
		}
		return $iValorFinal;
	}

	function fAEstrela($aMatrizAtual, $aMatrizFinal) {
		return fCaminhosManhattan($aMatrizAtual, $aMatrizFinal) + (9-fPosicoesIguais($aMatrizAtual, $aMatrizFinal));
	}
	
	function fMostraCaminho($oArvore, $aCaminho) {
		if ($oArvore->getOPai()) {			
			$aCaminho[] = $oArvore->getSOperacao();			
			return fMostraCaminho($oArvore->getOPai(), $aCaminho);						
		} else {
			return $aCaminho;
		}
	}

	function positivo($num) {
  	  	settype($num,"integer");
		$arm = explode("-",$num);
		foreach ($arm as $numer){
			$armi = $numer;
		}
		return($armi);
	}

	function fTamanhoCaminho($aArvore) {
		$aCaminhos = fMostraCaminho($aArvore, array());
		return count($aCaminhos);
	}
