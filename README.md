# Status
* Master: [![Build Status](https://magnum.travis-ci.com/serlo-org/athene2.png?token=gtodfPz6nLDS6xphYxdJ&branch=master)](https://magnum.travis-ci.com/serlo-org/athene2)
* Develop: [![Build Status](https://magnum.travis-ci.com/serlo-org/athene2.png?token=gtodfPz6nLDS6xphYxdJ&branch=develop)](https://magnum.travis-ci.com/serlo-org/athene2)
* PTR: [![Build Status](https://magnum.travis-ci.com/serlo-org/athene2.png?token=gtodfPz6nLDS6xphYxdJ&branch=ptr)](https://magnum.travis-ci.com/serlo-org/athene2)

# Commit rules

## Branches

Always open up new branches for your commits.
Do not commit into `develop` (unstable), `master` (stable) or `ptr` (beta) directly.
Those branches deploy automatically and may break stuff.

### Branches for issues

If you're fixing an issue name the branch `issueid-my-description`.

Example: `233-hydrator-fix`

### Branches for new features

If you're implementing a new feature name the branch `feature-my-feature`.

Example: `feature-database-caching`

### Create Pull Requests

Use rebase to bring your branches up to speed with the latest develop, master or ptr branch.
Now you can create a pull request which can be fast-forwarded (no merge necessary) instantly.

## Commit Messages

Example: Bad commit message
> finally fixed this dumb rendering bug that Joe talked about ... LOL
  -------------------------------------------------------------------
  also added another form field for password validation

Example: Good Commit Message
> BUG Formatting through prepValueForDB()
  -------------------------------------------------------------------
  Added prepValueForDB() which is called on DBField->writeToManipulation()
  to ensure formatting of value before insertion to DB on a per-DBField type basis (fixes #1234).
  Added documentation for DBField->writeToManipulation() (related to a4bd42fd).

# Code Rules

* Strictly follow [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

# Knowledge base

* Common ZF2 knowledge: http://zf2cheatsheet.com/
* ZF2 Docs: http://framework.zend.com/manual/2.2/en/index.html
* Doctrine Docs: http://docs.doctrine-project.org/en/latest/
* Doctrine ZF2 Module Docs: https://github.com/doctrine/DoctrineORMModule/tree/master/docs
* ZfcRbac Docs: https://github.com/ZF-Commons/zfc-rbac/tree/master/docs
* Athene2 Docs: http://serlo-org.github.io/athene2-guide