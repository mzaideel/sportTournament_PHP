function Nevkiiras()
{
    var e = document.getElementById("new-form-players-number");
    var elementid = document.getElementById("new-form-players-number").id;
    var elementertek = document.getElementById("new-form-players-number").value;//e.value;??
    //var valasztott = e.options[e.selectedIndex].value;

    document.getElementById("namesInput").style.display = "block";

    var inputFieldHeight = elementertek*25+10+"px";
    document.getElementById("toggledNamesInput").style.height = inputFieldHeight;
     $(function(){
       $.post('NewFormFunctions.php',{ elid: elementid, evalue: elementertek }, function(response){
           $('#toggledNamesInput').html(response);
       });
    });
  }


function SportSelect()
{
  var e = document.getElementById("sports");
  var ertek = document.getElementById("sports").value;

  if(ertek=="fifa")
  {
    document.getElementById("new-form-fifa").style.visibility="visible";
  }
}



//Ezt másoltam a netről!!
function SportSelect(evt, sport) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(sport).style.display = "block";
    evt.currentTarget.className += " active";
}
