# StayNotified

StayNotified is een alles-in-één oplossing voor persoonlijke applicatie die een kalendersysteem combineert met een geavanceerd notitieplatform. Het helpt gebruikers om hun afspraken bij te houden terwijl ze hun gedachten en notities georganiseerd kunnen houden op één plek.

## 🚀 Features

### Kalender
- Voeg afspraken toe, bewerk of verwijder ze.
- Markeer afspraken als **bevestigd** of **onbevestigd**.
- Bekijk al je afspraken in een overzichtelijke kalenderweergave.

### Notities
- Maak kleurrijke en stijlvolle notities.
- Ondersteuning voor opmaak zoals **vet**, *cursief*, koppen, etc.
- Notities zijn altijd beschikbaar na inloggen.

### Dashboard
- Direct overzicht van aankomende afspraken.
- Bekijk snel recent gebruikte en meest bekeken notities.
- Eenvoudige navigatie naar je agenda en notities.

## 🛠️ Tech Stack

- **Frontend**: Vue.js + Tailwind CSS
- **Backend**: Laravel + Inertia.js
- **Database**: MySQL of Sqlite
- **Authenticatie**: Laravel Sanctum

### Vereisten

- PHP 8.1 of hoger
- Composer
- Node.js en NPM
- MySQL of SQLite

## 📦 Installatie

### Stappen

1. **Clone de repository**

```bash
git clone https://github.com/jouw-gebruikersnaam/staynotified.git
cd staynotified
```

2. **Installeer PHP dependencies**

```bash
composer install
```

3. **Installeer JavaScript dependencies**

```bash
npm install
```

4. **Configureer de omgeving**

```bash
cp .env.example .env
php artisan key:generate
```

5. **Configureer de database**
    - Open het `.env` bestand
    - Vul de database gegevens in (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
    - Zorg ervoor dat je een lege database hebt aangemaakt

6. **Voer de migraties uit**

```bash
php artisan migrate
```

7. **Start de ontwikkelingsservers**

In één terminal:
```bash
npm run dev
```

In een andere terminal:
```bash
php artisan serve
```

8. **Open de applicatie**
    - Ga naar `http://127.0.0.1:8000` in je browser

## Gebruik

Na het installeren kun je inloggen of een account aanmaken. Eenmaal ingelogd kom je op het dashboard terecht waar je direct toegang hebt tot:

- Je aankomende afspraken
- Je recente notities
- Snelkoppelingen naar de kalender- en notitiefuncties

## Bijdragen

Wil je bijdragen aan StayNotified? Geweldig! Je kunt:
- Een issue aanmaken voor bugs of nieuwe functies
- Een pull request indienen met verbeteringen
- De documentatie verbeteren
