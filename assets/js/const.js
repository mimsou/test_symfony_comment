var api_url = "http://127.0.0.1:8001"
window.confSetEvent = false;
window.isConnected = function(){
    return  localStorage.getItem('token') ?  true :  false;
};
window.token = "";