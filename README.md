# ClubSuite Finance

[![Nextcloud Version](https://img.shields.io/badge/Nextcloud-28--32-blue.svg)](https://nextcloud.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.1--8.3-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-AGPL%20v3-green.svg)](LICENSE)

> 💰 Einfache Buchhaltung und Kassenbuch für Vereine.

## 📋 Übersicht

ClubSuite Finance bietet eine vollständige Finanzverwaltung für Vereine:

- **Konten**: Verwaltung von Bankkonten und Barkassen
- **Kategorien**: Einnahmen- und Ausgabenkategorien
- **Transaktionen**: Buchungen mit optionaler Mitgliederzuordnung
- **Berichte**: Jahresübersichten, Kassenberichte, Export
- **Integration**: Automatische Buchungen aus anderen ClubSuite-Modulen

## 🚀 Installation

### Über den Nextcloud App Store
1. **ClubSuite Core** muss installiert sein
2. Apps → Organisation → "ClubSuite Finance" suchen
3. Installieren und aktivieren

### Manuelle Installation
```bash
cd /path/to/nextcloud/apps
git clone https://github.com/ClubSuite-for-Nextcloud/clubsuite-finance.git
php occ app:enable clubsuite-finance
```

## 📦 Anforderungen

| Komponente | Version |
|------------|--------|
| Nextcloud | 28 - 32 |
| PHP | 8.1 - 8.3 |
| **clubsuite-core** | erforderlich |

## 🔒 DSGVO / Datenschutz

- Personenbezogene Finanzdaten werden DSGVO-konform verarbeitet
- Datenexport über Nextcloud Privacy API
- Audit-Log für alle Buchungen

## 📄 Lizenz

AGPL v3 – Siehe [LICENSE](LICENSE)

## 🐛 Bugs & Feature Requests

[GitHub Issues](https://github.com/ClubSuite-for-Nextcloud/clubsuite-finance/issues)

---

© 2026 Stefan Schulz
