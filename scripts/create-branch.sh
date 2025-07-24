#!/bin/bash

# DDO Database Development Branch Helper
# Usage: ./scripts/create-branch.sh [type] [task-id] [description]
# Example: ./scripts/create-branch.sh feature TASK-007 "wiki-scraping-service"

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_color() {
    printf "${1}${2}${NC}\n"
}

# Function to show usage
show_usage() {
    print_color $BLUE "🚀 DDO Database Branch Creation Helper"
    echo ""
    print_color $YELLOW "Usage:"
    echo "  ./scripts/create-branch.sh [type] [task-id] [description]"
    echo ""
    print_color $YELLOW "Branch Types:"
    echo "  feature  - New features or enhancements"
    echo "  bugfix   - Bug fixes"
    echo "  hotfix   - Critical fixes that need immediate attention"
    echo "  docs     - Documentation updates"
    echo "  refactor - Code refactoring without feature changes"
    echo "  test     - Adding or updating tests"
    echo ""
    print_color $YELLOW "Examples:"
    echo "  ./scripts/create-branch.sh feature TASK-007 wiki-scraping-service"
    echo "  ./scripts/create-branch.sh bugfix TASK-012 fix-quest-xp-calculation"
    echo "  ./scripts/create-branch.sh docs README update-installation-guide"
    echo ""
    print_color $YELLOW "This will:"
    echo "  ✅ Create a properly named branch"
    echo "  ✅ Switch to the new branch"
    echo "  ✅ Set up tracking with origin"
    echo "  ✅ Show next steps"
}

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    print_color $RED "❌ Error: Not in a git repository"
    exit 1
fi

# Check if we're in the DDO project root
if [ ! -f "artisan" ] || [ ! -f "composer.json" ]; then
    print_color $RED "❌ Error: Not in DDO project root directory"
    print_color $YELLOW "Please run this script from the project root where artisan and composer.json are located"
    exit 1
fi

# Check arguments
if [ $# -lt 2 ]; then
    print_color $RED "❌ Error: Missing required arguments"
    echo ""
    show_usage
    exit 1
fi

BRANCH_TYPE=$1
TASK_ID=$2
DESCRIPTION=${3:-""}

# Validate branch type
case $BRANCH_TYPE in
    feature|bugfix|hotfix|docs|refactor|test)
        ;;
    *)
        print_color $RED "❌ Error: Invalid branch type '$BRANCH_TYPE'"
        echo ""
        show_usage
        exit 1
        ;;
esac

# Clean up description (remove spaces, convert to lowercase, limit length)
if [ -n "$DESCRIPTION" ]; then
    DESCRIPTION=$(echo "$DESCRIPTION" | tr '[:upper:]' '[:lower:]' | sed 's/[^a-z0-9]/-/g' | sed 's/--*/-/g' | sed 's/^-\|-$//g')
    BRANCH_NAME="${BRANCH_TYPE}/${TASK_ID}-${DESCRIPTION}"
else
    BRANCH_NAME="${BRANCH_TYPE}/${TASK_ID}"
fi

# Truncate if too long
if [ ${#BRANCH_NAME} -gt 50 ]; then
    print_color $YELLOW "⚠️  Branch name truncated to 50 characters"
    BRANCH_NAME=$(echo "$BRANCH_NAME" | cut -c1-50 | sed 's/-$//')
fi

print_color $BLUE "🌿 Creating branch: $BRANCH_NAME"

# Make sure we're on main and up to date
print_color $YELLOW "📥 Updating main branch..."
git checkout main
git pull origin main

# Check if branch already exists
if git show-ref --verify --quiet refs/heads/$BRANCH_NAME; then
    print_color $RED "❌ Error: Branch '$BRANCH_NAME' already exists locally"
    print_color $YELLOW "Use: git checkout $BRANCH_NAME"
    exit 1
fi

if git ls-remote --exit-code --heads origin $BRANCH_NAME >/dev/null 2>&1; then
    print_color $RED "❌ Error: Branch '$BRANCH_NAME' already exists on remote"
    print_color $YELLOW "Use: git checkout -b $BRANCH_NAME origin/$BRANCH_NAME"
    exit 1
fi

# Create and switch to new branch
print_color $GREEN "✅ Creating and switching to branch: $BRANCH_NAME"
git checkout -b $BRANCH_NAME

# Set up tracking (will be done on first push)
print_color $BLUE "📋 Branch created successfully!"
echo ""
print_color $YELLOW "🚀 Next steps:"
echo "  1. Make your changes"
echo "  2. Commit changes: git add . && git commit -m \"feat($TASK_ID): your description\""
echo "  3. Push branch: git push -u origin $BRANCH_NAME"
echo "  4. Create Pull Request on GitHub"
echo ""
print_color $YELLOW "📏 Commit message format:"
echo "  feat($TASK_ID): add new feature"
echo "  fix($TASK_ID): resolve bug"
echo "  docs($TASK_ID): update documentation"
echo "  refactor($TASK_ID): improve code structure"
echo "  test($TASK_ID): add tests"
echo ""
print_color $BLUE "💡 Tips:"
echo "  - Keep commits focused and atomic"
echo "  - Write descriptive commit messages"
echo "  - Run tests locally: ./vendor/bin/sail artisan test"
echo "  - Check code style: composer run-script cs-fix"
echo ""
print_color $GREEN "🎉 Happy coding!"
