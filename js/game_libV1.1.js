// Download game HTML, create iFrame and setup game.
function loadGame(gameId, { mountNode = null } = {}) {
  function loadFrame(html) {
    return new Promise((resolve) => {
      const frame = document.createElement("iframe");
      frame.onload = () => {
        const frameDocument = frame.contentWindow.document;
        frameDocument.open();
        frameDocument.write(html);
        frame.style.width = "100%";
        frame.style.height = "100%";
        frame.style.border = "none";
        resolve({ frame, frameDocument });
      };
      mountNode.appendChild(frame);
    });
  }

  return new Promise((resolve, reject) => {
    fetch(gameId)
      .then((res) => res.text())
      .then(async (html) => {
        const { frame, frameDocument } = await loadFrame(html);
        resolve({
          start(sessionId) {
            frameDocument.GAME.events.emit("session.start", { sessionId });
          },
          on(event, fn) {
            frameDocument.GAME.events.on(event, fn);
          },
          off(event, fn) {
            frameDocument.GAME.events.off(event, fn);
          },
          emit(event, fn) {
            frameDocument.GAME.events.emit(event, fn);
          },
          destroy() {
            frameDocument.GAME.events.destroy();
            frameDocument.close();
            mountNode.removeChild(frame);
          },
        });
      })
      .catch(reject);
  });
}

// Unique identifier
function guid() {
  const s4 = () =>
    Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  return `${s4()}${s4()}-${s4()}-${s4()}-${s4()}-${s4()}${s4()}${s4()}`;
}


 