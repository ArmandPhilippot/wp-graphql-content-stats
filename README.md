# WPGraphQL Content Stats

Add some stats to WPGraphQL: total posts, word count and estimated reading time.

## Introduction

This WordPress plugin adds some new fields to WPGraphQL:

-   a `total` field is added to `pageInfo`: it contains the total of found posts or found users (depending on `WP_Query` type).
-   an `info` field is added to `ContentNode` (used by posts for example): it contains itself two fields:
    -   `readingTime`: an estimated reading time,
    -   `wordsCount`: the number of words in the post content.

The `readingTime` field offers two formats:

-   `minutes`: the returned number is a rounded estimate of reading time in minutes.
-   `seconds`: the returned number is a rounded estimate of reading time in seconds. It allows you to add some extra logic in frontend to display the reading time (displaying minutes and seconds for example).

## Requirements

-   [WordPress](https://wordpress.org/)
-   [WPGraphQL](https://github.com/wp-graphql/wp-graphql)

## License

This project is open source and available under the [GPL2.0-or-later License](./LICENSE).
