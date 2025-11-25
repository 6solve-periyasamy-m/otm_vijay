// Source - https://stackoverflow.com/a
// Posted by Dr. Ayilara
// Retrieved 2025-11-21, License - CC BY-SA 4.0

const { join } = require('path');

/**
 * @type {import("puppeteer").Configuration}
 */
module.exports = {
    // Changes the cache location for Puppeteer
    cacheDirectory: join(__dirname, '.cache', 'puppeteer'),
};
