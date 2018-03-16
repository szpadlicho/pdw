function display_result_hide(){
    $( '#chat_display_result_placeholder' ).hide();
}
function chat_box_hide(){
    $( '#chat_box' ).hide( 'fast', function() {
        //alert( "Animation complete." );
    });
    $( '#chat_box .speech-bubble-me' ).hide( 'fast', function() {
        //alert( "Animation complete." );
        $( '.conversation' ).empty();
    });
}

$(function() {
    $( '#result_close' ).on( 'click', function() {
        display_result_hide();
    });
});

/**
** request
**/
$(function(){
    $( '#close_button' ).on( 'click', function() {
        chat_box_hide();
    });
});

function loadData(q1 = 'a', q2 = 'a', q3 = 'a', q4 = 'a', q5 = 'a', q6 = 'a') {
    /**
    ** amplifier.base_url - global variable set in amplifier.php (smarty)
    **/
    //var base_dir = amplifier.base_url_ssl;
    var dots = 0;

    $.ajax({
        type: 'post',
        //url: '{$base_dir}modules/amplifiers/result.php',
        //url: base_dir+'../result.php',
        //url: 'https://new-electric.pl/modules/amplifiers/result.php',
        url: 'result.php',
        data: {
            a1: q1, a2: q2, a3: q3, a4: q4, a5: q5, a6: q6
        },
        beforeSend: function() {
            function type() {
                if(dots < 3) {
                    $('#dots').append('.');
                    dots++;
                } else {
                    $('#dots').html('');
                    dots = 0;
                }
            }
            setInterval (type, 600);
        },
        success: function (response) {
            $( '#chat_display_result_placeholder' ).show();
            $( '#chat_display_result' ).html(response);
            chat_box_hide();
        },
        complete: function(){
            $('#processing').delay(6500).hide();
            $('#processing_done').delay(7500).show();
        }
    });
}

$(document).ajaxStart(function () {
    //$('#ajaxBusy').show();
}).ajaxStop(function () {
    //$('#ajaxBusy').hide();
});

$(function(){
    $( '#calculate_button' ).on( 'click', function() {
        loadData();
    });
});

$(function(){
    /**
    ** Start and initiate questions
    **/
                $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                            +'<span class="pdw-start"><a id="q0a" class="answer a0">Rozpocznij</a></span>'
                            +'</p>'
                            +''
                            );
                $( '.question' ).delay(0).fadeIn( 'fast', function() {
                    //alert( "Animation complete." );
                });
    /* Question 1 */
    $(document).on( 'click', '.a0', function() {
        $('.background').stop().fadeOut('slow', function () {
            $(this).css("background-image", "url('img/start1.jpg')").fadeIn('slow');
        });
        $( '.conversation' ).empty();
        $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                                    +'<span class="pdw-number">1</span> <span class="pdw-question">Jakiego rodzaju wzmocnienia sygnału  potrzebujesz:</span>'
                                    +'<br /><a id="q1a" class="answer a1">a) <strong>GSM</strong> (lepszy sygnał i jakość sygnału telefonicznego we wszystkich sieciach telefonicznych za wyjątkiem sieci PLAY) </a>'
                                    +'<br /><a id="q1b" class="answer a1">b) <strong>GSM + EGSM</strong> (lepsza jakość sygnału telefonicznego dla sieci PLAY (EGSM) + inne sieci(GSM) + lepsza jakość Internetu 3G na częstotliwości 900MHz) <span class="pd-polecamy">NAJLEPSZY WYBÓR!</span></a>'
                                    +'<br /><a id="q1c" class="answer a1">c) <strong>GSM + Internet 3G</strong> (UMTS, WCDMA)</a>'
                                    +'<br /><a id="q1d" class="answer a1">d) <strong>GSM + EGSM (PLAY) + Internet 3G</strong> (UMTS, WCDMA)</a>'
                                    +'<br /><a id="q1e" class="answer a1">e) <strong>GSM + Internet 4G LTE</strong> (jeśli Twój telefon nie łapie zasięgu 4G lub LTE na zewnątrz przed budynkiem wybierz opcje&nbspc)</a>'
                                    +'<br /><a id="q1f" class="answer a1">f) <strong>GSM + EGSM (PLAY) + Internet 4G LTE</strong> (jeśli nie łapiesz zasięgu 4G lub LTE na zewnątrz przed budynkiem wybierz opcje&nbspd)</a>'
                                    +'<br /><a id="q1g" class="answer a1">g) <strong>GSM + 3G + 4G LTE<strong> (wszystkie sieci wzmocnione)</a>'
                                    +'<br /><a id="q1h" class="answer a1">h) Potrzebuję wzmocnić sam Internet 3G i 4G (<strong>przejdź do programu doboru anten</strong>)</a>' 
                                    +'</p>'
                                    );
        // $('.background').animate({opacity: 0}, 'slow', function() {
            // $(this).css({'background-image': 'url(img/start1.jpg)'}).animate({opacity: 1}, 'slow');
        // });                            
        $( '.question' ).delay(0).fadeIn( 'fast', function() {
            //alert( "Animation complete." );
        });
    });
    /* Question 2 */
    $(document).on( 'click', '.a1', function() {
        // Store
        localStorage.setItem( 'q1', $( this ).text().charAt(0) );
        // Retrieve
        console.log( localStorage.getItem('q1') ); 
        //alert('lol');
        // $('.background').animate({opacity: 0}, 'slow', function() {
            // $(this).css({'background-image': 'url(img/start2.jpg)'}).animate({opacity: 1}, 'slow');
        // });
        $('.background').stop().fadeOut('slow', function () {
            $(this).css("background-image", "url('img/start2.jpg')").fadeIn('slow');
        });
        $( '.conversation' ).empty();
        $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                                    +'<span class="pdw-number">2</span> <span class="pdw-question">Na jakiej powierzchni w m2 potrzebują Państwo poprawić zasięg telefoniczny lub telefoniczny i Internetu?</span>'
                                    +'<br /><a id="q2a" class="answer a2">a) do 100 m2</a>'
                                    +'<br /><a id="q2b" class="answer a2">b) od 100-200 m2</a>'
                                    +'<br /><a id="q2c" class="answer a2">c) powyżej 200 m2</a>'
                                    +'</p>'
                                    );
        $( '.question' ).delay(0).fadeIn( 'fast', function() {
            //alert( "Animation complete." );
            $('.conversation').stop();
            $('.conversation').animate({scrollTop: $('.conversation').prop('scrollHeight')}, 500);
        });
    });
    /* Question 3 */
    $(document).on( 'click', '.a2', function() {
        //alert('lol');
        localStorage.setItem( 'q2', $( this ).text().charAt(0) );
        console.log( localStorage.getItem('q2') );
        // $('.background').animate({opacity: 0}, 'slow', function() {
            // $(this).css({'background-image': 'url(img/start3.jpg)'}).animate({opacity: 1}, 'slow');
        // });
        $('.background').stop().fadeOut('slow', function () {
            $(this).css("background-image", "url('img/start3.jpg')").fadeIn('slow');
        });
        $( '.conversation' ).empty();
        $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                                    +'<span class="pdw-number">3</span> <span class="pdw-question">Czy wzmacniacz będzie pracował w:</span>'
                                    +'<br /><a id="q3a" class="answer a3">a) bloku</a>'
                                    +'<br /><a id="q3b" class="answer a3">b) kontenerze lub garażu</a>'
                                    +'<br /><a id="q3c" class="answer a3">c) domu jednopoziomowym</a>'
                                    +'<br /><a id="q3d" class="answer a3">d) domu dwu lub trzy poziomowym do 300 m2</a>'
                                    +'<br /><a id="q3e" class="answer a3">e) firmie lub hali magazynowej do 200 m2</a>'
                                    +'<br /><a id="q3f" class="answer a3">f) firmie o powierzchni powyżej 200 m2</a>'
                                    +'</p>'
                                    );
        $( '.question' ).delay(0).fadeIn( 'fast', function() {
            //alert( 'Animation complete.' );
            $('.conversation').stop();
            $('.conversation').animate({scrollTop: $('.conversation').prop('scrollHeight')}, 500);
        });
    });
    /* Question 4 */
    $(document).on( 'click', '.a3', function() {
        //alert('lol');
        localStorage.setItem( 'q3', $( this ).text().charAt(0) );
        console.log( localStorage.getItem('q3') );
        // $('.background').animate({opacity: 0}, 'slow', function() {
            // $(this).css({'background-image': 'url(img/start4.jpg)'}).animate({opacity: 1}, 'slow');
        // });
        $('.background').stop().fadeOut('slow', function () {
            $(this).css("background-image", "url('img/start4.jpg')").fadeIn('slow');
        });
        $( '.conversation' ).empty();
        $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                                    +'<span class="pdw-number">4</span> <span class="pdw-question">Podaj odległość od nadajnika w km?</span>'
                                    +'<br /><a id="q4a" class="answer a4">a) do 3 km</a>'
                                    +'<br /><a id="q4b" class="answer a4">b) do 3-8 km</a>'
                                    +'<br /><a id="q4c" class="answer a4">c) powyżej 8 km</a>'
                                    +'<br />Sprawdź dostępne nadajniki w twojej okolicy. Kliknij tutaj:'
                                    +'<br /><a target="_blank" href="http://www.mapabts.pl">www.mapabts.pl</a>'
                                    +'</p>'
                                    );
        $( '.question' ).delay(0).fadeIn( 'fast', function() {
            //alert( "Animation complete." );
            $('.conversation').stop();
            $('.conversation').animate({scrollTop: $('.conversation').prop('scrollHeight')}, 500);
        });
    });
    /* Question 5 */
    $(document).on( 'click', '.a4', function() {
        //alert('lol');
        localStorage.setItem( 'q4', $( this ).text().charAt(0) );
        console.log( localStorage.getItem('q4') );
        // $('.background').animate({opacity: 0}, 'slow', function() {
            // $(this).css({'background-image': 'url(img/start5.jpg)'}).animate({opacity: 1}, 'slow');
        // });
        $('.background').stop().fadeOut('slow', function () {
            $(this).css("background-image", "url('img/start5.jpg')").fadeIn('slow');
        });
        $( '.conversation' ).empty();
        $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                                    +'<span class="pdw-number">5</span> <span class="pdw-question">Proszę wybrać jedną z poniższych odpowiedzi w celu dobrania idealnego zestawu:</span>'
                                    +'<br /><a id="q5a" class="answer a5">a) posiadam przed obiektem 2-3 kreski w skali od 0 do 5 dla 2G (wzmacniacze GSM i EGSM)</a>'
                                    +'<br /><a id="q5b" class="answer a5">b) przed obiektem w telefonie często wskakuje mi LTE (GSM+ LTE lub EGSM/GSM/4GLTE)</a>'
                                    +'<br /><a id="q5c" class="answer a5">c) przed obiektem w telefonie wskakuje mi tylko 3G, nie wskakuje mi 4G LTE (wzmacniacz GSM/3G lub EGSM/GSM/3G)</a>'
                                    +'</p>'
                                    );
        $( '.question' ).delay(0).fadeIn( 'fast', function() {
            //alert( "Animation complete." );
            $('.conversation').stop();
            $('.conversation').animate({scrollTop: $('.conversation').prop('scrollHeight')}, 500);
        });
    });
    /* Question 5 */
    $(document).on( 'click', '.a5', function() {
        //alert('lol');
        localStorage.setItem( 'q5', $( this ).text().charAt(0) );
        console.log( localStorage.getItem('q5') );
        // $('.background').animate({opacity: 0}, 'slow', function() {
            // $(this).css({'background-image': 'url(img/start6.jpg)'}).animate({opacity: 1}, 'slow');
        // });
        $('.background').stop().fadeOut('slow', function () {
            $(this).css("background-image", "url('img/start6.jpg')").fadeIn('slow');
        });
        $( '.conversation' ).empty();
        $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                                    +'<span class="pdw-number">6</span> <span class="pdw-question">Ukształtowanie terenu i inne warunki:</span>'
                                    +'<br /><a id="q6a" class="answer a6">a) Stacja nadawcza (BTS) widoczna gołym okiem</a>'
                                    +'<br /><a id="q6b" class="answer a6">b) Teren w miarę równy (nizinny)</a>'
                                    +'<br /><a id="q6c" class="answer a6">c) Posiadam w okolicy lasy i pagórki (wzmacniacz GSM/3G lub GSM/4G lub T2000)</a>'
                                    +'<br /><a id="q6d" class="answer a6">d) Posiadam w okolicy góry i doliny (wzmacniacz GSM/3G  lub GSM/4G lub T2000)</a>'
                                    +'</p>'
                                    );
        $( '.question' ).delay(0).fadeIn( 'fast', function() {
            //alert( "Animation complete." );
            $('.conversation').stop();
            $('.conversation').animate({scrollTop: $('.conversation').prop('scrollHeight')}, 500);
        });
    });
    /* Result */
    $(document).on( 'click', '.a6', function() {
        //alert('lol');
        localStorage.setItem( 'q6', $( this ).text().charAt(0) );
        console.log( localStorage.getItem('q6') );
        // $('.background').animate({opacity: 0}, 'slow', function() {
            // $(this).css({'background-image': 'url(img/start7.jpg)'}).animate({opacity: 1}, 'slow');
        // });
        $('.background').stop().fadeOut('slow', function () {
            $(this).css("background-image", "url('img/start7.jpg')").fadeIn('slow');
        });
        $( '.conversation' ).empty();
        $( '.conversation' ).append( '<p class="chb question speech-bubble-me">'
                                    +'<span>W razie pytań zapraszamy do kontaktu mailowo: </span><a href="mailto:biuro@new-electric.pl?Subject=PDW">biuro@new-electric.pl</a>'
                                    +'<br /><span>lub telefonicznie: 508 964 552</span>'
                                    +'<br /><span id="processing">Przetwarzam<span id="dots"></span></span><span id="processing_done">...gotowe!</span>'
                                    +'<br />'
                                    +'<br /><a id="q7d" class="answer a7">Pokaż ponownie wynik</a> lub <a id="q7d" class="answer a8">rozpocznij od nowa</a>'
                                    +'</p>'
                                    );
        $( '.question' ).delay(0).fadeIn( 'fast', function() {
            //alert( "Animation complete." );
            $('.conversation').stop();
            $('.conversation').animate({scrollTop: $('.conversation').prop('scrollHeight')}, 500);
                setInterval (loadData( localStorage.getItem('q1'), localStorage.getItem('q2'), localStorage.getItem('q3'), localStorage.getItem('q4'), localStorage.getItem('q5'), localStorage.getItem('q6') ), 5500);
                //loadData();
        });
    });
    /* Show again*/
    $(document).on( 'click', '.a7', function() {
        setInterval (loadData( localStorage.getItem('q1'), localStorage.getItem('q2'), localStorage.getItem('q3'), localStorage.getItem('q4'), localStorage.getItem('q5'), localStorage.getItem('q6') ), 5500);
    });
    $(document).on( 'click', '.a8', function() {
        location.reload();
    });
});