/**
 * Returns a list of usable skills based on the players level
 */
let ClassJobsGetSkills = function(ActiveClass) {
    let Usable = [];

    // add skills
    for(let key in ActiveClass.skills) {
        let action = ActiveClass.skills[key];

        if (!Usable[key] && typeof action !== 'undefined' && action.level <= PlayerApp.ProfileRaw.Level) {
            Usable[key] = action;
        }
    }

    return Usable;

};

/**
 * Items you can use
 */
let Items = [
    {
        name:   'Attack Boost',
        icon:   '/img/061644.png',
        action: function() {

        }
    },{
        name:   'Defense Boost',
        icon:   '/img/061645.png',
        action: function() {

        }
    },{
        name:   'Health Boost',
        icon:   '/img/061646.png',
        action: function() {

        }
    },{
        name:   'Mag Boost',
        icon:   '/img/061644.png',
        action: function() {

        }
    }
];

/**
 * The available class/jobs
 */
let ClassJobs = [
    // Tanks
    {
        name:       'Paladin',
        typeIcon:   '/img/mini_tank.png',
        sprite:     '/img/sprites/sprite_paladin.png',
        icon:       '/img/sym_paladin.png',
        isAlive: true,
        healthMax: 500,
        health: 500,
        skills: {
            1: {
                level: 1,
                name: 'Fast Blade',
                icon: '/img/skills/PLD/Fast Blade.png',
                tooltip: 'Deliver an attack with 50 damage',
                action: function (Player, Enemy) {
                    // Damage Algo
                    let damage = (200 * PlayerApp.Stats.Attack) * ramdomXToYFloat(0.85, 1.20);

                    // Crit Chance
                    if (PlayerApp.Stats.CritChanceTest()) {
                        damage = damage * (1 + PlayerApp.Stats.CritChance);
                    }

                    damage = Math.ceil(damage);
                    Battle.PlayerOrEnemyAttackTarget(Player, Enemy, damage);
                    Numbers.ShowPlayerDamage(damage);
                },
            },

            2: {
                level: 5,
                name: 'Flash',
                tooltip: 'Blind your enemy',
                icon: '/img/skills/PLD/Flash.png',
                action: function (Player, Enemy) {

                },
            }
        }
    },{
        name:       'Warrior',
        typeIcon:   '/img/mini_tank.png',
        sprite:     '/img/sprites/sprite_warrior.png',
        icon:       '/img/sym_warrior.png',
        isAlive: true,
        healthMax: 500,
        health: 500,
        skills:     [
            {
                level: 1,
                name: 'Heavy Swing',
                icon: '/img/skills/WAR/Heavy Swing.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Dark Knight',
        typeIcon:   '/img/mini_tank.png',
        sprite:     '/img/sprites/sprite_darkknight.png',
        icon:       '/img/sym_darkknight.png',
        isAlive: true,
        healthMax: 500,
        health: 500,
        skills:     [
            {
                level: 1,
                name: 'Hard Slash',
                icon: '/img/skills/DRK/Hard Slash.png',
                action: function(Player, Enemy) {

                },
            }
        ]

    // Healers
    },{
        name:       'White Mage',
        typeIcon:   '/img/mini_healer.png',
        sprite:     '/img/sprites/sprite_whitemage.png',
        icon:       '/img/sym_whitemage.png',
        isAlive: true,
        healthMax: 300,
        health: 300,
        skills:     [
            {
                level: 1,
                name: 'Cure',
                icon: '/img/skills/WHM/Cure.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Scholar',
        typeIcon:   '/img/mini_healer.png',
        sprite:     '/img/sprites/sprite_scholar.png',
        icon:       '/img/sym_scholar.png',
        isAlive: true,
        healthMax: 300,
        health: 300,
        skills:     [
            {
                level: 1,
                name: 'Physick',
                icon: '/img/skills/SCH/Physick.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Astrologian',
        typeIcon:   '/img/mini_healer.png',
        sprite:     '/img/sprites/sprite_astrologian.png',
        icon:       '/img/sym_astrologian.png',
        isAlive: true,
        healthMax: 300,
        health: 300,
        skills:     [
            {
                level: 1,
                name: 'Benefic',
                icon: '/img/skills/AST/Benefic.png',
                action: function(Player, Enemy) {

                },
            }
        ]

    // DPS
    },{
        name:       'Monk',
        typeIcon:   '/img/mini_dps.png',
        sprite:     '/img/sprites/sprite_monk.png',
        icon:       '/img/sym_monk.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Bootshine',
                icon: '/img/skills/MNK/Bootshine.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Dragoon',
        typeIcon:   '/img/mini_dps.png',
        sprite:     '/img/sprites/sprite_dragoon.png',
        icon:       '/img/sym_dragoon.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'True Thrust',
                icon: '/img/skills/MNK/True Thrust.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Ninja',
        typeIcon:   '/img/mini_dps.png',
        sprite:     '/img/sprites/sprite_ninja.png',
        icon:       '/img/sym_ninja.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Spinning Edge',
                icon: '/img/skills/NIN/Spinning Edge.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Samurai',
        typeIcon:   '/img/mini_dps.png',
        sprite:     '/img/sprites/sprite_samurai.png',
        icon:       '/img/sym_samurai.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Hakaze',
                icon: '/img/skills/SAM/Hakaze.png',
                action: function(Player, Enemy) {

                },
            }
        ]

    // RANGED DPS
    },{
        name:       'Bard',
        typeIcon:   '/img/mini_dps_rng.png',
        sprite:     '/img/sprites/sprite_bard.png',
        icon:       '/img/sym_bard.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Heavy Shot',
                icon: '/img/skills/BRD/Heavy Shot.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Machinist',
        typeIcon:   '/img/mini_dps_rng.png',
        sprite:     '/img/sprites/sprite_machinist.png',
        icon:       '/img/sym_machinist.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Split Shot',
                icon: '/img/skills/MCH/Split Shot.png',
                action: function(Player, Enemy) {

                },
            }
        ]

    // MAGIC RANGED DPS
    },{
        name:       'Black Mage',
        typeIcon:   '/img/mini_dps_mag.png',
        sprite:     '/img/sprites/sprite_blackmage.png',
        icon:       '/img/sym_blackmage.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Blizzard',
                icon: '/img/skills/BLM/Blizzard.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Summoner',
        typeIcon:   '/img/mini_dps_mag.png',
        sprite:     '/img/sprites/sprite_summoner.png',
        icon:       '/img/sym_summoner.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Ruin',
                icon: '/img/skills/SMN/Ruin.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    },{
        name:       'Red Mage',
        typeIcon:   '/img/mini_dps_mag.png',
        sprite:     '/img/sprites/sprite_redmage.png',
        icon:       '/img/sym_redmage.png',
        isAlive: true,
        healthMax: 400,
        health: 400,
        skills:     [
            {
                level: 1,
                name: 'Riposte',
                icon: '/img/skills/RDM/Riposte.png',
                action: function(Player, Enemy) {

                },
            }
        ]
    }
];
