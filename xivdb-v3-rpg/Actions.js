// Actions should be no longer than 2s long
let Actions = {
    DefaultActionDuration: 2000,
    EnemyActionDelay: 1500,
    StandDelay: 750,

    // Player performs an action
    PerformPlayerAction: function(Skill, Player, Enemy) {
        if (Battle.Complete) {
            return;
        }

        // show action
        SpeechBubbles.ShowPlayerBubble(Skill.name + '!', 1500);

        // grab original position
        const right = parseInt(Player.$ele.css('right'));
        const top = parseInt(Player.$ele.css('top'));
        const target = '[data-playerid="'+ Player.$ele.attr('data-playerid') +'"]';

        anime({
            targets:    target,
            easing:     'easeInOutQuad',
            right:      580,
            top:        170,
            duration:   200,
            offset:     0,
            complete:   function() {
                setTimeout(function() {
                    // jump up
                    anime({
                        targets:    target,
                        easing:     'easeOutQuad',
                        right:      right + 80,
                        top:        top - 100,
                        duration:   120,
                        complete:   function() {
                            // land
                            anime({
                                targets:    target,
                                easing:     'easeInOutQuad',
                                right:      right,
                                top:        top,
                                duration:   80,
                            })
                        }
                    });
                }, Actions.StandDelay);
            }
        });

        // run action
        Skill.action(Player, Enemy);

        // check if we won or lost
        Battle.CheckDeadState();

        // run enemy action
        setTimeout(function() {
            if (Battle.Complete) {
                return;
            }

            Actions.PerformEnemyAction(Enemy);
        }, Actions.EnemyActionDelay);
    },

    // Enemy performs an action
    PerformEnemyAction: function()
    {
        if (Battle.Complete) {
            return;
        }

        // pick a random player
        const Players = ClassJobsApp.GetCharacters();
        const Player = Players[Math.floor(Math.random()*Players.length)];

        // pick a random enemy
        const Enemies = EnemiesApp.GetEnemies();
        const Enemy = Enemies[Math.floor(Math.random()*Enemies.length)];

        // pick a random skill
        const RandomSkillId = randomXToY(1, Enemy.actions.length) - 1;
        const Skill = Enemy.actions[RandomSkillId];

        // grab enemy
        const left = parseInt(Enemy.$ele.css('left'));
        const top = parseInt(Enemy.$ele.css('top'));
        const target = '[data-enemyid="'+ Enemy.$ele.attr('data-enemyid') +'"]';

        // show bubble
        SpeechBubbles.ShowEnemyPlayer(Enemy.name + ' uses ' + Skill.name +'!', 1500);

        // show icon
        Party.PointToAttackedPlayer(Player);

        // Run Action
        Skill.action(Enemy, Player);

        // check if we won or lost
        Battle.CheckDeadState();

        // animate
        anime({
            targets:    target,
            easing:     'easeInOutQuad',
            duration:   200,
            left:       500,
            top:        120,
            offset:     0,
            complete:   function() {
                setTimeout(function() {
                    Party.HideAttackedPlayerCursor();

                    // jump up
                    anime({
                        targets:    target,
                        easing:     'easeOutQuad',
                        left:       left,
                        top:        top,
                        duration:   120,
                    });
                }, Actions.StandDelay);
            }
        });
    }
};
