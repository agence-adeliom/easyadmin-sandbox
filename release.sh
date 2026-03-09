#!/bin/bash
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

BUMP_TYPE=${1:-patch}

if [ "$BUMP_TYPE" != "patch" ] && [ "$BUMP_TYPE" != "minor" ]; then
    echo -e "${RED}Error: bump type must be 'patch' or 'minor'${NC}"
    exit 1
fi

CURRENT_MINOR=0
NEXT_MINOR=1

LATEST_TAG=$(git tag --list "3.*" --sort=-v:refname | head -1)
if [ -z "$LATEST_TAG" ]; then
    echo -e "${RED}Error: No 3.x tags found${NC}"
    exit 1
fi

IFS='.' read -r MAJOR MINOR PATCH <<< "$LATEST_TAG"
if [ "$MAJOR" != "3" ] || [ -z "$MINOR" ] || [ -z "$PATCH" ]; then
    echo -e "${RED}Error: Latest tag '$LATEST_TAG' is not a valid 3.x semantic version${NC}"
    exit 1
fi

CURRENT_MINOR=$MINOR
NEXT_MINOR=$((CURRENT_MINOR + 1))

if [ "$BUMP_TYPE" = "patch" ]; then
    VERSION="3.$CURRENT_MINOR.$((PATCH + 1))"
    RELEASE_CONSTRAINT="^$VERSION"
    DEVELOPMENT_CONSTRAINT="^3.$NEXT_MINOR"
else
    VERSION="3.$NEXT_MINOR.0"
    RELEASE_CONSTRAINT="^$VERSION"
    DEVELOPMENT_CONSTRAINT="^3.$((NEXT_MINOR + 1))"
fi

echo -e "${GREEN}Latest tag: $LATEST_TAG${NC}"
echo -e "${GREEN}Bump type: $BUMP_TYPE${NC}"
echo -e "${GREEN}Creating release: $VERSION${NC}"
echo ""

# Step 1: Update inter-bundle dependencies to the release version
echo -e "${YELLOW}Step 1: Updating dependencies to $RELEASE_CONSTRAINT${NC}"
for file in lib/*/composer.json; do
    if grep -Eq '"agence-adeliom/easy-.*-bundle": "\^3\.[0-9]+"' "$file" 2>/dev/null; then
        echo "  Processing $file"
        sed -E -i '' 's/("agence-adeliom\/easy-[^"]*-bundle": ")\^3\.[0-9]+"/\1'"$RELEASE_CONSTRAINT"'"/g' "$file"
    fi
done

# Step 2: Commit as "prepare release"
echo ""
echo -e "${YELLOW}Step 2: Committing 'prepare release'${NC}"
git add lib/*/composer.json
git commit -m "prepare release"

# Step 3: Revert back to development constraints
echo ""
echo -e "${YELLOW}Step 3: Reverting to development state${NC}"
for file in lib/*/composer.json; do
    if grep -q '"agence-adeliom/easy-.*-bundle": "'"$RELEASE_CONSTRAINT"'"' "$file" 2>/dev/null; then
        echo "  Processing $file"
        sed -E -i '' 's/("agence-adeliom\/easy-[^"]*-bundle": ")'"$(printf '%s' "$RELEASE_CONSTRAINT" | sed 's/\^/\\^/g')"'"/\1'"$DEVELOPMENT_CONSTRAINT"'"/g' "$file"
    fi
done

# Step 4: Commit as next development line
echo ""
echo -e "${YELLOW}Step 4: Committing 'open ${DEVELOPMENT_CONSTRAINT#^}.x-dev'${NC}"
git add lib/*/composer.json
git commit -m "open ${DEVELOPMENT_CONSTRAINT#^}.x-dev"

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
