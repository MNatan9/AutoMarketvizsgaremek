SELECT uzemanyag, COUNT(*) AS darabszam, ROUND(AVG(ar)) AS tipus_atlagar 
FROM jarmuvek 
GROUP BY uzemanyag;