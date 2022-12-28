## Instalasi:

1. Jalankan perintah ini di git bash

```bash
git clone https://github.com/systemduta/kms-api.git
```

2. Kemudian masuk ke folder 'kms-api' atau bisa langsung dengan paste dan run command dibawah di git bash

```bash
cd kms-api
```

3. Setelah itu alihkan ke branch "dev" engan paste dan run command dibawah di git bash

```bash
git checkout dev
```

-   branch digunakan untuk memisahkan antara code development dan code production
-   branch main: untuk production
-   branch dev: untuk development
-   kalau mau upload ke production ada "guard" jadi harus manual ke web github dan masuk ke akun github engineer

4. Kemudian buka project di Visual studio code (sangat disarankan) atau bisa langsung dengan paste dan run command dibawah di git bash

```bash
code .
```

5. Kemudian ketik satu persatu perintah dibawah :

```bash
composer install
composer require laravel/passport
```

6. Atur database di .env
7. Kemudian jalankan satu persatu

```bash
php artisan migrate
php artisan passport:migrate
```
