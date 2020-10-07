(function() {
    "use strict";
    let targetOrigin = '*';

    var iframe = document.getElementById('vimeoframe');
    var player = new Vimeo.Player(iframe);
    var sliderFrame = document.getElementById('slider');

    if (!(iframe && player && sliderFrame)) {
        console.log("slideer-frame.js can't find iframes");
        return;
    }

    player.on('play', function() {
        sliderFrame.contentWindow.postMessage({
            event: 'play'
        }, targetOrigin);
    });
    player.on('pause', function() {
        sliderFrame.contentWindow.postMessage({
            event: 'pause'
        }, targetOrigin);
    });
    player.on('ended', function() {
        sliderFrame.contentWindow.postMessage({
            event: 'end'
        }, targetOrigin);
    });

    player.on('timeupdate', function(d) {
        sliderFrame.contentWindow.postMessage({
            event: 'timeupdate',
            data: d
        }, targetOrigin);
    });

    player.getVideoTitle().then(function(title) {
        sliderFrame.contentWindow.postMessage({
            event: 'title',
            title: title
        }, targetOrigin);
    });
})();