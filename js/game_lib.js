window.GAME = {
    _events: [],
    _settings: {},
    on(event, func) {
        this._events.push({event, func});
        return this._events.length - 1;
    },
    off(listener) {
        this._events.splice(listener, 1);
    },
    emit(event, data = null) {
        for (let i = 0; i < this._events.length; i++) {
            if (this._events[i].event === event) {
                this._events[i].func.call(null, data);
            }
        }
    },
    load(game_file,settings) {
        this._settings = settings;
        this._settings.mountNode.classList.add('GAME');
        const script = document.createElement('script');
        script.src = game_file;
        document.head.appendChild(script);
    }
};