var tampilanInputs;
var data = [];
var radio = [];
var pilihan;


var nama = document.getElementById('username');
var jumlah = document.getElementById('jumlah');
var button1 = document.getElementById('btn');

nama.addEventListener('input',validateInputs);
jumlah.addEventListener('input',validateInputs);

function validateInputs(){
    if(nama !== '' && jumlah !== ''){
        button1.disabled = false;
    }else{
        button1.disabled = true;
    }
}


button1.addEventListener('click', function(event){
    this.remove();
    event.preventDefault(); 
    textInput();
});

function textInput() {
    var jumlah = parseInt(document.getElementById("jumlah").value); // Mengonversi nilai ke tipe integer
    tampilanInputs = document.getElementById("tampilanInputs");

    tampilanInputs.innerHTML = '';

    var input = [];

    for (var i = 1; i <= jumlah; i++) {
        var label = document.createElement("label");
        label.id = "label1";
        label.innerHTML = "Pilihan " + i + ":";

        input[i-1] = document.createElement("input");
        input[i-1].type = "text"; // Mengubah input.type menjadi "text"
        input[i-1].name = "teksTampilan" + i;
        input[i-1].id = "teksTampilan";

        tampilanInputs.appendChild(label);
        tampilanInputs.appendChild(input[i-1]);
        tampilanInputs.appendChild(document.createElement("br"));
    }
    var button = document.createElement("button");
    button.type = "button";
    button.id = "buttonOK";
    button.innerHTML = "OK";

    tampilanInputs.appendChild(button);
    tampilanInputs.appendChild(document.createElement("br"));
    tampilanInputs.appendChild(document.createElement("br"));

    document.getElementById("buttonOK").addEventListener("click", function(e){
        this.remove();
        for (var i = 1; i <= jumlah; i++) {
            data[i-1] = input[i-1].value;
        }
        textRadio();
    });

}

function textRadio() {
    var jumlah = parseInt(document.getElementById("jumlah").value); // Mengonversi nilai ke tipe integer

    console.log(data);

    for (var i = 1; i <= jumlah; i++) {
        var label = document.createElement("label");
        label.innerHTML ='<input type = "Radio" name = "teksTampilan" id = "Radio'+i+'">' + data[i-1];
        label.setAttribute("for", "pilihan" + i);

        tampilanInputs.appendChild(label);
        tampilanInputs.appendChild(document.createElement("br"));

    }

    for (var i = 1; i <= jumlah; i++) { // Memperbaiki perulangan di sini
        radio[i-1] = document.getElementById("Radio"+i);
        radio[i-1].addEventListener("click", function (e) {
            var radios = document.getElementsByName(this.name);
            
            radios.forEach(function (x) {
                x.checked = false;
            });
            
            this.checked = true;
        });
    }

    console.log(radio);

    var button = document.createElement("button");
    button.name = "buttonLast";
    button.textContent = "OK";
    button.id = "buttonLast";
    button.type = "button";

    tampilanInputs.appendChild(button);
    tampilanInputs.appendChild(document.createElement("br"));

    var div = document.createElement("div");
    div.id = "Last";
    document.getElementById("buttonLast").addEventListener("click", function(e){
        this.remove();
        pilihan = '';
        console.log(e);
        for (var i = 1; i <= jumlah; i++) {
            if (radio[i-1].checked){ // Menggunakan radio[i-1] karena kita menggunakan radio array
                pilihan = data[i-1];
                console.log(pilihan);
                //tampilkanData();
            }
        }
        tampilkanData();
    });
}



function tampilkanData(pilihan) {
    var nama = document.getElementById("username").value;
    var jumlah = parseInt(document.getElementById("jumlah").value);
    var teksPilihan = "";
    
    // Menggabungkan teks pilihan yang dimasukkan pengguna
    for (var i = 0; i < jumlah; i++) {
        if (i > 0) {
            teksPilihan += ", ";
        }
        teksPilihan += document.getElementsByName("teksTampilan" + (i + 1))[0].value;
    }

    var pilihan = document.querySelector('input[name="radiobutton"]:checked');
    for (var i = 1; i <= jumlah; i++) {
        if (radio[i-1].checked){ // Menggunakan radio[i-1] karena kita menggunakan radio array
            pilihan = data[i-1];
            console.log(pilihan);
            //tampilkanData();
        }
    }
    var hasil = "Hallo, nama saya " + nama + ", saya mempunyai sejumlah " + jumlah + " pilihan yaitu " + teksPilihan;
    if (pilihan) {
        hasil += ", dan saya memilih " + pilihan;
    }
    else {
        
        hasil += ", dan saya belum memilih pilihan.";
    }
    hasil.id="hasil";
    document.getElementById("hasil").innerHTML = hasil;
}


