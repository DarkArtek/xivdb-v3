let PlayerApp = {
    Profile: {
        Gil: 0,
        Level: 1,
        Experience: 0
    },

    ProfileRaw: {
        Gil: 0,
        Level: 1,
        Experience: 0
    },

    Stats: {
        Attack: 1.5,
        MagicAttack: 1.75,
        Defense: 2,
        CritChance: 0.15,
        IsCrit: false,
        CritChanceTest: function() {
            PlayerApp.Stats.IsCrit = ramdomXToYFloat(0.005, 0.95) < PlayerApp.Stats.CritChance;
            return PlayerApp.Stats.IsCrit;
        }
    },

    UpdatePlayerRewards: function()
    {
        anime({
            targets: PlayerApp.Profile,
            Gil: PlayerApp.ProfileRaw.Gil,
            Level: PlayerApp.ProfileRaw.Level,
            Experience: PlayerApp.ProfileRaw.Experience,
            easing: 'linear',
            round: 1,
            duration: 200,
            update: function() {
                $('.GameUserBar .Gil em').html(PlayerApp.Profile.Gil);
                $('.GameUserBar .Level em').html(PlayerApp.Profile.Level);
                $('.GameUserBar .Experience em').html(PlayerApp.Profile.Experience);
            }
        });
    },

    // don't reward more than 1000, cba working it out :D
    AddExperience: function(Exp)
    {
        // Randomize exp
        Exp = randomXToY((Exp * 0.8), (Exp * 1.5));
        Exp = (Exp > 999) ? 999 : Exp;
        PlayerApp.ProfileRaw.Experience += Exp;

        if (PlayerApp.ProfileRaw.Experience >= 999) {
            PlayerApp.AddLevelUp();
            PlayerApp.ProfileRaw.Experience -= 999;
        }

        PlayerApp.UpdatePlayerRewards();
    },

    // reward some gill to the player
    AddGil: function(Gil)
    {
        // Randomize gil
        Gil = randomXToY((Gil * 0.8), (Gil * 1.5));
        PlayerApp.ProfileRaw.Gil += Gil;
        PlayerApp.UpdatePlayerRewards();
    },

    // level up the player
    AddLevelUp: function()
    {
        PlayerApp.ProfileRaw.Level += 1;
        PlayerApp.UpdatePlayerRewards();

        // Increase stats
        PlayerApp.Stats.Attack += 0.2;
        PlayerApp.Stats.MagicAttack += 0.2;
        PlayerApp.Stats.Defense += 0.35;

        // increase stats
        for(let i in ClassJobsApp.PlayerRoles) {
            let Role = ClassJobsApp.PlayerRoles[i];

            // increase health and give full HP
            Role.healthMax = Math.ceil(Role.healthMax * 1.20);
            Role.health = Role.healthMax;

            percent = 100;
            Role.$ele.find('.health span').css('width', percent + '%');

            // update pt list
            let $PTMember = $('.ptid'+ Role.id);
            $PTMember.find('.HealthBar span').css('width', percent + '%');
            $PTMember.find('.PartyNameBar strong').text(Role.health);
        }
    }
};
