let Game = {
    // watch the game start button
    WatchStartGameButton: function() {
        let $window = $('.StartWindow');

        $window.find('.btn').on('click', function(event) {
            event.preventDefault();

            $window.find('.logo').fadeOut(300);
            $window.find('.btn').fadeOut(500, function() {
                $window.remove();
                $('.PartyWindow').fadeIn(300);
                Party.PopulatePartyList();
                Party.WatchPartyRoleSelection();
            });
        });

        $('.GameOverRestart').on('click', function() {
            location.reload();
        });
    }
};


Game.WatchStartGameButton();
Battle.WatchForActionSelection();
ClassJobsApp.WatchSelectionButtons();





// auto!
$('.StartButton').trigger('click');
setTimeout(function() {
    let $btn = $('.PartyRole[data-classjob="0"]');
    $btn.trigger('click');
    $btn.trigger('click');
    $btn.trigger('click');
    $btn.trigger('click');
}, 1000);
setTimeout(function() {
    $('.EnterInstanceButton').trigger('click');
}, 2000);
