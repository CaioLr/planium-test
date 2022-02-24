<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Planium</title>

        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
       
    </head>
    <header>
        <div>
            <img src="https://www.planium.io/wordpress/wp-content/uploads/2018/11/logo-Planium-06.svg" width="200" height="80">
            
        </div>
    </header>
    <body>
        <div id="form-create-container" class="col-md-6 offset-md-3">
            <h1>Confirme sua Escolha</h1>
            <p>Informações sobre planos escolhidos</p>
            <form action="/registrar" method="POST">
            @csrf
                <div class="mb-2 row">
                    <label for="quant" class="col-sm-4 col-form-label">Quantidade de beneficiários:  </label>
                        <div class="col-sm-1">
                            <input type="text" readonly class="form-control-plaintext" id="quant" name="quant" value="{{ $dados[0]['quant_beneficiarios'] }}">
                        </div>
                </div>
                @php
                    $counter = 0;
                @endphp
               
                @foreach($dados[0]['beneficiarios'] as $dados2)
                @php
                    $counter++;
                @endphp
                <h4>Beneficiário</h4>
                <div class="mb-2 row">
                    <label for="name_{{$counter}}" class="col-sm-2 col-form-label">Nome:  </label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="name_{{$counter}}" name="name_{{$counter}}" value="{{ $dados2['nome'] }}">
                        </div>
                </div>
                <div class="mb-2 row">
                    <label for="age_{{$counter}}" class="col-sm-2 col-form-label">idade:  </label>
                        <div class="col-sm-1">
                            <input type="text" readonly class="form-control-plaintext" id="age_{{$counter}}" name="age_{{$counter}}" value="{{ $dados2['idade'] }}">
                        </div>
                </div>
                <div class="mb-2 row">
                    <label for="plano_{{$counter}}" class="col-sm-2 col-form-label">Plano escolhido:</label>
                        <div class="col-sm-1">
                            <input type="text" readonly class="form-control-plaintext" id="plano_{{$counter}}" name="plano_{{$counter}}" value="{{ $dados2['plano'] }}">
                        </div>
                </div>
                <div class="mb-2 row">
                    <label for="preco" class="col-sm-2 col-form-label">Preço:  </label>
                        <div class="col-sm-1">
                            <input type="text" readonly class="form-control-plaintext" id="preco" name="preco" value="{{ $dados2['preco'] }}">
                        </div>
                </div>
                @endforeach
        
                <div class="mb-2 row">
                    <label for="precototal" class="col-sm-4 col-form-label"><h4>Preço total:</h4> </label>
                        <div class="col-sm-1">
                            <input type="text" readonly class="form-control-plaintext" id="precototal" name="precototal" value="{{ $dados[0]['preco_total'] }}">
                        </div>
                </div>

                            
            <input type="submit" class="btn btn-success" value="Confirmar">
            <input type="button" id="voltar" name="voltar" class="btn btn-secondary" value="Voltar" onclick="window.history.back()">
            </form>
        </div>

    </body>
</html>
