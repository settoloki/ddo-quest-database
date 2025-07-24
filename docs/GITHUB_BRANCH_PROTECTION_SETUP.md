# 🛡️ GitHub Branch Protection Setup Guide

## Automated Setup Instructions

To enable branch protection for the `main` branch, follow these steps in your GitHub repository:

### 1. Navigate to Branch Protection Settings
1. Go to your repository on GitHub: `https://github.com/settoloki/ddo-quest-database`
2. Click on **Settings** tab
3. Click on **Branches** in the left sidebar
4. Click **Add branch protection rule**

### 2. Configure Protection Rule

**Branch name pattern:** `main`

**Enable the following protections:**

#### Protect matching branches:
- ✅ **Require a pull request before merging**
  - ✅ Require approvals: `1`
  - ✅ Dismiss stale PR approvals when new commits are pushed
  - ✅ Require review from code owners (if CODEOWNERS file exists)

#### Status checks:
- ✅ **Require status checks to pass before merging**
- ✅ **Require branches to be up to date before merging**
- ✅ **Required status checks:** (Add these as they appear after first PR)
  - `🔍 Code Quality & Security`
  - `🧪 Backend Tests (PHP 8.4)`
  - `🎨 Frontend Tests (Node 20)`
  - `🎨 Frontend Tests (Node 22)`
  - `🔒 Security & Dependency Scan`
  - `🗃️ Database Tests`

#### Additional restrictions:
- ✅ **Restrict pushes that create files to main**
- ✅ **Include administrators** (applies rules to admins too)
- ❌ **Allow force pushes** (keep disabled)
- ❌ **Allow deletions** (keep disabled)

### 3. Save Protection Rule
Click **Create** to save the branch protection rule.

## Verification

After setup, you can verify the protection is working by:

1. **Testing direct push (should fail):**
   ```bash
   git checkout main
   echo "test" >> README.md
   git add README.md
   git commit -m "test direct push"
   git push origin main
   # This should be rejected
   ```

2. **Testing proper workflow (should work):**
   ```bash
   git reset HEAD~1  # Undo the test commit
   ./scripts/create-branch.sh test VERIFY branch-protection-test
   echo "test" >> README.md
   git add README.md
   git commit -m "test(VERIFY): test branch protection"
   git push -u origin test/VERIFY-branch-protection-test
   # Create PR on GitHub - this should work
   ```

## Expected Behavior After Setup

### ✅ Allowed Actions:
- Create feature branches from main
- Push to feature branches
- Create Pull Requests to main
- Merge PRs after CI passes and approval
- Emergency hotfix branches (with proper review)

### ❌ Blocked Actions:
- Direct pushes to main branch
- Force pushes to main branch
- Merging PRs without approval
- Merging PRs with failing CI checks
- Deleting main branch

## Emergency Override

If you need to bypass protection in extreme circumstances:
1. Go to Settings > Branches
2. Edit the protection rule
3. Temporarily uncheck "Include administrators"
4. Make necessary changes
5. **Immediately re-enable** protection

## Troubleshooting

### Status Checks Not Appearing
- The required status checks will only appear after your first PR is created
- Make sure the CI workflow has run at least once
- Check that workflow names match exactly

### CI Checks Not Required
- GitHub needs to see the status checks run once before they can be required
- Create a test PR to populate the available status checks
- Then edit the branch protection rule to require them

### Permission Issues
- Ensure you have admin rights to the repository
- Some settings require owner permissions
- Contact repository owner if needed

---

## Next Steps After Setup

1. **Test the workflow** with a small change
2. **Update team documentation** about the new process
3. **Create a test PR** to verify all CI checks work
4. **Add status badges** to README if desired
5. **Train team members** on the new workflow

The branch protection is now active! All changes to main must go through the pull request process with CI validation and code review. 🛡️
