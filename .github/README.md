# 🛠️ GitHub Configuration

This directory contains GitHub-specific configuration files for the DDO Quest Database project.

## 📁 Directory Structure

```
.github/
├── workflows/
│   └── ci.yml                    # Main CI/CD pipeline
├── ISSUE_TEMPLATE/
│   ├── bug_report.yml           # Bug report template
│   └── feature_request.yml      # Feature request template
├── pull_request_template.md     # PR template
├── dependabot.yml              # Automated dependency updates
└── .gitignore                  # GitHub-specific gitignore
```

## 🚀 CI/CD Pipeline

Our GitHub Actions workflow (`ci.yml`) provides comprehensive automated testing and quality checks:

### 🔍 Code Quality & Security
- **PHP CS Fixer**: Ensures consistent code style
- **PHPStan**: Static analysis for type safety
- **Security Audit**: Checks for known vulnerabilities
- **Trivy Scanner**: Container and filesystem security scanning

### 🧪 Testing Matrix
- **Backend Tests**: PHPUnit tests across PHP versions
- **Frontend Tests**: ESLint, Prettier, and build verification
- **Database Tests**: Migration and seeding validation
- **Performance Tests**: Lighthouse audits for web vitals

### 📊 Coverage & Reporting
- **Test Coverage**: Minimum 80% coverage requirement
- **Codecov Integration**: Coverage reporting and tracking
- **Performance Metrics**: Lighthouse performance scoring
- **Security Reports**: SARIF uploads for vulnerability tracking

## 🔄 Automated Dependencies

**Dependabot Configuration**:
- **PHP/Composer**: Weekly updates on Mondays
- **NPM/JavaScript**: Weekly updates on Tuesdays  
- **GitHub Actions**: Weekly updates on Wednesdays

**Major Version Handling**:
- Laravel and React major versions require manual review
- All other dependencies auto-update with PR creation
- Security updates are prioritized regardless of schedule

## 📝 Issue Templates

### 🐛 Bug Reports
Structured template requiring:
- Problem description and expected behavior
- Reproduction steps and environment details
- Browser compatibility information
- Relevant log output

### 🚀 Feature Requests
Comprehensive template including:
- Feature type classification
- Problem and solution descriptions
- Priority level and DDO context
- Alternative solutions consideration

## 📋 Pull Request Template

Standardized PR template ensuring:
- Clear description and issue linking
- Change type classification
- Testing confirmation checklist
- DDO-specific context where applicable
- Code review readiness verification

## 🎯 Quality Gates

**All PRs must pass**:
- ✅ All unit tests (134+ tests)
- ✅ Code style checks (PHP CS Fixer)
- ✅ Static analysis (PHPStan level 8)
- ✅ Security scans (no high/critical issues)
- ✅ Frontend build success
- ✅ Database migration tests

**Performance Requirements**:
- Lighthouse Performance Score ≥ 80
- Accessibility Score ≥ 90
- Best Practices Score ≥ 90
- SEO Score ≥ 80

## 🚨 Security

**Automated Security Measures**:
- Daily Trivy vulnerability scans
- Composer security audits on every push
- NPM audit checks for frontend dependencies
- SARIF report uploads for GitHub Security tab

**Manual Security Reviews**:
- All authentication-related changes
- Database migration modifications
- API endpoint additions/changes
- Third-party integration updates

## 📈 Metrics & Monitoring

**Tracked Metrics**:
- Test coverage percentage and trends
- Build success/failure rates
- Performance score evolution
- Security vulnerability resolution time
- Dependency update frequency

**Notifications**:
- Failed builds notify via GitHub
- Security issues create immediate alerts
- Performance regressions trigger warnings
- Coverage drops below threshold alert maintainers

---

This configuration ensures high code quality, security, and maintainability while providing excellent developer experience for the DDO community.
