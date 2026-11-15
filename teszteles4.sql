-- 4. DELETE és idegen kulcs (ON DELETE CASCADE) tesztek
INSERT INTO kedvencek (user_id, jarmu_id) 
VALUES (
    (SELECT id FROM users WHERE username = 'tesztelek' LIMIT 1),
    (SELECT id FROM jarmuvek WHERE marka = 'Ford' AND modell = 'Focus' LIMIT 1)
);

DELETE FROM jarmuvek WHERE marka = 'Ford' AND modell = 'Focus';
DELETE FROM users WHERE username = 'tesztelek';
DELETE FROM kategoria WHERE nev = 'Teherautó';