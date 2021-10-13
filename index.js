
(function() {
    fetch('./index.php')
    .then(response => response.json())
    .then(data => {
        var html = '<ul>';
        for(var service in data) {
            let link=window.location.href+service;
            html += '<li><h2><a target="_blank" href="'+link+'">'+link+'/</a></h2><ul>';
            for(var method in data[service]) {
                html += '<li><h3>'+method+'</h3>';
                html += '<h4>Request:</h4>';
                html += '<pre>'+JSON.stringify(data[service][method]['request'])+'</pre>';
                html += '<h4>Success response:</h4>';
                html += '<pre>'+JSON.stringify(data[service][method]['response'])+'</pre>';
                html += '<h4>Failure response:</h4>';
                html += '<pre>'+JSON.stringify(data[service][method]['failure_response'])+'</pre>';
                html += '</li>';
            }
            html += '</ul></li>';
        }
        html += '</ul>';
        document.getElementById('app').innerHTML = html;
    })
    .catch((error) => {
        console.log('Error:', error);
    });
})();

// Get the modal
var modal = document.getElementById("myModal");
document.getElementById('newApi').onclick = function() {
    modal.style.display = "block";
};
// When the user clicks on <span> (x), close the modal
document.getElementsByClassName("close")[0].onclick = function() {
    modal.style.display = "none";
};
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

document.getElementsByTagName('form')[0].onsubmit = function() {
    var datas = {
        url: document.getElementsByName('url')[0].value,
        method: document.getElementsByName('method')[0].value,
        request: document.getElementsByName('request')[0].value,
        response: document.getElementsByName('response')[0].value,
        failureResponse: document.getElementsByName('failureResponse')[0].value
    };
    fetch('./index.php?form', {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded'
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify(datas)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.response.Message);
    })
    .catch((error) => {
        alert("System error! Unable to updated the API. Please contact app administrator.");
        console.log('Error:', error);
    });
    return false;
};