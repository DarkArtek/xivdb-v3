let Party = {
    PartySelection: {
        1: false,
        2: false,
        3: false,
        4: false,
    },

    PopulatePartyList: function () {
        const $ui = $('.PartySelection > div:last-of-type');

        for (let i in ClassJobs) {
            let role = ClassJobs[i];

            // render
            $ui.append(
                '<div class="PartyRole" data-classjob="' + i + '">' +
                '<img src="' + role.sprite + '" class="PartyRoleSprite">' +
                '<img src="' + role.typeIcon + '" class="PartyRoleType" height="24">' +
                '<span>' + role.name + '</span>' +
                '</div>'
            )
        }
    },

    WatchPartyRoleSelection: function () {
        const $html = $('html');

        // On clicking a party member
        $html.on('click', '.PartyRole[data-classjob]', function (event) {
            const classJobId = $(event.currentTarget).attr('data-classjob');

            // populate the next empty one
            for (let i in Party.PartySelection) {
                let Role = Party.PartySelection[i];

                if (Role === false) {
                    Party.PartySelection[i] = ClassJobs[classJobId];
                    break;
                }
            }

            Party.RenderMemberList();
        });

        // On clicking a
        $html.on('click', '.RemovePartyMember', function (event) {
            const slotId = $(event.currentTarget).parents('.PartyMember').attr('data-slot');
            Party.PartySelection[slotId] = false;
            Party.RenderMemberList();
        });
    },

    RandomizePartyList: function () {
        Party.PartySelection[1] = ClassJobs[randomXToY(0, ClassJobs.length - 1)];
        Party.PartySelection[2] = ClassJobs[randomXToY(0, ClassJobs.length - 1)];
        Party.PartySelection[3] = ClassJobs[randomXToY(0, ClassJobs.length - 1)];
        Party.PartySelection[4] = ClassJobs[randomXToY(0, ClassJobs.length - 1)];
        Party.RenderMemberList();
    },

    RenderMemberList: function () {
        let AnyEmptySlots = false;
        for (let i in Party.PartySelection) {
            let Role = Party.PartySelection[i];

            const $ui = $('.PartyMember' + i);

            if (Role !== false) {
                $ui.html(
                    '<img src="' + Role.icon + '" class="RoleIcon" height="42"> ' + Role.name + ' <button class="btn btn-link btn-sm RemovePartyMember"><img src="/img/close.png" height="16"></button>'
                );
            } else {
                $ui.html('Select Member ' + i);
                AnyEmptySlots = true;
            }
        }

        $('.EnterInstanceButton').attr('disabled', AnyEmptySlots)
    },

    PointToActivePlayer: function () {
        const $cursor = $('.ActivePlayerCursor');
        $cursor.css({
            left: parseInt(Battle.ActivePlayer.$ele.css('left')) + 20,
            top: parseInt(Battle.ActivePlayer.$ele.css('top')) - 40,
        });

        $cursor.fadeIn(200);
    },

    HideActivePlayerCursor: function () {
        const $cursor = $('.ActivePlayerCursor');
        $cursor.fadeOut(200)
    },

    PointToAttackedPlayer: function (Player) {
        const $cursor = $('.ActiveEnemyCursor');

        $cursor.css({
            left: parseInt(Player.$ele.css('left')) + 30,
            top: parseInt(Player.$ele.css('top')) + 30,
        });

        $cursor.fadeIn(200);
    },

    HideAttackedPlayerCursor: function() {
        const $cursor = $('.ActiveEnemyCursor');
        $cursor.fadeOut(200);
    },

    RenderPartyList: function() {
        let $ui = $('.PartyList');

        for(let i in ClassJobsApp.PlayerRoles) {
            let Role = ClassJobsApp.PlayerRoles[i];

            $ui.append(
                '<div class="pt'+ i +' ptid'+ Role.id +'">' +
                    '<div>' +
                        '<div class="PartyNameBar">' +
                            '<span><strong>'+ Role.health +'</strong></span>' +
                            '<div class="PartyNameText">'+ Role.name +' '+ i +'</div>'+
                        '</div>' +
                        '<div class="HealthDisplay"><div class="HealthBar"><span style="width: 100%"></span></div></div>' +
                    '</div>' +
                    '<div><img src="'+ Role.icon +'" height="52"></div>' +
                '</div>'
            );
        }

        // fade in
        anime({
            targets: '.PartyList',
            opacity: 1,
            duration: 250,
            easing: 'easeInOutQuad',
            offset: 0,
        });

        // fade in
        anime({
            targets: '.pt1',
            opacity: 1,
            duration: 500,
            easing: 'easeInOutQuad',
            offset: 0,
        });

        anime({
            targets: '.pt2',
            opacity: 1,
            duration: 500,
            easing: 'easeInOutQuad',
            offset: 300,
        });

        anime({
            targets: '.pt3',
            opacity: 1,
            duration: 500,
            easing: 'easeInOutQuad',
            offset: 600,
        });

        anime({
            targets: '.pt4',
            opacity: 1,
            duration: 500,
            easing: 'easeInOutQuad',
            offset: 900,
        });
    }
};
