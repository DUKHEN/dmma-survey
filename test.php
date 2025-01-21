<input type="text" id="nameInput" placeholder="Enter your name">
<button onclick="sendName()">Greet Me</button>
<div id="greeting"></div>


<script>
    function sendName() {
    const name = document.getElementById('nameInput').value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/server-endpoint', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('greeting').innerText = xhr.responseText;
        } else {
            console.error('Failed to fetch greeting');
        }
    };
    xhr.send(JSON.stringify({ name: name }));
}

</script>