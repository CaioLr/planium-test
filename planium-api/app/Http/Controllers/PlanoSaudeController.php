<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanoSaudeController extends Controller
{
    public function index(){
        return view('welcome');
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

        return redirect('/')->with('msg','Registro de plano de saúde realizado com sucesso!');
        
    }
}
