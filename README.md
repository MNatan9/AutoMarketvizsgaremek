AutoMarket - Használtautó és Motor Apróhirdetési Platform

Csapattagok: Mészöly Nátán, Csizmadia Zsolt, Weisz Márkó
Projektmenedzser: Csizmadia Zsolt
Iskola: Tolnavármegyei Apáczai Csere János Technikum és Kollégium
Szakma: Szoftver Fejlesztő és Tesztelő


Mért pont ezt a témát valasztottuk?: 
Azért mert mind a hármunkat érdekelnek az autók és ezzel kapcsolatos témák. 

Mi a célunk vele? Milyen problémát old meg, vagy milyen igényt elégít ki?
Az a célunk vele hogy minél egyszerübb legyen az embereknek megtalálni a megfelelő autót, Az első probléma amit megold az az hogy kedvezőbb áron jussanak autóhoz az emberek. A második célunk az az hogy az emberek számára a vásárlás kényelmesebb opciót tartalmazzon.

Kik a célközönsége vagy a leendő felhasználói?
A fiatal friss jogsis emberek és az idősebb emberek.


Eszközök és anyagok: Milyen hardverre, szoftverre, alapanyagokra vagy szerszámokra volt szükség?
Egy vásárlási centrum ahol az érdeklődők tudnak maguknak választani járművet. Amikre még szükség van az egy műhely ahol karban tudjuk tartani az autókat egy telep ahol az autókat tudjuk tárolni és egy weboldal ahol tudjuk őket értékesiteni. 

A projekt egy modern, felhasználóbarát webes alkalmazás használtautók és motorok adásvételére, amely gyors és átlátható felületet biztosít a hirdetéshez és a böngészéshez.

Weboldal Tulajdonságai:
Saját adatbázis a felhasználók és hirdetések biztonságos tárolására.

Felhasználói Bejelentkezés és Regisztráció.
Kereső és Szűrőrendszer: szűrés márka modell, ár és évjárat szerint.(adott autokra ralehet majd keresni ,nem az összes márkára/tipusra)

Hirdetéskezelése: új jármű feladása adatokkal és képekkel a saját hirdetések szerkesztése és törlése.
Kedvencek: az érdekesnek talált hirdetések elmentése.(ha megtetszik valamelyik auto)

Adminisztrációs felület: hirdetések moderálása és felhasználók kezelése.


Feladat felosztás / szerepkörök : Csizmadia Zsolt:
                                  Weisz Márkó:
                                  Mészöly Nátán













                                  # AutoMarket – Backend API dokumentáció

## 1. Projekt bemutatása

Az AutoMarket egy járműhirdetési rendszer, amelyben a felhasználók regisztrálhatnak, bejelentkezhetnek, járműveket tölthetnek fel, valamint kedvenceket kezelhetnek.

A backend feladata az adatok kezelése, az adatbázissal való kommunikáció és a felhasználók jogosultságainak ellenőrzése.

## 2. Felhasznált technológiák

- PHP
- MySQL
- JSON
- Apache

## 3. Adatkommunikáció

A backend JSON formátumban küldi vissza a válaszokat.

Sikeres válasz:

```json
{
    "success": true,
    "message": "Sikeres művelet!",
    "data": null
}
```

Hibás válasz:

```json
{
    "success": false,
    "message": "Bejelentkezes szukseges!",
    "data": null
}
```

## 4. API végpontok

Az alábbi útvonalak a backend mappán belül értendők.

### 4.1. Felhasználókezelés

| HTTP metódus | Végpont | Leírás |
|---|---|---|
| POST | `/auth/register.php` | Új felhasználó regisztrációja |
| POST | `/auth/login.php` | Bejelentkezés |
| Ellenőrizendő | `/auth/logout.php` | Kijelentkezés |

### 4.2. Járműkezelés

| HTTP metódus | Végpont | Leírás |
|---|---|---|
| POST | `/jarmuvek/create.php` | Új jármű létrehozása |
| GET | `/jarmuvek/list.php` | Járművek listázása |
| GET | `/jarmuvek/get.php?id=1` | Egy jármű lekérése |
| POST | `/jarmuvek/update.php` | Jármű adatainak módosítása |
| POST | `/jarmuvek/delete.php` | Jármű törlése |
| POST | `/jarmuvek/upload.php` | Járműkép feltöltése |

### 4.3. Kedvencek kezelése

| HTTP metódus | Végpont | Leírás |
|---|---|---|
| Ellenőrizendő | `/kedvencek/add.php` | Jármű hozzáadása a kedvencekhez |
| Ellenőrizendő | `/kedvencek/delete.php` | Jármű eltávolítása a kedvencekből |
| GET* | `/kedvencek/list.php` | Kedvencek listázása |

*A kedvencek végpontjainak pontos HTTP-metódusát a PHP-kód alapján kell véglegesíteni.*

## 5. Jármű létrehozása – példa

**Végpont:** `/jarmuvek/create.php`

**Metódus:** POST

**Kérés törzse:**

```json
{
    "marka": "BMW",
    "modell": "320d",
    "tipus": "Szedan",
    "evjarat": 2020,
    "ar": 6500000,
    "uzemanyag": "Dizel",
    "futott_km": 85000,
    "helyseg": "Budapest",
    "kep_url": "uploads/vehicles/auto.jpg"
}
```

A jármű tulajdonosát a rendszer a bejelentkezett felhasználó alapján határozza meg.

## 6. Képfeltöltés

**Végpont:** `/jarmuvek/upload.php`

**Metódus:** POST

A képfeltöltés `multipart/form-data` formátumban történik.

A fájl mező neve: `kep`

Engedélyezett formátumok:
- JPG
- PNG
- WEBP

Maximális fájlméret: 5 MB.

A sikeres feltöltés után a backend visszaadja a kép elérési útját.

## 7. Hitelesítés és jogosultságok

A rendszer session alapú hitelesítést használ.

- A jelszavak biztonságos hash formában kerülnek tárolásra.
- A védett végpontok bejelentkezést igényelnek.
- A felhasználók saját járműveiket kezelhetik.
- Az adminisztrátor külön jogosultságokkal rendelkezik.
- A felhasználók saját kedvenceiket kezelhetik.

## 8. HTTP válaszkódok

| HTTP-kód | Jelentés |
|---|---|
| 200 | Sikeres kérés |
| 201 | Sikeres létrehozás |
| 400 | Hibás kérés |
| 401 | Bejelentkezés szükséges |
| 403 | Nincs jogosultság |
| 404 | Nem található |
| 405 | Nem engedélyezett metódus |
| 500 | Szerverhiba |

## 9. Tesztelés

A backend végpontjai Thunder Client vagy Postman segítségével tesztelhetők.

A tesztelés során ellenőrizni kell:

- Regisztráció és bejelentkezés.
- Hibás adatok kezelése.
- Járművek létrehozása, lekérése, módosítása és törlése.
- Képfeltöltés és fájlellenőrzés.
- Jogosultságok ellenőrzése.
- Kedvencek hozzáadása, listázása és törlése.
- Kijelentkezés és a munkamenet megszüntetése.
