# Dependabot Automation Workflows - Implementation Guide

## Overview

This repository includes two GitHub Actions workflows that automate Dependabot pull request management:

1. **Dependabot Auto-Approve** (`dependabot-auto-approve.yml`) - Automatically approves Dependabot PRs
2. **Dependabot Auto-Merge** (`dependabot-auto-merge.yml`) - Enables auto-merge on Dependabot PRs

## How It Works

Both workflows trigger when a pull request is created or updated. They:

1. Check if the PR author is `dependabot[bot]`
2. Fetch metadata about the Dependabot PR using the `dependabot/fetch-metadata` action
3. Perform their respective actions (approve or enable auto-merge)

## Prerequisites

### 1. Enable Dependabot

Dependabot should already be enabled in your repository (via `.github/dependabot.yml`). This repository already has Dependabot configured for npm updates.

### 2. Enable Auto-Merge in Repository Settings

Auto-merge must be enabled at the repository level:

1. Go to your repository on GitHub
2. Navigate to **Settings** → **General**
3. Scroll down to the **Pull Requests** section
4. Check the box for **"Allow auto-merge"**

### 3. Configure Branch Protection Rules (Recommended)

To ensure quality and safety, it's recommended to set up branch protection rules:

1. Go to **Settings** → **Branches**
2. Add a branch protection rule for your main/trunk branch
3. Configure the following settings:
   - ✅ **Require a pull request before merging**
   - ✅ **Require approvals** (at least 1)
   - ✅ **Require status checks to pass before merging** (if you have CI/CD)
   - ✅ **Require branches to be up to date before merging**

This ensures that even auto-merged PRs go through proper checks.

## No Additional Tokens Required

The workflows use the built-in `GITHUB_TOKEN` secret, which is automatically provided by GitHub Actions. **No additional personal access tokens or secrets need to be created.**

The `GITHUB_TOKEN` has sufficient permissions for:
- Reading pull request metadata
- Approving pull requests
- Enabling auto-merge on pull requests

## Workflow Details

### Dependabot Auto-Approve Workflow

**File**: `.github/workflows/dependabot-auto-approve.yml`

**Permissions Required**:
- `pull-requests: write` - To approve pull requests

**What it does**:
1. Triggers on any pull request event
2. Checks if the actor is Dependabot
3. Fetches PR metadata
4. Automatically approves the PR using `gh pr review --approve`

### Dependabot Auto-Merge Workflow

**File**: `.github/workflows/dependabot-auto-merge.yml`

**Permissions Required**:
- `contents: write` - To merge pull requests
- `pull-requests: write` - To modify pull request settings

**What it does**:
1. Triggers on any pull request event
2. Checks if the actor is Dependabot
3. Fetches PR metadata
4. Enables auto-merge using `gh pr merge --auto --merge`

The PR will automatically merge once all branch protection requirements are met (approvals, status checks, etc.).

## How to Use

Once the workflows are merged into your default branch, they will automatically run for all new Dependabot pull requests. No manual intervention is required.

### Workflow Execution Flow

1. Dependabot creates a PR for dependency updates
2. Both workflows trigger automatically
3. The auto-approve workflow approves the PR
4. The auto-merge workflow enables auto-merge
5. Once all branch protection checks pass, the PR merges automatically
6. Dependabot may rebase or update the PR, triggering the workflows again

## Monitoring and Troubleshooting

### Viewing Workflow Runs

1. Go to the **Actions** tab in your repository
2. Look for "Dependabot Auto-Approve" and "Dependabot Auto-Merge" workflows
3. Click on individual runs to see detailed logs

### Common Issues

**Issue**: Workflows don't run
- **Solution**: Ensure workflows are in the default branch (main/trunk)

**Issue**: Auto-merge doesn't work
- **Solution**: Verify that auto-merge is enabled in repository settings

**Issue**: PRs aren't merging automatically
- **Solution**: Check branch protection rules. The PR must pass all required status checks and approvals before it can auto-merge

**Issue**: Permission errors
- **Solution**: Verify that GitHub Actions has write permissions in repository settings under **Settings** → **Actions** → **General** → **Workflow permissions**

### Customization Options

You can customize the workflows to be more selective about which Dependabot PRs to auto-approve/merge:

#### Example: Only Auto-Merge Patch Updates

Add additional conditions to the workflow using the metadata from `dependabot/fetch-metadata`:

```yaml
- name: Enable auto-merge for Dependabot PRs
  if: steps.metadata.outputs.update-type == 'version-update:semver-patch'
  run: gh pr merge --auto --merge "$PR_URL"
  env:
    PR_URL: ${{ github.event.pull_request.html_url }}
    GH_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

#### Example: Different Behavior Based on Dependency Type

```yaml
- name: Auto-merge development dependencies
  if: steps.metadata.outputs.dependency-type == 'direct:development'
  run: gh pr merge --auto --merge "$PR_URL"
  env:
    PR_URL: ${{ github.event.pull_request.html_url }}
    GH_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

## Security Considerations

- The workflows only act on PRs created by `dependabot[bot]`, preventing unauthorized use
- They use the minimal required permissions (`pull-requests: write` and `contents: write`)
- Branch protection rules provide an additional safety layer
- The `dependabot/fetch-metadata` action is maintained by GitHub and regularly updated

## References

- [GitHub Docs: Automating Dependabot with GitHub Actions](https://docs.github.com/en/code-security/dependabot/working-with-dependabot/automating-dependabot-with-github-actions)
- [GitHub Docs: Fetching metadata about a pull request](https://docs.github.com/en/code-security/dependabot/working-with-dependabot/automating-dependabot-with-github-actions#fetching-metadata-about-a-pull-request)
- [GitHub Docs: Automatically approving a pull request](https://docs.github.com/en/code-security/dependabot/working-with-dependabot/automating-dependabot-with-github-actions#automatically-approving-a-pull-request)
- [GitHub Docs: Enabling automerge on a pull request](https://docs.github.com/en/code-security/dependabot/working-with-dependabot/automating-dependabot-with-github-actions#enabling-automerge-on-a-pull-request)
- [dependabot/fetch-metadata Action](https://github.com/dependabot/fetch-metadata)
