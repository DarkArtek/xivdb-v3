const EnemyDB = {
    SmallRaptor: {
        name:   'Raptor',
        icon:   '/img/enemy/084026.png',
        size:   120,
        isAlive: true,
        healthMax: 200,
        health: 200,
        exp: 300,
        gil: 100,
        actions: [
            {
                name: 'Attack 1',
                action: function(Enemy, Player) {
                    let damage = randomXToY(15, 30);
                    Battle.PlayerOrEnemyAttackTarget(Enemy, Player, damage);
                    Numbers.ShowEnemyDamage(damage);
                }
            }
        ]
    },
    BigRaptor: {
        name: 'Big Raptor',
        icon: '/img/enemy/084026.png',
        size: 200,
        isAlive: true,
        healthMax: 500,
        health: 500,
        exp: 600,
        gil: 100,
        actions: [
            {
                name: 'Attack 1',
                action: function(Enemy, Player) {
                    let damage = randomXToY(15, 50);
                    Battle.PlayerOrEnemyAttackTarget(Enemy, Player, damage);
                    Numbers.ShowEnemyDamage(damage);
                }
            }
        ]
    },
    SmallCoeurl: {
        name:   'Coeurl',
        icon:   '/img/enemy/084019.png',
        size:   120,
        isAlive: true,
        healthMax: 200,
        health: 200,
        exp: 300,
        gil: 100,
        actions: [
            {
                name: 'Attack 1',
                action: function(Enemy, Player) {
                    let damage = randomXToY(15, 30);
                    Battle.PlayerOrEnemyAttackTarget(Enemy, Player, damage);
                    Numbers.ShowEnemyDamage(damage);
                }
            }
        ]
    },
    BigCoeurl: {
        name: 'Big Raptor',
        icon: '/img/enemy/084026.png',
        size: 200,
        isAlive: true,
        healthMax: 500,
        health: 500,
        exp: 600,
        gil: 100,
        actions: [
            {
                name: 'Attack 1',
                action: function(Enemy, Player) {
                    let damage = randomXToY(15, 50);
                    Battle.PlayerOrEnemyAttackTarget(Enemy, Player, damage);
                    Numbers.ShowEnemyDamage(damage);
                }
            }
        ]
    },
};
