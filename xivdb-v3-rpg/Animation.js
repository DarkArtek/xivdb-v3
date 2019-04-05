let Animation = {
    SpawnPlayers: function() {
        // spawn part
        for (var i = 1; i < 5; i++) {
            (function(index) {
                // clone party selection over to roles
                ClassJobsApp.PlayerRoles[index] = _.clone(Party.PartySelection[index]);
                ClassJobsApp.PlayerRoles[index].id = randomXToY(1,99999999);
                Animation.SpawnPlayerForId(index);
            })(i);
        }

        setTimeout(function() {
            Animation.RunIntro();
        }, 250);

        setTimeout(function() {
            EnemiesApp.SpawnFloor(1);
        });
    },

    SpawnPlayerForId: function(id) {
        var $ele = $('.player'+ id);

        $ele.html(
            '<div class="name"><img src="' + ClassJobsApp.PlayerRoles[id].typeIcon + '" height="18"> ' + ClassJobsApp.PlayerRoles[id].name + ' '+ id +'</div>' +
            '<div class="health"><span style="width:100%;"></span></div>' +
            '<div class="sprite"><img src="'+ ClassJobsApp.PlayerRoles[id].sprite +'"></div>'
        );

        ClassJobsApp.PlayerRoles[id].$ele = $ele;
    },

    RunIntro: function() {
        PlayerApp.UpdatePlayerRewards();

        let timeline = anime.timeline();
        timeline
        .add({
            targets:    '.player.player1',
            easing:     'easeInOutQuad',
            right:      225,
            duration:   750,
            offset:     0,
        })
        .add({
            targets:    '.player.player2',
            easing:     'easeInOutQuad',
            right:      285,
            duration:   650,
            offset:     0,
        })
        .add({
            targets:    '.player.player3',
            easing:     'easeInOutQuad',
            right:      25,
            duration:   400,
            offset:     0,
        })
        .add({
            targets:    '.player.player4',
            easing:     'easeInOutQuad',
            right:      85,
            duration:   500,
            offset:     0,
        });
    }
};
