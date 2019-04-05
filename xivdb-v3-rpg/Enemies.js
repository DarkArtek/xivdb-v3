let Enemies = {
    // Floor 1
    1: {
        name: 'Wild Raptors!',
        exp: 500,
        gil: 500,
        list: [
            _.merge({ pos: 1 }, EnemyDB.SmallRaptor),
            _.merge({ pos: 2 }, EnemyDB.BigRaptor),
            _.merge({ pos: 3 }, EnemyDB.SmallRaptor)
        ]
    },

    // Floor 2
    2: {
        name: 'Crazy Coeurl!',
        exp: 500,
        gil: 500,
        list: [
            _.merge({ pos: 1 }, EnemyDB.SmallCoeurl),
            _.merge({ pos: 2 }, EnemyDB.BigCoeurl),
            _.merge({ pos: 3 }, EnemyDB.SmallCoeurl)
        ]
    }
};
