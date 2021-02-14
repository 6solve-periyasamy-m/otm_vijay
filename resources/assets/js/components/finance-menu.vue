<template>
 <div class="sec4 sec" id="sect sect4">
            <div class="menucontainer">
                <div class="row">
                    <div class="col hider"></div>
                    <div class="col">
                        <a href="f&t" class="textcenter tc1">Your balance and payments</a>
                    </div>
                    <div class="col">
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col"></div>
                    <div class="col">
                        <a href="f&t" class="textcenter tc2">View invoice</a>
                    </div>
                    <div class="col">
                    </div>
                </div>
                <br>
            </div>
            <br><br>
        </div>
</template>

<script>
export default {
  name: 'Menu1',
  props: {
    msg: String
  }
}
</script>

<style scoped>
.sec {
width: 95vw;
height: 50vh;
float: left;
z-index: 3;
align-content: center;
margin-left: 2vw;
position: relative;
}
.menucontainer{
margin-top: 6vh;
}
.textcenter{
padding: 6%;
font-size: 1.5em;
text-align: center;
background-color: rgb(45, 94, 255);
width: 60vw;
margin: auto;
color: rgb(255, 255, 255);
display: flex;
justify-content: center;
border-radius: 16vw;
z-index: 3;
box-shadow: 0 1vw 2vw 0 rgba(0, 0, 0, 0.2), 0 1vw 1vw 0 rgba(0, 0, 0, 0.19);
font-family: 'Montserrat', sans-serif;
}
.textcenter:hover{
background-color:rgb(0, 14, 139);
color: white;
}
@media only screen and (min-width: 450px) {
.textcenter{
    font-size: 1em;
}
}
@media only screen and (min-width: 850px) {
  .sec{
      margin-top: -40vh;
      margin-left: 66vw;
      height: 10vh;
      width: 10vw;
      float: left;
  }
  .textcenter{
      width: 20vw;
      padding: 1.5vw;
  }
  .tc1{
      margin-left: 6vw;
  }
  .tc2{
      margin-left: 4vw;
  }
  .tc3{
      margin-left: 2vw;
  }
}
</style>
