# 🗡️ DDO Quest Database

<div align="center">

```
  ____  ____   ___     ____    _  _____ _    ____    _    ____  _____ 
 |  _ \|  _ \ / _ \   |  _ \  / \|_   _| |  | __ )  / \  / ___|| ____|
 | | | | | | | | | |  | | | |/ _ \ | | | |  |  _ \ / _ \ \___ \|  _|  
 | |_| | |_| | |_| |  | |_| / ___ \| | | |  | |_) / ___ \ ___) | |___ 
 |____/|____/ \___/   |____/_/   \_\_| |_|  |____/_/   \_\____/|_____|
                                                                      
        Dungeons & Dragons Online Quest Database & Optimization Tool
```

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/settoloki/ddo-quest-database)
[![Laravel](https://img.shields.io/badge/Laravel-12.21.0-FF2D20?logo=laravel)](https://laravel.com)
[![React](https://img.shields.io/badge/React-18.2.0-61DAFB?logo=react)](https://reactjs.org)
[![Tests](https://img.shields.io/badge/tests-134%20passing-brightgreen)](./tests)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

*The ultimate tool for optimizing your DDO character progression*

</div>

## 🌟 About DDO Database

The **DDO Quest Database** is a comprehensive web application designed to revolutionize how players approach character progression in Dungeons & Dragons Online. Built with modern web technologies, this tool provides an intelligent, data-driven approach to quest selection and leveling optimization.

### 🎯 Our Mission

To create the most comprehensive and intelligent DDO quest database that empowers players to:
- **Optimize leveling routes** with scientific precision
- **Maximize XP efficiency** across all character builds
- **Discover quest synergies** and saga completions
- **Plan character progression** with confidence
- **Save time** and enhance the gaming experience

### 🚀 What We Hope to Achieve

**For Individual Players:**
- Reduce time spent researching optimal quest paths
- Maximize XP per hour played
- Discover hidden quest synergies and bonus opportunities
- Plan efficient True Reincarnation cycles

**For the DDO Community:**
- Provide accurate, up-to-date quest data sourced from DDOWiki
- Create a collaborative platform for sharing optimization strategies
- Build comprehensive analytics on quest popularity and efficiency
- Support both new players and veterans with tailored recommendations

**For Character Building:**
- Enable precise planning for specific level ranges
- Support multiple character builds and playstyles
- Account for group dynamics and solo play preferences
- Integrate with enhancement planning and gear progression

## ✨ Key Features

### 🗃️ Comprehensive Quest Database
- **1000+ Quests** from all level ranges (1-30 Heroic, Epic, Legendary)
- **Detailed XP calculations** for all difficulty levels
- **Saga tracking** and completion bonuses
- **Adventure pack organization** with purchase requirements
- **Location mapping** and quest prerequisites

### 🧮 Advanced Optimization Engine
- **XP per minute calculations** with accuracy metrics
- **Multi-objective optimization** (time, favor, saga completion)
- **Character build considerations** (melee, caster, hybrid)
- **Group size optimization** and bonus calculations
- **Ransack tracking** and repeat quest management

### 🎨 Modern Web Interface
- **Intuitive React frontend** with Chakra UI components
- **Advanced filtering** by level, patron, duration, rewards
- **Interactive quest browser** with detailed modal views
- **Real-time search** and sorting capabilities
- **Mobile-responsive design** for on-the-go planning

### 📊 Analytics & Insights
- **Quest popularity metrics** and community trends
- **Efficiency comparisons** across different approaches
- **Progress tracking** for individual characters
- **Statistical analysis** of leveling patterns

## 🛠️ Technology Stack

**Backend (Laravel 12.21.0)**
- PHP 8.4 with modern features
- MySQL database with optimized schema
- RESTful API with comprehensive validation
- Laravel Sail for containerized development

**Frontend (React 18.2.0)**
- Inertia.js for seamless SPA experience
- Chakra UI for beautiful, accessible components
- Modern JavaScript with ES6+ features
- Responsive design principles

**Development & Testing**
- 134+ comprehensive tests with PHPUnit
- Docker-based development environment
- Automated CI/CD pipeline
- Code quality tools and standards

## 🏗️ Project Architecture

### Database Schema
```
├── Core Tables
│   ├── quests (1000+ DDO quests)
│   ├── patrons (The Coin Lords, House Deneith, etc.)
│   ├── adventure_packs (Free to Play, Premium, VIP)
│   └── locations (Stormreach, Korthos, etc.)
├── XP System
│   ├── difficulties (Casual → Reaper)
│   ├── quest_xp_rewards (by difficulty)
│   └── bonus_xp_categories (Monster, Trap, etc.)
└── Advanced Features
    ├── sagas (completion tracking)
    ├── quest_objectives (optional goals)
    └── optimization_cache (performance)
```

### API Endpoints
```
GET  /api/v1/quests              - Quest listing with filters
GET  /api/v1/quests/{id}         - Detailed quest information
GET  /api/v1/quests/optimize     - Leveling route optimization
GET  /api/v1/patrons             - Patron information
GET  /api/v1/adventure-packs     - Adventure pack details
GET  /api/v1/sagas               - Saga completion tracking
```

## 🚀 Getting Started

### Prerequisites
- Docker & Docker Compose
- Git
- Modern web browser

### Quick Setup
```bash
# Clone the repository
git clone https://github.com/settoloki/ddo-quest-database.git
cd ddo-quest-database

# Start the development environment
./vendor/bin/sail up -d

# Install dependencies and setup database
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail artisan migrate --seed

# Build frontend assets
./vendor/bin/sail npm run build

# Access the application
open http://localhost
```

### Development Commands
```bash
# Run tests
./vendor/bin/sail artisan test

# Start frontend development server
./vendor/bin/sail npm run dev

# Access database
./vendor/bin/sail mysql

# View logs
./vendor/bin/sail artisan tail
```

## 📈 Current Status

### ✅ Completed Features
- **Core Infrastructure**: Database schema, models, API endpoints
- **Quest Management**: Full CRUD operations with validation
- **Frontend Interface**: React components with advanced filtering
- **Testing Suite**: 134 passing tests with comprehensive coverage
- **Data Foundation**: Reference data and sample quests

### 🚧 In Development
- **Wiki Scraping Service**: Automated data import from DDOWiki
- **Optimization Engine**: Multi-objective leveling algorithms
- **Saga System**: Completion tracking and bonus calculations
- **Mobile App**: React Native companion application

### 🎯 Roadmap
- **Q3 2025**: Complete wiki scraping and full quest database
- **Q4 2025**: Launch optimization engine and character tracking
- **Q1 2026**: Mobile app release and community features
- **Q2 2026**: Advanced analytics and machine learning insights

## 🤝 Contributing

We welcome contributions from the DDO community! Whether you're a developer, player, or data enthusiast, there are many ways to help:

### For Developers
- **Bug fixes** and feature implementations
- **Performance optimizations** and code quality improvements
- **Testing** and documentation enhancements
- **Wiki scraping** accuracy and data validation

### For Players
- **Quest data validation** and corrections
- **Optimization strategy** testing and feedback
- **User experience** feedback and suggestions
- **Community outreach** and promotion

### Getting Started
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🎮 Dedication

*Built with ❤️ for the DDO community by players, for players.*

---

<div align="center">

**[Website](https://ddo-database.local) • [Documentation](./docs) • [Issues](https://github.com/settoloki/ddo-quest-database/issues) • [Discord](https://discord.gg/ddo)**

*Join thousands of players optimizing their DDO experience*

</div>
