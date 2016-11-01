$( document ).ready(function() {
    $( "#sh" ).on('click',function(){
        if ($( "#sh" ).text()=="Show aditional search properties") {
            $( "#sh" ).text("Hide aditional search properties");
        }else{
            $( "#sh" ).text("Show aditional search properties");
        }
        $( "#additional" ).toggleClass( "invisible" );
    });
    $("#getInfo").submit(function(e){
        e.preventDefault();
    });
});

var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";
  }
  x[slideIndex-1].style.display = "block";
}