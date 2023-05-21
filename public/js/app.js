
function show_error_alert(jqXHR, alert_class = 'alert')
{
    let response = jqXHR.responseJSON;
    let error_alert = '<ul>';
    for(responseKey in response) {
        if(response.hasOwnProperty(responseKey)){
            response[responseKey].forEach(message => {
                error_alert = error_alert + '<li>' + message + '</li>'
            })
        }
    }
    error_alert = error_alert + '</ul>'
    $(alert_class).html(error_alert);
    $(alert_class).show();
}

function create_query(object = {})
{
    let queries = [];
    for(key in object){
        if(key && object[key]){
            queries.push(key + "=" + object[key])
        }
    }

    url_query = '?'+queries.join('&');
    return url_query;
}