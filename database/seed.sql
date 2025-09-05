	USE `codex_mundi`;

	-- Demo users (hashes to be generated on first run if not present)
	INSERT INTO users (email, password_hash, role) VALUES
	('admin@demo.test', '$needs_hash$Admin123!', 'admin'),
	('researcher@demo.test', '$needs_hash$Research123!', 'researcher'),
	('editor@demo.test', '$needs_hash$Editor123!', 'editor');

	-- Tags
	INSERT INTO tags (name) VALUES ('ancient'),('architecture'),('myth'),('religion'),('nature'),('modern');

	-- Wonders (21 entries) with country
	INSERT INTO wonders (slug, title, country, summary, description, myth, category, continent, year_built, exists_now, status, lat, lng, created_by) VALUES
	('great-pyramid-giza','Great Pyramid of Giza','Egypt','Oldest and largest of the pyramids.','Long description...','Myths...', 'classic','africa',-2560,1,'approved',29.9792,31.1342,1),
	('hanging-gardens','Hanging Gardens of Babylon','Iraq','Legendary terraced gardens.','Long description...','Myths...', 'classic','asia',-600,0,'approved',32.5364,44.4200,1),
	('statue-of-zeus','Statue of Zeus at Olympia','Greece','Colossal statue by Phidias.','Long description...','Myths...', 'classic','europe',-435,0,'approved',37.6381,21.6307,1),
	('temple-of-artemis','Temple of Artemis at Ephesus','Türkiye','Grand temple to Artemis.','Long description...','Myths...', 'classic','asia',-550,0,'approved',37.9497,27.3639,1),
	('mausoleum-halicarnassus','Mausoleum at Halicarnassus','Türkiye','Tomb of Mausolus.','Long description...','Myths...', 'classic','asia',-350,0,'approved',37.0379,27.4241,1),
	('colossus-of-rhodes','Colossus of Rhodes','Greece','Giant bronze statue.','Long description...','Myths...', 'classic','europe',-280,0,'approved',36.4510,28.2278,1),
	('lighthouse-alexandria','Lighthouse of Alexandria','Egypt','Pharos of Alexandria.','Long description...','Myths...', 'classic','africa',-250,0,'approved',31.2135,29.8853,1),
	('chichen-itza','Chichén Itzá','Mexico','Maya city with El Castillo.','Long description...','Myths...', 'modern','north_america',600,1,'approved',20.6843,-88.5678,1),
	('christ-the-redeemer','Christ the Redeemer','Brazil','Art Deco statue in Rio.','Long description...','Myths...', 'modern','south_america',1931,1,'approved',-22.9519,-43.2105,1),
	('colosseum','Colosseum','Italy','Large amphitheatre in Rome.','Long description...','Myths...', 'modern','europe',80,1,'approved',41.8902,12.4922,1),
	('great-wall','Great Wall of China','China','Series of fortifications.','Long description...','Myths...', 'modern','asia',1400,1,'approved',40.4319,116.5704,1),
	('machu-picchu','Machu Picchu','Peru','Incan citadel.','Long description...','Myths...', 'modern','south_america',1450,1,'approved',-13.1631,-72.5450,1),
	('petra','Petra','Jordan','Rock-cut architecture city.','Long description...','Myths...', 'modern','asia',-300,1,'approved',30.3285,35.4444,1),
	('taj-mahal','Taj Mahal','India','Mausoleum in Agra.','Long description...','Myths...', 'modern','asia',1653,1,'approved',27.1751,78.0421,1),
	('grand-canyon','Grand Canyon','USA','Steep-sided canyon in Arizona.','Long description...','Myths...', 'natural','north_america',0,1,'approved',36.1069,-112.1129,1),
	('great-barrier-reef','Great Barrier Reef','Australia','World’s largest coral reef.','Long description...','Myths...', 'natural','oceania',0,1,'approved',-18.2871,147.6992,1),
	('harbor-rio','Harbor of Rio de Janeiro','Brazil','Stunning natural harbor.','Long description...','Myths...', 'natural','south_america',0,1,'approved',-22.9068,-43.1729,1),
	('mount-everest','Mount Everest','Nepal/China','Highest mountain above sea level.','Long description...','Myths...', 'natural','asia',0,1,'approved',27.9881,86.9250,1),
	('aurora-borealis','Aurora Borealis','Arctic regions','Natural light display.','Long description...','Myths...', 'natural','europe',0,1,'approved',69.6492,18.9553,1),
	('paricutin','Paricutín','Mexico','Cinder cone volcano.','Long description...','Myths...', 'natural','north_america',1943,1,'approved',19.4928,-102.2510,1),
	('victoria-falls','Victoria Falls','Zambia/Zimbabwe','Waterfall on the Zambezi River.','Long description...','Myths...', 'natural','africa',0,1,'approved',-17.9243,25.8567,1);

	-- Media (one image each placeholder)
	INSERT INTO media (wonder_id, type, url, mime, size, status, created_by)
	SELECT id, 'image', CONCAT('/public/assets/img/wonders/', slug, '.jpg'), 'image/jpeg', 12345, 'approved', 1 FROM wonders;

	-- Basic tag assignments
	INSERT INTO wonder_tags (wonder_id, tag_id, added_by)
	SELECT w.id, t.id, 1 FROM wonders w JOIN tags t ON (
		(t.name='ancient' AND w.category IN ('classic')) OR
		(t.name='modern' AND w.category='modern') OR
		(t.name='nature' AND w.category='natural')
	);
