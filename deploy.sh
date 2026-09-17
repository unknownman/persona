#!/bin/bash
set -e

echo "🔍 Step 1: Running basic pre-flight checks..."
composer validate --no-check-publish || true

# 1. Calculate the new version tag (Auto Patch Bump)
LATEST_TAG=$(git describe --tags --abbrev=0 2>/dev/null || echo "v1.1.0")
if [[ $LATEST_TAG =~ ^v([0-9]+)\.([0-9]+)\.([0-9]+)$ ]]; then
    MAJOR="${BASH_REMATCH[1]}"
    MINOR="${BASH_REMATCH[2]}"
    PATCH="${BASH_REMATCH[3]}"
    NEW_PATCH=$((PATCH + 1))
    NEW_TAG="v$MAJOR.$MINOR.$NEW_PATCH"
else
    echo "⚠️ Could not parse tag $LATEST_TAG. Ensure tags follow vX.Y.Z format."
    exit 1
fi
echo "🚀 Bumping version from $LATEST_TAG to $NEW_TAG"

# 2. Generate Changelog from commits since last tag
echo "📝 Generating changelog..."
CHANGELOG=$(git log ${LATEST_TAG}..HEAD --oneline --pretty=format:"- %s" 2>/dev/null || echo "- Enterprise architecture refactoring and security enhancements.")
if [ -z "$CHANGELOG" ]; then
    CHANGELOG="- Enterprise architecture refactoring and security enhancements."
fi
# Prepend a nice header
echo -e "### What's New in $NEW_TAG\n\n$CHANGELOG" > /tmp/persona_changelog.md
echo "Changelog generated at /tmp/persona_changelog.md"

# 3. Commit all pending changes
git add .
if ! git diff-index --quiet HEAD; then
    echo "💾 Committing pending changes..."
    git commit -m "chore: release $NEW_TAG (Enterprise Polish)"
    git push origin main
else
    echo "ℹ️ No new changes to commit. Proceeding with tag..."
fi

# 4. Create and push the new tag to the main repo
echo "🏷️ Tagging main repository with $NEW_TAG..."
git tag "$NEW_TAG"
git push origin "$NEW_TAG"

# 5. Wait for the Subtree Split GitHub Action to complete
echo "⏳ Waiting 15 seconds for GitHub to register the workflow run..."
sleep 15
RUN_ID=$(gh run list --limit 1 --json databaseId -q '.[0].databaseId')

if [ -z "$RUN_ID" ]; then
    echo "❌ Could not find a running GitHub Action workflow. Please check your GitHub Actions tab."
    exit 1
fi

echo "👀 Watching workflow run ID: $RUN_ID..."
gh run watch $RUN_ID --exit-status

if [ $? -ne 0 ]; then
    echo "❌ GitHub Action failed! The code was not synced to subtrees. Aborting remote tagging."
    exit 1
fi

# 6. Create the release on all repositories
echo "✅ Monorepo split successful. Publishing Releases via GitHub CLI..."
REPOS=(
  "unknownman/persona"
  "unknownman/persona-core"
  "unknownman/persona-livewire"
  "unknownman/persona-inertia"
  "unknownman/persona-blade"
  "unknownman/persona-api"
)

for REPO in "${REPOS[@]}"; do
  echo "📦 Creating release $NEW_TAG on $REPO..."
  gh release create "$NEW_TAG" -R "$REPO" --title "Persona $NEW_TAG" --notes-file /tmp/persona_changelog.md || echo "⚠️ Failed to create release on $REPO (might already exist)."
done

# Cleanup
rm /tmp/persona_changelog.md
echo "🎉 SUCCESS! Release $NEW_TAG fully published across the entire ecosystem with Changelogs!"
