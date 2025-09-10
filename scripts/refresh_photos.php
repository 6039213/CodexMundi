<?php
declare(strict_types=1);

use CodexMundi\Core\Database;

require_once __DIR__ . '/../src/bootstrap.php';

$db = Database::conn();

// Curated, watermark-free Wikimedia Commons Special:FilePath links (6 per wonder)
$photoMap = [
    // Ancient (classic)
    'Great Pyramid of Giza' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/All%20Gizah%20Pyramids.jpg','Giza pyramids 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Kheops-Pyramid.jpg','Giza pyramids 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Pyramide%20Kheops.JPG','Giza pyramids 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Khafre%27s%20Pyramid.jpg','Giza pyramids 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Pyramid%20of%20Giza%202010.jpg','Giza pyramids 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Pyramid%20of%20Giza%2C%20Kheops%20009.JPG','Giza pyramids 6'],
    ],
    'Hanging Gardens of Babylon' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20Maarten%20van%20Heemskerck.jpg','Hanging Gardens 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20engraving.jpg','Hanging Gardens 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20illustration%2019thC.jpg','Hanging Gardens 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20Babylon%20reconstruction.jpg','Hanging Gardens 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20Babylon%20depiction.jpg','Hanging Gardens 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20drawing.jpg','Hanging Gardens 6'],
    ],
    'Statue of Zeus at Olympia' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Statue%20of%20Zeus%20at%20Olympia%20engraving.jpg','Zeus at Olympia 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Zeus%20Olympia%20illustration.jpg','Zeus at Olympia 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Statue%20of%20Zeus%20Olympia%20drawing.jpg','Zeus at Olympia 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Zeus%20Olympia%20depiction.jpg','Zeus at Olympia 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Statue%20of%20Zeus%20painting.jpg','Zeus at Olympia 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Zeus%20statue%20Olympia.jpg','Zeus at Olympia 6'],
    ],
    'Temple of Artemis at Ephesus' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20at%20Ephesus.jpg','Temple of Artemis 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20Ephesus%20engraving.jpg','Temple of Artemis 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20drawing.jpg','Temple of Artemis 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20painting.jpg','Temple of Artemis 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20reconstruction.jpg','Temple of Artemis 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20Ephesus%20columns.jpg','Temple of Artemis 6'],
    ],
    'Mausoleum at Halicarnassus' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20at%20Halicarnassus%20engraving.jpg','Mausoleum 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20Halicarnassus%20illustration.jpg','Mausoleum 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20of%20Halicarnassus%20reconstruction.jpg','Mausoleum 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Halicarnassus%20Mausoleum.jpg','Mausoleum 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20Halicarnassus%20depiction.jpg','Mausoleum 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20Halicarnassus%20model.jpg','Mausoleum 6'],
    ],
    'Colossus of Rhodes' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20of%20Rhodes%20illustration.jpg','Colossus 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20of%20Rhodes%20Maarten%20van%20Heemskerck.jpg','Colossus 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20Rhodes%20drawing.jpg','Colossus 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20of%20Rhodes%20engraving.jpg','Colossus 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20Rhodes%20depiction.jpg','Colossus 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20Rhodes%20painting.jpg','Colossus 6'],
    ],
    'Lighthouse of Alexandria' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Lighthouse%20of%20Alexandria%20engraving.jpg','Pharos 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Pharos%20of%20Alexandria%20drawing.jpg','Pharos 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Pharos%20Alexandria%20reconstruction.jpg','Pharos 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Alexandria%20Pharos%20illustration.jpg','Pharos 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Lighthouse%20Alexandria%20depiction.jpg','Pharos 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Pharos%20Alexandria%20artwork.jpg','Pharos 6'],
    ],
    // New 7 Wonders (modern)
    'Chichén Itzá' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20El%20Castillo.jpg','Chichen Itza 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20Temple%20Kukulkan.jpg','Chichen Itza 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20pyramid.jpg','Chichen Itza 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/El%20Castillo%20Chichen%20Itza.jpg','Chichen Itza 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20ruins.jpg','Chichen Itza 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20Mexico.jpg','Chichen Itza 6'],
    ],
    'Christ the Redeemer' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20Rio%20de%20Janeiro.jpg','Christ the Redeemer 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20Corcovado.jpg','Christ the Redeemer 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Christ%20the%20Redeemer%20aerial%20view.jpg','Christ the Redeemer 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Christ%20the%20Redeemer%20closeup.jpg','Christ the Redeemer 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20at%20sunset.jpg','Christ the Redeemer 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20view%20from%20Sugarloaf.jpg','Christ the Redeemer 6'],
    ],
    'Colosseum' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20Rome%20Italy.jpg','Colosseum 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20exterior.jpg','Colosseum 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20inside.jpg','Colosseum 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20interior.jpg','Colosseum 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20panorama.jpg','Colosseum 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20at%20night.jpg','Colosseum 6'],
    ],
    'Great Wall of China' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/20090529%20Great%20Wall%208185.jpg','Great Wall 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20Badaling.JPG','Great Wall 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20of%20China%20Jinshanling.jpg','Great Wall 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20of%20China%20Mutianyu.jpg','Great Wall 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20Juyongguan.jpg','Great Wall 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20Simatai.jpg','Great Wall 6'],
    ],
    'Machu Picchu' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20Peru.jpg','Machu Picchu 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20view.jpg','Machu Picchu 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20panorama.jpg','Machu Picchu 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20Sunrise.jpg','Machu Picchu 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20Terraces.jpg','Machu Picchu 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20mountains.jpg','Machu Picchu 6'],
    ],
    'Petra' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Al-Khazneh%20Petra%20Jordan.jpg','Petra 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Treasury%20Siq.jpg','Petra 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Monastery.jpg','Petra 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Royal%20Tombs.jpg','Petra 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Theatre.jpg','Petra 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Urn%20Tomb.jpg','Petra 6'],
    ],
    'Taj Mahal' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20Agra%20India.jpg','Taj Mahal 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20sunrise.jpg','Taj Mahal 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20reflection.jpg','Taj Mahal 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20front%20view.jpg','Taj Mahal 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20garden.jpg','Taj Mahal 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20side%20view.jpg','Taj Mahal 6'],
    ],
    // New7Wonders of Nature present in DB
    'Amazon Rainforest' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20Rainforest%20river.jpg','Amazon 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20Rainforest%20aerial.jpg','Amazon 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20jungle%20trees.jpg','Amazon 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20Rainforest%20sunset.jpg','Amazon 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20River%20forest.jpg','Amazon 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20forest%20Brazil.jpg','Amazon 6'],
    ],
    'Ha Long Bay' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20Vietnam.jpg','Ha Long Bay 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20view.jpg','Ha Long Bay 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20boats.jpg','Ha Long Bay 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20cliffs.jpg','Ha Long Bay 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20sunset.jpg','Ha Long Bay 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20limestone.jpg','Ha Long Bay 6'],
    ],
    'Iguazu Falls' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20Brazil.jpg','Iguazu 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20Argentina.jpg','Iguazu 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20panorama.jpg','Iguazu 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20waterfall.jpg','Iguazu 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20rainbow.jpg','Iguazu 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20jungle.jpg','Iguazu 6'],
    ],
    'Jeju Island' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20Korea.jpg','Jeju 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20landscape.jpg','Jeju 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20Seongsan.jpg','Jeju 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20coast.jpg','Jeju 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20sunset.jpg','Jeju 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20mountains.jpg','Jeju 6'],
    ],
    'Komodo National Park' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20Indonesia.jpg','Komodo 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20National%20Park.jpg','Komodo 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20beach.jpg','Komodo 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20dragon%20island.jpg','Komodo 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20aerial.jpg','Komodo 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20view.jpg','Komodo 6'],
    ],
    'Table Mountain' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20Cape%20Town.jpg','Table Mountain 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20view.jpg','Table Mountain 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20South%20Africa.jpg','Table Mountain 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20sunset.jpg','Table Mountain 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20aerial.jpg','Table Mountain 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20clouds.jpg','Table Mountain 6'],
    ],
    'Puerto Princesa Underground River' => [
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20Philippines.jpg','Puerto Princesa 1'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20cave.jpg','Puerto Princesa 2'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20boats.jpg','Puerto Princesa 3'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20entrance.jpg','Puerto Princesa 4'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20inside.jpg','Puerto Princesa 5'],
        ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20tour.jpg','Puerto Princesa 6'],
    ],
];

$db->beginTransaction();
try {
    $findWonder = $db->prepare('SELECT id FROM wonders WHERE name=?');
    $delExternal = $db->prepare("DELETE FROM photos WHERE wonder_id=? AND (path NOT LIKE '/%')");
    $insertPhoto = $db->prepare('INSERT INTO photos (wonder_id,path,title,approved,uploaded_by,created_at) VALUES (?,?,?,?,?,?)');

    foreach ($photoMap as $name => $items) {
        $findWonder->execute([$name]);
        $wid = $findWonder->fetchColumn();
        if (!$wid) { continue; }

        // Remove existing external photos to avoid duplicates and bad links
        $delExternal->execute([(int)$wid]);

        // Insert curated set (6 per wonder)
        foreach ($items as $it) {
            $url = (string)$it[0];
            $title = (string)($it[1] ?? '');
            $insertPhoto->execute([(int)$wid, $url, $title, 1, null, date('c')]);
        }
    }

    $db->commit();
    echo "Refreshed photos successfully.\n";
} catch (Throwable $e) {
    $db->rollBack();
    fwrite(STDERR, 'Error: ' . $e->getMessage() . "\n");
    exit(1);
}



