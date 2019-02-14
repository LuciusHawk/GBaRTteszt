"# GBaRTteszt" <br>
Gyors telepítéshez a következő parancsot adjuk ki:
bash fast_install.sh

Start/restarthoz:
bash run.sh



Az alkalmazásnak a következő követelményeknek kell megfelelnie:

    a git clone utáni első elindítás laikusként is egyszerű legyen: ne kelljen xy parancsokat lefuttatni csomagok telepítéséhez, adatbázis inicializálásához külön, hanem legyen erre egy, valamilyen build script. (feltételezhetjük, hogy elérhetőek lesznek ezek futtatásához szükséges dependenciák: php, make, ant, stb)
    ne legyen szükség adatbázist létrehozni külső adatbázimotorhoz kapcsolódva, hanem oldja meg az adatok tárolását valamilyen fájl alapú tárolóban. 
    a feladatokról a következő információkat tárolja: 
        description
        creation date
        status (NEW, INPROGRESS, ONHOLD, DONE)
    a feladatok kezelése parancssorból legyen elérhető, és a következő műveleteket lehessen végezni az elemekkel:
        list
        create
        update status
    a státuszok a következő módon változhatnak. (ellentétes esetben jelezzen hibát az alkalmazás)
        új létrehozása esetén NEW
        NEW > INPROGRESS
        INPROGRESS > ONHOLD
        INPROGRESS > DONE
    az elemeken végzett műveletekről készüljön tevékenységnapló, és ezt az egyet webes felületen is tudja listázni az alkalmazás. Ez a funkció különálló modulként legyen megvalósítva, ami nélkül tud működni az alkalmazás
