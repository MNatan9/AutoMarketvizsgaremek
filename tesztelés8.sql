SELECT kategoria.nev AS kategoria, COUNT(hirdetesek.id) AS hirdetesek_szama 
FROM kategoria 
LEFT JOIN hirdetesek ON kategoria.id = hirdetesek.kategoria_id 
GROUP BY kategoria.id, kategoria.nev;