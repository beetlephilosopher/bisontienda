 // Cargar productos desde la base de datos con AJAX
 document.addEventListener("DOMContentLoaded", function() {
    fetch("fetch_products.php")
        .then(response => response.text())
        .then(data => document.getElementById("productList").innerHTML = data)
        .catch(error => console.error("Error al cargar productos:", error));
});

// Función para abrir el chat de cotización
function openChat(productName) {
    document.getElementById("productName").innerText = productName;
    document.getElementById("chatSection").style.display = "block";
}