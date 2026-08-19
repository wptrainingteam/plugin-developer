# Setting Up Dependabot, Auto-Approve, and Auto-Merge Workflows

This guide provides detailed instructions for setting up Dependabot version updates along with automatic approval and merging of Dependabot pull requests in your repository.

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Getting Started](#getting-started)
4. [Creating the Dependabot Configuration](#creating-the-dependabot-configuration)
5. [Creating the Auto-Approve Workflow](#creating-the-auto-approve-workflow)
6. [Creating the Auto-Merge Workflow](#creating-the-auto-merge-workflow)
7. [Examples for Different Dependency Types](#examples-for-different-dependency-types)
8. [Submitting Your Changes](#submitting-your-changes)

## Overview

This setup includes three components:

1. **Dependabot Configuration** (`.github/dependabot.yml`) - Monitors your dependencies and creates pull requests when updates are available
2. **Auto-Approve Workflow** (`.github/workflows/dependabot-auto-approve.yml`) - Automatically approves Dependabot pull requests
3. **Auto-Merge Workflow** (`.github/workflows/dependabot-auto-merge.yml`) - Automatically enables auto-merge for Dependabot pull requests

## Prerequisites

- A GitHub account with access to the target repository
- Git installed on your local machine
- Basic understanding of GitHub workflows and YAML syntax
- Repository with package dependencies (npm, composer, or both)

## Getting Started

### Step 1: Fork the Repository

1. Navigate to the repository on GitHub where you want to add these configurations
2. Click the **Fork** button in the top-right corner
3. Select your GitHub account as the destination for the fork

### Step 2: Clone Your Fork Locally

```bash
git clone https://github.com/YOUR_USERNAME/REPOSITORY_NAME.git
cd REPOSITORY_NAME
```

Replace `YOUR_USERNAME` with your GitHub username and `REPOSITORY_NAME` with the name of the repository.

### Step 3: Create a New Branch

```bash
git checkout -b add-dependabot-workflows
```

You can use any descriptive branch name you prefer.

## Creating the Dependabot Configuration

### Step 1: Create the `.github` Directory (if it doesn't exist)

```bash
mkdir -p .github
```

### Step 2: Create the `dependabot.yml` File

Create a new file at `.github/dependabot.yml`:

```bash
touch .github/dependabot.yml
```

### Step 3: Add the Configuration

Open `.github/dependabot.yml` in your text editor and add the appropriate configuration based on your dependency type (see [Examples](#examples-for-different-dependency-types) section below).

## Creating the Auto-Approve Workflow

### Step 1: Create the Workflows Directory (if it doesn't exist)

```bash
mkdir -p .github/workflows
```

### Step 2: Create the Auto-Approve Workflow File

Create a new file at `.github/workflows/dependabot-auto-approve.yml`:

```bash
touch .github/workflows/dependabot-auto-approve.yml
```

### Step 3: Add the Workflow Configuration

Open `.github/workflows/dependabot-auto-approve.yml` in your text editor and add the following content:

```yaml
name: Dependabot Auto-Approve
on: pull_request

permissions:
  pull-requests: write

jobs:
  dependabot-approve:
    runs-on: ubuntu-latest
    if: github.actor == 'dependabot[bot]'
    steps:
      - name: Dependabot metadata
        id: metadata
        uses: dependabot/fetch-metadata@v2
        with:
          github-token: "${{ secrets.GITHUB_TOKEN }}"

      - name: Approve Dependabot PR
        run: gh pr review --approve "$PR_URL"
        env:
          PR_URL: ${{ github.event.pull_request.html_url }}
          GH_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

## Creating the Auto-Merge Workflow

### Step 1: Create the Auto-Merge Workflow File

Create a new file at `.github/workflows/dependabot-auto-merge.yml`:

```bash
touch .github/workflows/dependabot-auto-merge.yml
```

### Step 2: Add the Workflow Configuration

Open `.github/workflows/dependabot-auto-merge.yml` in your text editor and add the following content:

```yaml
name: Dependabot Auto-Merge
on: pull_request

permissions:
  contents: write
  pull-requests: write

jobs:
  dependabot-auto-merge:
    runs-on: ubuntu-latest
    if: github.actor == 'dependabot[bot]'
    steps:
      - name: Dependabot metadata
        id: metadata
        uses: dependabot/fetch-metadata@v2
        with:
          github-token: "${{ secrets.GITHUB_TOKEN }}"

      - name: Enable auto-merge for Dependabot PRs
        run: gh pr merge --auto --merge "$PR_URL"
        env:
          PR_URL: ${{ github.event.pull_request.html_url }}
          GH_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

## Examples for Different Dependency Types

### Example 1: NPM Dependencies Only

If your repository has npm dependencies (i.e., `package.json` files), use this configuration in `.github/dependabot.yml`:

```yaml
# To get started with Dependabot version updates, you'll need to specify which
# package ecosystems to update and where the package manifests are located.
# Please see the documentation for all configuration options:
# https://docs.github.com/code-security/dependabot/dependabot-version-updates/configuration-options-for-the-dependabot.yml-file

version: 2
updates:
  # Enable version updates for npm
  - package-ecosystem: "npm"
    # Look for `package.json` and `lock` files in the root directory
    directory: "/"
    # Check the npm registry for updates every week
    schedule:
      interval: "weekly"
    # Raise all npm pull requests with assignees
    assignees:
      - "YOUR_GITHUB_USERNAME"
```

**For npm dependencies in subdirectories**, add additional update blocks:

```yaml
version: 2
updates:
  # Enable version updates for npm in the root directory
  - package-ecosystem: "npm"
    directory: "/"
    schedule:
      interval: "weekly"
    assignees:
      - "YOUR_GITHUB_USERNAME"

  # Enable version updates for npm in a subdirectory
  - package-ecosystem: "npm"
    directory: "/subdirectory-name"
    schedule:
      interval: "weekly"
    assignees:
      - "YOUR_GITHUB_USERNAME"
```

### Example 2: Composer Dependencies Only

If your repository has composer dependencies (i.e., `composer.json` files), use this configuration in `.github/dependabot.yml`:

```yaml
# To get started with Dependabot version updates, you'll need to specify which
# package ecosystems to update and where the package manifests are located.
# Please see the documentation for all configuration options:
# https://docs.github.com/code-security/dependabot/dependabot-version-updates/configuration-options-for-the-dependabot.yml-file

version: 2
updates:
  # Enable version updates for composer
  - package-ecosystem: "composer"
    # Look for `composer.json` and `composer.lock` files in the root directory
    directory: "/"
    # Check for updates every week
    schedule:
      interval: "weekly"
    # Raise all composer pull requests with assignees
    assignees:
      - "YOUR_GITHUB_USERNAME"
```

### Example 3: Both NPM and Composer Dependencies

If your repository has both npm and composer dependencies, combine both configurations in `.github/dependabot.yml`:

```yaml
# To get started with Dependabot version updates, you'll need to specify which
# package ecosystems to update and where the package manifests are located.
# Please see the documentation for all configuration options:
# https://docs.github.com/code-security/dependabot/dependabot-version-updates/configuration-options-for-the-dependabot.yml-file

version: 2
updates:
  # Enable version updates for npm
  - package-ecosystem: "npm"
    # Look for `package.json` and `lock` files in the root directory
    directory: "/"
    # Check the npm registry for updates every week
    schedule:
      interval: "weekly"
    # Raise all npm pull requests with assignees
    assignees:
      - "YOUR_GITHUB_USERNAME"

  # Enable version updates for composer
  - package-ecosystem: "composer"
    # Look for `composer.json` and `composer.lock` files in the root directory
    directory: "/"
    # Check for updates every week
    schedule:
      interval: "weekly"
    # Raise all composer pull requests with assignees
    assignees:
      - "YOUR_GITHUB_USERNAME"
```

**For multiple directories with different package managers**, you can mix and match:

```yaml
version: 2
updates:
  # NPM in subdirectory 1
  - package-ecosystem: "npm"
    directory: "/subdirectory-1"
    schedule:
      interval: "weekly"
    assignees:
      - "YOUR_GITHUB_USERNAME"

  # NPM in subdirectory 2
  - package-ecosystem: "npm"
    directory: "/subdirectory-2"
    schedule:
      interval: "weekly"
    assignees:
      - "YOUR_GITHUB_USERNAME"

  # Composer in root directory
  - package-ecosystem: "composer"
    directory: "/"
    schedule:
      interval: "weekly"
    assignees:
      - "YOUR_GITHUB_USERNAME"
```

## Submitting Your Changes

### Step 1: Stage and Commit Your Changes

```bash
git add .github/dependabot.yml
git add .github/workflows/dependabot-auto-approve.yml
git add .github/workflows/dependabot-auto-merge.yml
git commit -m "Add Dependabot configuration with auto-approve and auto-merge workflows"
```

### Step 2: Push Your Branch

```bash
git push origin add-dependabot-workflows
```

### Step 3: Create a Pull Request

1. Navigate to your forked repository on GitHub
2. You should see a prompt to create a pull request from your recently pushed branch
3. Click **Compare & pull request**
4. Fill in the pull request details:
   - **Title**: "Add Dependabot configuration with auto-approve and auto-merge workflows"
   - **Description**: Explain what you've added and why
5. Click **Create pull request**

### Step 4: Wait for Review and Merge

Once your pull request is created, maintainers of the original repository will review your changes. If approved, they will merge your pull request into the main branch.

## Configuration Options

### Customizing the Update Schedule

You can change the `interval` in the `dependabot.yml` file to:
- `"daily"`
- `"weekly"`
- `"monthly"`

### Customizing Assignees

Replace `YOUR_GITHUB_USERNAME` with the GitHub usernames of people who should be assigned to Dependabot pull requests. You can add multiple assignees:

```yaml
assignees:
  - "username1"
  - "username2"
```

### Additional Configuration Options

For more advanced configuration options, refer to the official Dependabot documentation:
- [Configuration options for the dependabot.yml file](https://docs.github.com/code-security/dependabot/dependabot-version-updates/configuration-options-for-the-dependabot.yml-file)

## Important Notes

1. **Auto-merge requires branch protection rules**: For auto-merge to work, your repository should have appropriate branch protection rules configured, including status checks that must pass before merging.

2. **Permissions**: The workflows use `GITHUB_TOKEN` which is automatically provided by GitHub Actions. No additional secrets need to be configured.

3. **Security considerations**: Auto-approving and auto-merging Dependabot PRs can be convenient but ensure you have adequate test coverage and CI/CD checks in place to catch breaking changes.

4. **Review the changes**: Even with auto-merge enabled, it's good practice to periodically review what dependencies are being updated.

## Troubleshooting

### Dependabot PRs are not being created

- Verify that the `dependabot.yml` file is correctly placed in `.github/dependabot.yml`
- Check that the `directory` paths are correct
- Ensure the package manifest files (`package.json` or `composer.json`) exist in the specified directories

### Auto-approve or auto-merge is not working

- Verify that the workflow files are correctly placed in `.github/workflows/`
- Check the Actions tab in your repository to see if the workflows are running
- Ensure branch protection rules are properly configured for auto-merge
- Verify that the workflows have the necessary permissions

### Dependabot is creating too many PRs

- Adjust the `schedule` interval to be less frequent
- Add ignore rules to the `dependabot.yml` configuration
- Group updates together using the `groups` configuration option

For more help, consult the [GitHub Dependabot documentation](https://docs.github.com/code-security/dependabot).
