!function(o){o.extend({playSound:function(){return o("<embed src='"+arguments[0]+".mp3' hidden='true' autostart='true' loop='false' class='playSound'><audio autoplay='autoplay' style='display:none;' controls='controls'><source src='"+arguments[0]+".mp3' /></audio>").appendTo("body")}})}(jQuery);