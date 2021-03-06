    <div class="box" id="div_geetest_lib">
        <div id="div_id_embed"></div>
        <script type="text/javascript">

            var gtFailbackFrontInitial = function(result) {
                var s = document.createElement('script');
                s.id = 'gt_lib';
                s.src = 'http://static.geetest.com/static/js/geetest.0.0.0.js';
                s.charset = 'UTF-8';
                s.type = 'text/javascript';
                document.getElementsByTagName('head')[0].appendChild(s);
                var loaded = false;
                s.onload = s.onreadystatechange = function() {
                    if (!loaded && (!this.readyState|| this.readyState === 'loaded' || this.readyState === 'complete')) {
                        loadGeetest(result);
                        loaded = true;
                    }
                };
            }
            //get  geetest server status, use the failback solution


            var loadGeetest = function(config) {

                //1. use geetest capthca
                window.gt_captcha_obj = new window.Geetest({
                    gt : config.gt,
                    challenge : config.challenge,
                    product : 'embed',
                    offline : !config.success
                });

                gt_captcha_obj.appendTo("#div_id_embed");
            }

            s = document.createElement('script');
            s.src = 'http://api.geetest.com/get.php?callback=gtcallback';
            $("#div_geetest_lib").append(s);

            var gtcallback =( function() {
                var status = 0, result, apiFail;
                return function(r) {
                    status += 1;
                    if (r) {
                        result = r;
                        setTimeout(function() {
                            if (!window.Geetest) {
                                apiFail = true;
                                gtFailbackFrontInitial(result)
                            }
                        }, 1000)
                    }
                    else if(apiFail) {
                        return
                    }
                    if (status == 2) {
                        loadGeetest(result);
                    }
                }
            })()

            $.ajax({
                url : "/code/getcode?rand="+Math.round(Math.random()*100),
                type : "get",
                dataType : 'JSON',
                success : function(result) {
                    console.log(result);
                    gtcallback(result)
                }
            })
        </script>
    </div>