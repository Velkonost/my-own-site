<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>А.К. - Контакты</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-grid.min.css">
    <link rel="stylesheet" href="libs/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="libs/animate/animate.min.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/skins/tomato.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/topnav.css">
    <link rel="stylesheet" href="css/skills.css">
    <link rel="stylesheet" href="css/icons.css">

</head>
<body data-gr-c-s-loaded="true" oncontextmenu="return false" oncontextmenu="return false" onselectstart="return false;">

<div class="loader" style="display: none;">
    <div class="loader_inner" style="display: none;"></div>
</div>

<div id="main">

<div role="navigation" class="navbar navbar-inverse">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-xs-12">
                <ul class="bmenu navbar-right" style="margin-top: auto; margin-left: 25%; width: 100% ">
                    <li class="menu-1420"><a href="/main" title="Главная страница">Главная</a></li>
                    <li class="menu-749 first"><a href="portfolio" title="Взгляни на мое портфолио">Портфолио</a></li>
                    
                </ul>
            </div>
        </div>
    </div>
</div>

<section id="contacts" class="s_contacts bg_light _mPS2id-t">
    <div class="">
        <h2>Контакты</h2>
        <div class="s_descr_wrap">
            <div class="s_descr">Связаться со мной</div>
        </div>
    </div>

    <div class="image-effect-future">

        <div class="share-layer">
            <div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
                <a href="https://vk.com/velkonost" target="_blank" class="hi-icon">
                    <img src="img/vk.png" class="hi-icon" style="width: 15%; height: auto; margin-left: 11%; margin-top: 18% ">
                </a>
                <a href="https://t.me/velkonost" target="_blank">
                    <img src="img/telegram.png" style="width: 15%; height: auto; margin-left: 5%; margin-top: 18% ">
                </a>
                <a href="https://github.com/Velkonost" target="_blank">
                    <img src="img/github.png" style="width: 15%; height: auto; margin-left: 5%; margin-top: 18% ">
                </a>
                <a href="skype:velkonost?userinfo">
                    <img src="img/skype.png" style="width: 15%; height: auto; margin-left: 5%; margin-top: 18% ">
                </a>
            </div>
        </div>

        <div class="image-layer" style="width: 100%;height:100%" >
            <img src="./img/meblur.jpg" alt="я" class="img-thumbnail" style="width: 100%; height: auto">
        </div>

    </div>


    <div class="section_content" style="margin-top: 5%; margin-left: 18%">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="contact_box">
                        <div class="contacts_icon icon-basic-geolocalize-05"></div>
                        <h3>Адрес:</h3>
                        <p>Россия, г.Омск</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="contact_box">
                        <div class="contacts_icon icon-basic-mail-open-text"></div>
                        <h3>Email:</h3>
                        <p><a id="email" onclick="selectEmail()">velkonost@gmail.com</a></p>
                    </div>
                    
                </div>
                <div class="col-md-4 col-sm-4" >
                    <div class="contact_box">
                        <div class="contacts_icon icon-basic-webpage-img-txt"></div>
                        <h3>Веб-сайт:</h3>
                        <p><a href="http://velkonost.ru/" target="_blank">velkonost.ru</a></p>
                    </div>
                    
                    
                </div>

             
            </div>
        </div>
    </div>
</section>


</div>



<footer class="main_footer bg_dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="color:#fff;">
                © 2017 Артём Клименко
            </div>
        </div>
    </div>
</footer>

<script type="text/javascript">
    
 function selectEmail() {
     var element = document.getElementById('email');
      var selection = window.getSelection();        
        var range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
 }
    

</script>

<div class="hidden"></div>

<script src="libs/jquery/jquery-2.1.3.min.js"></script>
<script src="libs/parallax/parallax.min.js"></script>
<script src="libs/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="libs/mixitup/mixitup.min.js"></script>
<!-- <script src="libs/scroll2id/PageScroll2id.min.js"></script> -->
<script src="libs/waypoints/waypoints.min.js"></script>
<script src="libs/animate/animate-css.js"></script>
<script src="libs/jqBootstrapValidation/jqBootstrapValidation.js"></script>
<script src="js/common.js"></script>
<script src="js/modernizr.custom.js"></script>
<script src="js/plugins.js"></script>

</body></html>