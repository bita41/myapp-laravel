# Setup myapp (Laragon)

## 1. PHP: pdo_mysql obligatoriu

Fara extensia **pdo_mysql**, Laravel nu poate conecta la MySQL => migrari si login (cu `SESSION_DRIVER=database`) pica.

**Laragon:**

1. Click dreapta pe Laragon tray → **PHP** → **php.ini**
2. Cauta si **decomenteaza** (sterge `;` de la inceput):
   - `extension=pdo_mysql`
   - `extension=mysqli` (optional dar util)
3. Salveazi, apoi **Restart Laragon**.

**Verificare:**

```bash
php -m | findstr pdo
```

Trebuie sa apara: `PDO` si `pdo_mysql`. Daca lipseste `pdo_mysql`, migrarile si login-ul cu session in DB nu merg.

---

## 2. Ordinea configurarii (evitare 419 / "could not find driver")

### 2.1 Pana cand MySQL merge

In `.env`:

- `SESSION_DRIVER=file` (ca sa nu depinzi de DB pentru sesiune)
- `APP_URL=http://myapp.test` (sau URL-ul tau Laragon)

Apoi:

```bash
php artisan optimize:clear
```

Testezi `/login`. Form-ul are deja `@csrf` si `action="{{ route('login.submit') }}"`.

### 2.2 După ce pdo_mysql e activ

In `.env` configurezi MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=root
DB_PASSWORD=
```

Rulezi:

```bash
php artisan migrate
```

Abia dupa ce migrarile merg poti trece la sesiune in DB:

```env
SESSION_DRIVER=database
```

Apoi din nou:

```bash
php artisan optimize:clear
```

---

## 3. De ce 419 (Page Expired) apare cand DB nu merge

Daca ai `SESSION_DRIVER=database` dar PHP nu are pdo_mysql (sau MySQL nu ruleaza):

- Laravel nu poate citi/scrie sesiunea
- Request-ul "se rupe", token-ul CSRF nu se potriveste
- Rezultat: **419 Page Expired**

Nu e din cauza setarilor de securitate (SESSION_SECURE_COOKIE etc.), ci din cauza ca driverul de sesiune (database) nu functioneaza.

Pe **HTTP** (ex. `http://myapp.test`), daca ai 419 si DB-ul merge, verifica si `SESSION_SECURE_COOKIE=false` in `.env` (sau nu seta nimic; in local default-ul din config e `false`).

---

## 4. Rezumat pasi (in ordine)

1. Activezi **pdo_mysql** in php.ini + restart Laragon.
2. In `.env`: `SESSION_DRIVER=file`, `APP_URL=http://myapp.test` → `php artisan optimize:clear` → testezi `/login`.
3. In `.env`: configurezi **DB_*** pentru MySQL** → `php artisan migrate`.
4. In `.env`: `SESSION_DRIVER=database` → `php artisan optimize:clear`.

Dupa aceasta ordine, login-ul si sesiunea in DB ar trebui sa functioneze.
