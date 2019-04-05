let ClassJobsApp = {
    SelectionActive: false,

    // the choices the player made
    PlayerRoles: {
        1: 1,
        2: 1,
        3: 1,
        4: 1
    },

    // watch for selection list
    WatchSelectionButtons: function() {
        $('.classjobSelectBoxPrev').on('click', function(event) {
            let playerNumber = ($(event.currentTarget).parents('.classjobBox').attr('data-player'));

            if (ClassJobsApp.SelectionActive || ClassJobsApp.PlayerRoles[playerNumber] === 0) {
                return;
            }

            ClassJobsApp.SelectionActive = true;
            ClassJobsApp.PlayerRoles[playerNumber] -= 1;
            ClassJobsApp.MoveSelectionList();
            setTimeout(function() {
                ClassJobsApp.SelectionActive = false;
            }, 300);
        });

        $('.classjobSelectBoxNext').on('click', function(event) {
            let playerNumber = ($(event.currentTarget).parents('.classjobBox').attr('data-player'));

            if (ClassJobsApp.SelectionActive || ClassJobsApp.PlayerRoles[playerNumber] === ClassJobs.length-1) {
                return;
            }

            ClassJobsApp.SelectionActive = true;
            ClassJobsApp.PlayerRoles[playerNumber] += 1;
            ClassJobsApp.MoveSelectionList();
            setTimeout(function() {
                ClassJobsApp.SelectionActive = false;
            }, 300);
        });

        // random party
        $('.RandomPartyButton').on('click', function(event) {
            Party.RandomizePartyList();
        });

        // on entering the instance
        $('.EnterInstanceButton').on('click', function(event) {
            // Remove party window
            $('.PartyWindow').fadeOut(200, function() {
                $('.PartyWindow').remove();

                // spawn PlayerRoles
                Animation.SpawnPlayers();

                setTimeout(function() {
                    Party.RenderPartyList();
                }, 1000);

                // start gcd
                setTimeout(function() {
                    // load hotbar
                    Battle.RunGCD();
                }, 2000)
            })
        });

        return ClassJobsApp;
    },

    GetCharacters: function() {
        // filter alive characters
        let AliveCharacters = [];
        for(let i in ClassJobsApp.PlayerRoles) {
            let Char = ClassJobsApp.PlayerRoles[i];

            if (Char.isAlive) {
                AliveCharacters.push(Char);
            }
        }

        return AliveCharacters;
    }
};
