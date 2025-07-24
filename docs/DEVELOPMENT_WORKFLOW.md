# 🚀 DDO Database Development Workflow

## 🎯 Overview

This document outlines the development workflow for the DDO Quest Database project. We use a **feature branch workflow** with **pull request reviews** to maintain code quality and ensure all changes are properly tested.

## 🛡️ Branch Protection Rules

The `main` branch is protected and requires:
- ✅ **Pull Request reviews** before merging
- ✅ **Status checks** must pass (all CI/CD pipeline jobs)
- ✅ **Up-to-date branches** before merging
- ❌ **No direct pushes** to main branch
- ❌ **No force pushes** allowed

## 📋 Development Workflow

### 1. 🌿 Create a Feature Branch

**Option A: Using the helper script (Recommended)**
```bash
# For new features
./scripts/create-branch.sh feature TASK-007 wiki-scraping-service

# For bug fixes  
./scripts/create-branch.sh bugfix TASK-012 fix-quest-xp-calculation

# For documentation
./scripts/create-branch.sh docs README update-installation-guide
```

**Option B: Manual branch creation**
```bash
# Update main branch
git checkout main
git pull origin main

# Create and switch to new branch
git checkout -b feature/TASK-XXX-description
```

### 2. 🛠️ Make Your Changes

- Implement your feature or fix
- Follow coding standards (PSR-12 for PHP, ES2021 for JavaScript)
- Add/update tests as needed
- Update documentation if required

### 3. 🧪 Test Locally

```bash
# Run all tests
./vendor/bin/sail artisan test

# Run specific test suites
./vendor/bin/sail artisan test --filter="Quest"
./vendor/bin/sail artisan test --filter="DDO"

# Check code style
composer run-script cs-check
composer run-script cs-fix

# Frontend checks
npm run lint
npm run format:check
npm run build
```

### 4. 💾 Commit Changes

Use conventional commit messages:
```bash
git add .
git commit -m "feat(TASK-007): implement wiki scraping service"
git commit -m "fix(TASK-012): correct XP calculation for epic quests"
git commit -m "docs(README): add development workflow guide"
git commit -m "test(TASK-015): add unit tests for quest model"
```

**Commit Message Format:**
```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### 5. 📤 Push Your Branch

```bash
# First push (sets up tracking)
git push -u origin feature/TASK-XXX-description

# Subsequent pushes
git push
```

### 6. 🔄 Create Pull Request

1. Go to GitHub repository
2. Click "New Pull Request"
3. Select your branch as source, `main` as target
4. Fill out the PR template with:
   - Clear description of changes
   - Link to related issue
   - Type of change checkboxes
   - Testing confirmation
   - Screenshots if applicable

### 7. ⏳ Wait for CI/CD and Review

Your PR will automatically trigger:
- 🔍 Code quality checks (PHP CS Fixer, PHPStan, ESLint)
- 🧪 Test suites (134+ tests across multiple environments)
- 🔒 Security scans (Trivy, dependency audits)
- ⚡ Performance tests (Lighthouse audits)
- 🗃️ Database migration tests

**All checks must pass** before merge is allowed.

### 8. 🎉 Merge After Approval

Once approved and all checks pass:
1. **Squash and Merge** (recommended for clean history)
2. Delete the feature branch
3. Pull latest main locally

```bash
git checkout main
git pull origin main
git branch -d feature/TASK-XXX-description
```

## 🌳 Branch Naming Convention

| Purpose | Format | Example |
|---------|--------|---------|
| **Feature** | `feature/TASK-XXX-description` | `feature/TASK-007-wiki-scraping-service` |
| **Bug Fix** | `bugfix/TASK-XXX-description` | `bugfix/TASK-012-fix-quest-xp-calculation` |
| **Hotfix** | `hotfix/critical-description` | `hotfix/security-vulnerability-patch` |
| **Documentation** | `docs/description` | `docs/update-api-documentation` |
| **Refactoring** | `refactor/TASK-XXX-description` | `refactor/TASK-020-optimize-database-queries` |
| **Testing** | `test/TASK-XXX-description` | `test/TASK-025-add-integration-tests` |

## 🚫 What NOT to Do

- ❌ **Never push directly to main** - use feature branches
- ❌ **Never force push** to shared branches
- ❌ **Never commit sensitive data** (API keys, passwords)
- ❌ **Never skip tests** - ensure all tests pass locally
- ❌ **Never merge without review** - all PRs need approval

## 🛠️ Development Environment

### Quick Setup
```bash
# Start development environment
./vendor/bin/sail up -d

# Install dependencies
./vendor/bin/sail composer install
./vendor/bin/sail npm install

# Setup database
./vendor/bin/sail artisan migrate --seed

# Start frontend development
./vendor/bin/sail npm run dev
```

### Useful Commands
```bash
# View logs
./vendor/bin/sail artisan tail

# Run database migrations
./vendor/bin/sail artisan migrate

# Seed database with test data
./vendor/bin/sail artisan db:seed

# Generate application key
./vendor/bin/sail artisan key:generate

# Clear caches
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
```

## 🔍 Code Quality Standards

### PHP Standards
- **PSR-12** coding style
- **PHPStan level 8** static analysis
- **100% type coverage** for new code
- **80% minimum test coverage**

### JavaScript Standards
- **ES2021** syntax
- **React 18** best practices
- **ESLint** with React hooks rules
- **Prettier** formatting

### Database Standards
- **Laravel migrations** for all schema changes
- **Eloquent relationships** properly defined
- **Factory classes** for all models
- **Rollback testing** for migrations

## 📊 Quality Gates

All PRs must pass:

| Check | Requirement |
|-------|-------------|
| **Unit Tests** | 134+ tests pass |
| **Code Coverage** | ≥ 80% |
| **Code Style** | PHP CS Fixer, ESLint pass |
| **Static Analysis** | PHPStan level 8 |
| **Security** | No critical vulnerabilities |
| **Performance** | Lighthouse scores ≥ 80% |
| **Database** | Migrations up/down successfully |

## 🚨 Emergency Procedures

### Hotfix for Critical Issues
```bash
# Create hotfix branch from main
git checkout main
git pull origin main
git checkout -b hotfix/critical-issue-description

# Make minimal changes to fix critical issue
# Test thoroughly
# Create PR with "HOTFIX" label
# Request immediate review
# Merge after approval
```

### Rollback if Needed
```bash
# If main branch has issues after merge
git checkout main
git revert <commit-hash>
git push origin main
```

## 🎮 DDO-Specific Guidelines

### Quest Data Changes
- Always validate against DDOWiki source
- Test XP calculations with multiple difficulty levels
- Ensure patron and adventure pack relationships are correct
- Verify saga completion logic

### Performance Considerations
- Quest searches must remain fast (< 100ms)
- Database queries should be optimized
- Frontend should remain responsive
- Consider caching for expensive operations

### Community Impact
- Features should enhance player experience
- Changes should not break existing quest routes
- Consider impact on different playstyles (solo, group, etc.)
- Document changes that affect optimization algorithms

---

## 📞 Getting Help

- **Questions**: Open a discussion on GitHub
- **Bugs**: Create an issue with bug template
- **Features**: Create an issue with feature template
- **Code Review**: Tag maintainers in PR
- **Emergency**: Contact project maintainers directly

Remember: This workflow ensures high code quality and prevents issues from reaching production. Every step helps maintain the reliability that DDO players depend on for their character optimization! 🎯
