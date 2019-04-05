let Numbers = {
    NumberDuration: 2000,

    ShowPlayerDamage: function(damage) {

        damage = PlayerApp.Stats.IsCrit ? damage + '!!' : damage;
        let $span = $('.DamageNumbersPlayers');

        if (PlayerApp.Stats.IsCrit) {
            $span.find('span').addClass('NumCrit');
        } else {
            $span.find('span').addClass('NumCrit');
        }

        $span.find('span').text(damage);
        $span.fadeIn(300);

        setTimeout(function() {
            $span.fadeOut(300);
        }, Numbers.NumberDuration);
    },

    ShowEnemyDamage: function(damage) {
        let $span = $('.DamageNumbersEnemy');

        $span.find('span').text(damage);
        $span.fadeIn(300);

        setTimeout(function() {
           $span.fadeOut(300);
        }, Numbers.NumberDuration);
    },
};
