SELECT 
    jarmuvek.marka, 
    jarmuvek.modell, 
    COUNT(kedvencek.id) AS kedvelesek_szama 
FROM jarmuvek 
LEFT JOIN kedvencek ON jarmuvek.id = kedvencek.jarmu_id 
GROUP BY jarmuvek.id, jarmuvek.marka, jarmuvek.modell 
ORDER BY kedvelesek_szama DESC;