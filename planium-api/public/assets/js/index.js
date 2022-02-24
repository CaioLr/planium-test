
let counter = 1;


document.getElementById("add_pessoa").addEventListener("click", function() {

    let myNodeList = document.getElementById('plano_1').querySelectorAll("option");

    let option =''
    for(i=0; i< myNodeList.length;i++){
        option += `<option value="${myNodeList[i].value}">${myNodeList[i].label}</option>`;
    }


    counter++;
    let string = `

    <h2 class="accordion-header" id="flush-heading_${counter}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_${counter}" aria-expanded="false" aria-controls="flush-collapse_${counter}">
            ${counter}ª Beneficiário
        </button>
    </h2>
    <div id="flush-collapse_${counter}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlush">
        <div class="accordion-body">
            
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="name_${counter}" name="name_${counter}">
                </div>
                <div class="form-group">
                    <label for="idade">Idade</label>
                    <input type="number" class="form-control" id="age_${counter}" name="age_${counter}">
                </div>
                <div class="form-group">
                    <label for="idade">Escolha seu plano</label>
                    <select class="form-select" id="plano_${counter}" name="plano_${counter}" aria-label="Plano:">`
                       +option+
                    `
                    </select>
                </div>
        </div>
    </div>
`
    var elemento = document.getElementById('accordionFlush')
    

    let div = document.createElement("div");
    div.innerHTML =string
    div.className = 'accordion-item'

    elemento.append(div);

    document.getElementById('quant').value = counter;
    
  });

  document.getElementById("delete_pessoa").addEventListener("click", function(){
    let myNodeList = document.getElementById('accordionFlush').querySelectorAll("div.accordion-item");

    if (myNodeList.length > 1) {
        myNodeList[myNodeList.length-1].parentNode.removeChild( myNodeList[myNodeList.length-1]);
        counter--;
        document.getElementById('quant').value = counter;
    }
  });
