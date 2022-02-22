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
        $data = array();

        $json_pre = file_get_contents("../json/beneficiarios/beneficiarios.json");
        $data_2 = json_decode($json_pre);
           
        if($data_2 != null){
            for ($i=0; $i < count($data_2); $i++) { 
                array_push($data,
                    $data_2[$i]
                );
            }
        }

        $array_aux = array();

        for ($i=1; $i <= $request->input("quant"); $i++) { 
            array_push($array_aux,array(
                    "nome" => $request->input("name_$i"),
                    "idade"=> $request->input("age_$i"),
                    "plano"=> $request->input("plano_$i")
                )
            );
        }

        
        array_push($data,array(
            "quant_beneficiarios"=> $request->input("quant"),
            "beneficiarios"=> $array_aux
        ));
        
        $json = json_encode($data);

        $file = fopen('../json/beneficiarios/beneficiarios.json','w');
        
        fwrite($file, $json);
        fclose($file);

        $prices = file_get_contents("../json/prices.json");
        $prices = json_decode($prices);

        function getPreco($plano,$quant,$prices){
            $arr = array();
            for ($i=0; $i < count($prices); $i++) { 
                if($prices[$i]->codigo == $plano){
                    array_push($arr, $prices[$i]);
                }
            }
            return $arr;

        }


        
        

        return getPreco(1,2,$prices);


        #return redirect('/')->with('msg','Registro de plano de saúde realizado com sucesso!');
        
    }
}
