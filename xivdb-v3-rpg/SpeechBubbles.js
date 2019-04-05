let SpeechBubbles = {
    DefaultTimeout: 3000,

    Render: function(element, text, time) {
       setTimeout(function() {
           $(element).find('span').html(text);
           $(element).fadeIn(100);
       }, 120);

        if (time) {
            setTimeout(function() {
                $(element).fadeOut(300);
            }, time);
        }
    },

    Hide: function() {
        $('.SpeechPlayer, .SpeechEnemy, .SpeechPC, .SpeechSystem').hide();
    },

    ShowPlayerBubble: function(text, time) {
        time = time ? time : SpeechBubbles.DefaultTimeout;
        SpeechBubbles.Hide();
        SpeechBubbles.Render('.SpeechPlayer', text, time);
    },

    ShowEnemyPlayer: function(text, time) {
        time = time ? time : SpeechBubbles.DefaultTimeout;
        SpeechBubbles.Hide();
        SpeechBubbles.Render('.SpeechEnemy', text, time);
    },

    ShowSystem: function(text, time) {
        time = time ? time : SpeechBubbles.DefaultTimeout;
        SpeechBubbles.Hide();
        SpeechBubbles.Render('.SpeechPC', text, time);
    },

    ShowSystemStay: function(text) {
        SpeechBubbles.Hide();
        SpeechBubbles.Render('.SpeechSystem', text, false);
    }
};
