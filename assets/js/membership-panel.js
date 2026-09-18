document.addEventListener("DOMContentLoaded", function () {

const panelHTML = `
<div id="membershipPanel">

<div class="panel-logo">
<img src="assets/img/logo-large.png">
</div>

<h2>Membership</h2>
<p>Bond with the best</p>

<button onclick="window.location.href='register.html'">
Let's Connect
</button>

</div>
`;


const panel = document.getElementById("membershipPanel");

setInterval(()=>{

panel.classList.add("show");

setTimeout(()=>{
panel.classList.remove("show");
},5000);

},10000);

document.body.insertAdjacentHTML("beforeend", panelHTML);


});
