<?php

function logger($text) {
    echo $text . PHP_EOL;
}

$pages = [
    [ 'img/skills/PLD', 'https://eu.finalfantasyxiv.com/jobguide/paladin/' ],
    [ 'img/skills/WAR', 'https://eu.finalfantasyxiv.com/jobguide/warrior/' ],
    [ 'img/skills/DRK', 'https://eu.finalfantasyxiv.com/jobguide/darkknight/' ],
    [ 'img/skills/WHM', 'https://eu.finalfantasyxiv.com/jobguide/whitemage/' ],
    [ 'img/skills/SCG', 'https://eu.finalfantasyxiv.com/jobguide/scholar/' ],
    [ 'img/skills/AST', 'https://eu.finalfantasyxiv.com/jobguide/astrologian/' ],
    [ 'img/skills/MNK', 'https://eu.finalfantasyxiv.com/jobguide/monk/' ],
    [ 'img/skills/DRG', 'https://eu.finalfantasyxiv.com/jobguide/dragoon/' ],
    [ 'img/skills/NIN', 'https://eu.finalfantasyxiv.com/jobguide/ninja/' ],
    [ 'img/skills/SAM', 'https://eu.finalfantasyxiv.com/jobguide/samurai/' ],
    [ 'img/skills/BRD', 'https://eu.finalfantasyxiv.com/jobguide/bard/' ],
    [ 'img/skills/MCH', 'https://eu.finalfantasyxiv.com/jobguide/machinist/' ],
    [ 'img/skills/BLM', 'https://eu.finalfantasyxiv.com/jobguide/blackmage/' ],
    [ 'img/skills/SMN', 'https://eu.finalfantasyxiv.com/jobguide/summoner/' ],
    [ 'img/skills/RDM', 'https://eu.finalfantasyxiv.com/jobguide/redmage/' ],
];

logger('Downloading pages');
foreach($pages as $page) {
    logger('Download: '. $page[1]);
    
    $html = file_get_contents($page[1]);
    $html = html_entity_decode($html);
    $html = explode("\n", $html);
    $folder = __DIR__.'/'. $page[0];
    
    // make folder
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    
    // grab icons
    foreach($html as $line) {
        if (stripos($line, 'job__skill_icon js__tooltip') !== false) {
            $isPvp = stripos($line, 'pvpaction_') !== false;
            
            $line = explode('"', trim($line));
            
            $name = $line[5];
            $icon = $line[7];
            $iconData = file_get_contents($line[7]);
            
            // Prefix PVP ones
            $name = $isPvp ? 'PVP '. $name : $name;
            $filename = $folder .'/'. $name .'.png';
            
            logger("Saving: {$filename}");
            file_put_contents($filename, $iconData);
        }
    }
    
    logger('Completed '. $page[0]);
    Logger('');
}
