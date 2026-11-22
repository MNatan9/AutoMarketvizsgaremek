-- 3. UPDATE (módosítás) tesztek
UPDATE jarmuvek 
SET ar = 4600000, szin = 'Sötétkék' 
WHERE marka = 'Ford' AND modell = 'Focus';

UPDATE hirdetesek 
SET aktiv = 0 
WHERE id = 1;