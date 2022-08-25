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

# Configure the GitHub Provider
provider "github" {
  token = var.token # or `GITHUB_TOKEN`
  owner = "agence-adeliom"
}


resource "github_repository" "repository" {
  name        = var.repo
  visibility = "public"

  has_issues = true
  has_projects = true
  has_wiki = true
}
