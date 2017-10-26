/* eslint-disable */
var prefix = '/~xsilhan2/';


function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

function encodeQueryData(data) {
    let ret = [];
    for (let d in data)
        ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
    return ret.join('&');
}

var currentId = null;
document.addEventListener("DOMContentLoaded", function (event) {

    var queryObject = {};
    var queries = document.querySelectorAll('[data-action=query]');
    for (var i = 0; i < queries.length; i++) {
        queryObject[queries[i].getAttribute('name')] = queries[i].value;
        queries[i].addEventListener('input', debounce(function () {
            queryObject[this.getAttribute('name')] = this.value;
            console.log('test', prefix + 'people?' + encodeQueryData(queryObject));
            window.location.href = prefix + 'people/?' + encodeQueryData(queryObject);
        }, 500))
    }
    $('[data-toggle=tooltip]').tooltip();
    $('.btn-danger').click(function(event) {
        if(confirm('Are you sure?')) {
            return true;
        }
        event.preventDefault();
    });
});