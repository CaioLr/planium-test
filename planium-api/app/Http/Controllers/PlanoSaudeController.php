<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PlanoSaudeRequest;

use Illuminate\Support\Facades\Validator;

class PlanoSaudeController extends Controller
{
    #função que calcula o preço de cada pessoa
    private function getPreco($plano,$quant,$idade,$prices){
    
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

    public function index(){

        #recebendo plans.json
        $planos = array();
        $json = file_get_contents("../storage/app/json/plans.json");
        $data = json_decode($json);

        for ($i=0; $i < count($data); $i++) { 
            array_push($planos, $data[$i]);
        }

        return view('index',["planos"=>$planos]);
        
    }

    public function confirma(Request $request){

        #recebendo plans.json
        $planos = array();
        $json = file_get_contents("../storage/app/json/plans.json");
        $data = json_decode($json);

        $plans_count = count($data);

        #Validando os inputs
        $array_validator = array();
        $values = array();
        for ($i=1; $i <= $request->input("quant"); $i++) { 
            array_push($array_validator,
                "name_$i",
                "age_$i",
                "plano_$i"
            );
            array_push($values,
                "required",
                "required|numeric|integer|min:0",
                "required|numeric|between:1,$plans_count"
            );
           
        }
        $array_validator = array_combine($array_validator,$values);

        
        $validator = Validator::make($request->all(),$array_validator);
   

        if ($validator->fails()) {
            return redirect('/')
                        ->with("quant",$request->input("quant"))
                        ->withErrors($validator)
                        ->withInput($request->except('_token'));
        }

        #lendo prices.json
        $prices = file_get_contents("../storage/app/json/prices.json");
        $prices = json_decode($prices);

        #lendo plans.json
        $plans = file_get_contents("../storage/app/json/plans.json");
        $plans = json_decode($plans);

        $plans_aux = array();
        for ($i=1; $i <= count($plans); $i++) { 
           array_push($plans_aux, array(
                "$i" => $plans[$i-1]->nome
           ));
        }

        #formatando dados arquivo proposta.json
        $array_aux_proposta = array();
        for ($i=1; $i <= $request->input("quant"); $i++) { 
            $num = $request->input("plano_$i");
            array_push($array_aux_proposta,array(
                    "nome" => $request->input("name_$i"),
                    "idade"=> $request->input("age_$i"),
                    "plano"=> $plans_aux[$num-1]["$num"],
                    "preco"=> $this->getPreco(
                        $request->input("plano_$i"),
                        $request->input("quant"),
                        $request->input("age_$i"),
                        $prices
                    )
                )
            );
        }

        #calculando preço total
        $preco_total = 0;
        for ($i=0; $i < count($array_aux_proposta); $i++) { 
            $preco_total += $array_aux_proposta[$i]["preco"];
        }

        $data_proposta = array();

        array_push($data_proposta,array(
            "quant_beneficiarios"=> $request->input("quant"),
            "beneficiarios"=> $array_aux_proposta,
            "preco_total"=> $preco_total
        ));


        return view('confirma',["dados"=>$data_proposta]);
        

    }

    public function registro(Request $request){

    #Criando beneficiarios.json
        $data = array();
        #le conteudo existente em beneficiarios.json

        $json_pre = file_get_contents("../storage/app/json/beneficiarios/beneficiarios.json");
        $data_2 = json_decode($json_pre);
        
        $id = 0;
        #coloca conteudo existe em $data
        if($data_2 != null){
            for ($i=0; $i < count($data_2); $i++) { 
                array_push($data,
                    $data_2[$i]
                );
            }
            $id = count($data_2);
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
            "id"=> $id,
            "quant_beneficiarios"=> $request->input("quant"),
            "beneficiarios"=> $array_aux
        ));
        
        $json = json_encode($data);

        #escrevendo no arquivo

        $file = fopen('../storage/app/json/beneficiarios/beneficiarios.json','w');
        
        fwrite($file, $json);
        fclose($file);

    #Criando proposta.json
        #lendo prices.json
        $prices = file_get_contents("../storage/app/json/prices.json");
        $prices = json_decode($prices);


        #array com os codigos do plano escolhido
        $codigos = array();
        for ($i=1; $i <= $request->input("quant"); $i++) { 
            array_push($codigos,$request->input("plano_$i"));
        }

        #calculando quantos codigos repetidos, e guardando em $quant_codigos
        $quant_codigos = array_count_values($codigos);

        #formatando dados arquivo proposta.json
        $array_aux_proposta = array();

        for ($i=1; $i <= $request->input("quant"); $i++) { 
            array_push($array_aux_proposta,array(
                    "nome" => $request->input("name_$i"),
                    "idade"=> $request->input("age_$i"),
                    "plano"=> $request->input("plano_$i"),
                    "preco"=> $this->getPreco(
                        $request->input("plano_$i"),
                        $request->input("quant"),
                        $request->input("age_$i"),
                        $prices
                    )
                )
            );
        }

        $data_proposta = array();
        #le conteudo existente em proposta.json

        $json_pre_proposta = file_get_contents("../storage/app/json/propostas/proposta.json");
        $data_2_proposta = json_decode($json_pre_proposta);
        
        #coloca conteudo existente em $data_proposta
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
            "id"=> $id,
            "quant_beneficiarios"=> $request->input("quant"),
            "beneficiarios"=> $array_aux_proposta,
            "preco_total"=> $preco_total
        ));
        
        $json_proposta = json_encode($data_proposta);

        #escrevendo no arquivo

        $file_proposta = fopen('../storage/app/json/propostas/proposta.json','w');
        
        fwrite($file_proposta, $json_proposta);
        fclose($file_proposta);

        return redirect('/')->with('msg','Registro de plano de saúde realizado com sucesso!');
    }

}
