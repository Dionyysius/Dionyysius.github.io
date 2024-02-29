document.getElementById('btn').addEventListener('click',function(event){
    event.preventDefault();
    var jumlah = parseInt(document.getElementById('jumlah').value);
    console.log('nilai yang dimasukan adalah : ',jumlah);
    createTextBoxes(jumlah);
});

function createTextBoxes(count){
    var container = document.getElementById('textbox-container');
    container.innerHTML='';
    for(var i = 0; i < count; i++){
        var label = document.createElement('label');
        label.textContent = 'Pilihan '+(i+1)+' : ';
        container.appendChild(label);

        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'textbox'+(i+1);
        input.placeholder = 'Pilihan '+(i+1);
        container.appendChild(input);

        container.appendChild(document.createElement('br'));
    }
    var buttonn = document.createElement('button');
    buttonn.name='buttonn';
    buttonn.textContent='OK';
    buttonn.addEventListener('click', function(){
        createRadioButton(this,count);

    });
    
    container.appendChild(buttonn);



};

function createRadioButton(button,x){
    //var container = button.parentNode;
    var container = document.getElementById('radiobutton-container');
    container.innerHTML='';
    var label = document.createElement('label');
    label.textContent = 'Pilihan : ';
    container.appendChild(label);
    for(var i = 0; i < x; i++){
        var radioYes = document.createElement('input');
        radioYes.type = 'radio';
        radioYes.name = 'Text Pilihan'+(i+1);
        radioYes.value = '';
        container.appendChild(radioYes);
        container.appendChild(document.createTextNode('Text Pilihan '));

        

        container.appendChild(document.createElement('br'));
    }
    var butn = document.createElement('button');
    butn.name='buttonnn';
    butn.textContent='OK';
    container.appendChild(butn);


};

