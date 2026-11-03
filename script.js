
 
let aktivTipus = "mind"; // 
 
 

 
let toastIdozito;
 
function uzenet(szoveg) {
    let toast = document.getElementById("toast");
 
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "toast";
        toast.className = "toast";
        toast.setAttribute("role", "status");
        toast.setAttribute("aria-live", "polite");
        document.body.appendChild(toast);
    }
 
    toast.textContent = szoveg;
    toast.classList.add("latszik");
 
    clearTimeout(toastIdozito);
    toastIdozito = setTimeout(function () {
        toast.classList.remove("latszik");
    }, 2800);
}
 
 
function bejelentkezes() {
    uzenet("A bejelentkezés hamarosan elérhető!");
}
 
 
function hirdetes() {
    uzenet("A hirdetés feladása hamarosan elérhető!");
}
 
 
 
function kedvenc(gomb) {
    if (!gomb.dataset.eredeti) {
        gomb.dataset.eredeti = gomb.innerText;
    }
 
    const bekapcsolva = gomb.classList.toggle("kedvenc-aktiv");
 
    gomb.innerText = bekapcsolva ? "✓ Kedvencekben" : gomb.dataset.eredeti;
    gomb.setAttribute("aria-pressed", bekapcsolva);
 
    uzenet(bekapcsolva ? "Hozzáadva a kedvencekhez" : "Eltávolítva a kedvencekből");
}
 

 
function aktivGomb() {
    const gombok = document.querySelectorAll(".kategoriak button");
 
    gombok.forEach(function (gomb) {
        const onclick = gomb.getAttribute("onclick") || "";
        let tipus = "mind";
 
        if (onclick.includes("mutatAutok")) tipus = "auto";
        if (onclick.includes("mutatMotorok")) tipus = "motor";
 
        const aktiv = tipus === aktivTipus;
        gomb.classList.toggle("aktiv", aktiv);
        gomb.setAttribute("aria-pressed", aktiv);
    });
}
 
 
function mutatAutok() {
    aktivTipus = "auto";
    frissit();
}
 
 
function mutatMotorok() {
    aktivTipus = "motor";
    frissit();
}
 
 
function mutatMindet() {
    aktivTipus = "mind";
    frissit();
}
 
 

 
function kereses() {
    frissit();
}
 
 

 
function frissit() {
    const keresett = document.getElementById("kereses").value.trim().toLowerCase();
    const marka = document.getElementById("marka").value;
    const evjarat = document.getElementById("evjarat").value;
    const ar = document.getElementById("ar").value;
 
    const jarmuvek = document.querySelectorAll(".auto");
    let talalat = 0;
 
    jarmuvek.forEach(function (auto) {
        const cim = auto.querySelector("h3");
        const szoveg = (cim ? cim.textContent : "") + " " + auto.textContent;
 
        let megfelel = true;
 
        if (aktivTipus !== "mind" && auto.dataset.tipus !== aktivTipus) {
            megfelel = false;
        }
 
        if (keresett && !szoveg.toLowerCase().includes(keresett)) {
            megfelel = false;
        }
 
        if (marka && auto.dataset.marka !== marka) {
            megfelel = false;
        }
 
        if (evjarat && Number(auto.dataset.ev) < Number(evjarat)) {
            megfelel = false;
        }
 
        if (ar && Number(auto.dataset.ar) > Number(ar)) {
            megfelel = false;
        }
 
        auto.hidden = !megfelel;
 
        if (megfelel) {
            talalat++;
        }
    });
 
    aktivGomb();
    uresAllapot(talalat);
}
 
 
function uresAllapot(talalat) {
    const lista = document.querySelector(".autok");
 
    if (!lista) return;
 
    let uzenetDoboz = document.getElementById("nincs-talalat");
 
    if (talalat === 0) {
        if (!uzenetDoboz) {
            uzenetDoboz = document.createElement("p");
            uzenetDoboz.id = "nincs-talalat";
            uzenetDoboz.className = "nincs-talalat";
            uzenetDoboz.textContent = "Nincs a feltételeknek megfelelő jármű. Próbálj kevesebb szűrőt használni.";
            lista.insertAdjacentElement("afterend", uzenetDoboz);
        }
        uzenetDoboz.hidden = false;
    } else if (uzenetDoboz) {
        uzenetDoboz.hidden = true;
    }
}
 
 

 
document.addEventListener("DOMContentLoaded", function () {
    const keresoMezo = document.getElementById("kereses");
 
    // Enter lenyomására is induljon a keresés
    if (keresoMezo) {
        keresoMezo.addEventListener("keydown", function (esemeny) {
            if (esemeny.key === "Enter") {
                esemeny.preventDefault();
                kereses();
            }
        });
    }
 
    ["marka", "evjarat", "ar"].forEach(function (id) {
        const elem = document.getElementById(id);
        if (elem) elem.addEventListener("change", kereses);
    });
 
    aktivGomb();
});
 
