# Project structure guide

This static website must remain live from the repository root so the current URLs and hosting setup continue to work.

## Live website files

The site is intentionally kept in the root folder for the current production setup:

- [index.html](../index.html)
- [business-and-leisure.html](../business-and-leisure.html)
- [club-membership.html](../club-membership.html)
- [contact-us.html](../contact-us.html)
- [assets](../assets)
- [images](../images)
- [fonts](../fonts)
- [js](../js)
- [css](../css)

## Safe organization rules

- Do not move the live HTML files from the root unless the hosting setup is updated in the same change.
- Keep any future folder cleanup non-destructive and separate from the current production site.
- Keep shared CSS tokens in [../assets/css/global.css](../assets/css/global.css).
- Keep compatibility wrappers in [../css/global.css](../css/global.css).
- Use this docs folder for maintainers notes, not for live site runtime.

## Why this is safe

The project is a multi-page static site, not a framework app. Reorganizing the live runtime without updating all paths would break the site. This guide keeps the website stable while making the project easier to understand.
