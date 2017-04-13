<h2>Welcome to Work With Me</h2><br />

<div id="introText">Interact with colleagues and friends for project management, corporate events, lunch plans and more.</div><br />

<h2 class="w3-center">What Can Work With Me Do For You?</h2>

<div class="w3-content w3-section" id="caro">
    <img class="slide" src="images\img1.jpg">
    <img class="slide" src="images\img2.jpg">
    <img class="slide" src="images\img3.jpg">
    <img class="slide" src="images\img4.jpg">
    <h3 class="caption">Networking</h3>
    <h3 class="caption">Team Building</h3>
    <h3 class="caption">Event Management</h3>
    <h3 class="caption">Connecting</h3>
</div>

<script>
    var slideIndex = 0;
    carousel();

    function carousel() {
        var i;
        var x = document.getElementsByClassName("slide");
        for (i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
        }
        var y = document.getElementsByClassName("caption");
        for (i = 0; i < y.length; i++) {
        y[i].style.display = "none"; 
        }
        slideIndex++;
        if (slideIndex > x.length) {slideIndex = 1}
        if (slideIndex > y.length) {slideIndex = 1}
        x[slideIndex-1].style.display = "block"; 
        y[slideIndex-1].style.display = "block";
        setTimeout(carousel, 2000); // Change image every 2 seconds
        }
</script>