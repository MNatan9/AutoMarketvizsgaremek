SELECT 
    jarmuvek.id AS jarmu_id,
    jarmuvek.marka, 
    jarmuvek.modell, 
    COUNT(jelentesek.id) AS jelentesek_szama 
FROM jarmuvek 
JOIN jelentesek ON jarmuvek.id = jelentesek.jarmu_id 
GROUP BY jarmuvek.id, jarmuvek.marka, jarmuvek.modell 
HAVING jelentesek_szama >= 1;