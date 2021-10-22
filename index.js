
(function() {
    fetch('./index.php')
    .then(response => response.json())
    .then(data => {
        var html = '<ul>';
        for(var service in data) {
            let link=window.location.href+service;
            html += '<li><h2><a target="_blank" href="'+link+'">'+link+'/</a></h2><ul>';
            for(var method in data[service]) {
                html += '<li><h3>'+method+' <a href="javascript:;" id="edit">Edit</a></h3>';
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

        document.getElementById('edit').onclick = function() {
            modal.style.display = "block";
            var counter = 0, sibilings = this.parentElement.parentElement.children;

            document.getElementsByName('url')[0].value = this.parentElement.parentElement.parentElement.parentElement.children[0].innerText.replace(window.location.href,'').slice(0,-1);
            for(var i in sibilings) {
                if(sibilings[i].nodeName == "PRE" && counter == 2) {
                    document.getElementsByName('failureResponse')[0].value = sibilings[i].innerHTML;
                    counter++;
                }
                if(sibilings[i].nodeName == "PRE" && counter == 1) {
                    document.getElementsByName('response')[0].value = sibilings[i].innerHTML;
                    counter++;
                }
                if(sibilings[i].nodeName == "PRE" && counter == 0) {
                    document.getElementsByName('request')[0].value = sibilings[i].innerHTML;
                    counter++;
                }
                if(sibilings[i].nodeName == "H3") {
                    document.getElementsByName('method')[0].value = sibilings[i].childNodes[0].data.trim().toUpperCase();
                }
            }
            
        };
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