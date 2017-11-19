<?php

class Arvore {

    private $aMatriz;    
    private $oPai;
    private $sToString;
    private $sOperacao;
    private $iNivel;

    public function setAMatriz($aMatriz) {
        $this->aMatriz = $aMatriz;
    } 

    public function getAMatriz(){
        return $this->aMatriz;
    }
    
    public function setOPai($oPai) {
        $this->oPai = $oPai;
    }

    public function getOPai() {
        return $this->oPai;
    }

    public function setSToString($sToString){
        $this->sToString = $sToString;
    }

    public function getSToString(){
        return $this->sToString;
    }

    public function setSOperacao($sOperacao){
        $this->sOperacao = $sOperacao;
    }

    public function getSOperacao(){
        return $this->sOperacao;
    }
}