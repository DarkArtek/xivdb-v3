let EnemiesApp = {
    ActiveFloor: false,
    ActiveFloorData: false,

    SpawnFloor: function(floor) {
        EnemiesApp.ActiveFloor = floor;
        EnemiesApp.ActiveFloorData = Enemies[floor];
        SpeechBubbles.ShowPlayerBubble('Welcome to Floor '+ floor +' <br> '+ Enemies[floor].name);

        let $ui = $('.party-left');
        $ui.html('');
        $('.GameActiveFloor').html('Floor ' + floor);

        // loop through enemies for this floor
        for (let i in Enemies[floor].list) {
            let enemy = Enemies[floor].list[i];

            $ui.append('<div class="enemy enemy'+ enemy.pos +'" data-enemyid="'+ enemy.pos +'">' +
                '<div class="name">Lv. '+ floor +' '+ enemy.name +'</div>' +
                '<div class="health"><span style="width: 100%;"></span></div>' +
                '<img src="'+ enemy.icon +'" width="'+ enemy.size +'"></div>' +
                '</div>');

            Enemies[floor].list[i].id = randomXToY(1,99999999);
            Enemies[floor].list[i].$ele = $('.enemy'+ enemy.pos);

            // clone object
            Enemies[floor].list[i] = _.clone(Enemies[floor].list[i]);
        }

        // load in the enemies
        setTimeout(function() {
            let timeline = anime.timeline();
            timeline
                .add({
                    targets: '.enemy.enemy1',
                    easing: 'easeInOutQuad',
                    left: 25,
                    duration: 500,
                    offset: 0,
                })
                .add({
                    targets: '.enemy.enemy2',
                    easing: 'easeInOutQuad',
                    left: 240,
                    duration: 1000,
                    offset: 0,
                })
                .add({
                    targets: '.enemy.enemy3',
                    easing: 'easeInOutQuad',
                    left: 80,
                    duration: 700,
                    offset: 0,
                });
        }, 800)
    },

    GetEnemies: function()
    {
        let alive = [];

        for(let i in Enemies[EnemiesApp.ActiveFloor].list) {
            let enemy = Enemies[EnemiesApp.ActiveFloor].list[i];

            if (enemy.isAlive) {
                alive.push(enemy);
            }
        }

        return alive;
    },

    GetEnemyOnFloor: function(EnemyId)
    {
        return Enemies[EnemiesApp.ActiveFloor].list[parseInt(EnemyId)-1];
    }
};
