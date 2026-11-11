-- 1. SELECT és JOIN tesztek
SELECT marka, modell, evjarat, ar, szin FROM jarmuvek;
CALL jarmu_kereses('Toyota');

SELECT 
    hirdetesek.id AS hirdetes_id, 
    hirdetesek.cim, 
    kategoria.nev AS kategoria, 
    CONCAT(users.lastname, ' ', users.firstname) AS hirdeto_neve, 
    users.username,
    hirdetesek.aktiv 
FROM hirdetesek 
JOIN kategoria ON hirdetesek.kategoria_id = kategoria.id 
JOIN users ON hirdetesek.user_id = users.id;