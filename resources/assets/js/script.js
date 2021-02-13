document.getElementById("addrow").onclick = function(){cloner()};
document.getElementById("contbutt").onclick = function(){link()};

     var counter = 0; //Set counter
     var form = document.getElementById('extraPersonTemplate');

function link() {
     var url = "views/BookingForm2.blade.php";
    window.location.href = url;
}

function removebtn(tempcount, eventArgs){
     var tempform = "extraPersonTemplate" + (tempcount);
     var tempbutt = "b" + (tempcount);

     console.log("You tried to remove me" + tempcount);
     console.log("You tried to remove me" + tempform);
     console.log("You tried to remove me" + tempbutt);

     if (tempcount>0){
     document.getElementById(tempform).remove();
     document.getElementById(tempbutt).remove();
     }
     else{
          document.getElementById("extraPersonTemplate").remove();
          document.getElementById(tempbutt).remove();   
     }

}

function cloner(sender, eventArgs) {
     console.log("working button");
     var btn = document.createElement("BUTTON");
     var t = document.createTextNode("Remove Traveller");
     var tempcounter = counter;
     btn.appendChild(t);
     btn.setAttribute("class","btn btn-danger");
     btn.setAttribute("id","b"+counter);
     var formclone = form,
     clone = formclone.cloneNode(true);
     counter++;
     clone.id = "extraPersonTemplate" + counter;
     btn.onclick = function() { removebtn(tempcounter) };

     
     if (counter > 1){
     	console.log(counter);
     	var i = "extraPersonTemplate"+(counter-1);
     	console.log(i); 
          document.getElementById(i).after(clone);
          document.getElementById(i).after(btn);
              	
     }

     if (counter == 1){
          document.getElementById("extraPersonTemplate").after(clone);
          document.getElementById("extraPersonTemplate").after(btn);
     }
     


}

//$("#datepicker").datepicker();