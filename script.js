function openPopup(id) {
    // Fecha todas as popups abertas antes de abrir uma nova
    var popups = document.querySelectorAll('.popup');
    popups.forEach(function(popup) {
        popup.style.display = 'none';
    });
    
    var popup = document.getElementById('popup-' + id);
    if (popup) {
        popup.style.display = 'block';
    }
}

function closePopup(id) {
    var popup = document.getElementById('popup-' + id);
    if (popup) {
        popup.style.display = 'none';
    }
}

function openSubPopup(id) {
    // Fecha todas as subpopups abertas antes de abrir uma nova
    var subPopups = document.querySelectorAll('.sub-popup');
    subPopups.forEach(function(subPopup) {
        subPopup.style.display = 'none';
    });

    var subPopup = document.getElementById('popup-' + id);
    if (subPopup) {
        subPopup.style.display = 'block';
    }
}

function closeSubPopup(id) {
    var subPopup = document.getElementById('popup-' + id);
    if (subPopup) {
        subPopup.style.display = 'none';
    }
}

function openSub_SubPopup(id) {
    // Fecha todas as subpopups abertas antes de abrir uma nova
    var sub_subPopup = document.querySelectorAll('.sub_sub-popup');
    sub_subPopups.forEach(function(sub_subPopup) {
        sub_subPopup.style.display = 'none';
    });

    var sub_subPopup = document.getElementById('popup-' + id);
    if (sub_subPopup) {
        sub_subPopup.style.display = 'block';
    }
}

function closeSub_SubPopup(id) {
    var sub_subPopup = document.getElementById('popup-' + id);
    if (sub_subPopup) {
        sub_subPopup.style.display = 'none';
    }
}

function exibirProdutos() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "exibir_produtos.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("popup-exibirProdutos").innerHTML += xhr.responseText;
        }
    };
    xhr.send();
}
