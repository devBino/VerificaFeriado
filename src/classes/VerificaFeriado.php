<?php
namespace Feriado;
/**
 * @author Fernando Bino Machado
 * @description: Classe para buscar feriados de um determinado ano,estado,cidade
 * consumindo os dados da api https://api.calendario.com.br/
 * @see https://github.com/devBino/VerificaFeriado
*/

class VerificaFeriado{
   public $url;
   private $estado;
   private $cidade;
   private $token;
   private $ano;
   private $json;
   
   /**
    * @author Fernando Bino Machado
    * @description Construtor recebe estado,cidade,ano,json para instanciar essa classe e poder utilizar seus métodos
    * @param string $estado = estado desejado ex: PR, SP, RS ...
    * @param string $cidade = cidade desejada ex: CAMPO_LARGO, CURITIBA ...
    * @param int $ano = ano deseja ex: 2019
    * @param boolean $json = se for true o retorno da api sera json se for false o retorno será em xml
    * @example $verifica = new VerificaFeriado('SC','ITAPOA',2019,true);
   */

    public function __construct($estado="PR",$cidade="CAMPO_LARGO",$ano=0,$json=true){
        
        //trata o ano caso tenha sido omitido
        $validaAno = ( $ano != 0 ) ? $ano : date('Y',time());

        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->ano = $validaAno;
        $this->json = $json;
        $this->url = "https://api.calendario.com.br/";
    }

    /**
    * @author Fernando Bino Machado
    * @description recebe e seta o token na classe
    */
    public function setToken($strToken=null){
        if(!is_null($strToken)){
            $this->token = $strToken;
        }
    }

    /**
    * @author Fernando Bino Machado
    * @description monta a url para buscar os feriados na api
    * @return $this->url;
    */
    public function makeUrl(){
        $mudaUrl = $this->url;

        //define sinal inical para receber parametros deve ser ? ou &
        $separadorParametros = "?";

        //caso o parametro json seja verdadeiro deve retornar um json então precisa passar para url
        if( $this->json ){
            $mudaUrl.= $separadorParametros."json=".$this->json;
        }else{
            $mudaUrl.="?";
        }

        //verifica se a url já começou a receber parametros para então mudar o separadorParametros para &
        $jaRecebeu = strripos($mudaUrl,"?");

        if( $jaRecebeu !== false ){
            $separadorParametros = "&";
        }else{
            $separadorParametros = "?";
        }

        //aplica na url os demais parametros
        $mudaUrl.= $separadorParametros."ano=".$this->ano;
        $mudaUrl.= $separadorParametros."estado=".$this->estado;
        $mudaUrl.= $separadorParametros."cidade=".$this->cidade;
        $mudaUrl.= $separadorParametros."token=".$this->token;

        $this->url = $mudaUrl;

        return $this->url;
    }

    /**
    * @author Fernando Bino Machado
    * @description monta a url e depois busca os feriados na api com a biblioteca curl
    * @return: se $this->json = true retorna um json com os feriados
    *          se $this->json = false retorna um xml com a reposta padrão da api
    */
    public function buscaFeriados(){
        $urlBusca = $this->makeUrl();

        //inicia o curl
        $curl = curl_init($urlBusca);

        //seta opções
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);      
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //pega a resposta da execução da curl
        $resposta = curl_exec($curl);

        curl_close($curl);

        if( $this->json ){
            return json_decode($resposta);
        }else{
            return $resposta;
        }
    }

    /**
    * @author Fernando Bino Machado
    * @description recebe a resposta da api e monta um array com as datas dos feriados
    * @param json/xml $feriados = resposta da api
    * @param boolen $returnTimesTamps = se for for verdadeiro retorna um array com timestamps das datas, se não retorna as datas normais
    * @return: dependendo de $this->json, retorna array $dadosFeriados['inicio'=>'data ou timestamps', 'fim'=>'data ou timestamps','tipo'=>'','tipoCod'=>'','nome'=>'']
    */
    public function makeArrayData($feriados=[], $returnTimesTamps=false){
        if( $this->json ){
            if( count($feriados) ){
            
                $dadosFeriados = [];
                
                foreach($feriados as $num => $val){
                    
                    $dataApi = str_replace('/','-',$val->date);
                    $dataInicio = date("Y-m-d", strtotime($dataApi)). " 00:00:00";
                    $dataFim = date("Y-m-d", strtotime($dataApi)). " 23:59:59";

                    if( !$returnTimesTamps ){
                        $dadosFeriados[] = ['inicio'=>$dataInicio, 'fim'=>$dataFim,'tipo'=>$val->type,'tipoCod'=>$val->type_code,'nome'=>$val->name];
                    }else{
                        $dadosFeriados[] = ['inicio'=>strtotime($dataInicio),'fim'=>strtotime($dataFim),'tipo'=>$val->type,'tipoCod'=>$val->type_code,'nome'=>$val->name];
                    }
                    
                }
                
                return $dadosFeriados;

            }else{
                return [];
            }
        }else{
            return $feriados;
        }
    }

    /**
     * @author Fernando Bino Machado
     * @description verifica se uma determinada data está entre os feríados do ano se estiver retorna 
     * um array com os dados do feriado
     * @param array $feriados = dados sobre as datas retornadas da api
     * @return array $dadosFeriado;
    */
    public function arrayFeriado($feriados=[],$data=0){
        $dadosFeriado = [];

        if( count($feriados) && $data != 0 ){
            
            foreach( $feriados as $num => $val ){
                if( $data >= $val['inicio'] && $data <= $val['fim'] ){
                    $dadosFeriado = $val;
                    break;
                }
            }

            return $dadosFeriado;
        }else{
            return $dadosFeriado;
        }
    }


}