var tampilanInputs;
var data = [];
var radio = [];
var pilihan;

document.getElementById('btn').addEventListener('click', function(event){
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
        label.innerHTML = "Pilihan " + i + ":";

        input[i-1] = document.createElement("input");
        input[i-1].type = "text"; // Mengubah input.type menjadi "text"
        input[i-1].name = "teksTampilan" + i;

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
        label.innerHTML ='<input type = "Radio" name = "teksTampilan" id = "Radio'+i+'">' + " Pilihan " + i;
        label.setAttribute("for", "pilihan" + i);

        tampilanInputs.appendChild(label);
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
        pilihan = '';
        console.log(e);
        for (var i = 1; i <= jumlah; i++) {
            if (radio[i-1].checked){ // Menggunakan radio[i-1] karena kita menggunakan radio array
                pilihan = data[i-1];
                console.log(pilihan);
            }
        }
        tampilkanData();
    });
}



function tampilkanData() {
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

    var hasil = "Hallo, nama saya " + nama + ", saya mempunyai sejumlah " + jumlah + " pilihan yaitu " + teksPilihan;
    if (pilihan) {
        hasil += ", dan saya memilih " + pilihan.value;
    }
    else {
        hasil += ", dan saya belum memilih pilihan.";
    }

    document.getElementById("hasil").innerHTML = hasil;
}


