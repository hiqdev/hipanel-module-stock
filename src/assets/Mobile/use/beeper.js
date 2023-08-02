export default function useBeeper() {
    return {
        playSuccess: success,
        playError: error,
        playExists: playNTimesWithInitialDelay(error, 3, 1)
    };
}

const playNTimesWithInitialDelay = (play, n, initialDelay) => {
    return () => {
        let timesPlayed = 0;
        let intervalId = setInterval(() => {
            if (timesPlayed <= initialDelay) {
                timesPlayed++;
                return;
            }

            play();
            if (timesPlayed === n+initialDelay) {
                clearInterval(intervalId);
                timesPlayed = 0;
            }
            timesPlayed++;
        }, 200);
    }
};

const sound = () => {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    var audioCtx = new AudioContext();
    const c = new AudioContext();
    const o = c.createOscillator();
    const g = c.createGain();
    o.connect(g);
    g.connect(c.destination);

    return {o, g, c};
}

function success() {
    const {o, g, c} = sound();
    o.type = "sine";
    o.frequency.value = 1000;
    g.gain.exponentialRampToValueAtTime(
        0.00001,
        c.currentTime + 1
    );
    o.start(0);
}

function error() {
    const {o, g, c} = sound();
    o.frequency.value = 400;
    o.type = "square";
    g.gain.exponentialRampToValueAtTime(
        0.00001,
        c.currentTime + 1
    );
    o.start(0);
}
