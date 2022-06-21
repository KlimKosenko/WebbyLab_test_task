var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];
var film_id = undefined;
var page_url = undefined;

function showModal(id,url){
    modal.style.display = "block";
    film_id = id;
    page_url = url;
}
window.onload = function(){ 
    span.onclick = function() {
        modal.style.display = "none";
    }
};
function hideModal(){
    modal.style.display = "none";
}
function delFilm(){
    modal.style.display = "none";
    document.location.href = 'delFilm.php?web_page='+page_url+'&id='+film_id;
}
