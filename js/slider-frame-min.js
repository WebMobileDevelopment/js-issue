(function(){var d=document.getElementById("vimeoframe"),a=new Vimeo.Player(d),b=document.getElementById("slider");d&&a&&b?(a.on("play",function(){b.contentWindow.postMessage({event:"play"},"*")}),a.on("pause",function(){b.contentWindow.postMessage({event:"pause"},"*")}),a.on("ended",function(){b.contentWindow.postMessage({event:"end"},"*")}),a.on("timeupdate",function(c){b.contentWindow.postMessage({event:"timeupdate",data:c},"*")}),a.getVideoTitle().then(function(c){b.contentWindow.postMessage({event:"title",
title:c},"*")})):console.log("slideer-frame.js can't find iframes")})();