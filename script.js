document.getElementById("uploadForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var form = this;
    var xhr = new XMLHttpRequest();
    xhr.open(form.method, form.action, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                document.getElementById("message").innerHTML = "<p>Data uploaded successfully.</p>";
                form.reset();
            } else {
                document.getElementById("message").innerHTML = "<p>Error: " + xhr.responseText + "</p>";
            }
        }
    };
    xhr.send(new FormData(form));
});
