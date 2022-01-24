/**
 * translate.js
 *
 * Generate a pot files in languages folder.
 */
const dotenv = require( 'dotenv' );
const dotenvExpand = require( 'dotenv-expand' );
const wpPot = require( 'wp-pot' );

// Load environment variables
const myDotenv = dotenv.config();

if ( myDotenv.error ) {
	throw myDotenv.error;
}

dotenvExpand.expand( myDotenv );

const options = {
	bugReport: process.env.WP_PLUGIN_REPO + '/issues',
	destFile: `./languages/${ process.env.WP_PLUGIN_TEXT_DOMAIN }.pot`,
	domain: process.env.WP_PLUGIN_TEXT_DOMAIN,
	lastTranslator: process.env.WP_PLUGIN_LAST_TRANSLATOR,
	package: process.env.WP_PLUGIN_NAME.replace( ' ', '-' ),
	src: [ '**/*.php', '!vendor/**/*.php' ],
	team: process.env.WP_PLUGIN_TRANSLATION_TEAM,
};

wpPot( options );
