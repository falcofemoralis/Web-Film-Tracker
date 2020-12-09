<link rel='stylesheet' href="/CSS/footer.css">

<footer id="footer">
    <div class="container">
        <div class="b-footer__center" style="margin-bottom: 5px">
            <h3 class="b-footer">
                FilmsTracker — трекер информации про фильмы!
            </h3>
            <div>
                <span id="hotlog_counter" style="margin-left: 25px"></span>
                <span id="hotlog_dyn"></span>
                <script> let hot_s = document.createElement('script');
                    hot_s.type = 'text/javascript';
                    hot_s.async = true;
                    hot_s.src = 'http://js.hotlog.ru/dcounter/2592668.js';
                    hot_d = document.getElementById('hotlog_dyn');
                    hot_d.appendChild(hot_s);
                </script>
                <noscript>
                    <a href="http://click.hotlog.ru/?2592668" target="_blank">
                        <img src="http://hit5.hotlog.ru/cgi-bin/hotlog/count?s=2592668&im=560" style="border: 0"
                             title="HotLog" alt="HotLog"></a>
                </noscript>
                <!-- /HotLog -->
            </div>
        </div>
        <div class="b-footer__center">
            <span class="b-footer">
                Трейлеры были сгенерированы на основании поиска на сайте <a href="https://www.youtube.com/">YouTube.com</a> и их несоответствие никак не регулируется.
            </span>
        </div>
    </div>
</footer>