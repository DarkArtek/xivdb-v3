let Battle = {
    Complete: false,
    PlayerWin: false,
    EnemyWin: false,
    GlobalCooldownSpeed: 1000,
    ActiveSkill: false,
    ActivePlayer: false,
    HotbarActive: false,
    Hotbar: [
        1,2,3,4,5,6,7,8,9,0
    ],

    // Run the global cool down visual
    RunGCD: function() {
        console.log('--- COMBAT CYCLE ---');
        // select a random character
        let AliveCharacters = ClassJobsApp.GetCharacters();
        Battle.ActivePlayer = AliveCharacters[Math.floor(Math.random()*AliveCharacters.length)];
        console.log(Battle.ActivePlayer);

        // fade in the global cooldown progress bar
        $('.GameGCD').fadeIn(150);

        // animate it
        anime({
            targets:    '.GameProgress span',
            easing:     'linear',
            width:      '100%',
            duration:   Battle.GlobalCooldownSpeed,
            complete:   function() {
                $('.GameGCD').fadeOut(100, function() {
                    $('.GameProgress span').css('width', 0);
                });
            }
        });

        setTimeout(function() {
            Battle.ShowSkillsForActivePlayer();
            Battle.HotbarActive = true;
        }, (Battle.GlobalCooldownSpeed));
    },

    // Show skills for the active player
    ShowSkillsForActivePlayer: function() {
        Battle.HotbarActive = true;

        // show active class
        $('.GameSkillsClassName').html('<img src="'+ Battle.ActivePlayer.typeIcon +'" height="24"> ' + Battle.ActivePlayer.name).fadeIn(50);
        Party.PointToActivePlayer();

        // game skills bar
        let $GS = $('.GameSkills');
        $GS.html('');

        let ActivePlayerSkills = ClassJobsGetSkills(Battle.ActivePlayer);

        // load skills
        for(let key in Object.keys(Battle.Hotbar)) {
            let keyVisual = Battle.Hotbar[key],
                skill = ActivePlayerSkills[keyVisual];

            if (typeof skill !== 'undefined') {
                $GS.append(
                    '<div class="GameHotKey" data-skillid="'+ keyVisual +'" data-toggle="tooltip" data-placement="top" title="'+ skill.name +': '+ skill.tooltip +'">'+
                        '<img src="'+ skill.icon +'" height="64">'+
                        '<span>'+ keyVisual +'</span>'+
                    '</div>'
                )
            } else {
                $GS.append(
                    '<div class="GameHotKey">'+
                        '<img src="/img/skills/blank.png" height="64">'+
                        '<span>'+ keyVisual +'</span>'+
                    '</div>'
                )
            }
        }


        // hook tooltips
        $GS.fadeIn(100);
        $('[data-toggle="tooltip"]').tooltip();
    },

    // Watch for active selection
    WatchForActionSelection: function() {
        const $html = $('html');

        // hotkey press
        $html.on('keyup', function(event) {
            if (!Battle.HotbarActive) {
                return;
            }

            const key = event.key;
            const $button = $('[data-skillid="'+ key +'"]');

            let action = Battle.ActivePlayer.skills[key];

            if (typeof action !== 'undefined') {
                event.preventDefault();
                Battle.RequestTarget($button, action);
                Party.HideActivePlayerCursor();
            }
        });

        // click
        $html.on('click', '.GameHotKey', function(event) {
            if (!Battle.HotbarActive) {
                return;
            }

            event.preventDefault();
            const key = $(event.currentTarget).attr('data-skillid');
            const $button = $('[data-skillid="'+ key +'"]');

            let action = Battle.ActivePlayer.skills[key];

            if (typeof action !== 'undefined') {
                Battle.RequestTarget($button, action);
                Party.HideActivePlayerCursor();
            }
        });

        // click an enemy
        $html.on('click', '.enemy:not(.isDead)', function(event) {
            // only do action if one set
            if (!Battle.HotbarActive || !Battle.ActiveSkill) {
                return false;
            }

            // set hotbar active
            Battle.HotbarActive = false;

            // grab enemy id
            const EnemyId = $(event.currentTarget).attr('data-enemyid');
            const Enemy = EnemiesApp.GetEnemyOnFloor(EnemyId);
            SpeechBubbles.Hide();

            // fire action
            Actions.PerformPlayerAction(
                Battle.ActiveSkill,
                Battle.ActivePlayer,
                Enemy
            );

            // hide hotbar
            $('.HotKeyPulse').removeClass('HotKeyPulse');
            $('.GameSkillsClassName').fadeOut(150);
            $('.GameSkills').fadeOut(250);

            // loop
            setTimeout(function() {
                // if finished, return
                if (Battle.Complete) {
                    Battle.ShowWinner();
                    return;
                }

                Battle.RunGCD();
                Battle.ActiveSkill = false;
            }, Actions.DefaultActionDuration);
        });
    },

    // Perform an action
    RequestTarget: function($KeyButton, Action) {
        // set active skill
        Battle.ActiveSkill = Action;

        // pulse the active button
        $('.HotKeyPulse').removeClass('HotKeyPulse');
        $KeyButton.find('img').addClass('HotKeyPulse');

        // show system message
        SpeechBubbles.ShowSystemStay('Select a target');
    },

    PlayerOrEnemyAttackTarget: function(Initiator, Target, Damage)
    {
        Target.health -= Damage;

        // if died
        if (Target.health < 0) {
            Target.health = 0;
            Target.isAlive = false;
            Target.$ele.addClass('isDead');

            console.log(Target);

            // reward exp
            if (typeof Target.exp !== 'undefined') {
                PlayerApp.AddExperience(Target.exp);
                PlayerApp.AddGil(Target.gil);
            }
        }

        const percent = Target.health !== 0
            ? (Target.health / Target.healthMax) * 100
            : 0;

        Target.$ele.find('.health span').css('width', percent + '%');

        // update pt list
        let $PTMember = $('.ptid'+ Target.id);
        $PTMember.find('.HealthBar span').css('width', percent + '%');
        $PTMember.find('.PartyNameBar strong').text(Target.health);

        Battle.CheckDeadState();
    },

    PlayerOrEnemyHealTarget: function(Player, Target, Amount)
    {
        Target.health += Amount;

        if (Target.health > Target.healthMax) {
            Target.health = Target.healthMax;
        }

        const percent = Target.health !== 0
            ? (Target.health / Target.healthMax) * 100
            : 0;

        Target.$ele.find('.health span').css('width', percent + '%');
    },

    OpenInventory: function() {
        console.log('Open Inventory');
    },

    CheckDeadState: function() {
        if (ClassJobsApp.GetCharacters().length === 0) {
            Battle.Complete = true;
            Battle.EnemyWin = true;
            return;
        }

        if (EnemiesApp.GetEnemies().length === 0) {
            Battle.Complete = true;
            Battle.PlayerWin = true;
        }
    },

    ShowWinner: function() {
        if (!Battle.Complete) {
            return;
        }

        if (Battle.PlayerWin) {
            SpeechBubbles.Hide();

            // reward EXP and Gil
            PlayerApp.AddExperience(EnemiesApp.ActiveFloorData.exp);
            PlayerApp.AddGil(EnemiesApp.ActiveFloorData.gil);

            // load next floor

            console.log('You Win!');

            // remove enemies
            $('.party-left .enemy').fadeOut(1000, function() {
                $('.party-left .enemy').remove();
            });

            // if floor 50
            if (EnemiesApp.ActiveFloor === 50) {
                // todo COMPLETE
                return;
            }

            setTimeout(function() {
                // reset
                Battle.Complete = false;
                Battle.PlayerWin = false;
                Battle.EnemyWin = false;
                Battle.ActiveSkill = false;
                Battle.ActivePlayer = false;
                Battle.HotbarActive = false;

                // load next floor
                // todo - trigger this after reward screen
                EnemiesApp.SpawnFloor(EnemiesApp.ActiveFloor+1);
                Battle.RunGCD();

            }, 5000);
        }

        if (Battle.EnemyWin) {
            SpeechBubbles.Hide();

            const $ga = $('.GameOver');
            $ga.find('em').text(EnemiesApp.ActiveFloor);
            $ga.fadeIn(500);
        }
    }
};
