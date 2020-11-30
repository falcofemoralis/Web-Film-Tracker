<link rel='stylesheet' href="/CSS/footer.css">

<footer id="footer">
    <div class="container">
        <div class="b-footer">
            <h3>FilmsTracker — трекер информации про фильмы!</h3>
        </div>
    </div>
</footer>

<script>
    let screenHeight = window.screen.height;
    let docHeight = document.body.offsetHeight;
    if (docHeight > (screenHeight - 160)) {
        let footer = document.getElementById("footer");
        footer.style.position = "relative";
    }
</script>