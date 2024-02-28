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
        label.textContent = 'Text'+(i+1);
        container.appendChild(label);

        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'textbox'+(i+1);
        input.placeholder = 'Text'+(i+1);
        container.appendChild(input);

        container.appendChild(document.createElement('br'));
    }
    var btn = document.createElement('button');
    btn.name='buttonn';
    btn.textContent='OK';
    container.appendChild(btn);

    
};

document.getElementById('buttonn').addEventListener('click',function(event){
    event.preventDefault();

})

function createRadioButton(p){
    var container = document.getElementById('radiobutton-container');
    container.innerHTML='';
    for(var i = 0; i < count; i++){
        var label = document.createElement('label');
        label.textContent = 'Text'+(i+1);
        container.appendChild(label);

        var radioYes = document.createElement('input');
        radioYes.type = 'radio';
        radioYes.name = 'radio_group'+(i+1);
        radioYes.value = 'yes';
        container.appendChild();

        container.appendChild(document.createElement('br'));
    }
    var btn = document.createElement('button');
    btn.name='buttonn';
    btn.textContent='OK';
    container.appendChild(btn);


}

