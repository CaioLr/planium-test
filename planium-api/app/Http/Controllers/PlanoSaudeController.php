<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanoSaudeController extends Controller
{
    public function index(){

        $planos = array();
        $json = file_get_contents("../json/plans.json");
        $data = json_decode($json);

        for ($i=0; $i < count($data); $i++) { 
            array_push($planos, $data[$i]);
        }

        return view('welcome',["planos"=>$planos]);
        
    }

    public function registro(Request $request){
#Criando beneficiarios.json
        $data = array();
        #le conteudo existente em beneficiarios.json

        $json_pre = file_get_contents("../json/beneficiarios/beneficiarios.json");
        $data_2 = json_decode($json_pre);
        
        #coloca conteudo existe em $data
        if($data_2 != null){
            for ($i=0; $i < count($data_2); $i++) { 
                array_push($data,
                    $data_2[$i]
                );
            }
        }

        #array com as pessoas
        $array_aux = array();

        for ($i=1; $i <= $request->input("quant"); $i++) { 
            array_push($array_aux,array(
                    "nome" => $request->input("name_$i"),
                    "idade"=> $request->input("age_$i"),
                    "plano"=> $request->input("plano_$i")
                )
            );
        }

        #colocando em $data o conteudo do request

        array_push($data,array(
            "quant_beneficiarios"=> $request->input("quant"),
            "beneficiarios"=> $array_aux
        ));
        
        $json = json_encode($data);

        #escrevendo no arquivo

        $file = fopen('../json/beneficiarios/beneficiarios.json','w');
        
        fwrite($file, $json);
        fclose($file);

#Criando proposta.json
        #lendo prices.json
        $prices = file_get_contents("../json/prices.json");
        $prices = json_decode($prices);


        #array com os codigos do plano escolhido
        $codigos = array();
        for ($i=1; $i <= $request->input("quant"); $i++) { 
            array_push($codigos,$request->input("plano_$i"));
        }

        #calculando quantos codigos repetidos, e guardando em $quant_codigos
        $quant_codigos = array_count_values($codigos);



        #função que calcula o preço de cada pessoa
        function getPreco($plano,$quant,$idade,$prices){
            #plano=1, quant=4, 
            $arr = array();
            #somente codigos correspondente
            for ($i=0; $i < count($prices); $i++) { 
                if($prices[$i]->codigo == $plano){
                    array_push($arr, $prices[$i]);
                }
            }

            #somente os valores de minimo_vidas
            $array_minimo_vidas = array();
            for ($i=0; $i < count($arr); $i++) { 
                array_push($array_minimo_vidas,$arr[$i]->minimo_vidas);
            }

            #indice do $arr correpondente
            $index_price =0;
            for ($i=$quant; $i >= 1 ; $i--) { 
                for($j=0; $j < count($array_minimo_vidas); $j++){
                    if ($i == $array_minimo_vidas[$j]) {
                        $index_price = $j;
                        break;
                    }
                }
                if ($i == $array_minimo_vidas[$index_price]) {
                    break;
                }
            }

            #retornando o preço de acordo com a idade
            if ($idade >= 0 and $idade <= 17 ){
                return $arr[$index_price]->faixa1;
            }elseif($idade >= 18 and $idade <= 40 ){
                return $arr[$index_price]->faixa2;
            }elseif($idade >= 41 ){
                return $arr[$index_price]->faixa3;
            }

        }

        #formatando dados arquivo proposta.json
        $array_aux_proposta = array();

        for ($i=1; $i <= $request->input("quant"); $i++) { 
            array_push($array_aux_proposta,array(
                    "nome" => $request->input("name_$i"),
                    "idade"=> $request->input("age_$i"),
                    "plano"=> $request->input("plano_$i"),
                    "preco"=> getPreco(
                        $request->input("plano_$i"),
                        $request->input("quant"),
                        $request->input("age_$i"),
                        $prices
                    )
                )
            );
        }

        $data_proposta = array();
        #le conteudo existente em beneficiarios.json

        $json_pre_proposta = file_get_contents("../json/propostas/proposta.json");
        $data_2_proposta = json_decode($json_pre_proposta);
        
        #coloca conteudo existente em $data
        if($data_2_proposta != null){
            for ($i=0; $i < count($data_2_proposta); $i++) { 
                array_push($data_proposta,
                    $data_2_proposta[$i]
                );
            }
        }

        #calculando preço total
        $preco_total = 0;
        for ($i=0; $i < count($array_aux_proposta); $i++) { 
            $preco_total += $array_aux_proposta[$i]["preco"];
        }
        
        #escrevendo no arquivo
        array_push($data_proposta,array(
            "quant_beneficiarios"=> $request->input("quant"),
            "beneficiarios"=> $array_aux_proposta,
            "preco_total"=> $preco_total
        ));
        
        $json_proposta = json_encode($data_proposta);

        #escrevendo no arquivo

        $file_proposta = fopen('../json/propostas/proposta.json','w');
        
        fwrite($file_proposta, $json_proposta);
        fclose($file_proposta);
        
        #return $preco_total;
        #return getPreco(6,2,80,$prices);


        return redirect('/')->with('msg','Registro de plano de saúde realizado com sucesso!');
        
    }
}
