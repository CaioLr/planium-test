<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Planium</title>

        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


       
        <style>
            header{
                background-color: gray;
            
            }
            body {
                font-family: 'Nunito', sans-serif;
            }
            #form-create-container{
                margin-top: 50px;
            }
            .form-group{
                margin-bottom: 10px;
            }
            .msg{
                background-color: #D4EDDA;
                text-align: center;
                margin-bottom: 0;
                padding: 10px;
            }
            .error_text{
                text-align: center;
            }
        </style>
    </head>
    <header>
        <div>
            <img src="https://www.planium.io/wordpress/wp-content/uploads/2018/11/logo-Planium-06.svg" width="200" height="80">
            
        </div>
    </header>
    <body>

        @if ($errors->any())
            <div class="alert alert-danger">
                <p class="error_text">Preencha todos os campos obrigatórios.</p>
            </div>
        @endif

        <div class="container-fluid">
            <div class="row">
                @if(session('msg'))
                <p class="msg">{{session('msg')}}</p>
                @endif
            </div>
        </div>
        <div id="form-create-container" class="col-md-6 offset-md-3">
            <h1>Solicite seu Plano de Saúde</h1>
            <p>Preencha seus dados abaixo</p>
            <form action="/confirmar" method="POST">
            @csrf

                <div class="accordion accordion-flush mb-2" id="accordionFlush" data-count="{{session('quant')??1}}">
                    @php
                        $quant = session('quant')??1;
                    @endphp
                    @for($i=1; $i <= $quant ;$i++)

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading_{{$i}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$i}}" aria-expanded="false" aria-controls="flush-collapse_{{$i}}">
                                    Beneficiário
                            </button>
                        </h2>
                        <div id="flush-collapse_{{$i}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlush">
                            <div class="accordion-body">
                                
                                    <div class="form-group">
                                        <label for="nome">Nome</label>
                                        <input type="text" class="form-control @error('name_'.$i) is-invalid @enderror"  id="name_{{$i}}" name="name_{{$i}}" value="{{old('name_'.$i)}}">
                                        @error('name_'.$i)
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="idade">Idade</label>
                                        <input type="number" class="form-control @error('age_'.$i) is-invalid @enderror" id="age_{{$i}}" name="age_{{$i}}" value="{{old('age_'.$i)}}">
                                        @error('age_'.$i)
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="idade">Escolha seu plano</label>
                                        <select class="form-select" id="plano_{{$i}}" name="plano_{{$i}}" aria-label="Plano:">
                                            @foreach($planos as $plano)
                                                <option value="{{$plano->codigo}}" @if(old('plano_'.$i) == $plano->codigo) selected @endif>{{$plano->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="mb-2 row">
                    <label for="quant" class="col-sm-4 col-form-label">Quantidade de beneficiários:  </label>
                        <div class="col-sm-1">
                            <input type="text" readonly class="form-control-plaintext" id="quant" name="quant" value="{{$i-1}}">
                        </div>
                </div>

            <input type="submit" class="btn btn-success" value="Enviar">
            <input type="button" id="add_pessoa" name="add_pessoa" class="btn btn-primary" value="Adicionar Beneficiário">
            <input type="button" id="delete_pessoa" name="delete_pessoa" class="btn btn-danger" value="Remover Beneficiário">
            </form>
        </div>


        <script src="{{ asset('assets/js/index.js') }}"></script>
    </body>
</html>
