terraform {
  required_providers {
    github = {
      source  = "integrations/github"
      version = "~> 4.0"
    }
  }
}

variable "token" {
  type = string
}

variable "repo" {
  type = string
}

variable "branch" {
  type = string
}

# Configure the GitHub Provider
provider "github" {
  token = var.token # or `GITHUB_TOKEN`
  owner = "agence-adeliom"
}

data "github_repository" "repository" {
  name = var.repo
}

resource "github_repository" "create_repository" {
  count = data.github_repository.repository.repo_id == null ? 1 : 0
  name = var.repo
  visibility = "public"

  has_issues = false
  has_projects = true
  has_wiki = true
  lifecycle {
    prevent_destroy = true
  }
}

data "github_branch" "branch" {
  repository = var.repo
  branch     = var.branch
}

resource "github_branch" "create_branch" {
  count = data.github_branch.branch.branch == null ? 1 : 0
  repository = var.repo
  branch     = var.branch
  lifecycle {
    prevent_destroy = true
  }
}

resource "github_branch_default" "default"{
  repository = var.repo
  branch     = var.branch
}
