SELECT users.username, COUNT(hirdetesek.id) AS feladott_hirdetesek 
FROM users 
LEFT JOIN hirdetesek ON users.id = hirdetesek.user_id 
GROUP BY users.id, users.username;