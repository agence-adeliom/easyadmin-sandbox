#!/bin/bash
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get the latest 3.0.x tag and calculate next version
LATEST_TAG=$(git tag --list "3.0.*" --sort=-v:refname | head -1)
if [ -z "$LATEST_TAG" ]; then
    echo -e "${RED}Error: No 3.0.x tags found${NC}"
    exit 1
fi

LATEST_PATCH=$(echo "$LATEST_TAG" | sed 's/3\.0\.//')
NEXT_PATCH=$((LATEST_PATCH + 1))
VERSION="3.0.$NEXT_PATCH"

echo -e "${GREEN}Latest tag: $LATEST_TAG${NC}"
echo -e "${GREEN}Creating release: $VERSION${NC}"
echo ""

# Step 1: Replace ^3.1 with ^3.0.X in all agence-adeliom dependencies
echo -e "${YELLOW}Step 1: Updating dependencies to ^$VERSION${NC}"
for file in lib/*/composer.json; do
    if grep -q '"agence-adeliom/easy-.*-bundle": "\^3\.1"' "$file" 2>/dev/null; then
        echo "  Processing $file"
        sed -i '' 's/\("agence-adeliom\/easy-[^"]*-bundle": "\)\^3\.1"/\1^'"$VERSION"'"/g' "$file"
    fi
done

# Step 2: Commit as "prepare release"
echo ""
echo -e "${YELLOW}Step 2: Committing 'prepare release'${NC}"
git add lib/*/composer.json
git commit -m "prepare release"

# Step 3: Revert back to ^3.1
echo ""
echo -e "${YELLOW}Step 3: Reverting to development state${NC}"
for file in lib/*/composer.json; do
    if grep -q '"agence-adeliom/easy-.*-bundle": "\^'"$VERSION"'"' "$file" 2>/dev/null; then
        echo "  Processing $file"
        sed -i '' 's/\("agence-adeliom\/easy-[^"]*-bundle": "\)\^'"$VERSION"'"/\1^3.1"/g' "$file"
    fi
done

# Step 4: Commit as "open 3.1.x-dev"
echo ""
echo -e "${YELLOW}Step 4: Committing 'open 3.1.x-dev'${NC}"
git add lib/*/composer.json
git commit -m "open 3.1.x-dev"

# Step 5: Create tag on the "prepare release" commit (one commit back)
echo ""
echo -e "${YELLOW}Step 5: Creating tag $VERSION${NC}"
git tag "$VERSION" HEAD~1

# Summary
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Release $VERSION prepared successfully!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Commits since $LATEST_TAG:"
git --no-pager log --oneline "$LATEST_TAG"..HEAD
echo ""
echo "New tag: $VERSION"
echo ""
echo -e "${YELLOW}To push (verify first, then run):${NC}"
echo "  git push origin 3.x"
echo "  git push origin $VERSION"
