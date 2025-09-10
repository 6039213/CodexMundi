<?php
declare(strict_types=1);

namespace CodexMundi\Core;

use PDO;

class Migrations {
    public static function run(): void {
        $db = Database::conn();

        // users & roles
        $db->exec('CREATE TABLE IF NOT EXISTS roles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        );');

        $db->exec('CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            name TEXT NOT NULL,
            role_id INTEGER NOT NULL,
            created_at TEXT NOT NULL,
            FOREIGN KEY(role_id) REFERENCES roles(id)
        );');

        // wonders
        $db->exec('CREATE TABLE IF NOT EXISTS wonders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            short_description TEXT,
            year INTEGER,
            continent TEXT,
            type TEXT, -- classic or modern
            exists_now INTEGER DEFAULT 1, -- 0/1
            myth TEXT,
            story TEXT,
            lat REAL,
            lng REAL,
            approved INTEGER DEFAULT 0,
            created_by INTEGER,
            updated_by INTEGER,
            view_count INTEGER DEFAULT 0,
            created_at TEXT NOT NULL,
            updated_at TEXT NOT NULL,
            FOREIGN KEY(created_by) REFERENCES users(id),
            FOREIGN KEY(updated_by) REFERENCES users(id)
        );');

        // tags
        $db->exec('CREATE TABLE IF NOT EXISTS tags (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        );');
        $db->exec('CREATE TABLE IF NOT EXISTS wonder_tags (
            wonder_id INTEGER NOT NULL,
            tag_id INTEGER NOT NULL,
            PRIMARY KEY (wonder_id, tag_id),
            FOREIGN KEY(wonder_id) REFERENCES wonders(id) ON DELETE CASCADE,
            FOREIGN KEY(tag_id) REFERENCES tags(id) ON DELETE CASCADE
        );');

        // media
        $db->exec('CREATE TABLE IF NOT EXISTS photos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            wonder_id INTEGER NOT NULL,
            path TEXT NOT NULL,
            title TEXT,
            approved INTEGER DEFAULT 0,
            uploaded_by INTEGER,
            created_at TEXT NOT NULL,
            FOREIGN KEY(wonder_id) REFERENCES wonders(id) ON DELETE CASCADE,
            FOREIGN KEY(uploaded_by) REFERENCES users(id)
        );');

        $db->exec('CREATE TABLE IF NOT EXISTS documents (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            wonder_id INTEGER NOT NULL,
            path TEXT NOT NULL,
            title TEXT,
            uploaded_by INTEGER,
            created_at TEXT NOT NULL,
            FOREIGN KEY(wonder_id) REFERENCES wonders(id) ON DELETE CASCADE,
            FOREIGN KEY(uploaded_by) REFERENCES users(id)
        );');

        // audit log
        $db->exec('CREATE TABLE IF NOT EXISTS audit_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            action TEXT NOT NULL,
            entity TEXT NOT NULL,
            entity_id INTEGER,
            created_at TEXT NOT NULL,
            details TEXT,
            FOREIGN KEY(user_id) REFERENCES users(id)
        );');

        // config
        $db->exec('CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT
        );');

        self::seed();
    }

    private static function seed(): void {
        $db = Database::conn();

        $roles = ['bezoeker','onderzoeker','redacteur','archivaris','beheerder'];
        foreach ($roles as $r) {
            $stmt = $db->prepare('INSERT OR IGNORE INTO roles (name) VALUES (?)');
            $stmt->execute([$r]);
        }

        // Ensure an admin account exists (requested credentials)
        $adminRoleId = (int) $db->query("SELECT id FROM roles WHERE name='beheerder'")->fetchColumn();
        $now = date('c');
        $stmt = $db->prepare(
            'INSERT INTO users (email, password, name, role_id, created_at) VALUES (?,?,?,?,?) '
            . 'ON CONFLICT(email) DO UPDATE SET password=excluded.password, role_id=excluded.role_id'
        );
        $stmt->execute([
            'admin@codexmundi.com',
            password_hash('codexmundi2025', PASSWORD_DEFAULT),
            'Admin',
            $adminRoleId,
            $now
        ]);

        // Seed world wonders if table empty (classic + modern)
        $wCount = (int) $db->query('SELECT COUNT(*) FROM wonders')->fetchColumn();
        if ($wCount === 0) {
            $now = date('c');
            $wonders = [
                // Ancient (classic)
                ['Great Pyramid of Giza','Enige overlevende van de klassieke zeven wereldwonderen.', -2560, 'Africa','classic', 1, 29.9792, 31.1342],
                ['Hanging Gardens of Babylon','Legendarische tuinen in Babylon, mogelijk mythisch.', -600, 'Asia','classic', 0, 32.5364, 44.4200],
                ['Statue of Zeus at Olympia','Reusachtig beeld van Zeus door Phidias.', -435, 'Europe','classic', 0, 37.6370, 21.6300],
                ['Temple of Artemis at Ephesus','Grootse tempel gewijd aan Artemis.', -550, 'Asia','classic', 0, 37.9497, 27.3639],
                ['Mausoleum at Halicarnassus','Grafmonument voor Mausolos.', -350, 'Asia','classic', 0, 37.0379, 27.4241],
                ['Colossus of Rhodes','Gigantisch bronzen beeld in de haven van Rhodos.', -280, 'Europe','classic', 0, 36.4510, 28.2278],
                ['Lighthouse of Alexandria','Pharos, antieke vuurtoren bij Alexandrië.', -280, 'Africa','classic', 0, 31.2135, 29.8853],
                // New 7 Wonders (modern)
                ['Chichén Itzá','Maya‑stad met de piramide El Castillo.', 600, 'North America','modern', 1, 20.6843, -88.5678],
                ['Christ the Redeemer','Iconisch standbeeld dat over Rio de Janeiro uitkijkt.', 1931, 'South America','modern', 1, -22.9519, -43.2105],
                ['Colosseum','Romeins amfitheater in Rome.', 80, 'Europe','modern', 1, 41.8902, 12.4922],
                ['Great Wall of China','Uitgestrekte verdedigingsmuur in China.', -220, 'Asia','modern', 1, 40.4319, 116.5704],
                ['Machu Picchu','Inca‑citadel in de Andes.', 1450, 'South America','modern', 1, -13.1631, -72.5450],
                ['Petra','Rotsstad in Jordanië met uitgehouwen gevels.', -300, 'Asia','modern', 1, 30.3285, 35.4444],
                ['Taj Mahal','Mausoleum in Agra, India.', 1653, 'Asia','modern', 1, 27.1751, 78.0421],
            ];
            $stmt = $db->prepare('INSERT INTO wonders (name, short_description, year, continent, type, exists_now, myth, story, lat, lng, approved, created_by, updated_by, view_count, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            foreach ($wonders as $w) {
                [$name,$short,$year,$continent,$type,$exists,$lat,$lng] = [$w[0],$w[1],$w[2],$w[3],$w[4],$w[5],$w[6],$w[7]];
                $stmt->execute([
                    $name,
                    $short,
                    $year,
                    $continent,
                    $type,
                    $exists,
                    '', // myth
                    '', // story
                    $lat,
                    $lng,
                    1, // approved
                    null,
                    null,
                    0,
                    $now,
                    $now,
                ]);
            }
        }

        // Ensure there is at least one approved cover photo per wonder using generated placeholders
        try {
            // Create upload directory if missing
            $photosDir = \CodexMundi\Config::UPLOAD_DIR_PHOTOS;
            if (!is_dir($photosDir)) { @mkdir($photosDir, 0777, true); }

            $wRows = $db->query('SELECT id, name FROM wonders')->fetchAll(PDO::FETCH_ASSOC);
            $coverStmt = $db->prepare('SELECT id FROM photos WHERE wonder_id=? AND approved=1 LIMIT 1');
            $insPhoto = $db->prepare('INSERT INTO photos (wonder_id,path,title,approved,uploaded_by,created_at) VALUES (?,?,?,?,?,?)');
            foreach ($wRows as $wr) {
                $wid = (int)$wr['id'];
                $coverStmt->execute([$wid]);
                $has = $coverStmt->fetchColumn();
                if ($has) { continue; }

                $file = 'wonder_' . $wid . '.svg';
                $abs = rtrim($photosDir, '/\\') . DIRECTORY_SEPARATOR . $file;
                if (!is_file($abs)) {
                    $svg = self::placeholderSvg((string)$wr['name']);
                    @file_put_contents($abs, $svg);
                }
                $insPhoto->execute([$wid, '/uploads/photos/' . $file, (string)$wr['name'], 1, null, date('c')]);
            }
        } catch (\Throwable $e) {
            // best-effort; ignore failures
        }

        // Enrich textual information for each wonder (facts, not copyrighted text)
        try {
            $info = [
                'Great Pyramid of Giza' => [
                    'short' => 'De oudste en grootste piramide in Gizeh, gebouwd voor farao Cheops.',
                    'story' => 'De Grote Piramide (ca. 2560 v.Chr.) is het enige klassieke wereldwonder dat nog overeind staat. Het bouwwerk maakte deel uit van een groter dodencomplex met mastaba’s en tempels. Het getuigt van geavanceerde bouwkunde, organisatie en astronomische oriëntatie.'
                ],
                'Hanging Gardens of Babylon' => [
                    'short' => 'Legendarische terrastuinen in Babylon, mogelijk mythisch of elders gesitueerd.',
                    'story' => 'Antieke bronnen beschrijven een kunstmatig irrigatiesysteem en weelderige terrassen. Archeologisch bewijs is schaars; sommige theorieën plaatsen de tuinen in Nineveh. Het wonder blijft een mix van geschiedenis en mythe.'
                ],
                'Statue of Zeus at Olympia' => [
                    'short' => 'Reusachtig chryselefantien beeld van Zeus door Phidias in Olympia.',
                    'story' => 'Het beeld (5e eeuw v.Chr.) stond in de tempel van Zeus en was een toonbeeld van klassieke beeldhouwkunst. Het verdween in de late oudheid, mogelijk door brand.'
                ],
                'Temple of Artemis at Ephesus' => [
                    'short' => 'Monumentale tempel gewijd aan de godin Artemis in Efeze.',
                    'story' => 'Meerdere malen herbouwd; bekend om zijn omvang en rijke versiering. Vernield door brandstichting en plunderingen; de resten vormden eeuwenlang een steengroeve.'
                ],
                'Mausoleum at Halicarnassus' => [
                    'short' => 'Grafmonument voor satraap Mausolos en Artemisia II.',
                    'story' => 'Het mausoleum (4e eeuw v.Chr.) gaf zijn naam aan alle “mausolea”. Rijk gedecoreerd met reliëfs van beroemde beeldhouwers. Aardbevingen en hergebruik van steen maakten er uiteindelijk een ruïne van.'
                ],
                'Colossus of Rhodes' => [
                    'short' => 'Gigantisch bronzen beeld van de zonnegod Helios in Rhodos.',
                    'story' => 'Gebouwd na een belegering (3e eeuw v.Chr.). Het beeld stond vermoedelijk naast de haven en stortte door een aardbeving in. De bronzen resten zouden later zijn verkocht.'
                ],
                'Lighthouse of Alexandria' => [
                    'short' => 'Antieke vuurtoren (Pharos) die schepen naar Alexandrië leidde.',
                    'story' => 'Een technisch meesterwerk op het eiland Pharos. Aardbevingen beschadigden het bouwwerk; in de middeleeuwen verdween het definitief. Fundamenten zijn mogelijk onder water teruggevonden.'
                ],
                'Chichén Itzá' => [
                    'short' => 'Maya-stad op Yucatán, beroemd om El Castillo en cenotes.',
                    'story' => 'Een belangrijk precolumbiaans centrum met astronomisch georiënteerde architectuur. De schaduwwerking tijdens equinoxen op de piramide is wereldberoemd.'
                ],
                'Christ the Redeemer' => [
                    'short' => 'Art-deco Christusbeeld boven Rio de Janeiro.',
                    'story' => 'Geopend in 1931 op de Corcovado. Iconisch symbool van Brazilië en het christendom, met een wijdse blik over stad en baai.'
                ],
                'Colosseum' => [
                    'short' => 'Romeins amfitheater voor spelen en spektakels.',
                    'story' => 'Ingewijd in 80 n.Chr. met grootschalige spelen. Het amfitheater toont de kunde van Romeinse bouwkunde en is een baken van archeologie en toerisme.'
                ],
                'Great Wall of China' => [
                    'short' => 'Uitgestrekt verdedigingswerk over bergkammen en valleien.',
                    'story' => 'Gebouwd en uitgebouwd door verschillende dynastieën. De muur diende als grensverdediging, signaalroute en controlepost; vandaag een nationaal symbool.'
                ],
                'Machu Picchu' => [
                    'short' => 'Inca-citadel in de Andes met terrasbouw en precisie-metselwerk.',
                    'story' => 'Herontdekt in 1911. De ligging en architectuur tonen Inca-kennis van waterbeheer, landbouw en aardbevingsbestendig bouwen.'
                ],
                'Petra' => [
                    'short' => 'Nabatese rotsstad met uitgehouwen gevels en siq-kloof.',
                    'story' => 'Belangrijke handelsknooppunt in de oudheid. De Schatkamer (Al-Chazneh) en het Klooster zijn iconische voorbeelden van steenhouwen en Hellenistische invloeden.'
                ],
                'Taj Mahal' => [
                    'short' => 'Mausoleum in Agra, gebouwd door Shah Jahan voor Mumtaz Mahal.',
                    'story' => 'Een meesterwerk van Mogol-architectuur (17e eeuw), bekend om zijn symmetrie, marmerinleg en tuinontwerp. UNESCO-werelderfgoed en symbool van liefde.'
                ],
            ];
            $u = $db->prepare('UPDATE wonders SET short_description=?, story=? WHERE name=?');
            foreach ($info as $name => $v) {
                $u->execute([$v['short'], $v['story'], $name]);
            }
        } catch (\Throwable $e) {}

        // Add external slideshow photos (approved) for selected wonders, idempotent
        try {
            $photoMap = [
                'Great Pyramid of Giza' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/e/e3/Kheops-Pyramid.jpg', 'piramide_of_gyza1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/a/af/All_Gizah_Pyramids.jpg', 'piramide_of_gyza2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/5/55/Kheops-Pyramid-in-2010.jpg', 'piramide_of_gyza3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/f/fc/Khephren-Pyramid.jpg', 'piramide_of_gyza4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/c/c9/Pyramid_of_Khafre_Giza_Egypt.jpg', 'piramide_of_gyza5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/b/b0/Pyramid-of-menkaure.jpg', 'piramide_of_gyza6'],
                ],
                'Petra' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/0/00/Petra_Jordan_BW_21.JPG', 'petra1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/0/08/Petra_Jordan_BW_22.JPG', 'petra2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/f/f0/Al_Khazneh_Petra_edit_2.jpg', 'petra3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/7/7d/PetraTreasury.jpg', 'petra4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/9/9c/Siq-Petra.jpg', 'petra5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/0/0c/Petra_Roman_Theatre.jpg', 'petra6'],
                ],
                'Colosseum' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/d/de/Colosseo_2020.jpg', 'colosseum1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/5/5e/Colosseum_in_Rome%2C_Italy_-_April_2007.jpg', 'colosseum2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/1/12/Colosseum_interior.jpg', 'colosseum3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/5/5d/Colosseum_inside_2007.jpg', 'colosseum4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/3/32/Colosseum_exterior_panorama.jpg', 'colosseum5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/8/89/Colosseum-night.jpg', 'colosseum6'],
                ],
                'Chichén Itzá' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/2/2d/Chichen-Itza-Castillo-Seen-From-East.JPG', 'chichen_itza1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/1/1f/Chichen_Itza_3.jpg', 'chichen_itza2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/f/f7/Chichen_Itza_Temple_of_Kukulkan_2017.jpg', 'chichen_itza3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/4/4f/Chichen_Itza_2009.JPG', 'chichen_itza4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/6/6c/Chichen_Itza_%28panorama%29.jpg', 'chichen_itza5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/2/29/El_Castillo_%28Chichen_Itza%29_2010.JPG', 'chichen_itza6'],
                ],
                'Machu Picchu' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/e/eb/Machu_Picchu%2C_Peru.jpg', 'machu_picchu1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/1/16/Machu_Picchu%2C_Peru_%281%29.jpg', 'machu_picchu2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/1/12/Machu_Picchu_in_June_2009_-_panoramio.jpg', 'machu_picchu3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/9/97/Machu_Picchu_Panoramic_View.jpg', 'machu_picchu4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/d/de/Machu_Picchu_2009.JPG', 'machu_picchu5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/8/8f/Machu_Picchu_%28HDR%29.jpg', 'machu_picchu6'],
                ],
                'Taj Mahal' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/d/da/Taj-Mahal.jpg', 'taj_mahal1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/1/1d/Taj_Mahal%2C_Agra%2C_India_edit3.jpg', 'taj_mahal2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/0/00/Taj_Mahal_in_March_2004.jpg', 'taj_mahal3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/0/0c/Taj_Mahal_in_India_2017.jpg', 'taj_mahal4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/9/92/Taj_Mahal%2C_Agra%2C_India_edit1.jpg', 'taj_mahal5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/3/3e/Taj_Mahal%2C_Agra%2C_India_edit2.jpg', 'taj_mahal6'],
                ],
                'Christ the Redeemer' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/1/1e/Cristo_Redentor_-_Rio_de_Janeiro%2C_Brasil.jpg', 'christus_de_verlosser1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/9/9c/Cristo_Redentor_-_Rio_de_Janeiro.jpg', 'christus_de_verlosser2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/6/6f/Cristo_Redentor_-_Corcovado_-_Rio_de_Janeiro%2C_Brasil.jpg', 'christus_de_verlosser3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/7/72/Cristo_Redentor_-_panorama.jpg', 'christus_de_verlosser4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/4/4d/Cristo_Redentor_-_sunset.jpg', 'christus_de_verlosser5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/5/5f/Cristo_Redentor_seen_from_Sugarloaf.jpg', 'christus_de_verlosser6'],
                ],
                'Great Wall of China' => [
                    ['https://upload.wikimedia.org/wikipedia/commons/1/10/20090529_Great_Wall_8185.jpg', 'great_wall_of_china1'],
                    ['https://upload.wikimedia.org/wikipedia/commons/0/08/GreatWallBadaling.JPG', 'great_wall_of_china2'],
                    ['https://upload.wikimedia.org/wikipedia/commons/6/6f/GreatWall_2004_Summer_3.jpg', 'great_wall_of_china3'],
                    ['https://upload.wikimedia.org/wikipedia/commons/f/f9/Great_Wall_of_China_July_2006.JPG', 'great_wall_of_china4'],
                    ['https://upload.wikimedia.org/wikipedia/commons/8/87/Great_Wall_of_China_at_Jinshanling-edit.jpg', 'great_wall_of_china5'],
                    ['https://upload.wikimedia.org/wikipedia/commons/2/23/Mutianyu_Great_Wall_June_2004.JPG', 'great_wall_of_china6'],
                ],
            ];
            $findWonder = $db->prepare('SELECT id FROM wonders WHERE name=?');
            $hasPhoto = $db->prepare('SELECT id FROM photos WHERE wonder_id=? AND path=?');
            $ins = $db->prepare('INSERT INTO photos (wonder_id,path,title,approved,uploaded_by,created_at) VALUES (?,?,?,?,?,?)');
            foreach ($photoMap as $name => $items) {
                $findWonder->execute([$name]);
                $wid = $findWonder->fetchColumn();
                if (!$wid) { continue; }
                foreach ($items as $it) {
                    $url = $it[0]; $title = $it[1] ?? '';
                    $hasPhoto->execute([(int)$wid, $url]);
                    if ($hasPhoto->fetchColumn()) { continue; }
                    $ins->execute([(int)$wid, $url, $title, 1, null, date('c')]);
                }
            }
        } catch (\Throwable $e) {}

        // Add new nature wonders if missing (type modern, approved)
        try {
            $now = date('c');
            $addWonder = function($name,$short,$year,$continent,$type,$exists,$lat,$lng) use ($db,$now){
                $check = $db->prepare('SELECT id FROM wonders WHERE name=?');
                $check->execute([$name]);
                if ($check->fetchColumn()) { return; }
                $stmt = $db->prepare('INSERT INTO wonders (name, short_description, year, continent, type, exists_now, myth, story, lat, lng, approved, created_by, updated_by, view_count, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
                $stmt->execute([$name,$short,$year,$continent,$type,$exists,'','',$lat,$lng,1,null,null,0,$now,$now]);
            };
            $addWonder('Amazon Rainforest','Tropisch regenwoud rond de Amazone met ongekende biodiversiteit.', null, 'South America', 'modern', 1, -3.4653, -62.2159);
            $addWonder('Ha Long Bay','Wereldberoemde baai met kalkstenen eilanden in Vietnam.', null, 'Asia', 'modern', 1, 20.9101, 107.1839);
            $addWonder('Iguazu Falls','Watervallen op de grens van Argentinië en Brazilië.', null, 'South America', 'modern', 1, -25.6953, -54.4367);
            $addWonder('Jeju Island','Vulkanisch eiland ten zuiden van Korea, UNESCO-biosfeerreservaat.', null, 'Asia', 'modern', 1, 33.4996, 126.5312);
            $addWonder('Komodo National Park','Eilandengroep in Indonesië, thuisbasis van de komodovaraan.', null, 'Asia', 'modern', 1, -8.5662, 119.4891);
            $addWonder('Table Mountain','Vlakke tafelberg bij Kaapstad met rijke flora.', null, 'Africa', 'modern', 1, -33.9628, 18.4098);
            $addWonder('Puerto Princesa Underground River','Ondergrondse rivier op Palawan, Filipijnen.', null, 'Asia', 'modern', 1, 10.1980, 118.9260);
        } catch (\Throwable $e) {}

        // Seed external slideshow photos from Wikimedia Commons (idempotent)
        try {
            $photoMap = [
                'Great Pyramid of Giza' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/All%20Gizah%20Pyramids.jpg','piramide van gizeh 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Kheops-Pyramid.jpg','piramide van gizeh 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Pyramide%20Kheops.JPG','piramide van gizeh 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Khafre%27s%20Pyramid.jpg','piramide van gizeh 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Pyramid%20of%20Giza%202010.jpg','piramide van gizeh 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Pyramid%20of%20Giza%2C%20Kheops%20009.JPG','piramide van gizeh 6'],
                ],
                'Hanging Gardens of Babylon' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20Maarten%20van%20Heemskerck.jpg','hangende tuinen 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20engraving.jpg','hangende tuinen 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20illustration%2019thC.jpg','hangende tuinen 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20Babylon%20reconstruction.jpg','hangende tuinen 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20Babylon%20depiction.jpg','hangende tuinen 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Hanging%20Gardens%20of%20Babylon%20drawing.jpg','hangende tuinen 6'],
                ],
                'Temple of Artemis at Ephesus' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20at%20Ephesus.jpg','tempel van artemis 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20Ephesus%20engraving.jpg','tempel van artemis 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20drawing.jpg','tempel van artemis 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20painting.jpg','tempel van artemis 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20reconstruction.jpg','tempel van artemis 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Temple%20of%20Artemis%20Ephesus%20columns.jpg','tempel van artemis 6'],
                ],
                'Statue of Zeus at Olympia' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Statue%20of%20Zeus%20at%20Olympia%20engraving.jpg','beeld van zeus 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Zeus%20Olympia%20illustration.jpg','beeld van zeus 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Statue%20of%20Zeus%20Olympia%20drawing.jpg','beeld van zeus 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Zeus%20Olympia%20depiction.jpg','beeld van zeus 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Statue%20of%20Zeus%20painting.jpg','beeld van zeus 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Zeus%20statue%20Olympia.jpg','beeld van zeus 6'],
                ],
                'Mausoleum at Halicarnassus' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20at%20Halicarnassus%20engraving.jpg','mausoleum 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20Halicarnassus%20illustration.jpg','mausoleum 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20of%20Halicarnassus%20reconstruction.jpg','mausoleum 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Halicarnassus%20Mausoleum.jpg','mausoleum 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20Halicarnassus%20depiction.jpg','mausoleum 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Mausoleum%20Halicarnassus%20model.jpg','mausoleum 6'],
                ],
                'Colossus of Rhodes' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20of%20Rhodes%20illustration.jpg','kolossus 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20of%20Rhodes%20Maarten%20van%20Heemskerck.jpg','kolossus 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20Rhodes%20drawing.jpg','kolossus 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20of%20Rhodes%20engraving.jpg','kolossus 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20Rhodes%20depiction.jpg','kolossus 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colossus%20Rhodes%20painting.jpg','kolossus 6'],
                ],
                'Lighthouse of Alexandria' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Lighthouse%20of%20Alexandria%20engraving.jpg','vuurtoren 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Pharos%20of%20Alexandria%20drawing.jpg','vuurtoren 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Pharos%20Alexandria%20reconstruction.jpg','vuurtoren 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Alexandria%20Pharos%20illustration.jpg','vuurtoren 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Lighthouse%20Alexandria%20depiction.jpg','vuurtoren 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Pharos%20Alexandria%20artwork.jpg','vuurtoren 6'],
                ],
                'Great Wall of China' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/20090529%20Great%20Wall%208185.jpg','chinese muur 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20Badaling.JPG','chinese muur 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20of%20China%20Jinshanling.jpg','chinese muur 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20of%20China%20Mutianyu.jpg','chinese muur 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20Juyongguan.jpg','chinese muur 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Great%20Wall%20Simatai.jpg','chinese muur 6'],
                ],
                'Petra' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Al-Khazneh%20Petra%20Jordan.jpg','petra 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Treasury%20Siq.jpg','petra 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Monastery.jpg','petra 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Royal%20Tombs.jpg','petra 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Theatre.jpg','petra 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Petra%20Urn%20Tomb.jpg','petra 6'],
                ],
                'Christ the Redeemer' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20Rio%20de%20Janeiro.jpg','christus de verlosser 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20Corcovado.jpg','christus de verlosser 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Christ%20the%20Redeemer%20aerial%20view.jpg','christus de verlosser 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Christ%20the%20Redeemer%20closeup.jpg','christus de verlosser 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20at%20sunset.jpg','christus de verlosser 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Cristo%20Redentor%20view%20from%20Sugarloaf.jpg','christus de verlosser 6'],
                ],
                'Machu Picchu' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20Peru.jpg','machu picchu 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20view.jpg','machu picchu 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20panorama.jpg','machu picchu 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20Sunrise.jpg','machu picchu 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20Terraces.jpg','machu picchu 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Machu%20Picchu%20mountains.jpg','machu picchu 6'],
                ],
                'Chichén Itzá' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20El%20Castillo.jpg','chichen itza 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20Temple%20Kukulkan.jpg','chichen itza 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20pyramid.jpg','chichen itza 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/El%20Castillo%20Chichen%20Itza.jpg','chichen itza 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20ruins.jpg','chichen itza 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Chichen%20Itza%20Mexico.jpg','chichen itza 6'],
                ],
                'Colosseum' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20Rome%20Italy.jpg','colosseum 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20exterior.jpg','colosseum 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20inside.jpg','colosseum 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20interior.jpg','colosseum 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20panorama.jpg','colosseum 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Colosseum%20at%20night.jpg','colosseum 6'],
                ],
                'Taj Mahal' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20Agra%20India.jpg','taj mahal 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20sunrise.jpg','taj mahal 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20reflection.jpg','taj mahal 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20front%20view.jpg','taj mahal 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20garden.jpg','taj mahal 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Taj%20Mahal%20side%20view.jpg','taj mahal 6'],
                ],
                'Amazon Rainforest' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20Rainforest%20river.jpg','amazone 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20Rainforest%20aerial.jpg','amazone 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20jungle%20trees.jpg','amazone 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20Rainforest%20sunset.jpg','amazone 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20River%20forest.jpg','amazone 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Amazon%20forest%20Brazil.jpg','amazone 6'],
                ],
                'Ha Long Bay' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20Vietnam.jpg','halongbaai 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20view.jpg','halongbaai 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20boats.jpg','halongbaai 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20cliffs.jpg','halongbaai 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20sunset.jpg','halongbaai 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Halong%20Bay%20limestone.jpg','halongbaai 6'],
                ],
                'Iguazu Falls' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20Brazil.jpg','iguazu 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20Argentina.jpg','iguazu 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20panorama.jpg','iguazu 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20waterfall.jpg','iguazu 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20rainbow.jpg','iguazu 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Iguazu%20Falls%20jungle.jpg','iguazu 6'],
                ],
                'Jeju Island' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20Korea.jpg','jeju 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20landscape.jpg','jeju 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20Seongsan.jpg','jeju 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20coast.jpg','jeju 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20sunset.jpg','jeju 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Jeju%20Island%20mountains.jpg','jeju 6'],
                ],
                'Komodo National Park' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20Indonesia.jpg','komodo 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20National%20Park.jpg','komodo 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20beach.jpg','komodo 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20dragon%20island.jpg','komodo 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20aerial.jpg','komodo 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Komodo%20Island%20view.jpg','komodo 6'],
                ],
                'Table Mountain' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20Cape%20Town.jpg','table mountain 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20view.jpg','table mountain 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20South%20Africa.jpg','table mountain 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20sunset.jpg','table mountain 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20aerial.jpg','table mountain 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Table%20Mountain%20clouds.jpg','table mountain 6'],
                ],
                'Puerto Princesa Underground River' => [
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20Philippines.jpg','puerto princesa 1'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20cave.jpg','puerto princesa 2'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20boats.jpg','puerto princesa 3'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20entrance.jpg','puerto princesa 4'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20inside.jpg','puerto princesa 5'],
                    ['https://commons.wikimedia.org/wiki/Special:FilePath/Puerto%20Princesa%20Underground%20River%20tour.jpg','puerto princesa 6'],
                ],
            ];
            $findWonder = $db->prepare('SELECT id FROM wonders WHERE name=?');
            $hasPhoto = $db->prepare('SELECT id FROM photos WHERE wonder_id=? AND path=?');
            $ins = $db->prepare('INSERT INTO photos (wonder_id,path,title,approved,uploaded_by,created_at) VALUES (?,?,?,?,?,?)');
            foreach ($photoMap as $name => $items) {
                $findWonder->execute([$name]);
                $wid = $findWonder->fetchColumn();
                if (!$wid) { continue; }
                foreach ($items as $it) {
                    $url = (string)$it[0]; $title = (string)($it[1] ?? '');
                    $hasPhoto->execute([(int)$wid, $url]);
                    if ($hasPhoto->fetchColumn()) { continue; }
                    $ins->execute([(int)$wid, $url, $title, 1, null, date('c')]);
                }
            }
        } catch (\Throwable $e) {}
    }

    private static function placeholderSvg(string $text): string {
        // Deterministic color from name
        $hash = substr(md5($text), 0, 6);
        $c1 = '#' . $hash;
        $c2 = '#' . substr(md5(strrev($text)), 0, 6);
        $safe = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675" viewBox="0 0 1200 675">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="$c1"/>
      <stop offset="100%" stop-color="$c2"/>
    </linearGradient>
  </defs>
  <rect width="1200" height="675" fill="url(#g)"/>
  <g fill="rgba(255,255,255,0.15)">
    <circle cx="1050" cy="90" r="70"/>
    <circle cx="160" cy="560" r="110"/>
    <rect x="980" y="520" width="160" height="40" rx="20"/>
  </g>
  <text x="60" y="340" font-family="'Segoe UI', Roboto, Arial, sans-serif" font-size="72" font-weight="800" fill="#fff">$safe</text>
  <text x="60" y="400" font-family="'Segoe UI', Roboto, Arial, sans-serif" font-size="28" fill="rgba(255,255,255,0.9)">Codex Mundi</text>
  <rect x="60" y="440" width="220" height="48" rx="12" fill="rgba(0,0,0,0.25)"/>
  <text x="80" y="472" font-family="'Segoe UI', Roboto, Arial, sans-serif" font-size="22" fill="#fff">Wereldwonder</text>
</svg>
SVG;
    }
}
