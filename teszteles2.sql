-- 2. INSERT (beszúrás) tesztek
INSERT INTO kategoria (nev) VALUES ('Teherautó');

INSERT INTO users (username, firstname, lastname, email, password, szerepkor) 
VALUES ('tesztelek', 'Elek', 'Teszt', 'tesztelek@example.com', 'titkosjelszo123', 'user');

CALL uj_jarmu_beszurasa('Ford', 'Focus', 'Kombi', 2020, 4800000, 'Dízel', 85000, 'Debrecen', 'ford_focus.jpg');