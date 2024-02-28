document.getElementById('btn').addEventListener('click',function(event){
    event.preventDefault();
    var jumlah = parseInt(document.getElementById('jumlah').value);
    console.log('nilai yang dimasukan adalah : ',jumlah)
});

function createTextBoxes(count){
    var container = document.getElementById('textbox-container');
    container.innerHTML='';
    for(var i = 0; i < count; i++){
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'text'+(i+1);
        input.placeholder = 'Text'+(i+1);
        container.appendChild(input);
    }
};